<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laravel T-Shirt',
                'description' => 'A comfortable t-shirt featuring the Laravel logo. Made from 100% cotton.',
                'price' => 29.99,
                'stock_quantity' => 50,
                'image' => "storage/products/tshirt.png",
            ],
            [
                'name' => 'Laravel Mug',
                'description' => 'A ceramic mug with the Laravel logo. Perfect for your morning coffee.',
                'price' => 19.99,
                'stock_quantity' => 30,
                'image' => "storage/products/mug.png",
            ],
            [
                'name' => 'Laravel Sticker Pack',
                'description' => 'A set of high-quality stickers featuring Laravel-related designs.',
                'price' => 9.99,
                'stock_quantity' => 100,
                'image' => "storage/products/stickers.png",
            ],
            [
                'name' => 'Laravel Hoodie',
                'description' => 'A warm and cozy hoodie with the Laravel logo. Perfect for coding sessions.',
                'price' => 49.99,
                'stock_quantity' => 25,
                'image' => "storage/products/hoodie.png",
            ],
            [
                'name' => 'Laravel Notebook',
                'description' => 'A premium notebook with the Laravel logo. Great for planning and note-taking.',
                'price' => 14.99,
                'stock_quantity' => 40,
                'image' => "storage/products/notebook.png",
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
