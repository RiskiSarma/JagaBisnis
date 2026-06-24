<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $promos = Promo::where('business_id', $request->user()->business_id)
            ->latest()
            ->get()
            ->map(fn (Promo $p) => $this->formatPromo($p));

        return response()->json([
            'success' => true,
            'promos'  => $promos,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'type'        => 'required|in:percent,flat',
            'value'       => 'required|integer|min:1',
            'code'        => 'required|string|max:30|unique:promos,code',
            'min_buy'     => 'nullable|integer|min:0',
        ]);

        $promo = Promo::create([
            ...$data,
            'business_id' => $request->user()->business_id,
            'status'      => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Promo berhasil dibuat!',
            'promo'   => $this->formatPromo($promo),
        ], 201);
    }

    public function toggle(Request $request, Promo $promo)
    {
        abort_if($promo->business_id !== $request->user()->business_id, 403);

        $promo->update([
            'status' => $promo->status === 'active' ? 'inactive' : 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status promo diperbarui.',
            'status'  => $promo->status,
        ]);
    }

    public function destroy(Request $request, Promo $promo)
    {
        abort_if($promo->business_id !== $request->user()->business_id, 403);

        $promo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Promo dihapus.',
        ]);
    }

    private function formatPromo(Promo $p): array
    {
        return [
            'id'          => $p->id,
            'name'        => $p->name,
            'description' => $p->description,
            'type'        => $p->type,
            'value'       => $p->value,
            'min_buy'     => $p->min_buy,
            'code'        => $p->code,
            'status'      => $p->status,
        ];
    }
}
