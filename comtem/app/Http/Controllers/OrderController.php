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

        // Attach each item to the order (assuming you have an OrderItem model)
//        foreach ($request['items'] as $item) {
//            $order->items()->create([
//                'product_id' => $item['id'],
//                'quantity' => $item['quantity'],
//                'price' => $item['price'],
//            ]);
//        }
//
//        // Return Stripe session ID for redirect
//        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
//        $session = $stripe->checkout->sessions->create([
//            'payment_method_types' => ['card'],
//            'line_items' => array_map(function ($item) {
//                return [
//                    'price_data' => [
//                        'currency' => 'usd',
//                        'product_data' => ['name' => $item['name']],
//                        'unit_amount' => $item['price'] * 100, // Stripe expects amount in cents
//                    ],
//                    'quantity' => $item['quantity'],
//                ];
//            }, $request['items']),
//            'mode' => 'payment',
//            'success_url' => url('/order-success?session_id={CHECKOUT_SESSION_ID}'),
//            'cancel_url' => url('/cart'),
//        ]);
//
//        return response()->json(['id' => $session->id]);

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
