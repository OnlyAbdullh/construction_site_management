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
            'internal_reference'   => $this->internal_reference,
            'name'                 => $this->name,
            'product_category'     => $this->product_category,
            'unit_measure'         => $this->unit_measure,
            'notes'                => $this->notes,
            'sub_materials'        => SubMaterialResource::collection($this->whenLoaded('subMaterials')), // Load sub-materials
            'price_histories'      => PriceHistoryResource::collection($this->whenLoaded('priceHistories')),
        ];
    }
}
