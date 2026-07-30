<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefreshCategoryProductCountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_counts_products_including_descendant_categories(): void
    {
        $root = Category::create(['code' => '1.', 'name' => 'Root']);
        $child = Category::create(['code' => '1.1.', 'name' => 'Child']);
        $grandchild = Category::create(['code' => '1.1.1.', 'name' => 'Grandchild']);
        $sibling = Category::create(['code' => '2.', 'name' => 'Sibling']);

        Product::factory()->count(2)->create(['category_id' => $root->id]);
        Product::factory()->count(3)->create(['category_id' => $child->id]);
        Product::factory()->count(1)->create(['category_id' => $grandchild->id]);
        Product::factory()->count(5)->create(['category_id' => $sibling->id]);

        $this->artisan('categories:refresh-product-counts')->assertSuccessful();

        $this->assertSame(6, CategoryProduct::find($root->id)->products_count);
        $this->assertSame(4, CategoryProduct::find($child->id)->products_count);
        $this->assertSame(1, CategoryProduct::find($grandchild->id)->products_count);
        $this->assertSame(5, CategoryProduct::find($sibling->id)->products_count);
    }

    public function test_it_does_not_conflate_sibling_codes_with_similar_prefixes(): void
    {
        $one = Category::create(['code' => '1.1.', 'name' => 'One']);
        $ten = Category::create(['code' => '1.10.', 'name' => 'Ten']);

        Product::factory()->create(['category_id' => $one->id]);
        Product::factory()->create(['category_id' => $ten->id]);

        $this->artisan('categories:refresh-product-counts')->assertSuccessful();

        $this->assertSame(1, CategoryProduct::find($one->id)->products_count);
        $this->assertSame(1, CategoryProduct::find($ten->id)->products_count);
    }

    public function test_it_upserts_on_repeated_runs(): void
    {
        $category = Category::create(['code' => '1.', 'name' => 'Root']);
        Product::factory()->count(2)->create(['category_id' => $category->id]);

        $this->artisan('categories:refresh-product-counts')->assertSuccessful();
        Product::factory()->count(3)->create(['category_id' => $category->id]);
        $this->artisan('categories:refresh-product-counts')->assertSuccessful();

        $this->assertSame(1, CategoryProduct::query()->where('category_id', $category->id)->count());
        $this->assertSame(5, CategoryProduct::find($category->id)->products_count);
    }
}
