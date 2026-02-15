<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $products = Product::where('is_active', true)
            ->with(['category', 'reviews' => function($q) {
                $q->latest()->limit(10);
            }])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->selectRaw('products.*, (SELECT COALESCE(SUM(oi.qty),0) FROM order_items oi WHERE oi.product_id = products.id) as total_ordered')
            ->orderByDesc('is_best_seller')
            ->limit(8)
            ->get();

        $bestSellers = Product::where('is_active', true)
            ->where('is_best_seller', true)
            ->with(['reviews' => function($q) {
                $q->latest()->limit(10);
            }])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->selectRaw('products.*, (SELECT COALESCE(SUM(oi.qty),0) FROM order_items oi WHERE oi.product_id = products.id) as total_ordered')
            ->limit(4)
            ->get();

        $banners = Banner::active()->ordered()->get();

        return view('home', [
            'categories' => $categories,
            'products' => $products,
            'bestSellers' => $bestSellers,
            'banners' => $banners,
        ]);
    }
}
