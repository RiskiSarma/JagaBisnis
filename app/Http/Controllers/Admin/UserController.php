<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
 
class UserController extends Controller
{
    public function index()
    {
        $kasirs = User::where('business_id', auth()->user()->business_id)
                      ->whereHas('roles', fn($q) => $q->where('name', 'kasir'))
                      ->get();
        return view('admin.users.index', compact('kasirs'));
    }
 
    public function store(Request $request)
    {
        $business = auth()->user()->business;

        if (!$business->canAddKasir()) {
            return back()->with('error', 'Batas jumlah kasir untuk paket ' . ucfirst($business->paket) . ' sudah tercapai. Silakan upgrade paket.');
        }
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
 
        $kasir = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'business_id' => auth()->user()->business_id,
        ]);
        $kasir->assignRole('kasir');
 
        return back()->with('success', "Kasir \"{$kasir->name}\" ditambahkan!");
    }
 
    public function destroy(User $user)
    {
        abort_if($user->business_id !== auth()->user()->business_id, 403);
        $user->delete();
        return back()->with('success', 'Kasir dihapus.');
    }
}
