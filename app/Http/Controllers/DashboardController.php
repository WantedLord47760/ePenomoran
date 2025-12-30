<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\TipeSurat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSurats = Surat::count();
        $pendingSurats = Surat::where('status', '0')->count();
        $approvedSurats = Surat::where('status', '1')->count();
        $rejectedSurats = Surat::where('status', '2')->count();
        $totalTipeSurats = TipeSurat::count();

        $recentSurats = Surat::with(['user', 'tipeSurat'])
            ->latest()
            ->take(10)
            ->get();

        // Chart data: Monthly stats for current year
        $currentYear = now()->year;

        // Get all tipe surats for chart categorization
        $tipeSurats = TipeSurat::all();

        // Monthly data for all letter types (Surat Keluar)
        $monthlyAll = Surat::selectRaw('MONTH(tanggal_surat) as month, COUNT(*) as total')
            ->whereYear('tanggal_surat', $currentYear)
            ->where('status', '1') // Only approved
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Monthly data by specific types (SPT, Cuti, Surat Keluar)
        $monthlySPT = $this->getMonthlyByType($currentYear, 'SPT');
        $monthlyCuti = $this->getMonthlyByType($currentYear, 'CUTI');
        $monthlyKeluar = $this->getMonthlyByType($currentYear, 'SURAT KELUAR');

        // Prepare chart data for JavaScript
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartDataAll = array_map(fn($m) => $monthlyAll[$m] ?? 0, range(1, 12));
        $chartDataSPT = array_map(fn($m) => $monthlySPT[$m] ?? 0, range(1, 12));
        $chartDataCuti = array_map(fn($m) => $monthlyCuti[$m] ?? 0, range(1, 12));
        $chartDataKeluar = array_map(fn($m) => $monthlyKeluar[$m] ?? 0, range(1, 12));

        return view('dashboard', compact(
            'totalSurats',
            'pendingSurats',
            'approvedSurats',
            'rejectedSurats',
            'totalTipeSurats',
            'recentSurats',
            'chartLabels',
            'chartDataAll',
            'chartDataSPT',
            'chartDataCuti',
            'chartDataKeluar',
            'currentYear'
        ));
    }

    /**
     * Get monthly data by tipe surat name (partial match)
     */
    private function getMonthlyByType(int $year, string $typeKeyword): array
    {
        return Surat::selectRaw('MONTH(tanggal_surat) as month, COUNT(*) as total')
            ->whereYear('tanggal_surat', $year)
            ->where('status', '1')
            ->whereHas('tipeSurat', function ($q) use ($typeKeyword) {
                $q->where('jenis_surat', 'like', "%{$typeKeyword}%");
            })
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }

    /**
     * AJAX Search endpoint with fuzzy/abbreviation matching
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Check for bidang abbreviations
        $bidangAbbreviations = User::getBidangAbbreviations();
        $expandedQuery = $query;
        foreach ($bidangAbbreviations as $abbr => $fullName) {
            if (stripos($abbr, $query) !== false || stripos($query, $abbr) !== false) {
                $expandedQuery = $fullName;
                break;
            }
        }

        // Search surats
        $surats = Surat::with(['user', 'tipeSurat'])
            ->where(function ($q) use ($query, $expandedQuery) {
                $q->where('nomor_surat_full', 'like', "%{$query}%")
                    ->orWhere('perihal', 'like', "%{$query}%")
                    ->orWhereHas('user', function ($uq) use ($query, $expandedQuery) {
                        $uq->where('name', 'like', "%{$query}%")
                            ->orWhere('bidang', 'like', "%{$expandedQuery}%");
                    });
            })
            ->where('status', '1') // Only approved letters
            ->latest()
            ->take(10)
            ->get();

        $results = $surats->map(function ($surat) {
            return [
                'id' => $surat->id,
                'nomor' => $surat->getDisplayNumber(),
                'perihal' => \Str::limit($surat->perihal, 50),
                'pembuat' => $surat->user->name,
                'bidang' => $surat->user->bidang ?? '-',
                'url' => route('surat.show', $surat),
            ];
        });

        // Add bidang suggestions if query matches abbreviation
        $bidangSuggestions = [];
        foreach ($bidangAbbreviations as $abbr => $fullName) {
            if (stripos($abbr, $query) !== false) {
                $bidangSuggestions[] = [
                    'type' => 'bidang',
                    'text' => $fullName,
                    'abbr' => $abbr,
                ];
            }
        }

        return response()->json([
            'surats' => $results,
            'bidang_suggestions' => $bidangSuggestions,
        ]);
    }
}

