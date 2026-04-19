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

        Bids::create([
            'bid_amount' => $request ->bid_amount,
            'user_id' => auth()->id(),
            'item_id' => $item,
        ]);

        return response()->json(['success' => 'Bid placed successfully'], 200);
    }

    /**
     * Get the authenticated user's bid history with item details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userBids(Request $request)
    {
        $user = Auth::user();

        $bids = Bids::with('auction')
        ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($bid) {
                $auction = $bid->auction;

                if (!$auction) {
                    return $bid;
                }

                // Auction ended?
                $auctionEnded = $auction->end_time && now()->gt($auction->end_time);

                // Higher bid exists?
                $higherBidExists = Bids::where('item_id', $bid->item_id)
                    ->where('bid_amount', '>', $bid->bid_amount)
                    ->exists();

                $isWinning = !$auctionEnded && !$higherBidExists;

                // Attach computed fields
                $bid->is_winning = $isWinning;
                $bid->auction_ended = $auctionEnded;

                $bid->product = (object) [
                    'name' => $auction->name,
                    'img' => $auction->img,
                    'starting_bid' => $auction->starting_bid,
                ];

                return $bid;
            });

        return response()->json(['data' => $bids]);
    }
}
