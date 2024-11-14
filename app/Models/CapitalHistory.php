<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapitalHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'recordable_id',       // Polymorphic ID reference to Material or SubMaterial
        'recordable_type',     // Polymorphic type reference to Material or SubMaterial
        'capital',
        'quantity',
        'entry_date',
    ];

    public function recordable()
    {
        return $this->morphTo();
    }
}
