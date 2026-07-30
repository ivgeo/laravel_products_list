<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use DatabaseTruncation;

    public function test_index_returns_paginated_products(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/products');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'content',
                        'category' => ['code', 'name', 'full_name'],
                        'default_image',
                        'images',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_index_returns_empty_list_when_no_products_exist(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_index_search_matches_title(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id, 'title' => 'Wireless Keyboard']);
        Product::factory()->create(['category_id' => $category->id, 'title' => 'Desk Lamp']);

        $response = $this->getJson('/api/products?search=Keyboard');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Wireless Keyboard');
    }

    public function test_index_search_matches_content(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create([
            'category_id' => $category->id,
            'title' => 'Product A',
            'content' => 'Made from recycled aluminium.',
        ]);
        Product::factory()->create([
            'category_id' => $category->id,
            'title' => 'Product B',
            'content' => 'Comes in three colors.',
        ]);

        $response = $this->getJson('/api/products?search=aluminium');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Product A');
    }

    public function test_index_rejects_search_that_is_too_long(): void
    {
        $response = $this->getJson('/api/products?search='.str_repeat('a', 256));

        $response->assertUnprocessable();
    }

    public function test_index_filters_by_price_range(): void
    {
        $category = Category::factory()->create();

        $cheap = Product::factory()->create(['category_id' => $category->id]);
        $mid = Product::factory()->create(['category_id' => $category->id]);
        $expensive = Product::factory()->create(['category_id' => $category->id]);

        ProductPrice::create(['product_id' => $cheap->id, 'price' => 10, 'effective_date' => now()]);
        ProductPrice::create(['product_id' => $mid->id, 'price' => 49.99, 'effective_date' => now()]);
        ProductPrice::create(['product_id' => $expensive->id, 'price' => 100, 'effective_date' => now()]);

        $response = $this->getJson('/api/products?price_min=20&price_max=60');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $mid->id)
            ->assertJsonPath('data.0.price', 49.99);
    }

    public function test_index_excludes_products_without_current_price_when_filtering_by_price(): void
    {
        $category = Category::factory()->create();

        $withPrice = Product::factory()->create(['category_id' => $category->id]);
        Product::factory()->create(['category_id' => $category->id]);

        ProductPrice::create(['product_id' => $withPrice->id, 'price' => 30, 'effective_date' => now()]);

        $response = $this->getJson('/api/products?price_min=1');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $withPrice->id);
    }

    public function test_index_rejects_price_max_below_price_min(): void
    {
        $response = $this->getJson('/api/products?price_min=50&price_max=10');

        $response->assertUnprocessable();
    }

    public function test_index_includes_default_image_and_ordered_images_list(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $first = ProductImage::factory()->create(['product_id' => $product->id, 'sort_order' => 0]);
        $second = ProductImage::factory()->create(['product_id' => $product->id, 'sort_order' => 1]);
        $product->update(['default_image_id' => $first->id]);

        $response = $this->getJson('/api/products');

        $response->assertOk()
            ->assertJsonPath('data.0.default_image.id', $first->id)
            ->assertJsonCount(2, 'data.0.images')
            ->assertJsonPath('data.0.images.0.id', $first->id)
            ->assertJsonPath('data.0.images.1.id', $second->id);
    }

    public function test_index_returns_null_default_image_and_empty_images_when_none_exist(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/products');

        $response->assertOk()
            ->assertJsonPath('data.0.default_image', null)
            ->assertJsonCount(0, 'data.0.images');
    }
}
