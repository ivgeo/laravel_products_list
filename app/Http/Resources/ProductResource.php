<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Product',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Wireless Mouse'),
        new OA\Property(property: 'content', type: 'string'),
        new OA\Property(property: 'price', type: 'number', format: 'float', nullable: true, example: 19.99),
        new OA\Property(property: 'category', ref: '#/components/schemas/Category', type: 'object'),
        new OA\Property(property: 'default_image', ref: '#/components/schemas/ProductImage', type: 'object', nullable: true),
        new OA\Property(property: 'images', type: 'array', items: new OA\Items(ref: '#/components/schemas/ProductImage')),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ],
)]
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'price' => $this->whenLoaded('currentPrice', fn () => $this->currentPrice?->price !== null
                ? (float) $this->currentPrice->price
                : null),
            'category' => new CategoryResource($this->category),
            'default_image' => $this->whenLoaded('defaultImage', fn () => new ProductImageResource($this->defaultImage)),
            'images' => $this->whenLoaded('images', fn () => ProductImageResource::collection($this->images)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
