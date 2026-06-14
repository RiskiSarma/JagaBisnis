<?php

namespace App\Services;
 
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Collection;
 
class ReportService
{
    public function harian(int $bizId): Collection
    {
        return Transaction::forBusiness($bizId)
            ->selectRaw('DATE(created_at) as label, COUNT(*) as count, SUM(total) as revenue,
                          SUM(CASE WHEN status="lunas" THEN 1 ELSE 0 END) as lunas,
                          SUM(CASE WHEN status="belum_lunas" THEN 1 ELSE 0 END) as belum')
            ->groupByRaw('DATE(created_at)')
            ->orderByDesc('label')
            ->get();
    }
 
    public function bulanan(int $bizId): Collection
    {
        return Transaction::forBusiness($bizId)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as label, COUNT(*) as count,
                          SUM(total) as revenue,
                          SUM(CASE WHEN status='lunas' THEN 1 ELSE 0 END) as lunas,
                          SUM(CASE WHEN status='belum_lunas' THEN 1 ELSE 0 END) as belum")
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m')")
            ->orderByDesc('label')
            ->get();
    }
 
    public function tahunan(int $bizId): Collection
    {
        return Transaction::forBusiness($bizId)
            ->selectRaw("YEAR(created_at) as label, COUNT(*) as count, SUM(total) as revenue,
                          SUM(CASE WHEN status='lunas' THEN 1 ELSE 0 END) as lunas,
                          SUM(CASE WHEN status='belum_lunas' THEN 1 ELSE 0 END) as belum")
            ->groupByRaw("YEAR(created_at)")
            ->orderByDesc('label')
            ->get();
    }
 
    public function produkTerlaris(int $bizId, string $sort = 'laris'): Collection
    {
        $transactions = Transaction::forBusiness($bizId)->get();
        $map = [];

        foreach ($transactions as $tx) {
            foreach ($tx->items as $item) {
                $name = $item['name'];
                if (!isset($map[$name])) {
                    $map[$name] = ['name' => $name, 'count' => 0, 'revenue' => 0];
                }
                $map[$name]['count']   += $item['qty'];
                $map[$name]['revenue'] += $item['price'] * $item['qty'];
            }
        }

        usort($map, match($sort) {
            'kurang'  => fn($a, $b) => $a['count'] - $b['count'],
            'revenue' => fn($a, $b) => $b['revenue'] - $a['revenue'],
            default   => fn($a, $b) => $b['count'] - $a['count'],
        });

        return collect($map);
    }

    public function customerReport(int $bizId, string $sort = 'spending'): Collection
    {
        $query = Customer::where('business_id', $bizId)
            ->withCount('transactions as visits')
            ->withMax('transactions as last_visit', 'created_at');

        return match($sort) {
            'visits' => $query->orderByDesc('visits')->get(),
            'recent' => $query->orderByDesc('last_visit')->get(),
            default  => $query->orderByDesc('total_spend')->get(),
        };
    }
}