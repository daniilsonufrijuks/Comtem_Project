<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Bids;
use App\Models\Comment;
use App\Models\Orders;
use App\Models\Products;
use App\Models\User;
use App\Models\OrderGoods;
use App\Models\Family;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(): \Inertia\Response
    {
        $orders = Orders::with(['user', 'orderGoods'])
            ->select(['id', 'user_id', 'status', 'total', 'ordered_at', 'created_at', 'payment_method', 'shipping_address'])
            ->latest()
            ->limit(100)
            ->get();

        $products = Products::select(['id', 'name', 'price', 'description', 'image', 'category', 'admin_id', 'created_at'])
            ->latest()
            ->limit(100)
            ->get();

        $ordersj = Orders::join('users', 'orders.user_id', '=', 'users.id')
            ->join('goods_orders', 'orders.id', '=', 'goods_orders.order_id')
            ->select(
                'orders.id as order_id',
                'orders.status as order_status',
                'orders.total',
                'orders.created_at',
                'users.name as customer_name',
                'users.email as customer_email',
                'goods_orders.name as item_name',
                'goods_orders.price as item_price',
                'goods_orders.status as item_status',
            )
            ->latest()
            ->limit(100)
            ->get();

        $comments = Comment::with('user:id,name,email')
            ->latest()
            ->limit(100)
            ->get();

        $users = User::withCount('orders')
            ->select(['id', 'name', 'email', 'role', 'family_id', 'created_at', 'awards'])
            ->latest()
            ->limit(100)
            ->get();

        $families = Family::withCount('users')
            ->with(['parent:id,name,email'])
            ->select(['id', 'family_name', 'parent_id', 'invitation_code', 'created_at'])
            ->latest()
            ->limit(50)
            ->get();

        $auctions = Auction::with(['user:id,name,email'])
            ->latest()
            ->limit(100)
            ->get();

        return Inertia::render('Admin', [
            'orders' => $orders,
            'products' => $products,
            'ordersj' => $ordersj,
            'users' => $users,
            'families' => $families,
            'comments' => $comments,
            'auctions' => $auctions,
        ]);
    }

    // Get all auctions (API)
    public function indexAuctions(): \Illuminate\Http\JsonResponse
    {
        $auctions = Auction::with(['user:id,name,email'])
            ->latest()
            ->get();
        return response()->json($auctions);
    }

    public function showOrders(Request $request): \Illuminate\Http\JsonResponse
    {
        $orders = Orders::with(['user:id,name,email'])
            ->select(['id', 'user_id', 'status', 'total', 'ordered_at', 'created_at'])
            ->latest()
            ->get();

        return response()->json($orders);
    }

    public function showOrderItems(): \Illuminate\Http\JsonResponse
    {
        $orderItems = OrderGoods::select([
            'id', 'order_id', 'name', 'price', 'status', 'created_at'
        ])->latest()->get();

        return response()->json($orderItems);
    }

    public function showProducts(Request $request): \Illuminate\Http\JsonResponse
    {
        $products = Products::select(['id', 'name', 'price', 'description', 'image', 'category', 'created_at'])
            ->latest()
            ->get();

        return response()->json($products);
    }

    public function showUsers(Request $request): \Illuminate\Http\JsonResponse
    {
        $users = User::withCount('orders')
            ->with(['family:id,family_name'])
            ->select(['id', 'name', 'email', 'role', 'family_id', 'created_at', 'awards'])
            ->latest()
            ->get();

        return response()->json($users);
    }

    public function showFamilies(Request $request): \Illuminate\Http\JsonResponse
    {
        $families = Family::withCount('users')
            ->with(['parent:id,name,email', 'users:id,name,email,role'])
            ->select(['id', 'family_name', 'parent_id', 'invitation_code', 'created_at'])
            ->latest()
            ->get();

        return response()->json($families);
    }

    // Get bids for a specific auction
    public function showBids($auctionId): \Illuminate\Http\JsonResponse
    {
        $bids = Bids::with('user:id,name,email')
            ->where('item_id', $auctionId)
            ->orderBy('bid_amount', 'desc')
            ->get();

        return response()->json($bids);
    }

    // Get all bids (optional)
    public function indexBids(): \Illuminate\Http\JsonResponse
    {
        $bids = Bids::with(['user:id,name,email', 'auction:id,name'])
            ->latest()
            ->get();

        return response()->json($bids);
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/front'), $imageName);
                $imagePath = 'images/front/' . $imageName;
            }

            Products::create([
                'name' => $request->name,
                'price' => $request->price,
                'category' => $request->category,
                'description' => $request->description,
                'image' => $imagePath,
                'admin_id' => auth()->id(),
            ]);

            return redirect()->back()->with('success', 'Product added successfully!');

        } catch (\Exception $e) {
            Log::error('Error adding product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add product: ' . $e->getMessage());
        }
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,parent,child,standalone',
            'awards' => 'nullable|integer|min:0',
            'family_id' => 'nullable|exists:families,id',
        ]);

        try {
            // Ensure role is valid for your database
            $validRoles = ['standalone', 'parent', 'child', 'admin'];
            $role = in_array($request->role, $validRoles) ? $request->role : 'user';

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $role,
                'awards' => $request->awards ?? 0,
                'family_id' => $request->family_id ?: null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            \Log::info('User data to create:', $userData);

            User::create($userData);

            return redirect()->back()->with('success', 'User added successfully!');

        } catch (\Exception $e) {
            \Log::error('Full error creating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add user: ' . $e->getMessage());
        }
    }

    public function storeFamily(Request $request)
    {
        $request->validate([
            'family_name' => 'required|string|max:255|unique:families,family_name',
            'parent_id' => 'required|exists:users,id',
        ]);

        try {
            $invitationCode = strtoupper(substr(md5(uniqid()), 0, 8));

            $family = Family::create([
                'family_name' => $request->family_name,
                'parent_id' => $request->parent_id,
                'invitation_code' => $invitationCode,
            ]);

            User::where('id', $request->parent_id)->update([
                'family_id' => $family->id,
                'role' => 'parent',
            ]);

            return redirect()->back()->with('success', 'Family created successfully! Invitation code: ' . $invitationCode);

        } catch (\Exception $e) {
            Log::error('Error creating family: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create family: ' . $e->getMessage());
        }
    }

    // Store new auction
    public function storeAuction(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'starting_bid' => 'required|numeric|min:0',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        try {
            $imgPath = null;
            if ($request->hasFile('img')) {
                $img = $request->file('img');
                $imgName = time() . '_' . $img->getClientOriginalName();
                $img->move(public_path('images/auctions'), $imgName);
                $imgPath = 'images/auctions/' . $imgName;
            }

            $auction = Auction::create([
                'name' => $request->name,
                'description' => $request->description,
                'starting_bid' => $request->starting_bid,
                'img' => $imgPath,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Auction created successfully!',
                'auction' => $auction->load('user:id,name,email')
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating auction: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create auction.'], 500);
        }
    }

    // Update auction
    public function updateAuction(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'starting_bid' => 'required|numeric|min:0',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        try {
            $auction = Auction::findOrFail($id);

            $updateData = $request->only(['name', 'description', 'starting_bid', 'start_time', 'end_time']);

            if ($request->hasFile('img')) {
                // Delete old image
                if ($auction->img && file_exists(public_path($auction->img))) {
                    unlink(public_path($auction->img));
                }
                $img = $request->file('img');
                $imgName = time() . '_' . $img->getClientOriginalName();
                $img->move(public_path('images/auctions'), $imgName);
                $updateData['img'] = 'images/auctions/' . $imgName;
            }

            $auction->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Auction updated successfully!',
                'auction' => $auction->load('user:id,name,email')
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating auction: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update auction.'], 500);
        }
    }

    // Delete auction
    public function destroyAuction($id): \Illuminate\Http\JsonResponse
    {
        try {
            $auction = Auction::findOrFail($id);
            // Delete associated bids first
            Bid::where('item_id', $id)->delete();
            // Delete image if exists
            if ($auction->img && file_exists(public_path($auction->img))) {
                unlink(public_path($auction->img));
            }
            $auction->delete();

            return response()->json(['success' => 'Auction deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Error deleting auction: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete auction.'], 500);
        }
    }


    public function destroyProduct($id)
    {
        try {
            $product = Products::findOrFail($id);
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $product->delete();

            return response()->json(['success' => 'Product deleted successfully!']);

        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete product.'], 500);
        }
    }

    public function destroyOrder($id)
    {
        try {
            $order = Orders::findOrFail($id);
            // Delete associated order goods
            OrderGoods::where('order_id', $id)->delete();
            $order->delete();

            return response()->json(['success' => 'Order deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Error deleting order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete order.'], 500);
        }
    }

    public function destroyOrderItem($id)
    {
        try {
            $orderItem = OrderGoods::findOrFail($id);
            $orderItem->delete();
            return response()->json(['success' => 'Order item deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Error deleting order item: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete order item.'], 500);
        }
    }

    public function destroyUser($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->orders()->exists()) {
                return response()->json(['error' => 'Cannot delete user with existing orders.'], 400);
            }
            $user->delete();
            return response()->json(['success' => 'User deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete user.'], 500);
        }
    }

    public function destroyFamily($id)
    {
        try {
            $family = Family::findOrFail($id);

            // Remove family_id from all users in this family
            User::where('family_id', $id)->update(['family_id' => null, 'role' => 'user']);

            $family->delete();
            return response()->json(['success' => 'Family deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Error deleting family: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete family.'], 500);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $product = Products::findOrFail($id);

            $updateData = [
                'name' => $request->name,
                'price' => $request->price,
                'category' => $request->category,
                'description' => $request->description,
            ];

            if ($request->hasFile('image')) {
                if ($product->image && file_exists(public_path($product->image))) {
                    unlink(public_path($product->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/front'), $imageName);
                $updateData['image'] = 'images/front/' . $imageName;
            }

            $product->update($updateData);

            return redirect()->back()->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }


    public function updateOrder(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'total' => 'nullable|numeric|min:0',
        ]);

        try {
            // Find the order
            $order = Orders::findOrFail($id);

            // Update order data
            $order->status = $request->status;
            if ($request->has('total')) {
                $order->total = $request->total;
            }
            $order->save();

            // Update all related order items with the same status
            OrderGoods::where('order_id', $order->id)
                ->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating order: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update order.'], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:user,parent,child,admin',
            'awards' => 'nullable|integer|min:0',
            'family_id' => 'nullable|exists:families,id',
        ]);

        try {
            $user = User::findOrFail($id);

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'awards' => $request->awards ?? $user->awards,
                'family_id' => $request->family_id,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return response()->json(['success' => true, 'message' => 'User updated successfully!']);

        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update user.'], 500);
        }
    }

    public function updateFamily(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'family_name' => 'required|string|max:255|unique:families,family_name,' . $id,
            'parent_id' => 'required|exists:users,id',
        ]);

        try {
            $family = Family::findOrFail($id);

            // Update old parent role
            $oldParent = User::find($family->parent_id);
            if ($oldParent && $oldParent->id != $request->parent_id) {
                $oldParent->update(['role' => 'user']);
            }

            // Update family
            $family->update([
                'family_name' => $request->family_name,
                'parent_id' => $request->parent_id,
            ]);

            // Update new parent
            $newParent = User::find($request->parent_id);
            if ($newParent) {
                $newParent->update([
                    'family_id' => $family->id,
                    'role' => 'parent',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Family updated successfully!',
                'family' => $family
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating family: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update family: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all comments for API
     */
    public function showComments(Request $request): \Illuminate\Http\JsonResponse
    {
        $comments = Comment::with('user:id,name,email')
            ->select(['id', 'body', 'user_id', 'created_at', 'updated_at'])
            ->latest()
            ->get();

        return response()->json($comments);
    }

    /**
     * Store a new comment
     */
    public function storeComment(Request $request)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        try {
            $comment = Comment::create([
                'body' => $request->body,
                'user_id' => auth()->id(),
            ]);

            // Load the user relationship for the response
            $comment->load('user:id,name,email');

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comment added successfully!',
                    'comment' => $comment
                ]);
            }

            return redirect()->back()->with('success', 'Comment added successfully!');

        } catch (\Exception $e) {
            Log::error('Error adding comment: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(['error' => 'Failed to add comment: ' . $e->getMessage()], 500);
            }

            return redirect()->back()->with('error', 'Failed to add comment: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing comment
     */
    public function updateComment(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        try {
            $comment = Comment::findOrFail($id);

            // Check if user is authorized (admin or comment owner)
            if (auth()->user()->role !== 'admin' && $comment->user_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $comment->update([
                'body' => $request->body,
            ]);

            $comment->load('user:id,name,email');

            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully!',
                'comment' => $comment
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating comment: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update comment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a comment
     */
    public function destroyComment($id)
    {
        try {
            $comment = Comment::findOrFail($id);

            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting comment: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete comment: ' . $e->getMessage()], 500);
        }
    }


    public function getAnalytics(): \Illuminate\Http\JsonResponse
    {
        try {
            // Basic stats
            $totalOrders = Orders::count();
            $totalRevenue = Orders::sum('total');
            $totalProducts = Products::count();
            $totalUsers = User::count();
            $totalFamilies = Family::count();

            $totalComments = Comment::count();

            // Comments per day for last 30 days
            $commentsPerDay = Comment::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Most active commenters
            $topCommenters = Comment::join('users', 'comments.user_id', '=', 'users.id')
                ->selectRaw('users.id, users.name, users.email, COUNT(comments.id) as comment_count')
                ->groupBy('users.id', 'users.name', 'users.email')
                ->orderByDesc('comment_count')
                ->limit(10)
                ->get();

            // Recent orders (last 30 days)
            $recentOrders = Orders::where('created_at', '>=', now()->subDays(30))
                ->count();

            // Recent revenue (last 30 days)
            $recentRevenue = Orders::where('created_at', '>=', now()->subDays(30))
                ->sum('total');

            // Orders per day for last 30 days
            $ordersPerDay = Orders::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Revenue per day for last 30 days
            $revenuePerDay = Orders::selectRaw('DATE(created_at) as date, SUM(total) as revenue')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Top products by sales
            $topProducts = OrderGoods::selectRaw('name, COUNT(*) as quantity_sold, SUM(price) as revenue')
                ->groupBy('name')
                ->orderByDesc('revenue')
                ->limit(10)
                ->get();

            // Order status distribution
            $statusDistribution = Orders::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get();

            // Payment method distribution
            $paymentMethodDistribution = Orders::selectRaw('payment_method, COUNT(*) as count')
                ->whereNotNull('payment_method')
                ->groupBy('payment_method')
                ->get();

            // Top customers by spending
            $topCustomers = Orders::join('users', 'orders.user_id', '=', 'users.id')
                ->selectRaw('users.id, users.name, users.email, COUNT(orders.id) as order_count, SUM(orders.total) as total_spent')
                ->groupBy('users.id', 'users.name', 'users.email')
                ->orderByDesc('total_spent')
                ->limit(10)
                ->get();

            // Category performance
            $categoryPerformance = OrderGoods::join('products', 'goods_orders.product_id', '=', 'products.id')
                ->selectRaw('
                products.category,
                COUNT(*) as item_count,
                SUM(goods_orders.price * goods_orders.quantity) as revenue')
                ->whereNotNull('goods_orders.product_id')
                ->groupBy('products.category')
                ->orderByDesc('revenue')
                ->get();

            // Monthly trends (last 6 months)
            $monthlyTrends = Orders::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as order_count, SUM(total) as revenue')
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // User growth
            $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as new_users')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Family stats
            $averageFamilySize = Family::withCount('users')->get()->avg('users_count');
            $largestFamily = Family::withCount('users')->orderByDesc('users_count')->first();

            return response()->json([
                // Basic stats
                'totalOrders' => $totalOrders,
                'totalRevenue' => $totalRevenue,
                'totalProducts' => $totalProducts,
                'totalUsers' => $totalUsers,
                'totalFamilies' => $totalFamilies,

                // Recent activity
                'recentOrders' => $recentOrders,
                'recentRevenue' => $recentRevenue,

                // Time series data
                'ordersPerDay' => $ordersPerDay,
                'revenuePerDay' => $revenuePerDay,
                'monthlyTrends' => $monthlyTrends,
                'userGrowth' => $userGrowth,

                // Product analytics
                'topProducts' => $topProducts,
                'categoryPerformance' => $categoryPerformance,

                // Customer analytics
                'topCustomers' => $topCustomers,
                'statusDistribution' => $statusDistribution,
                'paymentMethodDistribution' => $paymentMethodDistribution,

                // Family analytics
                'averageFamilySize' => $averageFamilySize,
                'largestFamily' => $largestFamily,

                'totalComments' => $totalComments,
                'commentsPerDay' => $commentsPerDay,
                'topCommenters' => $topCommenters,
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting analytics: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get analytics.'], 500);
        }
    }

    public function exportOrders(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            $query = Orders::with(['user:id,name,email', 'orderGoods']);

            if ($request->has('start_date')) {
                $query->where('created_at', '>=', $request->start_date);
            }

            if ($request->has('end_date')) {
                $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
            }

            $orders = $query->latest()->get();

            // Format for CSV/Excel export
            $exportData = [];
            foreach ($orders as $order) {
                $exportData[] = [
                    'Order ID' => $order->id,
                    'Customer' => $order->user->name ?? 'N/A',
                    'Email' => $order->user->email ?? 'N/A',
                    'Status' => $order->status,
                    'Total' => $order->total,
                    'Payment Method' => $order->payment_method ?? 'N/A',
                    'Shipping Address' => $order->shipping_address ?? 'N/A',
                    'Order Date' => $order->created_at,
                    'Items Count' => $order->orderGoods->count(),
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $exportData,
                'count' => count($exportData),
            ]);

        } catch (\Exception $e) {
            Log::error('Error exporting orders: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to export orders.'], 500);
        }
    }


}
