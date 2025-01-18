<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;
use Stripe\StripeClient;

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
//        $request->validate([
////            'items' => 'required|array',
////            'total' => 'required|numeric',
//            'items' => 'required|array',
//            'items.*.id' => 'required|integer',
//            'items.*.name' => 'required|string',
//            'items.*.price' => 'required|numeric',
//            'items.*.quantity' => 'required|integer',
//            'total' => 'required|numeric',
//        ]);
//
//        $user = $request->user();
//        // Initialize Stripe client
//        $stripe = new StripeClient(env('STRIPE_SECRET'));
//
//        try {
//            // Create a payment
//            $payment = $stripe->paymentIntents->create([
//                'amount' => $request->total * 100, // Stripe uses cents
//                'currency' => 'usd',
//                'payment_method' => $request->payment_method_id,
//                'confirm' => true,
//            ]);
//
//            // Store the order after successful payment
//            $order = Orders::create([
//                'user_id' => $user->id,
//                'items' => json_encode($request->items),
//                'total' => $request->total,
//            ]);
//
//            return response()->json([
//                'message' => 'Order placed successfully!',
//                'order' => $order,
//            ]);
//
//        } catch (\Exception $e) {
//            return response()->json([
//                'message' => 'Payment failed: ' . $e->getMessage(),
//            ], 400);
//        }
    }
}
