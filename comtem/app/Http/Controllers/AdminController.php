<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\Products;
use App\Models\User;
use App\Models\OrderGoods;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard(): \Inertia\Response
    {
        $orders = Orders::with(['user', 'orderGoods'])
            ->select(['id', 'user_id', 'status', 'total', 'ordered_at', 'created_at'])
            ->latest()
            ->get();

        $products = Products::select(['id', 'name', 'price', 'description', 'image', 'category', 'admin_id', 'created_at'])
            ->latest()
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
                'goods_orders.category',
                'goods_orders.total_price'
            )
            ->latest()
            ->get();

        $users = User::withCount('orders')
            ->select(['id', 'name', 'email', 'created_at'])
            ->latest()
            ->get();

//        $brands = Brand::select(['id', 'name', 'created_at'])
//            ->latest()
//            ->get();
//
//        $categories = Category::select(['id', 'name', 'created_at'])
//            ->latest()
//            ->get();

        return Inertia::render('Admin', [
            'orders' => $orders,
            'products' => $products,
            'ordersj' => $ordersj,
            'users' => $users,
//            'brands' => $brands,
//            'categories' => $categories,
        ]);
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
            'id', 'order_id', 'name', 'price', 'status', 'category', 'total_price', 'created_at'
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
            ->select(['id', 'name', 'email', 'created_at'])
            ->latest()
            ->get();

        return response()->json($users);
    }

//    public function showBrands(Request $request): \Illuminate\Http\JsonResponse
//    {
//        $brands = Brand::select(['id', 'name', 'created_at'])
//            ->latest()
//            ->get();
//
//        return response()->json($brands);
//    }

//    public function showCategories(Request $request): \Illuminate\Http\JsonResponse
//    {
//        $categories = Category::select(['id', 'name', 'created_at'])
//            ->latest()
//            ->get();
//
//        return response()->json($categories);
//    }

    public function storeProduct(Request $request): \Illuminate\Http\RedirectResponse
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
                'admin_id' => auth('admin')->id(),
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Product added successfully!');

        } catch (\Exception $e) {
            Log::error('Error adding product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add product: ' . $e->getMessage());
        }
    }

//    public function storeBrand(Request $request): \Illuminate\Http\RedirectResponse
//    {
//        $request->validate([
//            'name' => 'required|string|max:255|unique:brands,name',
//        ]);
//
//        try {
//            Brand::create([
//                'name' => $request->name,
//                'slug' => Str::slug($request->name),
//                'admin_id' => auth('admin')->id(),
//            ]);
//
//            return redirect()->route('admin.dashboard')->with('success', 'Brand added successfully!');
//
//        } catch (\Exception $e) {
//            Log::error('Error adding brand: ' . $e->getMessage());
//            return redirect()->back()->with('error', 'Failed to add brand: ' . $e->getMessage());
//        }
//    }

//    public function storeCategory(Request $request): \Illuminate\Http\RedirectResponse
//    {
//        $request->validate([
//            'name' => 'required|string|max:255|unique:categories,name',
//        ]);
//
//        try {
//            Category::create([
//                'name' => $request->name,
//                'slug' => Str::slug($request->name),
//                'admin_id' => auth('admin')->id(),
//            ]);
//
//            return redirect()->route('admin.dashboard')->with('success', 'Category added successfully!');
//
//        } catch (\Exception $e) {
//            Log::error('Error adding category: ' . $e->getMessage());
//            return redirect()->back()->with('error', 'Failed to add category: ' . $e->getMessage());
//        }
//    }

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

//    public function destroyBrand($id)
//    {
//        try {
//            $brand = Brand::findOrFail($id);
//            if ($brand->products()->exists()) {
//                return response()->json(['error' => 'Cannot delete brand with existing products.'], 400);
//            }
//            $brand->delete();
//            return response()->json(['success' => 'Brand deleted successfully!']);
//        } catch (\Exception $e) {
//            Log::error('Error deleting brand: ' . $e->getMessage());
//            return response()->json(['error' => 'Failed to delete brand.'], 500);
//        }
//    }

//    public function destroyCategory($id)
//    {
//        try {
//            $category = Category::findOrFail($id);
//            if ($category->products()->exists()) {
//                return response()->json(['error' => 'Cannot delete category with existing products.'], 400);
//            }
//            $category->delete();
//            return response()->json(['success' => 'Category deleted successfully!']);
//        } catch (\Exception $e) {
//            Log::error('Error deleting category: ' . $e->getMessage());
//            return response()->json(['error' => 'Failed to delete category.'], 500);
//        }
//    }

    public function updateProduct(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        Log::info('Update product request received', ['id' => $id, 'data' => $request->all()]);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $product = Products::findOrFail($id);
            Log::info('Product found', ['product' => $product->toArray()]);

            $updateData = [
                'name' => $request->name,
                'price' => $request->price,
                'category' => $request->category,
                'description' => $request->description,
            ];

            // Handle new image upload
            if ($request->hasFile('image')) {
                Log::info('New image uploaded');
                // Delete old image if exists
                if ($product->image && file_exists(public_path($product->image))) {
                    Log::info('Deleting old image', ['path' => $product->image]);
                    unlink(public_path($product->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/front'), $imageName);
                $updateData['image'] = 'images/front/' . $imageName;
                Log::info('New image saved', ['path' => $updateData['image']]);
            }

            // Update the product
            $updated = $product->update($updateData);
            Log::info('Product update result', ['updated' => $updated]);

            if ($updated) {
                return redirect()->route('admin.dashboard')->with('success', 'Product updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to update product in database.');
            }

        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function updateOrderStatus(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        try {
            $order = Orders::findOrFail($id);
            $order->update(['status' => $request->status]);

            return redirect()->route('admin.dashboard')->with('success', 'Order status updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update order status.');
        }
    }

    public function updateOrder(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'total' => 'nullable|numeric|min:0',
        ]);

        try {
            $order = Orders::findOrFail($id);

            $updateData = [
                'status' => $request->status,
            ];

            if ($request->has('total')) {
                $updateData['total'] = $request->total;
            }

            $order->update($updateData);

            return redirect()->route('admin.dashboard')->with('success', 'Order updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating order: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update order.');
        }
    }

    public function updateUser(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
        ]);

        try {
            $user = User::findOrFail($id);

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return redirect()->route('admin.dashboard')->with('success', 'User updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update user.');
        }
    }

    public function getAnalytics(): \Illuminate\Http\JsonResponse
    {
        try {
            $totalOrders = Orders::count();
            $totalRevenue = Orders::sum('total');
            $totalProducts = Products::count();
            $totalUsers = User::count();

            // Get orders per day for last 7 days
            $ordersPerDay = Orders::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Get revenue per day for last 7 days
            $revenuePerDay = Orders::selectRaw('DATE(created_at) as date, SUM(total) as revenue')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Get top products
            $topProducts = OrderGoods::selectRaw('name, COUNT(*) as quantity_sold, SUM(total_price) as revenue')
                ->groupBy('name')
                ->orderByDesc('revenue')
                ->limit(5)
                ->get();

            // Get order status distribution
            $statusDistribution = Orders::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get();

            return response()->json([
                'totalOrders' => $totalOrders,
                'totalRevenue' => $totalRevenue,
                'totalProducts' => $totalProducts,
                'totalUsers' => $totalUsers,
                'ordersPerDay' => $ordersPerDay,
                'revenuePerDay' => $revenuePerDay,
                'topProducts' => $topProducts,
                'statusDistribution' => $statusDistribution,
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting analytics: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get analytics.'], 500);
        }
    }
}
