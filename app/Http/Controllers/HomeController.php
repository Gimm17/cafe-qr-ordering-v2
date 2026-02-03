<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        $products = Product::where('is_active', true)
            ->with('category')
            ->orderByDesc('is_best_seller')
            ->limit(8)
            ->get();
            
        $bestSellers = Product::where('is_active', true)
            ->where('is_best_seller', true)
            ->limit(4)
            ->get();

        return view('home', [
            'categories' => $categories,
            'products' => $products,
            'bestSellers' => $bestSellers,
        ]);
    }
}
