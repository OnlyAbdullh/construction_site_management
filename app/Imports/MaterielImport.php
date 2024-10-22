<?php

namespace App\Imports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\ToModel;

class MaterielImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Assuming your Excel sheet columns are mapped to the following fields:
        return new Material([
            'internal_reference' => $row[0],   // Column A
            'name' => $row[1],                 // Column B
            'product_category' => $row[2],     // Column C
            'unit_measure' => $row[3],              // Column D
            'price' => $row[4],                // Column E
            'notes' => $row[5],                 // Column F
            'unit_cost_price' => $row[6]       // Column G
        ]);
    }
}
