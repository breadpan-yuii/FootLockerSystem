<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Nike', 'logo' => '/images/brands/nike.jpg', 'description' => 'Just Do It. Iconic sneakers and sportswear.'],
            ['name' => 'Adidas', 'logo' => '/images/brands/adidas.jpg', 'description' => 'Impossible is Nothing. Performance and lifestyle.'],
            ['name' => 'Jordan', 'logo' => '/images/brands/jordan.jpg', 'description' => 'Basketball heritage and street style.'],
            ['name' => 'Vans', 'logo' => '/images/brands/vans.jpg', 'description' => 'Off the Wall skate classics.'],
            ['name' => 'Puma', 'logo' => '/images/brands/puma.jpg', 'description' => 'Forever Faster sportswear.'],
            ['name' => 'New Balance', 'logo' => '/images/brands/new-balance.jpg', 'description' => 'Endorsed by no one. Built for everyone.'],
            ['name' => 'Converse', 'logo' => '/images/brands/converse.jpg', 'description' => 'Classic Chuck Taylor styles.'],
            ['name' => 'Asics', 'logo' => '/images/brands/asics.png', 'description' => 'Sound mind, sound body running tech.'],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($brand['name'])],
                [
                    'name' => $brand['name'],
                    'logo' => $brand['logo'],
                    'description' => $brand['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
