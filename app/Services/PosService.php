<?php

namespace App\Services;
 
use App\Models\Customer;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use RuntimeException;
 
class PosService
{
    public function processCheckout(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $user     = auth()->user();
            $business = $user->business;
 
            // Build items & hitung subtotal
            $items    = [];
            $subtotal = 0;
 
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['id']);
 
                // Validasi stok kalau feat_stok aktif
                if ($business->feat_stok && $product->stock_mode === 'tracked') {
                    if ($product->stock < $item['qty']) {
                        throw new RuntimeException("Stok {$product->name} tidak cukup (tersisa {$product->stock}).");
                    }
                }
 
                $lineTotal  = $product->price * $item['qty'];
                $subtotal  += $lineTotal;
 
                $items[] = [
                    'id'    => $product->id,
                    'name'  => $product->name,
                    'qty'   => (int) $item['qty'],
                    'price' => $product->price,
                ];
            }
 
            // Hitung diskon
            $discount = 0;
            if (!empty($data['promo_id'])) {
                $promo    = Promo::find($data['promo_id']);
                if ($promo) $discount = $promo->calcDiscount($subtotal);
            }
 
            $total        = $subtotal - $discount;
            $cashReceived = (int) ($data['cash_received'] ?? $total);
            $cashChange   = $data['pay_method'] === 'cash' ? max(0, $cashReceived - $total) : 0;
 
            // Upsert customer
            $customer = null;
            $custName = trim($data['customer_name'] ?? '');
            if ($custName && $custName !== 'Pelanggan') {
                $customer = Customer::firstOrCreate(
                    ['business_id' => $business->id, 'name' => $custName],
                    ['phone' => $data['customer_phone'] ?? null]
                );
                $customer->increment('total_visits');
                $customer->increment('total_spend', $total);
                $customer->update(['last_visit' => today()]);
 
                // Update phone kalau belum ada
                if (empty($customer->phone) && !empty($data['customer_phone'])) {
                    $customer->update(['phone' => $data['customer_phone']]);
                }
            }
 
            // Simpan transaksi
            $transaction = Transaction::create([
                'business_id'   => $business->id,
                'user_id'       => $user->id,
                'customer_id'   => $customer?->id,
                'items'         => $items,
                'subtotal'      => $subtotal,
                'discount'      => $discount,
                'total'         => $total,
                'pay_method'    => $data['pay_method'],
                'cash_received' => $cashReceived,
                'cash_change'   => $cashChange,
                'status'        => 'lunas',
            ]);
 
            // Kurangi stok tracked
            if ($business->feat_stok) {
                foreach ($data['items'] as $item) {
                    $product = Product::find($item['id']);
                    if ($product) $product->decreaseStock((int) $item['qty']);
                }
            }
 
            // Update statistik bisnis
            $business->increment('total_transactions');
            $business->increment('total_revenue', $total);
 
            return $transaction->load('kasir', 'customer', 'business');
        });
    }
}