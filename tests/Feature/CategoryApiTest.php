<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_paginated_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/categories');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['code', 'name', 'full_name', 'products_count'],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_index_returns_products_count_from_category_products_table(): void
    {
        $category = Category::factory()->create();
        CategoryProduct::create(['category_id' => $category->id, 'products_count' => 42]);

        $response = $this->getJson('/api/categories');

        $response->assertOk()->assertJsonPath('data.0.products_count', 42);
    }

    public function test_index_returns_null_products_count_when_not_yet_computed(): void
    {
        Category::factory()->create();

        $response = $this->getJson('/api/categories');

        $response->assertOk()->assertJsonPath('data.0.products_count', null);
    }
}
