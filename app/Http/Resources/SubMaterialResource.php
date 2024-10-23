<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubMaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'material_id'   => $this->material_id,
            'name'          => $this->name,
            'quantity'      => $this->quantity,
            'cost_price'    => $this->cost_price,
            'sold_price'    => $this->sold_price,
            'unit_measure'  => $this->unit_measure,
            'created_at'    => $this->created_at->toDateTimeString(),
            'updated_at'    => $this->updated_at->toDateTimeString(),
        ];
    }
}
