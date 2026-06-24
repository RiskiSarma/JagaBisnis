<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService)
    {
    }

    /**
     * Laporan penjualan. Query param: ?period=harian|bulanan|tahunan
     */
    public function sales(Request $request)
    {
        $bizId  = $request->user()->business_id;
        $period = $request->get('period', 'harian');

        $data = match ($period) {
            'bulanan' => $this->reportService->bulanan($bizId),
            'tahunan' => $this->reportService->tahunan($bizId),
            default   => $this->reportService->harian($bizId),
        };

        return response()->json([
            'success' => true,
            'period'  => $period,
            'data'    => $data->map(fn ($row) => [
                'label'   => (string) $row->label,
                'count'   => (int) $row->count,
                'revenue' => (int) $row->revenue,
                'lunas'   => (int) $row->lunas,
                'belum'   => (int) $row->belum,
            ]),
            'summary' => [
                'total_revenue'      => (int) $data->sum('revenue'),
                'total_transactions' => (int) $data->sum('count'),
            ],
        ]);
    }

    /**
     * Laporan produk terlaris/kurang laris. Query param: ?sort=laris|kurang|revenue
     */
    public function products(Request $request)
    {
        $bizId = $request->user()->business_id;
        $sort  = $request->get('sort', 'laris');

        $data = $this->reportService->produkTerlaris($bizId, $sort);

        return response()->json([
            'success' => true,
            'sort'    => $sort,
            'data'    => $data->values()->map(fn ($row) => [
                'name'    => $row['name'],
                'count'   => $row['count'],
                'revenue' => $row['revenue'],
            ]),
        ]);
    }

    /**
     * Laporan customer. Query param: ?sort=spending|visits|recent
     */
    public function customers(Request $request)
    {
        $bizId = $request->user()->business_id;
        $sort  = $request->get('sort', 'spending');

        $data = $this->reportService->customerReport($bizId, $sort);

        return response()->json([
            'success' => true,
            'sort'    => $sort,
            'data'    => $data->map(fn ($c) => [
                'id'          => $c->id,
                'name'        => $c->name,
                'phone'       => $c->phone,
                'total_spend' => $c->total_spend,
                'tier'        => $c->tier,
                'visits'      => $c->visits,
                'last_visit'  => $c->last_visit,
            ]),
        ]);
    }
}
