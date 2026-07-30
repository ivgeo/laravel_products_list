<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    //private const TOTAL = 1000000;
    private const TOTAL = 10000;

    private const CHUNK_SIZE = 500;

    public function run(): void
    {
        $categoryIds = Category::pluck('id');

        if ($categoryIds->isEmpty()) {
            $this->command?->warn('No categories found — run CategorySeeder first.');

            return;
        }

        $now = now();
        $rows = [];

        for ($i = 1; $i <= self::TOTAL; $i++) {
            $rows[] = [
                'title' => Str::title(fake()->words(random_int(2, 5), true)),
                'category_id' => $categoryIds->random(),
                'content' => fake()->paragraphs(random_int(1, 3), true),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($rows) === self::CHUNK_SIZE) {
                DB::table('products')->insert($rows);
                $rows = [];
            }
        }

        if ($rows !== []) {
            DB::table('products')->insert($rows);
        }

        $this->command?->info(sprintf('Seeded %d products.', self::TOTAL));
    }
}
