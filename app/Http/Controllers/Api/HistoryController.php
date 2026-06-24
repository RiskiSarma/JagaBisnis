<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Riwayat transaksi milik kasir yang sedang login.
     * Query param: ?filter=today|all
     */
    public function index(Request $request)
    {
        $user   = $request->user();
        $bizId  = $user->business_id;
        $today  = Carbon::today('Asia/Jakarta');
        $filter = $request->input('filter', 'today');

        $query = Transaction::forBusiness($bizId)
            ->where('user_id', $user->id)
            ->with('customer')
            ->latest();

        if ($filter === 'today') {
            $query->whereDate('created_at', $today);
        }

        $transactions = $query->get()->map(fn (Transaction $t) => $this->formatTransaction($t));

        // Summary selalu hari ini
        $todaySummary = Transaction::forBusiness($bizId)
            ->where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->get();

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
            'summary' => [
                'today_total' => $todaySummary->sum('total'),
                'today_lunas' => $todaySummary->where('status', 'lunas')->count(),
                'today_belum' => $todaySummary->where('status', '!=', 'lunas')->count(),
            ],
            'filter' => $filter,
        ]);
    }

    /**
     * Detail satu transaksi (untuk lihat struk).
     */
    public function show(Request $request, Transaction $transaction)
    {
        abort_if($transaction->business_id !== $request->user()->business_id, 403);

        $transaction->load('business', 'customer', 'kasir');

        return response()->json([
            'success'     => true,
            'transaction' => $this->formatTransaction($transaction, detailed: true),
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
        ];

        if ($detailed) {
            $data['business'] = ['name' => $t->business->name];
            $data['kasir']    = ['name' => $t->kasir?->name];
        }

        return $data;
    }
}