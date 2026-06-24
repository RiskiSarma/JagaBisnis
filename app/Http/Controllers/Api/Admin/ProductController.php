<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function bizId(Request $request): int
    {
        return $request->user()->business_id;
    }

    /**
     * Daftar produk + kategori (untuk halaman Kelola Produk).
     */
    public function index(Request $request)
    {
        $bizId = $this->bizId($request);

        $products = Product::where('business_id', $bizId)
            ->orderBy('name')
            ->get()
            ->map(fn (Product $p) => $this->formatProduct($p));

        $categories = Product::where('business_id', $bizId)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return response()->json([
            'success'    => true,
            'products'   => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Tambah produk baru. Gambar dikirim sebagai multipart/form-data field "image".
     */
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

        $product = Product::create([...$data, 'business_id' => $this->bizId($request)]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan!',
            'product' => $this->formatProduct($product),
        ], 201);
    }

    /**
     * Detail satu produk.
     */
    public function show(Request $request, Product $product)
    {
        abort_if($product->business_id !== $this->bizId($request), 403);

        return response()->json([
            'success' => true,
            'product' => $this->formatProduct($product),
        ]);
    }

    /**
     * Update produk. Untuk upload gambar dari Flutter, kirim sebagai
     * multipart/form-data dengan method override (_method=PUT) atau
     * gunakan POST ke endpoint ini (Laravel akan tetap menerima PUT
     * dengan field _method=PUT jika multipart, karena PHP tidak
     * mendukung parsing body PUT multipart secara native).
     */
    public function update(Request $request, Product $product)
    {
        abort_if($product->business_id !== $this->bizId($request), 403);

        $data = $request->validate([
            'name'         => 'required|string|max:100',
            'price'        => 'required|integer|min:0',
            'stock'        => 'nullable|integer|min:0',
            'stock_mode'   => 'required|in:tracked,unlimited,manual',
            'category'     => 'required|string',
            'color'        => 'nullable|string|max:10',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'remove_image' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $this->compressAndStore($request->file('image'));
        } elseif ($request->boolean('remove_image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = null;
        }

        unset($data['remove_image']);

        $product->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Produk diperbarui!',
            'product' => $this->formatProduct($product->refresh()),
        ]);
    }

    /**
     * Hapus produk.
     */
    public function destroy(Request $request, Product $product)
    {
        abort_if($product->business_id !== $this->bizId($request), 403);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk dihapus.',
        ]);
    }

    private function formatProduct(Product $p): array
    {
        return [
            'id'         => $p->id,
            'name'       => $p->name,
            'price'      => $p->price,
            'stock'      => $p->stock,
            'stock_mode' => $p->stock_mode,
            'category'   => $p->category,
            'color'      => $p->color,
            'image_url'  => $p->image_url,
            'out'        => $p->isOutOfStock(),
            'low_stock'  => $p->isLowStock(),
        ];
    }

    /**
     * Kompres gambar agar ukurannya < 1MB lalu simpan ke storage.
     * Sama persis dengan logic di Admin\ProductController (web).
     */
    private function compressAndStore($file): string
    {
        $maxBytes = 1024 * 1024; // 1MB

        if ($file->getSize() <= $maxBytes) {
            return $file->store('products', 'public');
        }

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

        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }
}
