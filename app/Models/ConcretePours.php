<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConcretePours extends Model
{
    use HasFactory;

    protected $fillable = [
        'height',
        'length',
        'width',
        'name',
    ];

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'concrete_pour_material');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
