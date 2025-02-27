<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Site extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'coordinates',
        'commissioning_date',
        'start_date',
        'delivery_status',
        'financial_closure_status',
        'capital',
        'sale_price',
        'profit_or_loss_ratio',
    ];
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'site_materials');
    }

    public function deliveryPhases()
    {
        return $this->hasMany(DeliveryPhase::class);
    }
    public function concretePours()
    {
        return $this->hasMany(ConcretePours::class);
    }

    protected $casts = [
        'commissioning_date' => 'date',
        'start_date' => 'date',
    ];
}
