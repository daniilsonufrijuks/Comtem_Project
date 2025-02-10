<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Bids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function placeBid(Request $request, $item)
    {
        // Ensure the user is logged in
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Validate the bid amount
        $request->validate([
            'bid_amount' => 'required|numeric|min:0.01',
//            'item_id' => 'required',
        ]);

        // Find the auction item
//        $auction = Auction::find($item);
//        if (!$auction) {
//            return response()->json(['error' => 'Item not found'], 404);
//        }

        Bids::create([
            'bid_amount' => $request ->bid_amount,
            'user_id' => auth()->id(),
            'item_id' => $item,
        ]);

        return response()->json(['success' => 'Bid placed successfully'], 200);
    }
}
