<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
 
class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $bizId  = auth()->user()->business_id;
        $filter = $request->get('filter', 'all');
 
        $query = Transaction::forBusiness($bizId)->with('customer', 'kasir')->latest();
 
        if ($filter === 'lunas')       $query->where('status', 'lunas');
        if ($filter === 'belum_lunas') $query->where('status', 'belum_lunas');
 
        $transactions = $query->paginate(25);
        $counts = [
            'all'         => Transaction::forBusiness($bizId)->count(),
            'lunas'       => Transaction::forBusiness($bizId)->where('status','lunas')->count(),
            'belum_lunas' => Transaction::forBusiness($bizId)->where('status','belum_lunas')->count(),
        ];
 
        return view('admin.transactions.index', compact('transactions', 'filter', 'counts'));
    }
 
    public function show(Transaction $transaction)
    {
        abort_if($transaction->business_id !== auth()->user()->business_id, 403);
        $transaction->load('customer', 'kasir', 'business');
        return view('admin.transactions.show', compact('transaction'));
    }
 
    public function update(Request $request, Transaction $transaction)
    {
        abort_if($transaction->business_id !== auth()->user()->business_id, 403);
 
        $transaction->update([
            'status'  => $request->status,
            'catatan' => $request->catatan,
        ]);
 
        return back()->with('success', 'Transaksi diperbarui.');
    }
 
    public function toggleStatus(Transaction $transaction)
    {
        abort_if($transaction->business_id !== auth()->user()->business_id, 403);
        $transaction->update([
            'status' => $transaction->status === 'lunas' ? 'belum_lunas' : 'lunas',
        ]);
        return back()->with('success', 'Status diperbarui.');
    }
}
