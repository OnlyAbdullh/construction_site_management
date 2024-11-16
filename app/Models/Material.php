<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_reference',
        'name',
        'product_category',
        'unit_measure',
        'price',
        'notes',
    ];

    public function getRouteKeyName()
    {
        return 'internal_reference';
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class, 'site_materials');
    }


    public function subMaterials()
    {
        return $this->hasMany(SubMaterial::class);
    }

    public function concretePours()
    {
        return $this->belongsToMany(ConcretePour::class, 'concrete_pour_material');
    }
    public function priceHistories()
    {
        return $this->morphMany(PriceHistory::class, 'recordable');
    }

    public function capitalHistories()
    {
        return $this->priceHistories()->where('type', 'capital');
    }

    public function soldHistories()
    {
        return $this->priceHistories()->where('type', 'sold');
    }
}
