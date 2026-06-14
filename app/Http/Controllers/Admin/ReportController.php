<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
 
class ReportController extends Controller
{
protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }
    public function sales(Request $request)
    {
        $bizId  = auth()->user()->business_id;
        $period = $request->get('period', 'harian');
 
        $data = match($period) {
            'bulanan' => $this->reportService->bulanan($bizId),
            'tahunan' => $this->reportService->tahunan($bizId),
            default   => $this->reportService->harian($bizId),
        };
 
        $totalRev = $data->sum('revenue');
        $totalTx  = $data->sum('count');
 
        return view('admin.reports.sales', compact('data', 'period', 'totalRev', 'totalTx'));
    }
 
    public function products(Request $request)
    {
        $bizId = auth()->user()->business_id;
        $sort  = $request->get('sort', 'laris');
        $data  = $this->reportService->produkTerlaris($bizId, $sort);
 
        return view('admin.reports.products', compact('data', 'sort'));
    }
 
    public function customers(Request $request)
    {
        $bizId = auth()->user()->business_id;
        $sort  = $request->get('sort', 'spending');
        $data  = $this->reportService->customerReport($bizId, $sort);

        $activePromos = \App\Models\Promo::where('business_id', $bizId)
        ->where('status', 'active')
        ->get();

        return view('admin.reports.customers', compact('data', 'sort', 'activePromos'));
    }
}