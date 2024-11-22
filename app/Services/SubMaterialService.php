<?php

namespace App\Services;

use App\Models\SiteMaterial;
use App\Models\SubMaterial;
use App\Models\PriceHistory;
use Illuminate\Database\Eloquent\Collection;

class SubMaterialService
{
    /**
     * Create a new sub-material and add its initial prices/quantities to the price_histories table.
     */
    public function create(array $data): SubMaterial
    {
        // Retrieve the related site material
        $siteMaterial = SiteMaterial::where('site_id', $data['site_id'])
            ->where('material_id', $data['material_id'])
            ->firstOrFail();

        // Create the sub-material with all required fields
        $subMaterial = SubMaterial::create([
            'material_id' => $data['material_id'],
            'site_material_id' => $siteMaterial->id,
            'name' => $data['name'],
            'rate_per_cubic_meter' => $data['rate_per_cubic_meter'],
            'quantity' => $data['quantity'],
            'cost_price' => $data['capital_price'],
            'sold_price' => $data['sold_price'],
            'unit_measure' => $data['unit_measure'],

        ]);

        PriceHistory::create([
            'recordable_id' => $subMaterial->id,
            'recordable_type' => SubMaterial::class,
            'type' => 'capital',
            'price' => $data['capital_price'],
            'quantity' => $data['quantity'],
            'entry_date'=>now(),
        ]);

        PriceHistory::create([
            'recordable_id' => $subMaterial->id,
            'recordable_type' => SubMaterial::class,
            'type' => 'sold',
            'price' => $data['sold_price'],
            'quantity' => $data['quantity'],
            'entry_date'=>now(),
        ]);

        return $subMaterial;
    }


    public function delete(array $data): bool
    {
        // Retrieve the related site material
        $siteMaterial = SiteMaterial::where('site_id', $data['site_id'])
            ->where('material_id', $data['material_id'])
            ->firstOrFail();

        // Ensure the sub-material belongs to the retrieved material
        $subMaterial = SubMaterial::where('id', $data['sub_material_id'])
            ->where('material_id', $siteMaterial->material_id) // Validate against material_id
            ->firstOrFail();

        // Remove price history related to the sub-material
        PriceHistory::where('recordable_id', $subMaterial->id)
            ->where('recordable_type', SubMaterial::class)
            ->delete();

        // Delete the sub-material
        return $subMaterial->delete();
    }

}
