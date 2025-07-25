<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductsController extends Controller
{
    public function getComponentsProducts(Request $request): \Illuminate\Http\JsonResponse
    {
//        $products = Products::where('category', 'Component')->get(['name', 'price', 'description', 'image']);
//        return response()->json($products);


        // Base query for "Component" category
        //global $request;
        $query = Products::where('category', 'Component');

        // Apply filters based on query parameters
        // Handle price_min only if not null
        // Check if price_min is provided
        // Apply filters only if values are not null or empty
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // Fetch the filtered results
        $products = $query->get(['id', 'name', 'price', 'description', 'image']);

        return response()->json($products);
    }

    public function getLaptopsProducts(Request $request): \Illuminate\Http\JsonResponse
    {
//        $products = Products::where('category', 'Laptop')->get(['name', 'price', 'description', 'image']);
//        return response()->json($products);


        // Base query for "Laptop" category
        //global $request;
        $query = Products::where('category', 'Laptop');

        // Apply filters based on query parameters
        // Handle price_min only if not null
        // Check if price_min is provided
        // Apply filters only if values are not null or empty
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // Fetch the filtered results
        $products = $query->get(['id', 'name', 'price', 'description', 'image']);

        return response()->json($products);
    }

    public function getPcsProducts(Request $request): \Illuminate\Http\JsonResponse
    {
//        $products = Products::where('category', 'Pc')->get(['name', 'price', 'description', 'image']);
//        return response()->json($products);

        // Base query for "Pc" category
        //global $request;
        $query = Products::where('category', 'Pc');

        // Apply filters based on query parameters
        // Handle price_min only if not null
        // Check if price_min is provided
        // Apply filters only if values are not null or empty
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // Fetch the filtered results
        $products = $query->get(['id', 'name', 'price', 'description', 'image']);

        return response()->json($products);

    }

//     for phones
    public function getPhonesProducts(Request $request): \Illuminate\Http\JsonResponse
    {
//        $products = Products::where('category', 'Pc')->get(['name', 'price', 'description', 'image']);
//        return response()->json($products);

        // Base query for "Pc" category
        //global $request;
        $query = Products::where('category', 'Phone');

        // Apply filters based on query parameters
        // Handle price_min only if not null
        // Check if price_min is provided
        // Apply filters only if values are not null or empty
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // Fetch the filtered results
        $products = $query->get(['id', 'name', 'price', 'description', 'image']);

        return response()->json($products);

    }

//    for periph
    public function getPeripheralsProducts(Request $request): \Illuminate\Http\JsonResponse
    {
//        $products = Products::where('category', 'Pc')->get(['name', 'price', 'description', 'image']);
//        return response()->json($products);

        // Base query for "Pc" category
        //global $request;
        $query = Products::where('category', 'Peripheral');

        // Apply filters based on query parameters
        // Handle price_min only if not null
        // Check if price_min is provided
        // Apply filters only if values are not null or empty
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // Fetch the filtered results
        $products = $query->get(['id', 'name', 'price', 'description', 'image']);

        return response()->json($products);

    }

    public function getFurnitureProducts(Request $request): \Illuminate\Http\JsonResponse
    {
//        $products = Products::where('category', 'Pc')->get(['name', 'price', 'description', 'image']);
//        return response()->json($products);

        // Base query for "Pc" category
        //global $request;
        $query = Products::where('category', 'Furniture');

        // Apply filters based on query parameters
        // Handle price_min only if not null
        // Check if price_min is provided
        // Apply filters only if values are not null or empty
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // Fetch the filtered results
        $products = $query->get(['id', 'name', 'price', 'description', 'image']);

        return response()->json($products);

    }


//    for cables
    public function getCablesProducts(Request $request): \Illuminate\Http\JsonResponse
    {
//        $products = Products::where('category', 'Pc')->get(['name', 'price', 'description', 'image']);
//        return response()->json($products);

        // Base query for "Pc" category
        //global $request;
        $query = Products::where('category', 'Cable');

        // Apply filters based on query parameters
        // Handle price_min only if not null
        // Check if price_min is provided
        // Apply filters only if values are not null or empty
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // Fetch the filtered results
        $products = $query->get(['id', 'name', 'price', 'description', 'image']);

        return response()->json($products);

    }


    public function show($id): \Illuminate\Http\JsonResponse
    {
//        $query = Products::find($id);
//        // Fetch the filtered results
//        $product = $query->get(['name', 'price', 'description', 'image', 'category']);
//        return response()->json($product);



        $products = Products::find($id, ['id', 'name', 'price', 'description', 'image', 'category']);

        if (!$products) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($products);
    }

//     for search bar for suggestions
    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Products::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

//        return view('products.search-results', compact('products', 'query'));
//        return Inertia::render('SearchResults', [
//            'products' => $products,
//            'query' => $query,
//        ]);
        return response()->json($products);
    }

}
