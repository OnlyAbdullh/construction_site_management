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
        'unit_cost_price',
    ];

    public function getRouteKeyName()
    {
        return 'internal_reference';
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class, 'site_materials')
            ->withPivot('quantity')
            ->withTimestamps();
    }


    public function subMaterials()
    {
        return $this->hasMany(SubMaterial::class);
    }

    public function concretePours()
    {
        return $this->belongsToMany(ConcretePour::class, 'concrete_pour_material')
            ->withPivot('quantity') // to track the quantity of material used in the pour
            ->withTimestamps();
    }
}
