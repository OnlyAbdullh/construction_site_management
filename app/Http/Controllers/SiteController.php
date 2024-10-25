<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteRequest;
use App\Http\Resources\SiteResource;
use App\Models\Material;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sites = Site::with('materials.subMaterials')->get();

        if ($sites->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No sites found.'
            ], 404);
        }
        return SiteResource::collection($sites)->response()->setStatusCode(200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiteRequest $request)
    {
        $site = Site::create($request->validated());

        if ($request->has('materials')) {
            foreach ($request->materials as $material) {
                $site->materials()->attach($material['id'], [
                    'quantity' => $material['quantity'] ?? null
                ]);

                // Step 3: Check if the material has sub-materials
                if (isset($material['sub_materials'])) {
                    foreach ($material['sub_materials'] as $subMaterial) {
                        // Find the attached material in the database
                        $existingMaterial = Material::find($material['id']);

                        // Step 4: Attach/create sub-materials related to this material
                        $existingMaterial->subMaterials()->create([
                            'name' => $subMaterial['name'],
                            'quantity' => $subMaterial['quantity'],
                            'cost_price' => $subMaterial['cost_price'],
                            'sold_price' => $subMaterial['sold_price'],
                            'unit_measure' => $subMaterial['unit_measure'],
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'message' => 'Site created successfully with associated materials and sub-materials',
            'site' => new SiteResource($site->load('materials.subMaterials'))  // Return the SiteResource with materials and sub-materials loaded
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site)
    {

        $site->load('materials.subMaterials');

        return (new SiteResource($site))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSiteRequest $request, Site $site)
    {
        $validated = $request->validated();
        $site->fill($validated);
        $site->save();

        return (new SiteResource($site))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {
        $site->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Site deleted successfully.'
        ], 200);
    }
    public function deleteMaterial($siteId, $materialId)
    {
        $site = Site::find($siteId);

        if (!$site) {
            return response()->json([
                'message' => 'Site not found'
            ], 404);
        }

        $site->materials()->detach($materialId);

        return response()->json([
            'message' => 'Material successfully detached from the site'
        ], 200);
    }
    public function searchMaterialInSite($siteId, $internalReference)
    {
        $site = Site::find($siteId);

        if (!$site) {
            return response()->json([
                'message' => 'Site not found'
            ], 404);
        }
        $material = $site->materials()->where('materials.internal_reference', $internalReference)->first();

        if ($material) {
            return response()->json([
                'message' => 'Material found in the site',
                'material' => $material
            ], 200);
        }

        return response()->json([
            'message' => 'Material not found in the site'
        ], 404);
    }
}
