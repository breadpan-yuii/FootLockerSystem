<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => "Men's Shoes", 'gender' => 'men', 'image' => '/images/categories/mens.jpg', 'description' => 'Sneakers and athletic shoes for men.'],
            ['name' => "Women's Shoes", 'gender' => 'women', 'image' => '/images/categories/womens.jpg', 'description' => 'Sneakers and athletic shoes for women.'],
            ['name' => "Kids' Shoes", 'gender' => 'kids', 'image' => '/images/categories/kids.jpg', 'description' => 'Comfortable shoes for kids.'],
            ['name' => 'Apparel', 'gender' => 'unisex', 'image' => '/images/categories/apparel.gif', 'description' => 'Shirts, hoodies and activewear.'],
            ['name' => 'Accessories', 'gender' => 'unisex', 'image' => '/images/products/new-balance-performance-cap.png', 'description' => 'Caps, socks, bags and more.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'gender' => $category['gender'],
                    'description' => $category['description'],
                    'image' => $category['image'],
                    'is_active' => true,
                ]
            );
        }
    }
}
