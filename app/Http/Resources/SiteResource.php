<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                          => $this->id,
            'name'                        => $this->name,
            'coordinates'                 => $this->coordinates,
            'commissioning_date'          => $this->commissioning_date->toDateString(),
            'start_date'                  => $this->start_date->toDateString(),
            'delivery_status'             => $this->delivery_status,
            'financial_closure_status'    => $this->financial_closure_status,
            'capital'                     => $this->capital,
            'sale_price'                  => $this->whenNotNull($this->sale_price),
            'profit_or_loss_ratio'        => $this->whenNotNull($this->profit_or_loss_ratio),
            'materials'                   => MaterialResource::collection($this->whenLoaded('materials')),
        ];
    }
    public function toBasicArray(Request $request): array
    {
        return [
            'name'                        => $this->name,
            'delivery_status'             => $this->delivery_status,
            'financial_closure_status'    => $this->financial_closure_status,
            'capital'                     => $this->capital,
            'sale_price'                  => $this->whenNotNull($this->sale_price),
            'profit_or_loss_ratio'        => $this->whenNotNull($this->profit_or_loss_ratio),
        ];
    }
}
