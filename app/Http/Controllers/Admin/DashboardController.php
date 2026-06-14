<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
 
class DashboardController extends Controller
{
    // public function __construct() { $this->middleware(['auth', 'role:admin']); }
 
    public function index()
    {
        $biz      = auth()->user()->business;
        $bizId    = $biz->id;
        $today    = today();
 
        $todayTx     = Transaction::forBusiness($bizId)->today()->with('customer')->latest()->get();
        $todayRev    = $todayTx->sum('total');
        $totalProds  = Product::forBusiness($bizId)->count();
        $lowStock    = Product::forBusiness($bizId)->where('stock_mode', 'tracked')->where('stock', '<=', 5)->get();
        $recentTx    = Transaction::forBusiness($bizId)->with('customer', 'kasir')->latest()->take(10)->get();
        $totalRev    = Transaction::forBusiness($bizId)->sum('total');
        $belumLunas  = Transaction::forBusiness($bizId)->where('status', 'belum_lunas')->count();
        $totalTransactions = Transaction::forBusiness($bizId)->count();
        
        return view('admin.dashboard', compact(
            'biz', 'todayTx', 'todayRev', 'totalProds',
            'lowStock', 'recentTx', 'totalRev', 'belumLunas', 'totalTransactions'
        ));
    }
}