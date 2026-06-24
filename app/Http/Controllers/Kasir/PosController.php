<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Transaction;
use App\Services\PosService;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PosController extends Controller
{
    public function __construct(private PosService $posService) {}

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
            'payment_method' => 'nullable|string',
        ]);

        try {
            $transaction = $this->posService->processCheckout($request->all());

            return response()->json([
                'success'        => true,
                'transaction_id' => $transaction->id,
                'transaction'    => $transaction,
                'receipt_url'    => route('kasir.receipt', $transaction->id),
                'message'        => ($request->input('payment_method') === 'midtrans')
                    ? 'Transaksi dibuat, silakan selesaikan pembayaran.'
                    : 'Transaksi berhasil.',
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
            ->where(fn($q2) => $q2->where('name', 'like', "%$q%")->orWhere('phone', 'like', "%$q%"))
            ->take(8)
            ->get(['id', 'name', 'phone']);

        return response()->json($customers);
    }

    public function getSnapToken(Request $request, MidtransService $midtrans)
    {
        $request->validate([
            'transaction_id' => ['required', 'exists:transactions,id'],
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);
        $business    = auth()->user()->business;

        if ($transaction->business_id !== $business->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        if ($transaction->snap_token) {
            return response()->json([
                'success'       => true,
                'snap_token'    => $transaction->snap_token,
                'client_key'    => $business->midtrans_client_key,
                'is_production' => $business->midtrans_is_production,
            ]);
        }

        try {
            $orderId = 'TXN-' . $transaction->id . '-' . now()->format('YmdHis') . '-' . Str::random(4);

            $items = collect($transaction->items ?? [])->map(fn($item) => [
                'id'       => 'prod-' . ($item['id'] ?? uniqid()),
                'price'    => (int) ($item['price'] ?? 0),
                'quantity' => (int) ($item['qty'] ?? 1),
                'name'     => $item['name'] ?? 'Produk',
            ])->toArray();

            if (empty($items)) {
                $items = [[
                    'id'       => 'total',
                    'price'    => (int) $transaction->total,
                    'quantity' => 1,
                    'name'     => 'Pembayaran Transaksi #' . $transaction->id,
                ]];
            }

            $transaction->update([
                'payment_gateway'   => 'midtrans',
                'midtrans_order_id' => $orderId,
            ]);

            $snapToken = $midtrans->createSnapTokenForTransaction($business, $transaction, $items);
            $transaction->update(['snap_token' => $snapToken]);

            return response()->json([
                'success'       => true,
                'snap_token'    => $snapToken,
                'client_key'    => $business->midtrans_client_key,
                'is_production' => $business->midtrans_is_production,
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('POS Midtrans error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}