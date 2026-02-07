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
            'payment_method' => 'nullable|string',
            'payment_intent_id' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $user = $request->user();
        $award = $user->awards ?? 0;

        // SHIPPING LOGIC
        $shippingCost = $award > 100 ? 0 : 3;

        // ITEMS TOTAL
        $itemsTotal = collect($request->items)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // FINAL TOTAL
        $finalTotal = $itemsTotal + $shippingCost;

        try {
            // Create order
            $order = Orders::create([
                'user_id' => auth()->id(),
                'total' => $finalTotal,
                'shipping_address' => $request->shipping_address ?? null,
                'payment_method' => $request->payment_method ?? 'stripe',
                'payment_intent_id' => $request->payment_intent_id ?? null,
                'status' => $request->status ?? 'pending',
            ]);

            $itemIds = [];

            foreach ($request->items as $item) {
                $orderItem = \App\Models\OrderGoods::create([
                    'order_id' => $order->id,
                    'status' => 'pending',
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'description' => $item['description'] ?? null,
                    'image' => $item['image'] ?? null,
                    'category' => $item['category'] ?? null,
                    'total_price' => $item['total_price'] ?? ($item['price'] * $item['quantity']),
                    'shipping_address' => $item['shipping_address'] ?? null,
                ]);

                $itemIds[] = $orderItem->id;

                $order->products()->attach($item['id'], [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order' => $order,
                'order_id' => $order->id,
            ]);

        } catch (\Exception $e) {
            \Log::error('Order creation error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to create order: ' . $e->getMessage(),
            ], 500);
        }
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
