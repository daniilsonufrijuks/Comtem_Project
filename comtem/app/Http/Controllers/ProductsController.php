<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

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
        $products = $query->get(['name', 'price', 'description', 'image']);

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
        $products = $query->get(['name', 'price', 'description', 'image']);

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
        $products = $query->get(['name', 'price', 'description', 'image']);

        return response()->json($products);

    }
}