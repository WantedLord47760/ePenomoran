<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\TipeSurat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;

class ReportController extends BaseController
{
    public function __construct()
    {
        $this->middleware('role:admin,pemimpin');
    }

    /**
     * Display letter statistics and reports
     * 
     * WHY THIS IS ESSENTIAL FOR INSTITUTIONAL SYSTEMS:
     * 1. Management Oversight - Leaders need data to make decisions
     * 2. Performance Metrics - Track efficiency and productivity
     * 3. Compliance Documentation - Audit trails for governance
     * 4. Budget Justification - Evidence for resource allocation
     * 5. Workload Distribution - Identify bottlenecks and staffing needs
     * 6. Trend Analysis - Spot patterns (seasonal spikes, declining usage)
     * 7. Quality Control - Monitor approval/rejection rates
     */
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', null);
        $tipeSuratId = $request->get('tipe_surat_id', null);

        // Summary by status
        $statusSummary = Surat::select('status', DB::raw('count(*) as total'))
            ->when($year, fn($q) => $q->whereYear('tanggal_surat', $year))
            ->when($month, fn($q) => $q->whereMonth('tanggal_surat', $month))
            ->when($tipeSuratId, fn($q) => $q->where('tipe_surat_id', $tipeSuratId))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $pending = $statusSummary->get('0')->total ?? 0;
        $approved = $statusSummary->get('1')->total ?? 0;
        $rejected = $statusSummary->get('2')->total ?? 0;
        $total = $pending + $approved + $rejected;

        // Monthly breakdown for the year
        $monthlyData = Surat::select(
            DB::raw('MONTH(tanggal_surat) as month'),
            DB::raw('count(*) as total'),
            DB::raw('SUM(CASE WHEN status = "1" THEN 1 ELSE 0 END) as approved'),
            DB::raw('SUM(CASE WHEN status = "0" THEN 1 ELSE 0 END) as pending'),
            DB::raw('SUM(CASE WHEN status = "2" THEN 1 ELSE 0 END) as rejected')
        )
            ->whereYear('tanggal_surat', $year)
            ->when($tipeSuratId, fn($q) => $q->where('tipe_surat_id', $tipeSuratId))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // By tipe surat
        $byTipe = Surat::with('tipeSurat')
            ->select('tipe_surat_id', DB::raw('count(*) as total'))
            ->when($year, fn($q) => $q->whereYear('tanggal_surat', $year))
            ->when($month, fn($q) => $q->whereMonth('tanggal_surat', $month))
            ->groupBy('tipe_surat_id')
            ->get();

        // Top creators (most productive users)
        $topCreators = Surat::with('user')
            ->select('user_id', DB::raw('count(*) as total'))
            ->when($year, fn($q) => $q->whereYear('tanggal_surat', $year))
            ->when($month, fn($q) => $q->whereMonth('tanggal_surat', $month))
            ->when($tipeSuratId, fn($q) => $q->where('tipe_surat_id', $tipeSuratId))
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $tipeSurats = TipeSurat::all();
        $years = range(date('Y'), date('Y') - 5); // Last 5 years

        return view('laporan.index', compact(
            'pending',
            'approved',
            'rejected',
            'total',
            'monthlyData',
            'byTipe',
            'topCreators',
            'tipeSurats',
            'years',
            'year',
            'month',
            'tipeSuratId'
        ));
    }
}
