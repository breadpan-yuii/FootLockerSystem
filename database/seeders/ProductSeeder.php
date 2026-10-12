<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brands = Brand::pluck('id', 'slug');
        $categories = Category::pluck('id', 'slug');

        $images = [
            'nike-air-force-1-07' => 'nike-air-force-1-07.jpg',
            'nike-air-max-270' => 'nike-air-max-270.png',
            'nike-dunk-low-retro' => 'nike-dunk-low-retro.jpg',
            'nike-air-force-1-shadow' => 'nike-air-force-1-shadow.png',
            'nike-air-max-sc-kids' => 'nike-air-max-sc-kids.png',
            'adidas-samba-og' => 'adidas-samba-og.jpg',
            'adidas-ultraboost-22' => 'adidas-ultraboost-22.png',
            'adidas-gazelle' => 'adidas-gazelle.png',
            'adidas-superstar' => 'adidas-superstar.jpg',
            'air-jordan-1-retro-high-og' => 'air-jordan-1-retro-high-og.jpg',
            'air-jordan-1-low' => 'air-jordan-1-low.jpg',
            'air-jordan-1-mid-kids' => 'air-jordan-1-mid-kids.png',
            'vans-old-skool' => 'vans-old-skool.jpg',
            'vans-knu-skool' => 'vans-knu-skool.png',
            'vans-authentic' => 'vans-authentic.jpg',
            'puma-suede-classic-xxi' => 'puma-suede-classic-xxi.jpg',
            'puma-rs-x' => 'puma-rs-x.png',
            'new-balance-550' => 'new-balance-550.png',
            'new-balance-327' => 'new-balance-327.jpg',
            'converse-chuck-taylor-all-star-high' => 'converse-chuck-taylor-all-star-high.jpg',
            'converse-run-star-hike' => 'converse-run-star-hike.jpg',
            'asics-gel-kayano-14' => 'asics-gel-kayano-14.jpg',
            'asics-gel-1130' => 'asics-gel-1130.jpg',
            'nike-sportswear-club-fleece-hoodie' => 'nike-sportswear-club-fleece-hoodie.png',
            'adidas-essential-3-stripes-tee' => 'adidas-essential-3-stripes-tee.jpg',
            'vans-old-skool-ii-backpack' => 'vans-old-skool-ii-backpack.jpg',
            'new-balance-performance-cap' => 'new-balance-performance-cap.jpg',
        ];

        $products = [
            ['nike', 'mens-shoes', 'Nike Air Force 1 \'07', 6495, 5495, 'men', true, 'The radiance lives on in the Nike Air Force 1 \'07, the b-ball icon that puts a fresh spin on what you know best.'],
            ['nike', 'mens-shoes', 'Nike Air Max 270', 7495, null, 'men', true, 'Nike\'s first lifestyle Air Max brings you style, comfort and big attitude.'],
            ['nike', 'mens-shoes', 'Nike Dunk Low Retro', 6195, 5295, 'men', true, 'Created for the hardwood but taken to the streets, the Nike Dunk Low returns.'],
            ['nike', 'womens-shoes', 'Nike Air Force 1 Shadow', 6995, null, 'women', false, 'A playful twist on the classic AF1 with double the branding and lifted midsole.'],
            ['nike', 'kids-shoes', 'Nike Air Max SC (Kids)', 3495, 2995, 'kids', false, 'Comfortable cushioning and a classic look for active kids.'],

            ['adidas', 'mens-shoes', 'adidas Samba OG', 5995, null, 'men', true, 'Born on the pitch, the Samba is a timeless icon of street style.'],
            ['adidas', 'mens-shoes', 'adidas Ultraboost 22', 9995, 8495, 'men', true, 'Feel the energy return with every stride in the Ultraboost 22.'],
            ['adidas', 'mens-shoes', 'adidas Gazelle', 5795, null, 'unisex', false, 'A premium suede classic with a clean, retro silhouette.'],
            ['adidas', 'womens-shoes', 'adidas Superstar', 5495, 4795, 'women', false, 'The iconic shell-toe trainer that started it all.'],

            ['jordan', 'mens-shoes', 'Air Jordan 1 Retro High OG', 10995, null, 'men', true, 'The shoe that started a dynasty, in premium tumbled leather.'],
            ['jordan', 'mens-shoes', 'Air Jordan 1 Low', 6495, 5995, 'men', false, 'The low-cut classic for everyday rotation.'],
            ['jordan', 'kids-shoes', 'Air Jordan 1 Mid (Kids)', 4295, null, 'kids', false, 'Legendary style scaled down for the next generation.'],

            ['vans', 'mens-shoes', 'Vans Old Skool', 3795, 3295, 'unisex', true, 'The classic side-stripe skate shoe that never goes out of style.'],
            ['vans', 'mens-shoes', 'Vans Knu Skool', 4595, null, 'unisex', false, 'A puffy, chunky take on the Old Skool from the 90s.'],
            ['vans', 'womens-shoes', 'Vans Authentic', 3295, 2795, 'women', false, 'The original Vans canvas deck shoe since 1966.'],

            ['puma', 'mens-shoes', 'Puma Suede Classic XXI', 4295, null, 'unisex', false, 'The legendary Suede returns with premium detailing.'],
            ['puma', 'mens-shoes', 'Puma RS-X', 5995, 4695, 'men', false, 'Chunky, futuristic and bold — the RS-X makes a statement.'],

            ['new-balance', 'mens-shoes', 'New Balance 550', 6495, null, 'men', true, 'A timeless low-top born on the hardwood in 1989.'],
            ['new-balance', 'womens-shoes', 'New Balance 327', 5495, 4795, 'women', false, 'Retro-inspired lifestyle runner with a bold oversized N.'],

            ['converse', 'mens-shoes', 'Converse Chuck Taylor All Star High', 3495, null, 'unisex', false, 'The one that started it all — canvas high-top legend.'],
            ['converse', 'womens-shoes', 'Converse Run Star Hike', 5995, 5195, 'women', false, 'Chuck Taylor meets chunky platform, made to stand out.'],

            ['asics', 'mens-shoes', 'ASICS GEL-KAYANO 14', 8195, null, 'unisex', true, 'Late 2000s running aesthetics with modern GEL cushioning.'],
            ['asics', 'womens-shoes', 'ASICS GEL-1130', 5495, 4795, 'women', false, 'Heritage running design with everyday comfort.'],

            ['nike', 'apparel', 'Nike Sportswear Club Fleece Hoodie', 2795, 2395, 'men', false, 'Soft, warm fleece hoodie for all-day comfort.', 'apparel'],
            ['adidas', 'apparel', 'adidas Essential 3-Stripes Tee', 1495, null, 'unisex', false, 'A go-to tee with classic 3-Stripes branding.', 'apparel'],
            ['vans', 'accessories', 'Vans Old Skool II Backpack', 2295, null, 'unisex', false, 'Everyday backpack with a padded laptop sleeve.', 'accessory'],
            ['new-balance', 'accessories', 'New Balance Performance Cap', 1195, 995, 'unisex', false, 'Lightweight training cap with moisture-wicking fabric.', 'accessory'],
        ];

        foreach ($products as $row) {
            [$brandSlug, $categorySlug, $name, $price, $salePrice, $gender, $featured, $description] = $row;
            $type = $row[8] ?? 'shoe';
            $slug = Str::slug($name);

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'brand_id' => $brands[$brandSlug],
                    'category_id' => $categories[$categorySlug],
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'stock' => random_int(15, 60),
                    'image' => isset($images[$slug])
                        ? '/images/products/'.$images[$slug]
                        : 'https://placehold.co/600x600/111827/ffffff?text='.urlencode($name),
                    'gender' => $gender,
                    'is_featured' => $featured,
                    'is_active' => true,
                ]
            );

            $product->variants()->delete();

            $sizes = match ($type) {
                'apparel' => ['S', 'M', 'L', 'XL'],
                'accessory' => ['One Size'],
                default => $gender === 'kids'
                    ? ['1', '2', '3', '4', '5']
                    : ['7', '8', '9', '10', '11', '12'],
            };

            foreach ($sizes as $size) {
                $product->variants()->create([
                    'size' => $size,
                    'sku' => strtoupper(Str::random(8)),
                    'stock' => random_int(3, 20),
                ]);
            }
        }
    }
}
