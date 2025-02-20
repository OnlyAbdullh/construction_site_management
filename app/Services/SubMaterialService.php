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


    /**
     * Update a sub-material and add new price/quantity records.
     */
    public function update(int $id, array $data): SubMaterial
    {
        $subMaterial = SubMaterial::findOrFail($id);

        // Add price history if capital price is provided
        if (isset($data['capital_price'])) {
            PriceHistory::create([
                'recordable_id' => $subMaterial->id,
                'recordable_type' => SubMaterial::class,
                'type' => 'capital',
                'price' => $data['capital_price'],
                'quantity' => $data['quantity'] ?? null,
                'entry_date' => $data['entry_date'],
            ]);
        }

        // Add price history if sold price is provided
        if (isset($data['sold_price'])) {
            PriceHistory::create([
                'recordable_id' => $subMaterial->id,
                'recordable_type' => SubMaterial::class,
                'type' => 'sold',
                'price' => $data['sold_price'],
                'entry_date' => $data['entry_date'],
            ]);
        }

        return $subMaterial;
    }
    /**
     * Delete a sub-material and its associated price history.
     */
    public function delete(array $data): bool
    {
        // Retrieve the related site material
        $siteMaterial = SiteMaterial::where('site_id', $data['site_id'])
            ->where('material_id', $data['material_id'])
            ->firstOrFail();

        // Ensure the sub-material belongs to the retrieved material
        $subMaterial = SubMaterial::where('id', $data['sub_material_id'])
            ->where('material_id', $siteMaterial->material_id)
            ->firstOrFail();

        PriceHistory::where('recordable_id', $subMaterial->id)
            ->where('recordable_type', SubMaterial::class)
            ->delete();

        // Delete the sub-material
        return $subMaterial->delete();
    }

    /**
     * Get all sub-materials for a specific site and material, including the latest prices/quantities.
     */
    public function getBySiteAndMaterial(int $site_id, int $material_id): Collection
    {
        // Validate the relationship
        $siteMaterial = SiteMaterial::where('site_id', $site_id)
            ->where('material_id', $material_id)
            ->firstOrFail();

        $subMaterials = $siteMaterial->subMaterials;

        // Append the latest prices/quantities to each sub-material
        $subMaterials->each(function ($subMaterial) {
            $subMaterial->latest_capital_price = PriceHistory::where('recordable_id', $subMaterial->id)
                ->where('recordable_type', SubMaterial::class)
                ->where('type', 'capital')
                ->latest('entry_date')
                ->value('price');

            $subMaterial->latest_sold_price = PriceHistory::where('recordable_id', $subMaterial->id)
                ->where('recordable_type', SubMaterial::class)
                ->where('type', 'sold')
                ->latest('entry_date')
                ->value('price');

            $subMaterial->latest_quantity = PriceHistory::where('recordable_id', $subMaterial->id)
                ->where('recordable_type', SubMaterial::class)
                ->where('type', 'capital')
                ->latest('entry_date')
                ->value('quantity');
        });

        return $subMaterials;
    }
}
