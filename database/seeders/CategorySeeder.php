<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Root category names. Their position in this list (1-based) becomes
     * the leading segment of the code, e.g. "Electronics" -> "1.".
     */
    private const ROOT_NAMES = [
        'Electronics',
        'Fashion & Apparel',
        'Home & Garden',
        'Sports & Outdoors',
        'Toys & Games',
        'Health & Beauty',
        'Automotive',
        'Books & Media',
        'Groceries & Gourmet Food',
        'Office & School Supplies',
        'Pet Supplies',
        'Jewelry & Watches',
        'Baby & Kids',
        'Tools & Home Improvement',
    ];

    /**
     * Realistic second-level category names per root, used for the direct
     * children of each root category.
     */
    private const SUBCATEGORY_POOLS = [
        'Electronics' => ['Smartphones & Accessories', 'Laptops & Computers', 'Audio & Headphones', 'Cameras & Photography', 'Wearable Technology', 'Gaming', 'TV & Home Theater', 'Smart Home', 'Networking', 'Tablets & E-Readers'],
        'Fashion & Apparel' => ["Men's Clothing", "Women's Clothing", "Kids' Clothing", 'Shoes', 'Bags & Luggage', 'Watches & Sunglasses', 'Activewear', 'Outerwear', 'Underwear & Sleepwear'],
        'Home & Garden' => ['Furniture', 'Kitchen & Dining', 'Bedding & Bath', 'Home Décor', 'Lighting', 'Outdoor & Patio', 'Storage & Organization', 'Garden & Plants', 'Cleaning Supplies'],
        'Sports & Outdoors' => ['Fitness Equipment', 'Camping & Hiking', 'Cycling', 'Team Sports', 'Water Sports', 'Winter Sports', 'Hunting & Fishing', 'Yoga & Wellness'],
        'Toys & Games' => ['Action Figures', 'Building Sets', 'Board Games & Puzzles', 'Dolls & Accessories', 'Outdoor Play', 'Educational Toys', 'Remote Control Toys', 'Arts & Crafts'],
        'Health & Beauty' => ['Skincare', 'Makeup', 'Hair Care', 'Personal Care', 'Vitamins & Supplements', 'Fragrances', 'Oral Care', 'Medical Supplies'],
        'Automotive' => ['Car Electronics', 'Replacement Parts', 'Tools & Equipment', 'Exterior Accessories', 'Interior Accessories', 'Tires & Wheels', 'Motorcycle Parts', 'Car Care'],
        'Books & Media' => ['Fiction', 'Non-Fiction', "Children's Books", 'Textbooks', 'Movies & TV', 'Music', 'Comics & Graphic Novels', 'Magazines'],
        'Groceries & Gourmet Food' => ['Snacks', 'Beverages', 'Pantry Staples', 'Breakfast Foods', 'Organic Foods', 'Candy & Chocolate', 'Coffee & Tea', 'Canned & Jarred Goods'],
        'Office & School Supplies' => ['Writing Instruments', 'Paper Products', 'Desk Accessories', 'Backpacks & Bags', 'Printers & Ink', 'Filing & Storage', 'Art Supplies', 'Calculators'],
        'Pet Supplies' => ['Dog Supplies', 'Cat Supplies', 'Fish & Aquatic', 'Bird Supplies', 'Small Animal Supplies', 'Pet Food', 'Pet Grooming', 'Pet Beds & Furniture'],
        'Jewelry & Watches' => ['Necklaces', 'Rings', 'Earrings', 'Bracelets', 'Watches', 'Jewelry Sets', 'Precious Stones', 'Jewelry Boxes'],
        'Baby & Kids' => ['Diapering', 'Feeding', 'Nursery Furniture', 'Baby Gear', 'Baby Clothing', 'Toys for Babies', 'Safety & Health', 'Strollers & Car Seats'],
        'Tools & Home Improvement' => ['Power Tools', 'Hand Tools', 'Plumbing', 'Electrical', 'Paint & Wall Treatments', 'Hardware', 'Measuring & Layout Tools', 'Safety Equipment'],
    ];

    /**
     * Modifier/noun pools used to build plausible names for third-level
     * categories and deeper, where a curated real-world list isn't practical.
     */
    private const MODIFIERS = ['Premium', 'Deluxe', 'Classic', 'Wireless', 'Portable', 'Heavy-Duty', 'Eco-Friendly', 'Smart', 'Compact', 'Outdoor', 'Indoor', 'Professional', 'Value', 'Limited Edition', 'Mini', 'Vintage', 'Modern', 'Rugged', 'Lightweight', 'Advanced'];

    private const GENERIC_NOUNS = ['Accessories', 'Parts', 'Kit', 'Set', 'Bundle', 'Series', 'Collection', 'Essentials', 'Pro Line', 'Starter Pack'];

    /**
     * Weighted distribution for how many children a category gets: mostly
     * a handful, occasionally as many as 7, per the "5-6-7 for some" ask.
     */
    private const CHILD_COUNT_WEIGHTS = [1 => 38, 2 => 27, 3 => 15, 4 => 9, 5 => 6, 6 => 3, 7 => 2];

    private int $count = 0;

    public function run(): void
    {
        foreach (self::ROOT_NAMES as $index => $rootName) {
            $rootCode = ($index + 1).'.';
            // Vary how deep each root's tree goes, so not every branch reaches the same depth.
            $maxDepth = random_int(4, 5);

            Category::create(['code' => $rootCode, 'name' => $rootName]);
            $this->count++;

            $this->generateChildren($rootCode, $rootName, 1, $maxDepth);
        }

        $this->command?->info(sprintf('Seeded %d categories.', $this->count));
    }

    private function generateChildren(string $parentCode, string $parentName, int $depth, int $maxDepth): void
    {
        $names = $depth === 1
            ? $this->pickSubcategoryNames($parentName, $this->randomChildCount())
            : $this->generateGenericNames($this->randomChildCount());

        foreach ($names as $i => $name) {
            $code = "{$parentCode}".($i + 1).'.';

            Category::create(['code' => $code, 'name' => $name]);
            $this->count++;

            if ($depth < $maxDepth) {
                $this->generateChildren($code, $name, $depth + 1, $maxDepth);
            }
        }
    }

    private function pickSubcategoryNames(string $rootName, int $count): array
    {
        $pool = self::SUBCATEGORY_POOLS[$rootName] ?? self::GENERIC_NOUNS;
        $count = min($count, count($pool));
        $keys = (array) array_rand($pool, $count);

        return array_map(fn ($key) => $pool[$key], $keys);
    }

    private function generateGenericNames(int $count): array
    {
        $combos = [];
        foreach (self::MODIFIERS as $modifier) {
            foreach (self::GENERIC_NOUNS as $noun) {
                $combos[] = "{$modifier} {$noun}";
            }
        }

        shuffle($combos);

        return array_slice($combos, 0, $count);
    }

    private function randomChildCount(): int
    {
        $total = array_sum(self::CHILD_COUNT_WEIGHTS);
        $roll = random_int(1, $total);
        $cumulative = 0;

        foreach (self::CHILD_COUNT_WEIGHTS as $count => $weight) {
            $cumulative += $weight;
            if ($roll <= $cumulative) {
                return $count;
            }
        }

        return 1;
    }
}
