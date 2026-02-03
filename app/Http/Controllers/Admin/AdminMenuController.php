<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ModGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminMenuController extends Controller
{
    public function index()
    {
        return view('admin.menu_index');
    }

    public function categories()
    {
        return view('admin.categories', [
            'categories' => Category::orderBy('sort_order')->get()
        ]);
    }

    public function storeCategory(Request $req)
    {
        $data = $req->validate([
            'name' => ['required', 'string', 'max:60'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        Category::create([
            'name' => $data['name'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Kategori ditambah.');
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Kategori dihapus.');
    }

    public function products()
    {
        return view('admin.products', [
            'products' => Product::with('category')->latest()->get()
        ]);
    }

    public function createProduct()
    {
        return view('admin.product_form', [
            'product' => new Product(),
            'categories' => Category::orderBy('sort_order')->get(),
            'modGroups' => ModGroup::where('is_active', true)->orderBy('sort_order')->get(),
            'selectedModGroups' => [],
        ]);
    }

    public function storeProduct(Request $req)
    {
        $data = $req->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:500'],
            'base_price' => ['required', 'integer', 'min:0'],
            'image_source' => ['nullable', 'in:url,upload'],
            'image_url' => ['nullable', 'url', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_sold_out' => ['nullable'],
            'is_active' => ['nullable'],
            'is_best_seller' => ['nullable'],
            'mod_groups' => ['nullable', 'array'],
            'mod_groups.*' => ['integer', 'exists:mod_groups,id'],
        ]);

        // Handle image
        $imageUrl = null;
        if ($data['image_source'] === 'upload' && $req->hasFile('image_file')) {
            $path = $req->file('image_file')->store('products', 'public');
            $imageUrl = asset('storage/' . $path);
        } elseif ($data['image_source'] === 'url' && !empty($data['image_url'])) {
            $imageUrl = $data['image_url'];
        }

        $slug = Str::slug($data['name']) . '-' . Str::lower(Str::random(4));

        $product = Product::create([
            'category_id' => $data['category_id'] ?? null,
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'base_price' => $data['base_price'],
            'image_url' => $imageUrl,
            'is_sold_out' => isset($data['is_sold_out']),
            'is_active' => isset($data['is_active']),
            'is_best_seller' => isset($data['is_best_seller']),
        ]);

        // Sync modifier groups
        if (!empty($data['mod_groups'])) {
            $product->modGroups()->sync($data['mod_groups']);
        }

        return redirect()->route('admin.products')->with('success', 'Produk dibuat.');
    }

    public function editProduct(Product $product)
    {
        return view('admin.product_form', [
            'product' => $product,
            'categories' => Category::orderBy('sort_order')->get(),
            'modGroups' => ModGroup::where('is_active', true)->orderBy('sort_order')->get(),
            'selectedModGroups' => $product->modGroups->pluck('id')->toArray(),
        ]);
    }

    public function updateProduct(Request $req, Product $product)
    {
        $data = $req->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:500'],
            'base_price' => ['required', 'integer', 'min:0'],
            'image_source' => ['nullable', 'in:url,upload'],
            'image_url' => ['nullable', 'url', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_sold_out' => ['nullable'],
            'is_active' => ['nullable'],
            'is_best_seller' => ['nullable'],
            'mod_groups' => ['nullable', 'array'],
            'mod_groups.*' => ['integer', 'exists:mod_groups,id'],
        ]);

        // Handle image
        $imageUrl = $product->image_url; // Keep existing image by default
        if ($data['image_source'] === 'upload' && $req->hasFile('image_file')) {
            // Delete old image if it was uploaded
            if ($product->image_url && str_contains($product->image_url, '/storage/products/')) {
                $oldPath = str_replace(asset('storage/'), '', $product->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $req->file('image_file')->store('products', 'public');
            $imageUrl = asset('storage/' . $path);
        } elseif ($data['image_source'] === 'url') {
            $imageUrl = !empty($data['image_url']) ? $data['image_url'] : null;
        }

        $product->update([
            'category_id' => $data['category_id'] ?? null,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'base_price' => $data['base_price'],
            'image_url' => $imageUrl,
            'is_sold_out' => isset($data['is_sold_out']),
            'is_active' => isset($data['is_active']),
            'is_best_seller' => isset($data['is_best_seller']),
        ]);

        // Sync modifier groups
        $product->modGroups()->sync($data['mod_groups'] ?? []);

        return redirect()->route('admin.products')->with('success', 'Produk diperbarui.');
    }

    public function deleteProduct(Product $product)
    {
        // Delete image file if uploaded
        if ($product->image_url && str_contains($product->image_url, '/storage/products/')) {
            $oldPath = str_replace(asset('storage/'), '', $product->image_url);
            Storage::disk('public')->delete($oldPath);
        }
        
        $product->delete();
        return back()->with('success', 'Produk dihapus.');
    }

    public function toggleBestSeller(Product $product)
    {
        $product->update(['is_best_seller' => !$product->is_best_seller]);
        return back()->with('success', $product->is_best_seller ? 'Produk ditandai Best Seller.' : 'Best Seller dihapus.');
    }
}
