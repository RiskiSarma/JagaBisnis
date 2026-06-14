<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

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

        return view('admin.products.index', compact('products', 'business'));
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
        ]);

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
        ]);

        $product->update($data);

        return back()->with('success', 'Produk diperbarui!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();

        return back()->with('success', 'Produk dihapus.');
    }
}