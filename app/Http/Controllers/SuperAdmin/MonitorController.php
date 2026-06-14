<?php

namespace App\Http\Controllers\SuperAdmin;
 
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Business;
 
class MonitorController extends Controller
{
 
    public function index()
    {
        $recentTx   = Transaction::with(['business', 'kasir'])->latest()->take(20)->get();
        $activeUsers = User::whereHas('roles', fn($q) => $q->whereIn('name', ['admin','kasir']))->count();
        $todayTx    = Transaction::whereDate('created_at', today())->count();
        $activeBiz  = Business::where('status', 'active')->count();
 
        return view('superadmin.monitor', compact('recentTx', 'activeUsers', 'todayTx', 'activeBiz'));
    }
}