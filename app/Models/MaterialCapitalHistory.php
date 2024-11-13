<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialCapitalHistory extends Model
{
    use HasFactory;

    protected $fillable = ['material_id', 'quantity', 'capital','entry_date'];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
