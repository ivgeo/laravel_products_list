<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Category',
    properties: [
        new OA\Property(property: 'code', type: 'string', example: 'electronics'),
        new OA\Property(property: 'name', type: 'string', example: 'Electronics'),
        new OA\Property(property: 'full_name', type: 'string', example: 'Home / Electronics'),
        new OA\Property(property: 'products_count', type: 'integer', example: 12),
    ],
)]
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
