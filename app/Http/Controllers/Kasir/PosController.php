<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Promo;
use App\Services\PosService;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function __construct(private PosService $posService)
    {
        // Middleware dihapus dari sini
        // Karena sudah dihandle di routes/web.php
    }

    public function index()
    {
        $bizId     = auth()->user()->business_id;
        $business  = auth()->user()->business;
        $products  = Product::forBusiness($bizId)->orderBy('category')->orderBy('name')->get();
        $promos    = Promo::where('business_id', $bizId)->active()->get();
        $customers = Customer::where('business_id', $bizId)->orderBy('name')->get();

        return view('kasir.pos', compact('products', 'promos', 'customers', 'business'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,id',
            'items.*.qty'    => 'required|integer|min:1',
            'pay_method'     => 'required|in:cash,transfer',
            'cash_received'  => 'nullable|integer|min:0',
            'customer_name'  => 'nullable|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'promo_id'       => 'nullable|exists:promos,id',
        ]);

        try {
            $transaction = $this->posService->processCheckout($request->all());

            return response()->json([
                'success'     => true,
                'transaction' => $transaction,
                'receipt_url' => route('kasir.receipt', $transaction->id),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function searchCustomers(Request $request)
    {
        $bizId = auth()->user()->business_id;
        $q     = $request->get('q', '');

        $customers = Customer::where('business_id', $bizId)
            ->where(fn($query) => $query->where('name', 'like', "%$q%")->orWhere('phone', 'like', "%$q%"))
            ->take(8)
            ->get(['id', 'name', 'phone']);

        return response()->json($customers);
    }
}