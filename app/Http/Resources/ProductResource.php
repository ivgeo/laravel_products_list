<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
