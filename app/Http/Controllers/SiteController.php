<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteRequest;
use App\Http\Resources\SiteResource;
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
        $validated = $request->validated();

        $site = Site::create([
            'name' => $validated['name'],
            'coordinates' => $validated['coordinates'],
            'commissioning_date' => $validated['commissioning_date'],
            'start_date' => $validated['start_date'],
            'delivery_status' => $validated['delivery_status'],
            'financial_closure_status' => $validated['financial_closure_status'],
            'capital' => $validated['capital'] ?? null,
            'sale_price' => $validated['sale_price'] ?? null,
            'profit_or_loss_ratio' => $validated['profit_or_loss_ratio'] ?? null,
        ]);

        return new SiteResource($site);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
