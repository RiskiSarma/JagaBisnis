<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $biz   = $request->user()->business;
        $bizId = $biz->id;

        $todayTx    = Transaction::forBusiness($bizId)->today()->with('customer')->latest()->get();
        $todayRev   = $todayTx->sum('total');
        $totalProds = Product::forBusiness($bizId)->count();
        $lowStock   = Product::forBusiness($bizId)->where('stock_mode', 'tracked')->where('stock', '<=', 5)->get();
        $recentTx   = Transaction::forBusiness($bizId)->with('customer', 'kasir')->latest()->take(10)->get();
        $totalRev   = Transaction::forBusiness($bizId)->sum('total');
        $belumLunas = Transaction::forBusiness($bizId)->where('status', 'belum_lunas')->count();
        $totalTx    = Transaction::forBusiness($bizId)->count();

        $todayLunas = $todayTx->where('status', 'lunas')->count();
        $todayBelum = $todayTx->where('status', '!=', 'lunas')->count();

        return response()->json([
            'success' => true,
            'business' => [
                'id'   => $biz->id,
                'name' => $biz->name,
            ],
            'stats' => [
                'today_tx_count'   => $todayTx->count(),
                'today_tx_lunas'   => $todayLunas,
                'today_tx_belum'   => $todayBelum,
                'today_revenue'    => $todayRev,
                'total_products'   => $totalProds,
                'low_stock_count'  => $lowStock->count(),
                'total_customers'  => $biz->customers()->count(),
                'total_revenue'    => $totalRev,
                'total_transactions' => $totalTx,
                'belum_lunas'      => $belumLunas,
            ],
            'low_stock_products' => $lowStock->map(fn (Product $p) => [
                'id'    => $p->id,
                'name'  => $p->name,
                'stock' => $p->stock,
            ]),
            'recent_transactions' => $recentTx->map(fn (Transaction $t) => [
                'id'         => $t->id,
                'created_at' => $t->created_at->toIso8601String(),
                'customer'   => $t->customer?->name ?? 'Pelanggan',
                'item_count' => is_array($t->items) ? count($t->items) : 0,
                'total'      => $t->total,
                'kasir'      => $t->kasir?->name,
                'status'     => $t->status,
            ]),
        ]);
    }
}
