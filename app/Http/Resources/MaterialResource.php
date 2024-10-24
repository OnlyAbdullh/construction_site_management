<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'                   => $this->id,
            'internal_reference'    => $this->internal_reference,
            'name'                 => $this->name,
            'product_category'      => $this->product_category,
            'unit_measure'         => $this->unit_measure,
            'price'                => $this->price,
            'notes'                => $this->notes,
            'unit_cost_price'      => $this->unit_cost_price,
            'sub_materials'        => SubMaterialResource::collection($this->whenLoaded('subMaterials')), // Load sub-materials
            'created_at'           => $this->created_at->toDateTimeString(),
            'updated_at'           => $this->updated_at->toDateTimeString(),
        ];
    }
}
