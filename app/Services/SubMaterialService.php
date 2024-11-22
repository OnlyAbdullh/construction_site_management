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
            'entry_date'=>now(),
        ]);

        PriceHistory::create([
            'recordable_id' => $subMaterial->id,
            'recordable_type' => SubMaterial::class,
            'type' => 'capital',
            'price' => $data['capital_price'],
            'quantity' => $data['quantity'],
            'entry_date' => $data['entry_date'],
        ]);

        PriceHistory::create([
            'recordable_id' => $subMaterial->id,
            'recordable_type' => SubMaterial::class,
            'type' => 'sold',
            'price' => $data['sold_price'],
            'quantity' => $data['quantity'],
            'entry_date' => $data['entry_date'],
        ]);

        return $subMaterial;
    }


}
