<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;

class OrderController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.description' => 'nullable|string',
            'items.*.image' => 'nullable|string',
            'items.*.category' => 'required|string',
            'items.*.total_price' => 'required|numeric',
            'items.*.shipping_address' => 'nullable|string',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'total' => 'required|numeric',
            'award' => 'required|integer',
            'shipping' => 'required|numeric',
            'shipping_address' => 'nullable|string',
        ]);



        $user = $request->user();
        // FIX: Use 'awards' column (plural)
        $award = $user->awards ?? 0;

        // SHIPPING LOGIC (SERVER AUTHORITY)
        $shippingCost = $award > 100 ? 0 : 3;

        // ITEMS TOTAL
        $itemsTotal = collect($request->items)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // FINAL TOTAL
        $finalTotal = $itemsTotal + $shippingCost;

        $orderNumber = uniqid();

        $order = Orders::create([
            'user_id' => auth()->id(),
            'total' => $finalTotal,
            'shipping_address' => $request->shipping_address ?? null, // Store order-level shipping address
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


        $itemIds = [];

        foreach ($request->items as $item) {
//            \Log::info('Processing item:', $item);
            $orderItem = \App\Models\OrderGoods::create([
                'order_id' => $order->id,
//                'order_number' => $orderNumber,
                'status' => 'pending',
                'name' => $item['name'],
                'price' => $item['price'],
                'description' => $item['description'] ?? null,
                'image' => $item['image'] ?? null,
//                'category' => $item['category'],
                'category' => $item['category'] ?? null,  // Check if category exists
                'total_price' => $item['total_price'] ?? null,
                'shipping_address' => $item['shipping_address'] ?? null,
            ]);

            $itemIds[] = $orderItem->id;

            $order->products()->attach($item['id'], [
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Store only the item IDs in the orders table
//        $order->items = implode(',', $itemIds);  // Store as a comma-separated string
        $order->save();



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

    public function userOrders()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $orders = DB::table('orders')
            ->join('goods_orders', 'orders.id', '=', 'goods_orders.order_id')
            ->where('orders.user_id', $user->id)
            ->select(
                'orders.id as order_id',
                'orders.status as order_status',
                'orders.total as order_total',
                'orders.created_at',
                'goods_orders.id as item_id',
                'goods_orders.name as item_name',
                'goods_orders.price as item_price',
                'goods_orders.total_price as item_total_price',
                'goods_orders.image',
                'goods_orders.category'
            )
            ->orderBy('orders.ordered_at', 'desc')
            ->get();

        return response()->json($orders);
    }
}
