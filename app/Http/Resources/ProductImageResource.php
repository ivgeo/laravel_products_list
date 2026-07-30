<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductImage',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'original_filename', type: 'string', example: 'front.jpg'),
        new OA\Property(property: 'mime_type', type: 'string', example: 'image/jpeg'),
        new OA\Property(property: 'size', type: 'integer', example: 102400),
        new OA\Property(property: 'width', type: 'integer', nullable: true, example: 800),
        new OA\Property(property: 'height', type: 'integer', nullable: true, example: 600),
        new OA\Property(property: 'alt_text', type: 'string', nullable: true),
        new OA\Property(property: 'sort_order', type: 'integer', example: 0),
    ],
)]
class ProductImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => Storage::disk($this->disk)->url($this->path),
            'original_filename' => $this->original_filename,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'width' => $this->width,
            'height' => $this->height,
            'alt_text' => $this->alt_text,
            'sort_order' => $this->sort_order,
        ];
    }
}
