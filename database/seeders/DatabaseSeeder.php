<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin BillUp',
            'email' => 'admin@billup.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Kasir User
        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@billup.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create Categories (Café Theme)
        $categories = [
            ['name' => 'Kopi', 'icon' => '☕'],
            ['name' => 'Minuman Non-Kopi', 'icon' => '🥤'],
            ['name' => 'Makanan', 'icon' => '🍞'],
            ['name' => 'Snack', 'icon' => '🍪'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Products
        $products = [
            // Kopi (category_id: 1)
            ['category_id' => 1, 'name' => 'Espresso', 'description' => 'Strong and bold coffee', 'price' => 15000, 'stock' => 100],
            ['category_id' => 1, 'name' => 'Americano', 'description' => 'Espresso with hot water', 'price' => 18000, 'stock' => 100],
            ['category_id' => 1, 'name' => 'Cappuccino', 'description' => 'Espresso with steamed milk', 'price' => 25000, 'stock' => 100],
            ['category_id' => 1, 'name' => 'Latte', 'description' => 'Espresso with more milk', 'price' => 28000, 'stock' => 100],
            ['category_id' => 1, 'name' => 'Mocha', 'description' => 'Latte with chocolate', 'price' => 30000, 'stock' => 100],
            ['category_id' => 1, 'name' => 'Caramel Macchiato', 'description' => 'Vanilla latte with caramel', 'price' => 32000, 'stock' => 100],

            // Minuman Non-Kopi (category_id: 2)
            ['category_id' => 2, 'name' => 'Teh Manis', 'description' => 'Sweet iced tea', 'price' => 10000, 'stock' => 100],
            ['category_id' => 2, 'name' => 'Teh Tarik', 'description' => 'Pulled tea', 'price' => 15000, 'stock' => 100],
            ['category_id' => 2, 'name' => 'Lemon Tea', 'description' => 'Tea with lemon', 'price' => 12000, 'stock' => 100],
            ['category_id' => 2, 'name' => 'Chocolate', 'description' => 'Hot or iced chocolate', 'price' => 20000, 'stock' => 100],
            ['category_id' => 2, 'name' => 'Matcha Latte', 'description' => 'Green tea latte', 'price' => 28000, 'stock' => 100],
            ['category_id' => 2, 'name' => 'Jus Jeruk', 'description' => 'Fresh orange juice', 'price' => 15000, 'stock' => 100],

            // Makanan (category_id: 3)
            ['category_id' => 3, 'name' => 'Croissant', 'description' => 'Buttery French pastry', 'price' => 25000, 'stock' => 50],
            ['category_id' => 3, 'name' => 'Sandwich', 'description' => 'Club sandwich', 'price' => 35000, 'stock' => 50],
            ['category_id' => 3, 'name' => 'Pasta Carbonara', 'description' => 'Creamy pasta', 'price' => 45000, 'stock' => 30],
            ['category_id' => 3, 'name' => 'Nasi Goreng', 'description' => 'Indonesian fried rice', 'price' => 30000, 'stock' => 40],
            ['category_id' => 3, 'name' => 'French Toast', 'description' => 'Sweet bread with syrup', 'price' => 28000, 'stock' => 50],

            // Snack (category_id: 4)
            ['category_id' => 4, 'name' => 'Cookies', 'description' => 'Chocolate chip cookies', 'price' => 15000, 'stock' => 100],
            ['category_id' => 4, 'name' => 'Brownies', 'description' => 'Fudgy chocolate brownies', 'price' => 20000, 'stock' => 80],
            ['category_id' => 4, 'name' => 'Donut', 'description' => 'Glazed donut', 'price' => 12000, 'stock' => 100],
            ['category_id' => 4, 'name' => 'Muffin', 'description' => 'Blueberry muffin', 'price' => 18000, 'stock' => 80],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
