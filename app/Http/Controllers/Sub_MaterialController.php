<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubMaterialResource;
use App\Models\ConcretePours;
use App\Models\Material;
use App\Models\Site;
use App\Models\SubMaterial;
use Decimal\Decimal;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class Sub_MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subMaterials = SubMaterial::paginate(20);
        return SubMaterialResource::collection($subMaterials)->response()->setStatusCode(200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'cost_price' => 'required|numeric|min:0',
            'sold_price' => 'nullable|numeric|min:0',
            'unit_measure' => 'required|string|max:50',
        ]);
        $materiel = SubMaterial::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Sub_Material created successfully.',
            'data' => $materiel
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($materialId, $subMaterialId)
    {
        $material = Material::findOrFail($materialId);
        $subMaterial = $material->subMaterials()->find($subMaterialId);

        if (!$subMaterial) {
            return response()->json([
                'status' => 'error',
                'message' => 'SubMaterial not found for this Material.'
            ], 404);
        }

        // Use the API Resource to return the formatted data
        return new SubMaterialResource($subMaterial);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubMaterial $subMaterial)
    {
        $validated = $request->validate([
            'material_id' => 'sometimes|exists:materials,id',
            'name' => 'sometimes|string|max:255',
            'quantity' => 'sometimes|integer|min:1',
            'cost_price' => 'sometimes|numeric|min:0',
            'sold_price' => 'sometimes|numeric|min:0|nullable',
            'unit_measure' => 'sometimes|string|max:50',
        ]);

        $subMaterial->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Material updated successfully.',
            'data' => $subMaterial
        ], 200);
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
