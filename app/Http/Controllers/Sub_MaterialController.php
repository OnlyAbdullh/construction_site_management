<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubMaterialResource;
use App\Models\Material;
use App\Models\SubMaterial;
use Illuminate\Http\Request;

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
}
