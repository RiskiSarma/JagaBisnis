<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. SUPER ADMIN ──────────────────────────────────────────────
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@jagabisnis.id'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('admin123'),
            ]
        );
        $superAdmin->assignRole('superadmin');

        // ── 2. BISNIS: Kopi Nusantara ───────────────────────────────────
        $biz1 = Business::firstOrCreate(
            ['name' => 'Kopi Nusantara'],
            [
                'type'      => 'F&B',
                'status'    => 'active',
                'feat_stok' => false,
            ]
        );

        $adminBudi = User::firstOrCreate(
            ['email' => 'budi@kopinusantara.com'],
            [
                'name'        => 'Budi Santoso',
                'password'    => Hash::make('budi123'),
                'business_id' => $biz1->id,
            ]
        );
        $adminBudi->assignRole('admin');

        $kasirDewi = User::firstOrCreate(
            ['email' => 'dewi@kopinusantara.com'],
            [
                'name'        => 'Dewi Kasir',
                'password'    => Hash::make('dewi123'),
                'business_id' => $biz1->id,
            ]
        );
        $kasirDewi->assignRole('kasir');

        // Produk Kopi Nusantara
        $produkKopi = [
            ['name' => 'Es Kopi Susu',  'price' => 22000, 'category' => 'Minuman', 'color' => '#1A56DB'],
            ['name' => 'Americano',      'price' => 18000, 'category' => 'Minuman', 'color' => '#1A56DB'],
            ['name' => 'Croissant',      'price' => 25000, 'category' => 'Makanan', 'color' => '#F59E0B'],
            ['name' => 'Matcha Latte',   'price' => 28000, 'category' => 'Minuman', 'color' => '#10B981'],
            ['name' => 'Roti Bakar',     'price' => 15000, 'category' => 'Makanan', 'color' => '#F59E0B'],
            ['name' => 'Teh Manis',      'price' => 8000,  'category' => 'Minuman', 'color' => '#3B82F6'],
        ];

        foreach ($produkKopi as $p) {
            Product::firstOrCreate(
                ['business_id' => $biz1->id, 'name' => $p['name']],
                [
                    'price'      => $p['price'],
                    'stock'      => 50,
                    'stock_mode' => 'unlimited',
                    'category'   => $p['category'],
                    'color'      => $p['color'],
                ]
            );
        }

        // Promo Kopi Nusantara
        Promo::firstOrCreate(
            ['business_id' => $biz1->id, 'code' => 'HAPPY15'],
            [
                'name'        => 'Happy Hour',
                'description' => 'Diskon 15% jam 14.00–16.00',
                'type'        => 'percent',
                'value'       => 15,
                'min_buy'     => 0,
                'status'      => 'active',
            ]
        );
        Promo::firstOrCreate(
            ['business_id' => $biz1->id, 'code' => 'BUY2DISC'],
            [
                'name'        => 'Buy 2 Get Disc',
                'description' => 'Beli 2 item minuman, potongan Rp 5.000',
                'type'        => 'flat',
                'value'       => 5000,
                'min_buy'     => 36000,
                'status'      => 'active',
            ]
        );

        // Customer & Transaksi Demo
        $cust1 = Customer::firstOrCreate(
            ['business_id' => $biz1->id, 'name' => 'Ahmad Fauzi'],
            ['phone' => '081211111111', 'total_visits' => 12, 'total_spend' => 580000, 'last_visit' => '2025-04-18']
        );
        $cust2 = Customer::firstOrCreate(
            ['business_id' => $biz1->id, 'name' => 'Siti Rahayu'],
            ['phone' => '085698765432', 'total_visits' => 8, 'total_spend' => 320000, 'last_visit' => '2025-04-18']
        );

        $esCoffee = Product::where('business_id', $biz1->id)->where('name', 'Es Kopi Susu')->first();
        $croissant = Product::where('business_id', $biz1->id)->where('name', 'Croissant')->first();
        $matcha    = Product::where('business_id', $biz1->id)->where('name', 'Matcha Latte')->first();

        if ($esCoffee && !Transaction::where('business_id', $biz1->id)->where('customer_id', $cust1->id)->exists()) {
            Transaction::create([
                'business_id'   => $biz1->id,
                'user_id'       => $kasirDewi->id,
                'customer_id'   => $cust1->id,
                'items'         => [
                    ['name' => $esCoffee->name,  'qty' => 2, 'price' => $esCoffee->price],
                    ['name' => $croissant->name,  'qty' => 1, 'price' => $croissant->price],
                ],
                'subtotal'      => 69000,
                'discount'      => 0,
                'total'         => 69000,
                'pay_method'    => 'cash',
                'cash_received' => 70000,
                'cash_change'   => 1000,
                'status'        => 'lunas',
                'created_at'    => now()->subDays(1),
            ]);

            Transaction::create([
                'business_id'   => $biz1->id,
                'user_id'       => $kasirDewi->id,
                'customer_id'   => $cust2->id,
                'items'         => [
                    ['name' => $matcha->name, 'qty' => 1, 'price' => $matcha->price],
                ],
                'subtotal'      => 28000,
                'discount'      => 0,
                'total'         => 28000,
                'pay_method'    => 'transfer',
                'cash_received' => 28000,
                'cash_change'   => 0,
                'status'        => 'lunas',
                'created_at'    => now()->subDays(1),
            ]);

            // Update statistik bisnis
            $biz1->update([
                'total_transactions' => 2,
                'total_revenue'      => 97000,
            ]);
        }

        // ── 3. BISNIS: Laundry Bersih ───────────────────────────────────
        $biz2 = Business::firstOrCreate(
            ['name' => 'Laundry Bersih'],
            [
                'type'      => 'Laundry',
                'status'    => 'active',
                'feat_stok' => false,
            ]
        );

        $adminAndi = User::firstOrCreate(
            ['email' => 'andi@laundrybersih.com'],
            [
                'name'        => 'Andi Laundry',
                'password'    => Hash::make('andi123'),
                'business_id' => $biz2->id,
            ]
        );
        $adminAndi->assignRole('admin');

        $produkLaundry = [
            ['name' => 'Cuci Reguler 3kg', 'price' => 15000, 'category' => 'Layanan', 'color' => '#8B5CF6'],
            ['name' => 'Setrika Saja',      'price' => 8000,  'category' => 'Layanan', 'color' => '#8B5CF6'],
            ['name' => 'Express 1hr',       'price' => 30000, 'category' => 'Layanan', 'color' => '#EF4444'],
        ];

        foreach ($produkLaundry as $p) {
            Product::firstOrCreate(
                ['business_id' => $biz2->id, 'name' => $p['name']],
                [
                    'price'      => $p['price'],
                    'stock'      => 999,
                    'stock_mode' => 'unlimited',
                    'category'   => $p['category'],
                    'color'      => $p['color'],
                ]
            );
        }

        // ── 4. BISNIS: Toko Serba Ada (inactive, feat_stok aktif) ───────
        $biz3 = Business::firstOrCreate(
            ['name' => 'Toko Serba Ada'],
            [
                'type'      => 'Retail',
                'status'    => 'inactive',
                'feat_stok' => true,
            ]
        );

        $produkToko = [
            ['name' => 'Mie Goreng',  'price' => 12000, 'stock' => 25,  'stock_mode' => 'tracked', 'category' => 'Makanan', 'color' => '#F59E0B'],
            ['name' => 'Air Mineral', 'price' => 5000,  'stock' => 100, 'stock_mode' => 'tracked', 'category' => 'Minuman', 'color' => '#3B82F6'],
            ['name' => 'Sabun Mandi', 'price' => 8000,  'stock' => 5,   'stock_mode' => 'tracked', 'category' => 'Produk',  'color' => '#8B5CF6'],
        ];

        foreach ($produkToko as $p) {
            Product::firstOrCreate(
                ['business_id' => $biz3->id, 'name' => $p['name']],
                [
                    'price'      => $p['price'],
                    'stock'      => $p['stock'],
                    'stock_mode' => $p['stock_mode'],
                    'category'   => $p['category'],
                    'color'      => $p['color'],
                ]
            );
        }

        $this->command->info('✅ AdminSeeder selesai. Akun tersedia:');
        $this->command->info('   superadmin  → admin@jagabisnis.id    / admin123');
        $this->command->info('   admin       → budi@kopinusantara.com / budi123');
        $this->command->info('   kasir       → dewi@kopinusantara.com / dewi123');
        $this->command->info('   admin       → andi@laundrybersih.com / andi123');
    }
}