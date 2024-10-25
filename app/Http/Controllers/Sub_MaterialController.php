<?php

namespace App\Http\Controllers;

use App\Models\SubMaterial;
use Illuminate\Http\Request;

class Sub_MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
