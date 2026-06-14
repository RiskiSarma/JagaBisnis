<?php
// FILE: app/Http/Controllers/SuperAdmin/BusinessController.php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BusinessController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:superadmin']);
    // }

    public function index()
    {
        $businesses = Business::with(['users'])->get();
        return view('superadmin.businesses.index', compact('businesses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:100',
            'type'                  => 'required|string',
            'mgr_name'              => 'required|string|max:100',
            'mgr_email'             => 'required|email|unique:users,email',
            'mgr_password'          => 'required|min:6|confirmed',
        ]);

        $business = Business::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        $manager = User::create([
            'name'        => $request->mgr_name,
            'email'       => $request->mgr_email,
            'password'    => Hash::make($request->mgr_password),
            'business_id' => $business->id,
        ]);
        $manager->assignRole('admin');

        return back()->with('success', "Bisnis \"{$business->name}\" dan akun manager berhasil dibuat!");
    }

    public function toggleStatus(Business $business)
    {
        $business->update([
            'status' => $business->status === 'active' ? 'inactive' : 'active',
        ]);

        if (request()->wantsJson()) {
            return response()->json(['status' => $business->status]);
        }
        return back()->with('success', "Status bisnis diperbarui.");
    }

    public function toggleFeatStok(Business $business)
    {
        $business->update(['feat_stok' => !$business->feat_stok]);

        if (request()->wantsJson()) {
            return response()->json(['feat_stok' => $business->feat_stok]);
        }
        return back()->with('success', "Fitur stok diperbarui.");
    }

    public function destroy(Business $business)
    {
        $business->delete();
        return back()->with('success', 'Bisnis dihapus.');
    }
}