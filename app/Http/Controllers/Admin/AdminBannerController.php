<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminBannerController extends Controller
{
    public function index()
    {
        $banners = Banner::ordered()->get();
        return view('admin.banners', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'images'   => 'required|array|min:1',
            'images.*' => 'image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $maxSort = Banner::max('sort_order') ?? 0;

        foreach ($request->file('images') as $file) {
            $maxSort++;
            $filename = 'banner_' . time() . '_' . $maxSort . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/banners'), $filename);

            Banner::create([
                'image_path' => 'uploads/banners/' . $filename,
                'sort_order' => $maxSort,
                'is_active'  => true,
            ]);
        }

        return back()->with('success', 'Banner berhasil diupload!');
    }

    public function toggleActive(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);
        return back()->with('success', 'Status banner diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        // Delete file
        $path = public_path($banner->image_path);
        if (File::exists($path)) {
            File::delete($path);
        }

        $banner->delete();
        return back()->with('success', 'Banner berhasil dihapus.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:banners,id',
        ]);

        foreach ($request->order as $index => $id) {
            Banner::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
