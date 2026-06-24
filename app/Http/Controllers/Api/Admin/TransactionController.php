<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Daftar transaksi seluruh kasir di business ini.
     * Query param: ?filter=all|lunas|belum_lunas, ?page=1
     */
    public function index(Request $request)
    {
        $bizId  = $request->user()->business_id;
        $filter = $request->get('filter', 'all');

        $query = Transaction::forBusiness($bizId)->with('customer', 'kasir')->latest();

        if ($filter === 'lunas')       $query->where('status', 'lunas');
        if ($filter === 'belum_lunas') $query->where('status', 'belum_lunas');

        $transactions = $query->paginate(25);

        $counts = [
            'all'         => Transaction::forBusiness($bizId)->count(),
            'lunas'       => Transaction::forBusiness($bizId)->where('status', 'lunas')->count(),
            'belum_lunas' => Transaction::forBusiness($bizId)->where('status', 'belum_lunas')->count(),
        ];

        return response()->json([
            'success' => true,
            'filter'  => $filter,
            'counts'  => $counts,
            'transactions' => $transactions->getCollection()->map(fn (Transaction $t) => $this->formatTransaction($t)),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'last_page'    => $transactions->lastPage(),
                'per_page'     => $transactions->perPage(),
                'total'        => $transactions->total(),
            ],
        ]);
    }

    /**
     * Detail satu transaksi.
     */
    public function show(Request $request, Transaction $transaction)
    {
        abort_if($transaction->business_id !== $request->user()->business_id, 403);

        $transaction->load('customer', 'kasir', 'business');

        return response()->json([
            'success'     => true,
            'transaction' => $this->formatTransaction($transaction, detailed: true),
        ]);
    }

    /**
     * Update status & catatan transaksi.
     */
    public function update(Request $request, Transaction $transaction)
    {
        abort_if($transaction->business_id !== $request->user()->business_id, 403);

        $data = $request->validate([
            'status'  => 'required|in:lunas,belum_lunas',
            'catatan' => 'nullable|string|max:500',
        ]);

        $transaction->update($data);

        return response()->json([
            'success'     => true,
            'message'     => 'Transaksi diperbarui.',
            'transaction' => $this->formatTransaction($transaction->refresh()),
        ]);
    }

    /**
     * Toggle status lunas / belum lunas.
     */
    public function toggleStatus(Request $request, Transaction $transaction)
    {
        abort_if($transaction->business_id !== $request->user()->business_id, 403);

        $transaction->update([
            'status' => $transaction->status === 'lunas' ? 'belum_lunas' : 'lunas',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status diperbarui.',
            'status'  => $transaction->status,
        ]);
    }

    private function formatTransaction(Transaction $t, bool $detailed = false): array
    {
        $data = [
            'id'            => $t->id,
            'items'         => $t->items,
            'subtotal'      => $t->subtotal,
            'discount'      => $t->discount,
            'total'         => $t->total,
            'pay_method'    => $t->pay_method,
            'cash_received' => $t->cash_received,
            'cash_change'   => $t->cash_change,
            'status'        => $t->status,
            'catatan'       => $t->catatan,
            'created_at'    => $t->created_at->toIso8601String(),
            'customer'      => $t->customer ? [
                'id'    => $t->customer->id,
                'name'  => $t->customer->name,
                'phone' => $t->customer->phone,
            ] : null,
            'kasir' => $t->kasir ? ['id' => $t->kasir->id, 'name' => $t->kasir->name] : null,
        ];

        if ($detailed) {
            $data['business'] = ['name' => $t->business->name];
        }

        return $data;
    }
}
