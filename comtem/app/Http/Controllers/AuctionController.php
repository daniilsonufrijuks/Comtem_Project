<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function showAuction()
    {
        // Fetch auction items from the database (you can modify this to match your actual table structure)
        $auctionItems = Auction::all();

        return response()->json($auctionItems);
    }
}
