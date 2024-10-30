<?php

namespace App\Http\Controllers;

use App\Models\DeliveryPhase;
use App\Models\Site;
use Illuminate\Http\Request;

class delivery_phasesController extends Controller
{
    public function index(Site $site)
    {
        $deliveryPhases = $site->deliveryPhases()->get();
        return response()->json($deliveryPhases);
    }

    /**
     * Store a newly created resource in storage for a specific site.
     */
    public function store(Request $request, Site $site)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'delivery_status' => 'required|in:delivered,in_progress,not_delivered',
        ]);

        $highestOrder = $site->deliveryPhases()->max('orderNum') ?: 0;

        $deliveryPhase = $site->deliveryPhases()->create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'delivery_status' => $request->delivery_status,
            'orderNum' => $highestOrder + 1,
        ]);

        return response()->json($deliveryPhase, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Site $site, DeliveryPhase $deliveryPhase)
    {
        if ($deliveryPhase->site_id !== $site->id) {
            return response()->json(['error' => 'Delivery phase not found'], 404);
        }

        return response()->json($deliveryPhase);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site, DeliveryPhase $deliveryPhase)
    {

        if ($deliveryPhase->site_id !== $site->id) {
            return response()->json(['error' => 'Delivery phase not found'], 404);
        }
        $request->validate([
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|nullable|date',
            'delivery_status' => 'sometimes|in:delivered,in_progress,not_delivered',
        ]);
        $deliveryPhase->update($request->all());
        return response()->json($deliveryPhase);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site, DeliveryPhase $deliveryPhase)
    {
        if ($deliveryPhase->site_id !== $site->id) {
            return response()->json(['error' => 'Delivery phase not found'], 404);
        }
        $deletedOrder = $deliveryPhase->orderNum;

        $deliveryPhase->delete();
        DeliveryPhase::where('site_id', $site->id)
            ->where('orderNum', '>', $deletedOrder)
            ->decrement('orderNum', 1);

        return response()->json(['message' => 'Delivery phase deleted and reordered successfully']);
    }
}
