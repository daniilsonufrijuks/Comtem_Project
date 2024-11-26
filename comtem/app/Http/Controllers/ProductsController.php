<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function getComponentsProducts(): \Illuminate\Http\JsonResponse
    {
        $products = Products::where('category', 'Component')->get(['name', 'price', 'description', 'image']);
        return response()->json($products);
    }

    public function getLaptopsProducts(): \Illuminate\Http\JsonResponse
    {
        $products = Products::where('category', 'Laptop')->get(['name', 'price', 'description', 'image']);
        return response()->json($products);
    }

    public function getPcsProducts(): \Illuminate\Http\JsonResponse
    {
        $products = Products::where('category', 'Pc')->get(['name', 'price', 'description', 'image']);
        return response()->json($products);
    }
}
