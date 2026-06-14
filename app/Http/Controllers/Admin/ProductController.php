<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    private function bizId(): int
    {
        return auth()->user()->business_id;
    }

    public function index()
    {
        $products = Product::where('business_id', $this->bizId())
            ->orderBy('name')
            ->get();

        $business = auth()->user()->business;

        $categories = Product::where('business_id', $this->bizId())
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.products.index', compact('products', 'business', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'price'      => 'required|integer|min:0',
            'stock'      => 'nullable|integer|min:0',
            'stock_mode' => 'required|in:tracked,unlimited,manual',
            'category'   => 'required|string',
            'color'      => 'nullable|string|max:10',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $this->compressAndStore($request->file('image'));
        }

        Product::create([...$data, 'business_id' => $this->bizId()]);

        return back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'price'      => 'required|integer|min:0',
            'stock'      => 'nullable|integer|min:0',
            'stock_mode' => 'required|in:tracked,unlimited,manual',
            'category'   => 'required|string',
            'color'      => 'nullable|string|max:10',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'remove_image' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $this->compressAndStore($request->file('image'));
        } elseif ($request->boolean('remove_image')) {
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $data['image'] = null;
        }

        unset($data['remove_image']);

        $product->update($data);

        return back()->with('success', 'Produk diperbarui!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', 'Produk dihapus.');
    }

    /**
     * Kompres gambar agar ukurannya < 1MB lalu simpan ke storage.
     */
    private function compressAndStore($file): string
    {
        $maxBytes = 1024 * 1024; // 1MB
        $extension = strtolower($file->getClientOriginalExtension());

        // Jika ukuran file sudah di bawah 1MB, simpan langsung tanpa kompresi
        if ($file->getSize() <= $maxBytes) {
            return $file->store('products', 'public');
        }

        // Jika lebih dari 1MB, kompres dengan GD driver
        $manager = \Intervention\Image\ImageManager::gd();
        $image   = $manager->read($file->getRealPath());

        if ($image->width() > 1000) {
            $image->scale(width: 1000);
        }

        $quality  = 80;
        $filename = 'products/' . uniqid('prod_') . '.jpg';

        do {
            $encoded = $image->toJpeg($quality);
            $size    = strlen((string) $encoded);

            if ($size > $maxBytes) {
                $quality -= 10;
            }
        } while ($size > $maxBytes && $quality > 10);

        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }
}