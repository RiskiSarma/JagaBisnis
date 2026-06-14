<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
 
class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::where('business_id', auth()->user()->business_id)->latest()->get();
        return view('admin.promos.index', compact('promos'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'type'   => 'required|in:percent,flat',
            'value'  => 'required|integer|min:1',
            'code'   => 'required|string|max:30|unique:promos,code',
            'min_buy'=> 'nullable|integer|min:0',
        ]);
 
        Promo::create([...$request->all(), 'business_id' => auth()->user()->business_id]);
        return back()->with('success', 'Promo berhasil dibuat!');
    }
 
    public function toggle(Promo $promo)
    {
        abort_if($promo->business_id !== auth()->user()->business_id, 403);
        $promo->update(['status' => $promo->status === 'active' ? 'inactive' : 'active']);
        return back()->with('success', 'Status promo diperbarui.');
    }
 
    public function destroy(Promo $promo)
    {
        abort_if($promo->business_id !== auth()->user()->business_id, 403);
        $promo->delete();
        return back()->with('success', 'Promo dihapus.');
    }
}