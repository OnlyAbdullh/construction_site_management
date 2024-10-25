<?php

namespace App\Http\Controllers;

use App\Exports\MaterielExport;
use App\Http\Requests\ImportMaterielValidate;
use App\Imports\MaterielImport;
use App\Models\Material;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materiels = Material::paginate(20);
        return response()->json($materiels);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'internal_reference' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'product_category' => 'required|string|max:100',
            'measure' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:500',
            'unit_cost_price' => 'required|numeric|min:0',
        ]);

        $materiel = Material::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Material created successfully.',
            'data' => $materiel
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Material $materiel)
    {
        return response()->json($materiel);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'internal_reference' => 'sometimes|required|string|max:100',
            'name' => 'sometimes|required|string|max:255',
            'product_category' => 'sometimes|required|string|max:100',
            'measure' => 'sometimes|required|string|max:50',
            'price' => 'sometimes|required|numeric|min:0',
            'note' => 'sometimes|nullable|string|max:500',
            'unit_cost_price' => 'sometimes|required|numeric|min:0',
        ]);

        $material->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Material updated successfully.',
            'data' => $material
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $materiel)
    {
        $materiel->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Material deleted successfully.'
        ], 200);
    }

    public function search($internal_reference)
    {
        $materiels = Material::where('internal_reference', 'LIKE', "%{$internal_reference}%")->get();

        if ($materiels->isEmpty()) {
            return response()->json(['message' => 'No materials found'], 404);
        }
        return response()->json($materiels);
    }

    public function export()
    {
        return Excel::download(new MaterielExport, 'materials.xlsx');
    }

    public function import(ImportMaterielValidate $importMaterielValidate)
    {
        $file = $importMaterielValidate->file('file'); // Get the uploaded file
        $filePath = $file->store('files');  // Store the file in the 'files' directory

        Excel::import(new MaterielImport, $filePath);

        return response()->json(['message' => 'File imported successfully.'], 200);
    }
}
