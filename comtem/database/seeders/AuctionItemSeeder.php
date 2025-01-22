<?php

namespace Database\Seeders;

use App\Models\Auction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuctionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Auction::create([
            'name' => 'MacBook Pro',
            'description' => 'MacBook Pro laptop uniq',
            'starting_bid' => 10000,
            'img' => 'images/front/mcbook.jpg',
        ]);
    }
}
