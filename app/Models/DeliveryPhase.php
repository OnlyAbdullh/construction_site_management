<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryPhase extends Model
{
    use HasFactory;
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
