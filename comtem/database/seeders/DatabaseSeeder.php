<?php

namespace Database\Seeders;

use App\Models\Products;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::create([
//            'name' => 'John Doe',
//            'email' => 'john.doe@example.com',
//            'password' => '11111',
//        ]);
//
//        // Add more users if needed
//        User::create([
//            'name' => 'Jane Smith',
//            'email' => 'jane.smith@example.com',
//            'password' => '12345',
//        ]);

        Products::create([
            'name' => 'Intel Core i5',
            'price' => 100,
            'description' => 'Intel Core i5 processor, 5 Ghz',
            'category' => 'Componenets'
        ]);

        Products::create([
            'name' => 'Intel Core i7',
            'price' => 300,
            'description' => 'Intel Core i7 processor, 7 Ghz',
            'category' => 'Componenets'
        ]);
    }
}
