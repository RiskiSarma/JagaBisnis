<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSubscription
{
    protected array $allowedRouteNames = [
        'admin.subscription.index',
        'admin.subscription.store-free',
        'admin.subscription.snap-token',
        'admin.subscription.store-manual',
        'kasir.subscription-locked',
        'logout',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->business) {
            return $next($request);
        }

        if ($user->hasRole('superadmin')) {
            return $next($request);
        }

        $business = $user->business;
        $business->refreshSubscriptionStatus();

        if ($business->hasAccess()) {
            return $next($request);
        }

        $currentRoute = $request->route()?->getName();

        if (in_array($currentRoute, $this->allowedRouteNames)) {
            return $next($request);
        }

        // Kalau request AJAX/JSON, jangan redirect — kembalikan JSON error
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses Anda sudah berakhir. Silakan pilih paket untuk melanjutkan.',
            ], 403);
        }

        // Admin/manager diarahkan ke halaman subscription (bisa bayar)
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.subscription.index')
                ->with('error', 'Periode gratis/akses Anda sudah berakhir. Silakan pilih paket untuk melanjutkan.');
        }

        // Kasir diarahkan ke halaman info saja (tidak bisa bayar)
        return redirect()->route('kasir.subscription-locked');
    }
}