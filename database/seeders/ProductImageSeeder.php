<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductImageSeeder extends Seeder
{
    private const MIN_IMAGES_PER_PRODUCT = 2;

    private const MAX_IMAGES_PER_PRODUCT = 4;

    private const CHUNK_SIZE = 500;

    public function run(): void
    {
        $productIds = DB::table('products')->pluck('id');

        if ($productIds->isEmpty()) {
            $this->command?->warn('No products found — run ProductSeeder first.');

            return;
        }

        $now = now();
        $rows = [];
        $count = 0;

        foreach ($productIds as $productId) {
            $imageCount = random_int(self::MIN_IMAGES_PER_PRODUCT, self::MAX_IMAGES_PER_PRODUCT);

            for ($sortOrder = 0; $sortOrder < $imageCount; $sortOrder++) {
                $rows[] = [
                    'product_id' => $productId,
                    'disk' => 'public',
                    'path' => sprintf('products/%d/%s.jpg', $productId, Str::uuid()),
                    'original_filename' => Str::slug(fake()->words(2, true)).'.jpg',
                    'mime_type' => 'image/jpeg',
                    'size' => random_int(20_000, 800_000),
                    'width' => fake()->randomElement([600, 800, 1024, 1200]),
                    'height' => fake()->randomElement([600, 800, 1024, 1200]),
                    'alt_text' => fake()->sentence(4),
                    'sort_order' => $sortOrder,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $count++;

                if (count($rows) === self::CHUNK_SIZE) {
                    DB::table('product_images')->insert($rows);
                    $rows = [];
                }
            }
        }

        if ($rows !== []) {
            DB::table('product_images')->insert($rows);
        }

        $this->command?->info(sprintf('Seeded %d product images.', $count));

        $updated = DB::affectingStatement(<<<'SQL'
            UPDATE products p
            INNER JOIN product_images pi ON pi.product_id = p.id AND pi.sort_order = 0
            SET p.default_image_id = pi.id
            SQL);

        $this->command?->info(sprintf('Set default image for %d product(s).', $updated));
    }
}
