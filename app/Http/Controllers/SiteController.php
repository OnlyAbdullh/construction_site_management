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
        $sites = Site::paginate(10);

        if ($sites->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No sites found.'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Sites retrieved successfully.',
            'data' => SiteResource::collection($sites)->map(function ($site) {
                return $site->toBasicArray(request());
            })
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSiteRequest $request)
    {
        $site = Site::create($request->validated());

        return response()->json([
            'status' => 201,
            'message' => 'Site created successfully with associated materials and sub-materials.',
            'data' => new SiteResource($site->load('materials.subMaterials'))
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site)
    {
        $site->load([
            'materials.subMaterials',
            'materials.priceHistories',  // Load priceHistories for materials
            'materials.subMaterials.priceHistories'  // Load priceHistories for subMaterials
        ]);
        return response()->json([
            'status' => 200,
            'message' => 'Site retrieved successfully.',
            'data' => new SiteResource($site)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSiteRequest $request, Site $site)
    {
        $validated = $request->validated();
        $site->update($validated);

        return response()->json([
            'status' => 200,
            'message' => 'Site updated successfully.',
            'data' => new SiteResource($site)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {
        // Detach all related materials
        $site->materials()->detach();
        $site->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Site deleted successfully.'
        ], 200);
    }

    public function deleteMaterial($siteId, $material_id)
    {
        $site = Site::find($siteId);

        if (!$site) {
            return response()->json([
                'status' => 404,
                'message' => 'Site not found.'
            ], 404);
        }

        $material = Material::where('internal_reference', $material_id)->first();

        if (!$material) {
            return response()->json([
                'status' => 404,
                'message' => 'Material not found.'
            ], 404);
        }

        $site->materials()->detach($material->id);

        return response()->json([
            'status' => 200,
            'message' => 'Material successfully detached from the site.'
        ], 200);
    }

    public function searchMaterialInSite($siteId, $internal_reference)
    {
        $site = Site::find($siteId);

        if (!$site) {
            return response()->json([
                'status' => 404,
                'message' => 'Site not found.'
            ], 404);
        }

        $material = $site->materials()->where('materials.internal_reference', $internal_reference)->first();

        if ($material) {
            return response()->json([
                'status' => 200,
                'message' => 'Material found in the site.',
                'data' => $material
            ], 200);
        }

        return response()->json([
            'status' => 404,
            'message' => 'Material not found in the site.'
        ], 404);
    }

    public function addMaterialToSite(Site $site, $material_id)
    {
        $material = Material::where('internal_reference', $material_id)->first();

        if (!$material) {
            return response()->json([
                'status' => 404,
                'message' => 'Material not found.'
            ], 404);
        }

        if (!$site->materials()->where('material_id', $material->id)->exists()) {
            $site->materials()->attach($material->id);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Material added successfully.',
            'data' => new SiteResource($site->load('materials'))
        ], 200);
    }

    public function search(Request $request)
    {
        $query = Site::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('profit_or_loss_ratio')) {
            $query->where('profit_or_loss_ratio', '>=', $request->input('profit_or_loss_ratio'));
        }
        if ($request->has('delivery_status')) {
            $query->where('delivery_status', 'like', '%' . $request->input('delivery_status') . '%');
        }
        if ($request->has('financial_closure_status')) {
            $query->where('financial_closure_status', 'like', '%' . $request->input('financial_closure_status') . '%');
        }
        if ($request->has('min_capital') && $request->has('max_capital')) {
            $query->whereBetween('capital', [$request->input('min_capital'), $request->input('max_capital')]);
        }
        $sites = $query->with('materials.subMaterials')->get();

        if ($sites->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No sites found matching the criteria.'
            ], 404);
        }
        return SiteResource::collection($sites)->response()->setStatusCode(200);
    }

    public function calculate_capital(Site $site)
    {
        $capital = $site->materials->sum(function ($material) {
            $materialCost = $material->unit_cost_price * $material->pivot->quantity;

            $subMaterialCost = $material->subMaterials->sum(function ($subMaterial) {
                return $subMaterial->cost_price * $subMaterial->quantity;
            });
            return $materialCost + $subMaterialCost;
        });

        return response()->json([
            "The Capital of the site is" => $capital,
        ], 201);
    }

    public function calculate_price(Site $site)
    {
        $price = $site->materials->sum(function ($material) {
            $materialPrice = $material->price * $material->pivot->quantity;

            $subMaterialPrice = $material->subMaterials->sum(function ($subMaterial) {
                return $subMaterial->price * $subMaterial->quantity;
            });

            return $materialPrice + $subMaterialPrice;
        });

        return response()->json([
            "The price of the site is" => $price,
        ], 201);
    }
}
