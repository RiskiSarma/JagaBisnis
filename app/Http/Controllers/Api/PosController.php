<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Promo;
use App\Services\PosService;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function __construct(private PosService $posService)
    {
    }

    /**
     * Data awal POS: promo aktif + customer (untuk dropdown/autocomplete awal).
     * Produk diambil terpisah dari /api/v1/products.
     */
    public function init(Request $request)
    {
        $bizId = $request->user()->business_id;

        $promos = Promo::where('business_id', $bizId)->active()->get()->map(fn (Promo $p) => [
            'id'      => $p->id,
            'name'    => $p->name,
            'type'    => $p->type,
            'value'   => $p->value,
            'min_buy' => $p->min_buy,
        ]);

        return response()->json([
            'success' => true,
            'promos'  => $promos,
        ]);
    }

    /**
     * Proses checkout — sama persis dengan logic web (PosService).
     */
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
                'transaction' => [
                    'id'            => $transaction->id,
                    'items'         => $transaction->items,
                    'subtotal'      => $transaction->subtotal,
                    'discount'      => $transaction->discount,
                    'total'         => $transaction->total,
                    'pay_method'    => $transaction->pay_method,
                    'cash_received' => $transaction->cash_received,
                    'cash_change'   => $transaction->cash_change,
                    'status'        => $transaction->status,
                    'created_at'    => $transaction->created_at->toIso8601String(),
                    'business'      => ['name' => $transaction->business->name],
                    'kasir'         => ['name' => $transaction->kasir->name],
                    'customer'      => $transaction->customer ? [
                        'id'    => $transaction->customer->id,
                        'name'  => $transaction->customer->name,
                        'phone' => $transaction->customer->phone,
                    ] : null,
                ],
                'receipt_url' => route('kasir.receipt', $transaction->id),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Cari customer (autocomplete nama/telepon).
     */
    public function searchCustomers(Request $request)
    {
        $bizId = $request->user()->business_id;
        $q     = $request->get('q', '');

        $customers = Customer::where('business_id', $bizId)
            ->where(fn ($query) => $query->where('name', 'like', "%$q%")->orWhere('phone', 'like', "%$q%"))
            ->take(8)
            ->get(['id', 'name', 'phone']);

        return response()->json([
            'success'   => true,
            'customers' => $customers,
        ]);
    }
}