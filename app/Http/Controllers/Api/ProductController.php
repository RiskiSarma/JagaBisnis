<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Daftar produk untuk katalog POS (kasir) — termasuk kategori unik.
     */
    public function index(Request $request)
    {
        $bizId = $request->user()->business_id;

        $products = Product::forBusiness($bizId)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->map(fn (Product $p) => $this->formatProduct($p));

        $categories = Product::forBusiness($bizId)
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
}