<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubMaterialResource;
use App\Models\ConcretePours;
use App\Models\Material;
use App\Models\Site;
use App\Models\SubMaterial;
use App\Services\SubMaterialService;
use Decimal\Decimal;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class Sub_MaterialController extends Controller
{
    protected $subMaterialService;

    public function __construct(SubMaterialService $subMaterialService)
    {
        $this->subMaterialService = $subMaterialService;
    }

    /**
     * Add a new sub-material with its initial prices and quantities.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'material_id' => 'required|exists:materials,id',
            'name' => 'required|string|max:255',
            'rate_per_cubic_meter' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'capital_price' => 'required|numeric|min:0',
            'sold_price' => 'required|numeric|min:0',
            'unit_measure' => 'required|string|max:50',
        ]);

        $subMaterial = $this->subMaterialService->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sub-material added successfully.',
            'data' => $subMaterial
        ], 201);
    }

    /**
     * Delete a sub-material.
     */
    public function destroy(Request $request, $sub_material_id)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'material_id' => 'required|exists:materials,id',
        ]);

        $isDeleted = $this->subMaterialService->delete(array_merge($validated, [
            'sub_material_id' => $sub_material_id,
        ]));

        if ($isDeleted) {
            return response()->json([
                'success' => true,
                'message' => 'Sub-material deleted successfully.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete sub-material.',
        ], 400);
    }



    /**
     * Retrieve all sub-materials for a specific material and site.
     */
    public function index($site_id, $material_id)
    {
        $subMaterials = $this->subMaterialService->getBySiteAndMaterial($site_id, $material_id);

        return response()->json([
            'success' => true,
            'data' => $subMaterials
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroySubMaterialInMaterial($internalReference, $subMaterialId)
    {
        $material = Material::where('internal_reference', $internalReference)->firstOrFail();

        $subMaterial = $material->subMaterials()->find($subMaterialId);

        if (!$subMaterial) {
            return response()->json([
                'status' => 'error',
                'message' => 'SubMaterial not found for this Material.'
            ], 404);
        }

        $subMaterial->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'SubMaterial deleted successfully.'
        ], 200);
    }
    public function setQuantity(Request $request)
    {
        $site_id = $request->site_id;
        $concrete_pour_id = $request->concrete_pour_id;
        $material_id = $request->material_id;
        $sub_material_id = $request->sub_material_id;
        $is_related = $request->is_related;
        $direct = $request->direct;
        $quantity = $request->quantity;

        $site = Site::findOrFail($site_id);

        if ($is_related) {
            // SubMaterial related to Concrete Pour
            if ($direct) {
                // Direct relationship path: Site -> ConcretePour -> Material -> SubMaterial
                $concretePour = $site->concretePours()
                    ->where('id', $concrete_pour_id)
                    ->firstOrFail();

                $material = $concretePour->materials()
                    ->where('materials.id', $material_id)
                    ->first();

                if ($material) {
                    $subMaterial = $material->subMaterials()
                        ->where('sub_materials.id', $sub_material_id)
                        ->first();

                    if ($subMaterial) {

                        $subMaterial->update(['quantity' => $quantity]);
                    } else {
                        return response()->json(['message' => 'Sub-material not found.'], 404);
                    }
                } else {
                    return response()->json(['message' => 'Material not found for this concrete pour.'], 404);
                }
            } else {

                $concretePour = ConcretePour::where('id', $concrete_pour_id)
                    ->where('site_id', $site->id)
                    ->firstOrFail();

                $material = Material::findOrFail($material_id);
                $subMaterial = $material->subMaterials()
                    ->where('sub_materials.id', $sub_material_id)
                    ->firstOrFail();


                $rate = $subMaterial->rate ?? 1;
                $dimensions = $concretePour->length * $concretePour->width * $concretePour->height;
                $calculatedQuantity = $dimensions * $rate;

                // Update calculated quantity directly on the sub_material model
                $subMaterial->update(['quantity' => $calculatedQuantity]);
            }
        } else {
            // SubMaterial not related to Concrete Pour
            $material = $site->materials()
                ->where('materials.id', $material_id)
                ->first();

            if ($material) {
                $subMaterial = $material->subMaterials()
                    ->where('sub_materials.id', $sub_material_id)
                    ->first();

                if ($subMaterial) {
                    // Update quantity directly on the sub_material model
                    $subMaterial->update(['quantity' => $quantity]);
                } else {
                    return response()->json(['message' => 'Sub-material not found.'], 404);
                }
            } else {
                return response()->json(['message' => 'Material not found for this site.'], 404);
            }
        }

        return response()->json([
            'message' => 'Quantity set successfully.',
            'sub_material' => $subMaterial,
        ]);
    }
}
