<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            ProductImageSeeder::class,
            PriceListSeeder::class,
        ]);

        $this->command->call('products:refresh-prices', ['--full' => true]);
        $this->command->call('categories:refresh-product-counts');

    }
}
