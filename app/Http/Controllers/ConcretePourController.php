<?php

namespace App\Http\Controllers;

use App\Models\ConcretePours;
use Illuminate\Http\Request;

class ConcretePourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $concrete_pours = ConcretePours::paginate(10);
        return response()->json($concrete_pours, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'length' => 'required|numeric|min:0',
            'width' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'site_id' => 'required|exists:sites,id',
        ]);

        $concretePour = ConcretePours::create($validated);

        return response()->json([
            'message' => 'Concrete pour created successfully.',
            'data' => $concretePour
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConcretePours $concretePour)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'length' => 'sometimes|numeric|min:0',
            'width' => 'sometimes|numeric|min:0',
            'height' => 'sometimes|numeric|min:0',
        ]);

        $concretePour->update($validated);

        return response()->json([
            'message' => 'Concrete pour updated successfully.',
            'data' => $concretePour
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConcretePours $concretePour)
    {
        // Detach materials to clean up pivot table
     //   $concretePour->materials()->detach();

        $concretePour->delete();

        return response()->json([
            'message' => 'Concrete pour deleted successfully.'
        ], 200);
    }


}
