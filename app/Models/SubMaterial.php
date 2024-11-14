<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMaterial extends Model
{
    use HasFactory;
    protected $fillable = [
        'material_id',
        'name',
        'quantity',
        'cost_price',
        'sold_price',
        'unit_measure',
    ];
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
    public function capitalHistories()
    {
        return $this->morphMany(CapitalHistory::class, 'recordable');
    }
}
