<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'type'         => $this->type,           // 'capital' or 'sold'
            'price'        => $this->price,
            'quantity'     => $this->quantity,
            'entry_date'   => $this->entry_date->toDateString(),
        ];
    }
}
