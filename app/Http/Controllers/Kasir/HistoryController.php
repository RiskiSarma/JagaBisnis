<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $bizId  = auth()->user()->business_id;
        $today  = Carbon::today('Asia/Jakarta');
        $filter = $request->input('filter', 'today');

        $query = Transaction::forBusiness($bizId)
            ->where('user_id', auth()->id())
            ->with('customer')
            ->latest();

        if ($filter === 'today') {
            $query->whereDate('created_at', $today);
        }

        $transactions = $query->get();

        // Summary selalu hanya hari ini
        $todaySummary = Transaction::forBusiness($bizId)
            ->where('user_id', auth()->id())
            ->whereDate('created_at', $today)
            ->get();

        $todayTotal = $todaySummary->sum('total');
        $todayLunas = $todaySummary->where('status', 'lunas')->count();
        $todayBelum = $todaySummary->where('status', '!=', 'lunas')->count();

        return view('kasir.history', compact(
            'transactions',
            'todayTotal',
            'todayLunas',
            'todayBelum',
            'filter',
        ));
    }

    public function toggleStatus(Transaction $transaction)
    {
        abort_if(
            $transaction->business_id !== auth()->user()->business_id,
            403
        );

        $transaction->update([
            'status' => $transaction->status === 'lunas' ? 'belum_lunas' : 'lunas',
        ]);

        return back();
    }

    /**
     * Return modal HTML via AJAX untuk struk/detail transaksi
     */
    public function modalStruk(Transaction $transaction)
    {
        abort_if(
            $transaction->business_id !== auth()->user()->business_id,
            403
        );

        $transaction->load('business', 'customer', 'kasir');

        return view('kasir._modal-struk', compact('transaction'));
    }
    public function sendWa(Transaction $transaction)
    {
        abort_if($transaction->business_id !== auth()->user()->business_id, 403);

        $transaction->load('customer', 'business');

        $items = is_string($transaction->getRawOriginal('items'))
            ? json_decode($transaction->getRawOriginal('items'), true) ?? []
            : ($transaction->items ?? []);

        $itemLines = collect($items)
            ->map(fn($i) => "• {$i['name']} ×{$i['qty']} = Rp " . number_format(($i['price'] ?? 0) * ($i['qty'] ?? 1), 0, ',', '.'))
            ->implode("\n");

        $msg = "🧾 *NOTA BELANJA*\n"
            . "━━━━━━━━━━━━━━━━\n"
            . "🏪 *{$transaction->business->name}*\n"
            . "📅 {$transaction->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i')} WIB\n"
            . "🔖 TXN-" . str_pad($transaction->id, 3, '0', STR_PAD_LEFT) . "\n"
            . "━━━━━━━━━━━━━━━━\n"
            . $itemLines . "\n"
            . "━━━━━━━━━━━━━━━━\n"
            . "💰 *TOTAL: Rp " . number_format($transaction->total, 0, ',', '.') . "*\n"
            . "✅ Status: {$transaction->status}\n\n"
            . "🙏 Terima kasih sudah berbelanja di *{$transaction->business->name}*!";

        $phone = preg_replace('/[^0-9]/', '', preg_replace('/^0/', '62', $transaction->customer->phone));

        return redirect("https://wa.me/{$phone}?text=" . urlencode($msg));
    }
}