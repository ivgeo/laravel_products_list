<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('products:refresh-prices {--full : Recompute the current price for every product instead of only today\'s changes}')]
#[Description('Refresh the product_prices cache from price_lists')]
class RefreshProductPrices extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $affected = $this->option('full')
            ? $this->refreshAll()
            : $this->refreshToday();

        $this->info(sprintf('Refreshed %d product price(s).', $affected));
    }

    /**
     * Recompute the current price for every product: the price_lists row with
     * the latest date <= today, for each product.
     */
    private function refreshAll(): int
    {
        return DB::affectingStatement(<<<'SQL'
            INSERT INTO product_prices (product_id, price, effective_date, created_at, updated_at)
            SELECT pl.product_id, pl.price, pl.date, NOW(), NOW()
            FROM price_lists pl
            INNER JOIN (
                SELECT product_id, MAX(date) AS max_date
                FROM price_lists
                WHERE date <= CURDATE()
                GROUP BY product_id
            ) latest ON latest.product_id = pl.product_id AND latest.max_date = pl.date
            ON DUPLICATE KEY UPDATE
                price = VALUES(price),
                effective_date = VALUES(effective_date),
                updated_at = VALUES(updated_at)
            SQL);
    }

    /**
     * Only touch products whose price_lists date is exactly today — i.e. a
     * previously future-dated (scheduled) price that just became active.
     */
    private function refreshToday(): int
    {
        return DB::affectingStatement(<<<'SQL'
            INSERT INTO product_prices (product_id, price, effective_date, created_at, updated_at)
            SELECT product_id, price, date, NOW(), NOW()
            FROM price_lists
            WHERE date = CURDATE()
            ON DUPLICATE KEY UPDATE
                price = VALUES(price),
                effective_date = VALUES(effective_date),
                updated_at = VALUES(updated_at)
            SQL);
    }
}
