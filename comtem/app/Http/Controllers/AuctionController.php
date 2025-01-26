<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function getitems()
    {
        // Fetch auction items from the database (you can modify this to match your actual table structure)
        $auctionItems = Auction::all();

        return response()->json($auctionItems);
    }


    public function show($id): \Illuminate\Http\JsonResponse
    {
        $auctionItems = Auction::find($id, ['name', 'description', 'img', 'starting_bid', 'start_time', 'end_time']);

        if (!$auctionItems) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        return response()->json($auctionItems);
    }
}
