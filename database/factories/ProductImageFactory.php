<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProductImage>
 */
class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'disk' => 'public',
            'path' => sprintf('products/%s.jpg', Str::uuid()),
            'original_filename' => Str::slug($this->faker->words(2, true)).'.jpg',
            'mime_type' => 'image/jpeg',
            'size' => $this->faker->numberBetween(20_000, 800_000),
            'width' => $this->faker->randomElement([600, 800, 1024, 1200]),
            'height' => $this->faker->randomElement([600, 800, 1024, 1200]),
            'alt_text' => $this->faker->sentence(4),
            'sort_order' => 0,
        ];
    }
}
