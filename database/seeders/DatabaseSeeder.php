<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CartsTableSeeder::class,
            CategoriesTableSeeder::class,
            ProductImagesTableSeeder::class,
            ProductsTableSeeder::class,
            UsersTableSeeder::class,
            OrderItemsTableSeeder::class,
            OrdersTableSeeder::class,
            RolesTableSeeder::class,
        ]);
        
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
