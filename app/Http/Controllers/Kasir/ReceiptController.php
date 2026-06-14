<?php

namespace App\Http\Controllers\Kasir;
 
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
 
class ReceiptController extends Controller
{
    public function show(Transaction $transaction)
    {
        abort_if($transaction->business_id !== auth()->user()->business_id, 403);
        $transaction->load('business', 'kasir', 'customer');
        return view('kasir.receipt', compact('transaction'));
    }
 
    public function pdf(Transaction $transaction)
    {
        abort_if($transaction->business_id !== auth()->user()->business_id, 403);
        $transaction->load('business', 'kasir', 'customer');
 
        $pdf = Pdf::loadView('kasir.receipt-pdf', compact('transaction'))
                  ->setPaper([0, 0, 226.77, 600], 'portrait');
 
        return $pdf->download("struk-TXN-{$transaction->id}.pdf");
    }
 
    public function updateCatatan(Transaction $transaction, \Illuminate\Http\Request $request)
    {
        abort_if($transaction->business_id !== auth()->user()->business_id, 403);
        $transaction->update(['catatan' => $request->catatan]);
        return response()->json(['success' => true]);
    }
}