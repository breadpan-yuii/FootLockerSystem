<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@footlocker.test'],
            [
                'name' => 'Store Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0917 000 0001',
                'address' => 'Foot Locker Head Office, Metro Manila',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@footlocker.test'],
            [
                'name' => 'Juan Dela Cruz',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '0917 000 0002',
                'address' => '123 Sample St, Barangay Uno, Quezon City',
            ]
        );

        $this->call([
            BrandSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
