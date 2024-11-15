<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryPhase extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'start_date',
        'end_date',
        'preliminary_invoice',
        'technical_receipt',
        'technical_receipt_invoice',
        'final_receipt',
        'final_receipt_invoice',
        'delivery_status',
        'orderNum'
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
