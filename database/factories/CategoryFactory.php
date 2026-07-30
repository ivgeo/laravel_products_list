<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->numberBetween(1, 1000000).'.',
            'name' => ucfirst($this->faker->words(2, true)),
        ];
    }
}
