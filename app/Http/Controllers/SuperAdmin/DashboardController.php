<?php
// =====================================================================
// FILE: app/Http/Controllers/SuperAdmin/DashboardController.php
// =====================================================================
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:superadmin']);
    // }

    public function index()
    {
        $businesses  = Business::withCount('users')->get();
        $totalRevenue = Business::sum('total_revenue');
        $totalTx      = Transaction::count();
        $totalUsers   = User::whereHas('roles', fn($q) => $q->whereIn('name', ['admin','kasir']))->count();

        $revenueByBiz = $businesses->map(fn($b) => [
            'name'    => $b->name,
            'revenue' => $b->total_revenue,
        ]);

        return view('superadmin.dashboard', compact(
            'businesses', 'totalRevenue', 'totalTx', 'totalUsers', 'revenueByBiz'
        ));
    }
    public function syncRevenue()
    {
        $count = Business::recalculateAll();
        
        return back()->with('success', "Berhasil menyinkronkan revenue untuk {$count->count()} bisnis.");
    }
}