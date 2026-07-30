<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PriceListSeeder extends Seeder
{
    private const START_DATE = '2019-01-01';

    private const MIN_PRICE_CENTS = 100;

    private const MAX_PRICE_CENTS = 100000;

    private const MIN_ENTRIES_PER_PRODUCT = 1;

    private const MAX_ENTRIES_PER_PRODUCT = 5;

    private const CHUNK_SIZE = 1000;

    public function run(): void
    {
        $productIds = DB::table('products')->pluck('id');

        if ($productIds->isEmpty()) {
            $this->command?->warn('No products found — run ProductSeeder first.');

            return;
        }

        $startDate = Carbon::parse(self::START_DATE)->startOfDay();
        $totalDays = $startDate->diffInDays(now()->startOfDay());

        $now = now();
        $rows = [];
        $count = 0;

        foreach ($productIds as $productId) {
            $entries = random_int(self::MIN_ENTRIES_PER_PRODUCT, self::MAX_ENTRIES_PER_PRODUCT);
            $usedDates = [];

            for ($i = 0; $i < $entries; $i++) {
                do {
                    $date = $startDate->copy()->addDays(random_int(0, $totalDays))->toDateString();
                } while (isset($usedDates[$date]));

                $usedDates[$date] = true;

                $rows[] = [
                    'product_id' => $productId,
                    'date' => $date,
                    'price' => number_format(random_int(self::MIN_PRICE_CENTS, self::MAX_PRICE_CENTS) / 100, 2, '.', ''),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $count++;

                if (count($rows) === self::CHUNK_SIZE) {
                    DB::table('price_lists')->insert($rows);
                    $rows = [];
                }
            }
        }

        if ($rows !== []) {
            DB::table('price_lists')->insert($rows);
        }

        $this->command?->info(sprintf('Seeded %d price list entries.', $count));
    }
}
