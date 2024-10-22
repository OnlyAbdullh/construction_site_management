<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'material_site')
            ->withPivot('quantity')
            ->withTimestamps();;
    }

    public function deliveryPhases()
    {
        return $this->hasMany(DeliveryPhase::class);
    }
}
