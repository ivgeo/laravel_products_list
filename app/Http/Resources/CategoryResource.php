<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'full_name' => $this->full_name,
            'products_count' => $this->whenLoaded('productCount', fn () => $this->productCount?->products_count ?? 0),
        ];
    }
}
