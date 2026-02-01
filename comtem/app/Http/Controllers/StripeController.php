<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use App\Models\Family;
use App\Models\FamilyPaymentMethod;
use App\Models\FamilyTransaction;
use App\Models\User;
use Stripe\PaymentMethod;
use Stripe\Customer;
use Stripe\Exception\CardException;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Original checkout method (for non-family payments)
     */
    public function create(Request $request)
    {
        $lineItems = collect($request->items)->map(function ($item) {
            return [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => intval($item['price'] * 100),
                ],
                'quantity' => $item['quantity'],
            ];
        })->toArray();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => url('/'),
            'cancel_url' => url('/cart'),
        ]);

        return response()->json(['id' => $session->id]);
    }

    /**
     * NEW: Create checkout session with family support
     */
    public function createCheckout(Request $request)
    {
        $user = Auth::user();
        $useFamilyCard = $request->input('use_family_card', false);

        // If user wants to use family card and can do so
        if ($useFamilyCard && $user->canUseFamilyCard()) {
            return $this->createFamilyCardPayment($request);
        }

        // Otherwise use regular Stripe checkout
        return $this->create($request);
    }

    /**
     * Process payment using family card
     */
    private function createFamilyCardPayment(Request $request)
    {
        $user = Auth::user();
        $family = $user->family;

        if (!$family || !$family->hasPaymentMethod()) {
            return response()->json([
                'error' => 'No family payment method available'
            ], 400);
        }

        // Get default payment method
        $defaultPaymentMethod = $family->defaultPaymentMethod();
        if (!$defaultPaymentMethod) {
            return response()->json([
                'error' => 'No default payment method set for family'
            ], 400);
        }

        // Calculate total
        $total = collect($request->items)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // Create payment intent with family's card
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $total * 100, // Convert to cents
                'currency' => 'usd',
                'customer' => $family->stripe_customer_id,
                'payment_method' => $defaultPaymentMethod->stripe_payment_method_id,
                'off_session' => true, // Important: user is not present for this payment
                'confirm' => true,
                'description' => 'Family account payment by ' . $user->name,
                'metadata' => [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'family_id' => $family->id,
                    'items' => json_encode($request->items),
                ],
            ]);

            // Log the transaction
            FamilyTransaction::create([
                'family_id' => $family->id,
                'user_id' => $user->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'amount' => $total,
                'description' => $this->generateDescription($request->items),
                'status' => $paymentIntent->status,
                'payment_method_used' => $defaultPaymentMethod->stripe_payment_method_id,
                'metadata' => json_encode([
                    'card_last_four' => $defaultPaymentMethod->card_last_four,
                    'card_brand' => $defaultPaymentMethod->card_brand,
                    'items' => $request->items,
                ]),
            ]);

            // Check if payment was successful
            if ($paymentIntent->status === 'succeeded') {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful using family card',
                    'payment_intent_id' => $paymentIntent->id,
                ]);
            } else {
                return response()->json([
                    'error' => 'Payment processing failed',
                    'status' => $paymentIntent->status,
                ], 400);
            }

        } catch (CardException $e) {
            // Handle card errors
            return response()->json([
                'error' => $e->getError()->message,
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Payment failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Setup family card for payments (Parent only)
     */
    public function setupFamilyPaymentMethod(Request $request)
    {
        $user = Auth::user();

        // Only family admin can add payment methods
        if (!$user->isFamilyAdmin()) {
            return response()->json([
                'error' => 'Only family admin can add payment methods'
            ], 403);
        }

        $request->validate([
            'payment_method_id' => 'required|string',
        ]);

        try {
            $family = $user->family;

            // Create Stripe customer if not exists
            if (!$family->stripe_customer_id) {
                $customer = Customer::create([
                    'email' => $user->email,
                    'name' => $family->family_name,
                    'metadata' => [
                        'family_id' => $family->id,
                        'user_id' => $user->id,
                    ]
                ]);

                $family->update(['stripe_customer_id' => $customer->id]);
            }

            // Attach payment method to customer
            $paymentMethod = PaymentMethod::retrieve($request->payment_method_id);
            $paymentMethod->attach(['customer' => $family->stripe_customer_id]);

            // Set as default payment method
            Customer::update($family->stripe_customer_id, [
                'invoice_settings' => [
                    'default_payment_method' => $request->payment_method_id
                ]
            ]);

            // Store in database
            $familyPaymentMethod = FamilyPaymentMethod::create([
                'family_id' => $family->id,
                'stripe_payment_method_id' => $request->payment_method_id,
                'stripe_customer_id' => $family->stripe_customer_id,
                'card_last_four' => $paymentMethod->card->last4,
                'card_brand' => $paymentMethod->card->brand,
                'is_default' => true,
                'added_by_user_id' => $user->id,
            ]);

            // Set all other payment methods as non-default
            FamilyPaymentMethod::where('family_id', $family->id)
                ->where('id', '!=', $familyPaymentMethod->id)
                ->update(['is_default' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Family card added successfully',
                'card' => [
                    'last_four' => $paymentMethod->card->last4,
                    'brand' => $paymentMethod->card->brand,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to add card: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get family payment methods
     */
    public function getFamilyPaymentMethods()
    {
        $user = Auth::user();

        if (!$user->family) {
            return response()->json(['error' => 'Not part of a family'], 400);
        }

        $paymentMethods = $user->family->paymentMethods()
            ->with('addedBy:id,name')
            ->orderBy('is_default', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json($paymentMethods);
    }

    /**
     * Set default payment method for family
     */
    public function setDefaultFamilyPaymentMethod(Request $request, $paymentMethodId)
    {
        $user = Auth::user();

        if (!$user->isFamilyAdmin()) {
            return response()->json([
                'error' => 'Only family admin can change payment methods'
            ], 403);
        }

        $family = $user->family;

        try {
            // Update in Stripe
            Customer::update($family->stripe_customer_id, [
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethodId
                ]
            ]);

            // Update in database
            FamilyPaymentMethod::where('family_id', $family->id)
                ->update(['is_default' => false]);

            FamilyPaymentMethod::where('family_id', $family->id)
                ->where('stripe_payment_method_id', $paymentMethodId)
                ->update(['is_default' => true]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove payment method
     */
    public function removePaymentMethod($paymentMethodId)
    {
        $user = Auth::user();

        if (!$user->isFamilyAdmin()) {
            return response()->json([
                'error' => 'Only family admin can remove payment methods'
            ], 403);
        }

        try {
            $family = $user->family;

            // Detach from Stripe
            PaymentMethod::retrieve($paymentMethodId)->detach();

            // Remove from database
            FamilyPaymentMethod::where('family_id', $family->id)
                ->where('stripe_payment_method_id', $paymentMethodId)
                ->delete();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Toggle child's permission to use family card
     */
    public function toggleChildCardPermission(Request $request, $childId)
    {
        $user = Auth::user();

        if (!$user->isFamilyAdmin()) {
            return response()->json([
                'error' => 'Only family admin can change permissions'
            ], 403);
        }

        $child = User::where('family_id', $user->family_id)
            ->where('id', $childId)
            ->where('role', 'child')
            ->first();

        if (!$child) {
            return response()->json(['error' => 'Child not found'], 404);
        }

        $child->update([
            'can_use_family_card' => !$child->can_use_family_card
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permission updated',
            'can_use_family_card' => $child->can_use_family_card
        ]);
    }

    /**
     * Get family transaction history
     */
    public function getFamilyTransactions()
    {
        $user = Auth::user();

        if (!$user->family) {
            return response()->json(['error' => 'Not part of a family'], 400);
        }

        $transactions = $user->family->transactions()
            ->with('user:id,name,email')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return response()->json($transactions);
    }

    /**
     * Helper method to generate description from items
     */
    private function generateDescription($items)
    {
        $itemNames = collect($items)->pluck('name')->toArray();
        return 'Purchase: ' . implode(', ', $itemNames);
    }

    /**
     * NEW: Check if user can use family card
     */
    public function checkFamilyCardAvailable()
    {
        $user = Auth::user();

        if (!$user->family_id) {
            return response()->json([
                'available' => false,
                'reason' => 'Not part of a family'
            ]);
        }

        try {
            $family = Family::with('paymentMethods')->find($user->family_id);

            if (!$family) {
                return response()->json([
                    'available' => false,
                    'reason' => 'Family not found'
                ]);
            }

            $canUse = (bool) $user->can_use_family_card || $user->role === 'parent';
            $familyHasCard = $family->paymentMethods()->where('is_default', true)->exists();

            $defaultPaymentMethod = null;
            if ($familyHasCard) {
                $defaultPaymentMethod = $family->paymentMethods()
                    ->where('is_default', true)
                    ->first();
            }

            return response()->json([
                'available' => $canUse && $familyHasCard,
                'can_use_family_card' => $canUse,
                'family_has_card' => $familyHasCard,
                'default_payment_method' => $defaultPaymentMethod,
                'user_permission' => $user->can_use_family_card,
                'user_role' => $user->role,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error checking family card: ' . $e->getMessage());
            return response()->json([
                'available' => false,
                'reason' => 'Error checking family card',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getFamilyMembers()
    {
        $user = Auth::user();

        if (!$user->family) {
            return response()->json(['error' => 'Not part of a family'], 400);
        }

        $members = $user->family->users()
            ->select('id', 'name', 'email', 'role', 'can_use_family_card')
            ->get();

        return response()->json($members);
    }

    /**
     * Add family checkout method
     */
    public function familyCheckout(Request $request)
    {
        $user = Auth::user();

        // Check if user can use family card
        if (!$user->can_use_family_card && $user->role !== 'parent') {
            return response()->json([
                'error' => 'You do not have permission to use the family card'
            ], 403);
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'nullable|string',
        ]);

        try {
            $family = $user->family;

            if (!$family) {
                return response()->json(['error' => 'No family found'], 400);
            }

            $defaultPaymentMethod = $family->paymentMethods()
                ->where('is_default', true)
                ->first();

            if (!$defaultPaymentMethod) {
                return response()->json(['error' => 'No payment method available'], 400);
            }

            // Calculate shipping and total (same logic as OrderController)
            $award = $user->awards ?? 0;
            $shippingCost = $award > 100 ? 0 : 3;

            $itemsTotal = collect($request->items)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            $total = $itemsTotal + $shippingCost;

            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $total * 100,
                'currency' => 'usd',
                'customer' => $family->stripe_customer_id,
                'payment_method' => $defaultPaymentMethod->stripe_payment_method_id,
                'off_session' => true,
                'confirm' => true,
                'description' => 'Family account payment by ' . $user->name,
                'metadata' => [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'family_id' => $family->id,
                    'items' => json_encode($request->items),
                ],
            ]);

            // Log the transaction
            FamilyTransaction::create([
                'family_id' => $family->id,
                'user_id' => $user->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'amount' => $total,
                'description' => 'Purchase from cart',
                'status' => $paymentIntent->status,
                'payment_method_used' => $defaultPaymentMethod->stripe_payment_method_id,
                'metadata' => json_encode([
                    'card_last_four' => $defaultPaymentMethod->card_last_four,
                    'card_brand' => $defaultPaymentMethod->card_brand,
                    'items' => $request->items,
                ]),
            ]);

            // Create order WITHOUT 'items' column - match your existing structure
            $orderResponse = \App\Models\Orders::create([
                'user_id' => $user->id,
                'total' => $total,
                'payment_method' => 'family_card',
                'payment_intent_id' => $paymentIntent->id,
                'shipping_address' => $request->shipping_address,
                'status' => $paymentIntent->status === 'succeeded' ? 'completed' : 'pending',
            ]);

            $itemIds = [];

            // Create order goods for each item
            foreach ($request->items as $item) {
                $orderItem = \App\Models\OrderGoods::create([
                    'order_id' => $orderResponse->id,
                    'status' => 'completed',
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'description' => $item['description'] ?? null,
                    'image' => $item['image'] ?? null,
                    'category' => $item['category'] ?? null,
                    'total_price' => $item['price'] * $item['quantity'],
                    'shipping_address' => $request->shipping_address ?? null,
                ]);

                $itemIds[] = $orderItem->id;

                // Attach products to order
                $orderResponse->products()->attach($item['id'], [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            // Optional: Store item IDs if you need them in orders table
            // $orderResponse->update(['items' => implode(',', $itemIds)]);

            if ($paymentIntent->status === 'succeeded') {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful using family card',
                    'payment_intent_id' => $paymentIntent->id,
                    'order_id' => $orderResponse->id,
                ]);
            } else {
                return response()->json([
                    'error' => 'Payment processing failed',
                    'status' => $paymentIntent->status,
                ], 400);
            }

        } catch (CardException $e) {
            return response()->json([
                'error' => $e->getError()->message,
            ], 400);
        } catch (\Exception $e) {
            \Log::error('Family checkout error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Payment failed: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }
}
