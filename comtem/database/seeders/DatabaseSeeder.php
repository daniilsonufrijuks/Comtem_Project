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
//        DB::table('books')->insert([
//            [
//                'name' => 'Computer Basics',
//                'description' => 'Learn about basic computer components and how they work.',
//                'file_path' => '/storage/books/computer_basics.pdf',
//                'img' => '/storage/books/computer_basics_cover.jpg',
//                'created_at' => now(),
//                'updated_at' => now(),
//            ],
//            [
//                'name' => 'Building Your Own PC',
//                'description' => 'Step-by-step guide to building a PC from scratch.',
//                'file_path' => '/storage/books/building_pc.pdf',
//                'img' => '/storage/books/building_pc_cover.jpg',
//                'created_at' => now(),
//                'updated_at' => now(),
//            ],
//            [
//                'name' => 'Computer Networks',
//                'description' => 'A beginner’s guide to networking concepts and setup.',
//                'file_path' => '/storage/books/networks.pdf',
//                'img' => '/storage/books/networks_cover.jpg',
//                'created_at' => now(),
//                'updated_at' => now(),
//            ]
//        ]);
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

//        Products::create([
//            'name' => 'Intel Core i5F',
//            'price' => 105,
//            'description' => 'Intel Core i5 processor, 5 Ghz',
//            'image' => 'images/front/inteli5.png',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'Intel Core i7',
//            'price' => 200,
//            'description' => 'Intel Core i7 processor, 6 Ghz',
//            'image' => 'images/front/inteli7.jpg',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'Intel Core i5',
//            'price' => 150,
//            'description' => 'Intel Core i5 processor, 6 Ghz',
//            'image' => 'images/front/inteli5.png',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'Intel Core i9',
//            'price' => 400,
//            'description' => 'Intel Core i9 processor, 7 Ghz',
//            'image' => 'images/front/inteli9.jpg',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'Intel Core i9',
//            'price' => 450,
//            'description' => 'Intel Core i9 processor, 9 Ghz',
//            'image' => 'images/front/inteli9.jpg',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'Intel Core i3',
//            'price' => 50,
//            'description' => 'Intel Core i3 processor, 3 Ghz',
//            'image' => 'images/front/inteli3.jpg',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'Adata SSD 1 TB',
//            'price' => 70,
//            'description' => 'Adata SSD 1 TB DISK',
//            'image' => 'images/front/adata.jpg',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'WD SSD 1 TB',
//            'price' => 90,
//            'description' => 'WD SSD 1 TB DISK',
//            'image' => 'images/front/adata.jpg',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//
//        Products::create([
//            'name' => 'Nvidia RTX 3060',
//            'price' => 300,
//            'description' => 'Nvidia RTX 3060 6GB',
//            'image' => 'images/front/3060.jpg',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'Nvidia RTX 3080',
//            'price' => 500,
//            'description' => 'Nvidia RTX 3080 12 GB',
//            'image' => 'images/front/3080.jpg',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'Nvidia RTX 3090',
//            'price' => 700,
//            'description' => 'Nvidia RTX 3090 12 GB',
//            'image' => 'images/front/3090.jpg',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//
//        Products::create([
//            'name' => 'Kingston RAM 3200 MHz 8GB',
//            'price' => 50,
//            'description' => 'Kingston RAM 3200 MHz 8GB 1 stick',
//            'image' => 'images/front/kingstonram.jpg',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'Kingston RAM 3200 MHz 16GB',
//            'price' => 70,
//            'description' => 'Kingston RAM 3200 MHz 16GB 1 stick',
//            'image' => 'images/front/kingstonram.jpg',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'Kingston RAM 3200 MHz 32GB',
//            'price' => 80,
//            'description' => 'Kingston RAM 3200 MHz 32GB 1 stick',
//            'image' => 'images/front/kingstonram.jpg',
//            'category' => 'Component',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'MSI Gaming Laptop',
//            'price' => 700,
//            'description' => 'MSI Gaming Laptop:
//                                Intel Core i7 processor, 4 Ghz, 12 cores, 24 threads
//                                NVIDIA RTX 3060 6 Gb
//                                RAM Fury 16 Gb 3200 Mhz
//                                SSD 1 TB',
//            'image' => 'images/front/msilp.jpg',
//            'category' => 'Laptop',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'Asus Gaming Laptop',
//            'price' => 870,
//            'description' => 'Asus Gaming Laptop:
//                                Intel Core i9 processor, 7 Ghz, 12 cores, 24 threads
//                                NVIDIA RTX 3080 6 Gb
//                                RAM Fury 16 Gb 3200 Mhz
//                                SSD 1 TB',
//            'image' => 'images/front/asusgl.jpg',
//            'category' => 'Laptop',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'Gigabyte Laptop',
//            'price' => 900,
//            'description' => 'Gigabyte Laptop:
//                                Intel Core i9 processor, 7 Ghz, 12 cores, 24 threads
//                                RAM Fury 32 Gb 3200 Mhz
//                                SSD 1 TB',
//            'image' => 'images/front/giglp.jpg',
//            'category' => 'Laptop',
//            'admin_id' => 1
//        ]);
//
//        Products::create([
//            'name' => 'MSI Gaming Station',
//            'price' => 700,
//            'description' => 'MSI Gaming Station:
//                                MSI Gaming Station:
//                                Intel Core i5 processor, 5 Ghz
//                                NVIDIA RTX 3060 8 Gb
//                                RAM Fury 32 Gb 3200 Mhz
//                                SSD 1 TB
//                                HDD 2 TB
//                                Power Supply Be quiet 700 W',
//            'image' => 'images/front/pc.png',
//            'category' => 'Pc',
//            'admin_id' => 1
//        ]);


//        Products::create([
//            'name' => 'Intel Core i7',
//            'price' => 300,
//            'description' => 'Intel Core i7 processor, 7 Ghz',
//            'category' => 'Componenets'
//        ]);
    }
}
