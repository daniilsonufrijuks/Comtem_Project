<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        $order = Orders::create([
            'user_id' => auth()->id(),
            'items' => json_encode($request->items),
            'total' => $request->total ?? 0,
        ]);

        return response()->json([
            'message' => 'Order placed successfully!',
            'order' => $order,
        ]);
    }
}
