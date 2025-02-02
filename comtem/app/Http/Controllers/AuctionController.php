<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{

    public function create()
    {
        return inertia('AddAItem');
    }
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

    public function store(Request $request)
    {

        // Check if the user is logged in
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'starting_bid' => 'required|numeric|min:0',
            'img' => 'nullable|file|image|max:2048',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time'
        ]);

//        $imagePath = null;
//        if ($request->hasFile('img')) {
//            $imagePath = $request->file('img');
//        }

        $imagePath = null;
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/front'), $imageName);
            $imagePath = 'images/front/' . $imageName; // Store the correct relative path in DB
        }

        Auction::create([
            'name' => $request->name,
            'description' => $request->description,
            'starting_bid' => $request->starting_bid,
            'img' => $imagePath,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'user_id' => Auth::id(), // Store the logged-in user's ID
        ]);

        return redirect('/auction')->with('success', 'Auction item added successfully!');
    }
}
