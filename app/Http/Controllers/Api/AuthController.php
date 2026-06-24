<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login & buat token Sanctum baru untuk perangkat ini.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $deviceName = $request->device_name ?? $request->userAgent() ?? 'mobile-device';

        // Hapus token lama dengan nama device yang sama (1 device = 1 token aktif)
        $user->tokens()->where('name', $deviceName)->delete();

        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => $this->formatUser($user),
        ]);
    }

    /**
     * Logout — hapus token yang sedang dipakai.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Ambil data user yang sedang login (untuk cek sesi & role saat app dibuka).
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user'    => $this->formatUser($request->user()),
        ]);
    }

    private function formatUser(\App\Models\User $user): array
    {
        $user->loadMissing('business');

        return [
            'id'          => $user->id,
            'name'        => $user->name,
            'email'       => $user->email,
            'role'        => $user->getRoleNames()->first(),
            'business_id' => $user->business_id,
            'business'    => $user->business ? [
                'id'        => $user->business->id,
                'name'      => $user->business->name,
                'type'      => $user->business->type,
                'status'    => $user->business->status,
                'feat_stok' => (bool) $user->business->feat_stok,
            ] : null,
        ];
    }
}