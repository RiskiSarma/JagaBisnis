<?php

namespace App\Http\Controllers\SuperAdmin;
 
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
 
class UserController extends Controller
{
 
    public function index()
    {
        $users      = User::with(['roles', 'business'])
                          ->whereHas('roles', fn($q) => $q->whereIn('name', ['admin','kasir']))
                          ->get();
        $businesses = Business::all();
 
        return view('superadmin.users.index', compact('users', 'businesses'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6|confirmed',
            'role'        => 'required|in:admin,kasir',
            'business_id' => 'required|exists:businesses,id',
        ]);
 
        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'business_id' => $request->business_id,
        ]);
        $user->assignRole($request->role);
 
        return back()->with('success', "Pengguna \"{$user->name}\" berhasil ditambahkan!");
    }
 
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role'     => 'required|in:admin,kasir',
        ]);
 
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            ...(filled($request->password) ? ['password' => Hash::make($request->password)] : []),
        ]);
 
        $user->syncRoles([$request->role]);
 
        return back()->with('success', 'Pengguna diperbarui!');
    }
 
    public function destroy(User $user)
    {
        if ($user->hasRole('superadmin')) {
            return back()->with('error', 'Tidak bisa menghapus superadmin.');
        }
        $user->delete();
        return back()->with('success', 'Pengguna dihapus.');
    }
}
