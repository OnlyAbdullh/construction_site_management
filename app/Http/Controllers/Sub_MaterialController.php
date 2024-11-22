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
}
