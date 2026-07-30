<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('categories:refresh-product-counts')]
#[Description('Refresh the category_products cache: total products per category, including descendant categories')]
class RefreshCategoryProductCounts extends Command
{
    private const CHUNK_SIZE = 200;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $total = 0;

        Category::query()
            ->orderBy('id')
            ->chunkById(self::CHUNK_SIZE, function ($categories) use (&$total) {
                $ids = $categories->pluck('id')->all();
                $this->refreshChunk($ids);
                $total += count($ids);
            });

        $this->info(sprintf('Refreshed product counts for %d categor%s.', $total, $total === 1 ? 'y' : 'ies'));
    }

    /**
     * For each category in the chunk, count products belonging to it or to
     * any of its descendants (matched via the "code" prefix, e.g. "1.2."
     * covers "1.2.1.", "1.2.2.3.", ...), and upsert the result.
     *
     * @param  array<int, int>  $categoryIds
     */
    private function refreshChunk(array $categoryIds): void
    {
        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

        DB::statement(<<<SQL
            INSERT INTO category_products (category_id, products_count, created_at, updated_at)
            SELECT c.id, COUNT(p.id), NOW(), NOW()
            FROM categories c
            INNER JOIN categories d ON d.code LIKE CONCAT(c.code, '%')
            LEFT JOIN products p ON p.category_id = d.id
            WHERE c.id IN ({$placeholders})
            GROUP BY c.id
            ON DUPLICATE KEY UPDATE
                products_count = VALUES(products_count),
                updated_at = VALUES(updated_at)
            SQL, $categoryIds);
    }
}
