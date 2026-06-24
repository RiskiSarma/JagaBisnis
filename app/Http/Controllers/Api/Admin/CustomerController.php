<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Promo;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $bizId = $request->user()->business_id;

        $customers = Customer::where('business_id', $bizId)
            ->withCount('transactions as visits')
            ->withMax('transactions as last_visit', 'created_at')
            ->orderByDesc('total_spend')
            ->get();

        $activePromos = Promo::where('business_id', $bizId)
            ->where('status', 'active')
            ->get(['id', 'name', 'type', 'value', 'code']);

        return response()->json([
            'success' => true,
            'customers' => $customers->map(fn (Customer $c) => [
                'id'          => $c->id,
                'name'        => $c->name,
                'phone'       => $c->phone,
                'total_spend' => $c->total_spend,
                'tier'        => $c->tier,
                'visits'      => $c->visits,
                'last_visit'  => $c->last_visit,
            ]),
            'active_promos' => $activePromos,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer = Customer::create([
            ...$data,
            'business_id' => $request->user()->business_id,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Customer ditambahkan!',
            'customer' => [
                'id'    => $customer->id,
                'name'  => $customer->name,
                'phone' => $customer->phone,
            ],
        ], 201);
    }

    public function destroy(Request $request, Customer $customer)
    {
        abort_if($customer->business_id !== $request->user()->business_id, 403);

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer dihapus.',
        ]);
    }
}
