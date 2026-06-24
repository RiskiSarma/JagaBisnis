<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFeature
{
    public function handle(Request $request, Closure $next, string $feature)
    {
        $user = $request->user();

        if (!$user || !$user->business) {
            return $next($request);
        }

        if ($user->hasRole('superadmin')) {
            return $next($request);
        }

        if (!$user->business->hasFeature($feature)) {
            return redirect()->route('admin.subscription.index')
                ->with('error', 'Fitur ini tidak tersedia di paket Anda saat ini. Silakan upgrade paket untuk mengaksesnya.');
        }

        return $next($request);
    }
}