<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categoryId = $request->get('category');
        
        // Cache categories for 60 seconds (they rarely change)
        $categories = Cache::remember('menu_categories', 60, function () {
            return Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });

        $productsQuery = Product::where('is_active', true)
            ->with('category')
            ->orderByDesc('is_best_seller')
            ->orderBy('name');

        if ($search) {
            $productsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }

        $products = $productsQuery->get();

        // Group by category for display
        $productsByCategory = $products->groupBy('category_id');

        return view('cafe.menu', [
            'categories' => $categories,
            'products' => $products,
            'productsByCategory' => $productsByCategory,
            'tableNo' => session('cafe_table_no'),
            'search' => $search,
            'selectedCategory' => $categoryId,
        ]);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        // Load modifier groups with options
        $product->load(['modGroups' => function ($q) {
            $q->where('mod_groups.is_active', true)
              ->orderBy('product_mod_groups.sort_order');
        }, 'modGroups.activeOptions']);

        return view('cafe.product', [
            'product' => $product,
            'tableNo' => session('cafe_table_no'),
        ]);
    }
}

