<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
 
class CustomerController extends Controller
{
    public function index()
    {
        $bizId = auth()->user()->business_id;
        $customers = Customer::where('business_id', $bizId)
            ->withCount('transactions as visits')
            ->withMax('transactions as last_visit', 'created_at')
            ->orderByDesc('total_spend')->get();

        $activePromos = \App\Models\Promo::where('business_id', $bizId)
            ->where('status', 'active')->get();

        return view('admin.customers.index', compact('customers', 'activePromos'));
    }
 
    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'phone' => 'nullable|string|max:20']);
        Customer::create([...$request->only('name','phone'), 'business_id' => auth()->user()->business_id]);
        return back()->with('success', 'Customer ditambahkan!');
    }
 
    public function destroy(Customer $customer)
    {
        abort_if($customer->business_id !== auth()->user()->business_id, 403);
        $customer->delete();
        return back()->with('success', 'Customer dihapus.');
    }
}