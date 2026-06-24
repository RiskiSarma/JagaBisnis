<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Business;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'name'          => ['required', 'string', 'max:255'],
        'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        'password'      => ['required', 'confirmed', 'min:8'],
        'business_name' => ['required', 'string', 'max:255'],
        'business_type' => ['required', 'string'],
        'paket'         => ['required', 'in:starter,pro,business'],
    ], [
        'email.unique'   => 'Email ini sudah terdaftar. Silakan login atau gunakan email lain.',
        'email.required' => 'Email wajib diisi.',
        'email.email'    => 'Format email tidak valid.',
        'password.min'      => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'name.required'  => 'Nama lengkap wajib diisi.',
        'business_name.required' => 'Nama bisnis wajib diisi.',
        'business_type.required' => 'Jenis bisnis wajib dipilih.',
    ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $business = Business::create([
                'name'          => $request->business_name,
                'type'          => $request->business_type,
                'phone'         => $request->business_wa ?? null,
                'city'          => $request->city ?? null,
                'paket'         => $request->paket,
                'is_active'     => true,
                'trial_ends_at' => now()->addDays(14),
            ]);

            $user = User::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'password'    => Hash::make($request->password),
                'business_id' => $business->id,
            ]);

            $user->assignRole('admin');

            event(new Registered($user));
            Auth::login($user);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Akun berhasil dibuat',
                'redirect' => route('admin.dashboard'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat akun: ' . $e->getMessage(),
            ], 500);
        }
    }
}