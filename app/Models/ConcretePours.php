<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConcretePours extends Model
{
    use HasFactory;
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'concrete_pour_material')
            ->withPivot('quantity') // to track the quantity of material used in the pour
            ->withTimestamps();
    }
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
