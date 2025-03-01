<?php

namespace Tests\Feature;

use App\Models\Auction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuctionControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_all_auction_items()
    {
        // Act: Make a GET request to the endpoint
        $response = $this->getJson('/auction/items');

        $response->assertStatus(200)
            ->assertJsonCount(1);

    }
}
