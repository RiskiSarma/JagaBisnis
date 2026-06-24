<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Daftar kasir milik business yang sedang login.
     */
    public function index(Request $request)
    {
        $kasirs = User::where('business_id', $request->user()->business_id)
            ->whereHas('roles', fn ($q) => $q->where('name', 'kasir'))
            ->get();

        return response()->json([
            'success' => true,
            'kasirs'  => $kasirs->map(fn (User $u) => [
                'id'    => $u->id,
                'name'  => $u->name,
                'email' => $u->email,
            ]),
        ]);
    }

    /**
     * Tambah kasir baru untuk business ini.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $kasir = User::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'business_id' => $request->user()->business_id,
        ]);
        $kasir->assignRole('kasir');

        return response()->json([
            'success' => true,
            'message' => "Kasir \"{$kasir->name}\" ditambahkan!",
            'kasir'   => [
                'id'    => $kasir->id,
                'name'  => $kasir->name,
                'email' => $kasir->email,
            ],
        ], 201);
    }

    /**
     * Hapus kasir.
     */
    public function destroy(Request $request, User $user)
    {
        abort_if($user->business_id !== $request->user()->business_id, 403);

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kasir dihapus.',
        ]);
    }
}
