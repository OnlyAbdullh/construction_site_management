<?php

namespace App\Exports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\FromArray;
class MaterielExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        // Step 1: Fetch all materiel data from the database
        $materiels = Material::all();

        // Step 2: Map the data into an array format compatible with Excel
        $data = [];

        // Optional: Add headers if needed
        $data[] = [
            'internal_reference', 'name', 'product_category', 'unit_measure', 'price', 'notes', 'unit_cost_price'
        ];

        // Step 3: Loop through the material records and add to the data array
        foreach ($materiels as $materiel) {
            $data[] = [
                $materiel->internal_reference,
                $materiel->name,
                $materiel->product_category,
                $materiel->measure,
                $materiel->price,
                $materiel->note,
                $materiel->unit_cost_price,
            ];
        }

        return $data;
    }
}
