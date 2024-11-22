<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'recordable_id',  // ID of the related material or sub-material
        'recordable_type', // Type of the related model (e.g., 'Material' or 'SubMaterial')
        'type',            // 'capital' or 'sold'
        'price',           // The price value
        'quantity',        // Quantity (nullable, applicable for 'sold')
        'entry_date',      // Date of the record
    ];
    public function recordable()
    {
        return $this->morphTo();
    }
    protected $casts = [
        'entry_date' => 'datetime',
    ];
}
