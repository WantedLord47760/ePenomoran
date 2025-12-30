<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\TipeSurat;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Build filtered query based on request parameters
     */
    private function buildFilteredQuery(Request $request)
    {
        $query = Surat::with(['user', 'tipeSurat']);

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_surat', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_surat', '<=', $request->date_to);
        }

        // Filter by bidang
        if ($request->filled('bidang')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('bidang', $request->bidang);
            });
        }

        // Filter by tipe surat
        if ($request->filled('tipe_surat_id')) {
            $query->where('tipe_surat_id', $request->tipe_surat_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    /**
     * Get filter description for exports
     */
    private function getFilterDescription(Request $request)
    {
        $filters = [];

        if ($request->filled('date_from')) {
            $filters[] = 'Dari: ' . Carbon::parse($request->date_from)->format('d/m/Y');
        }
        if ($request->filled('date_to')) {
            $filters[] = 'Sampai: ' . Carbon::parse($request->date_to)->format('d/m/Y');
        }
        if ($request->filled('bidang')) {
            $filters[] = 'Bidang: ' . $request->bidang;
        }
        if ($request->filled('tipe_surat_id')) {
            $tipe = TipeSurat::find($request->tipe_surat_id);
            $filters[] = 'Tipe: ' . ($tipe ? $tipe->jenis_surat : '-');
        }
        if ($request->filled('status')) {
            $statusLabels = ['0' => 'Pending', '1' => 'Disetujui', '2' => 'Ditolak'];
            $filters[] = 'Status: ' . ($statusLabels[$request->status] ?? '-');
        }

        return empty($filters) ? 'Semua Data' : implode(' | ', $filters);
    }

    /**
     * Display laporan index with DataTable
     */
    public function index(Request $request)
    {
        // Only admin, pemimpin, operator can access
        if (!in_array(auth()->user()->role, ['admin', 'pemimpin', 'operator'])) {
            abort(403);
        }

        $surats = $this->buildFilteredQuery($request)
            ->orderBy('tanggal_surat', 'desc')
            ->paginate(20)
            ->withQueryString();

        $tipeSurats = TipeSurat::all();
        $bidangOptions = User::getBidangOptions();

        return view('laporan.index', compact('surats', 'tipeSurats', 'bidangOptions'));
    }

    /**
     * Export to Excel (CSV format for simplicity)
     */
    public function exportExcel(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'pemimpin', 'operator'])) {
            abort(403);
        }

        $surats = $this->buildFilteredQuery($request)
            ->orderBy('tanggal_surat', 'desc')
            ->get();

        $filterDesc = $this->getFilterDescription($request);

        // Generate CSV
        $filename = 'laporan_surat_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($surats, $filterDesc) {
            $file = fopen('php://output', 'w');
            // BOM for UTF-8 Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Title and filter info
            fputcsv($file, ['LAPORAN DATA SURAT']);
            fputcsv($file, ['Filter: ' . $filterDesc]);
            fputcsv($file, ['Tanggal Export: ' . date('d/m/Y H:i:s')]);
            fputcsv($file, ['Total Data: ' . $surats->count() . ' surat']);
            fputcsv($file, []); // Empty row

            // Header row
            fputcsv($file, [
                'No',
                'Nomor Surat',
                'Tipe Surat',
                'Tanggal',
                'Tujuan',
                'Perihal',
                'Isi Surat',
                'Nama Pembuat',
                'Bidang',
                'Metode',
                'Status'
            ]);

            // Data rows
            $no = 1;
            foreach ($surats as $surat) {
                fputcsv($file, [
                    $no++,
                    $surat->nomor_surat_full ?? 'Draft/Menunggu',
                    $surat->tipeSurat->jenis_surat ?? '-',
                    $surat->tanggal_surat->format('d/m/Y'),
                    $surat->tujuan,
                    $surat->perihal,
                    strip_tags($surat->isi_surat ?? '-'),
                    $surat->user->name ?? '-',
                    $surat->user->bidang ?? '-',
                    $surat->metode_pembuatan ?? 'Manual',
                    $surat->getStatusLabel()
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'pemimpin', 'operator'])) {
            abort(403);
        }

        $surats = $this->buildFilteredQuery($request)
            ->orderBy('tanggal_surat', 'desc')
            ->get();

        $filterDesc = $this->getFilterDescription($request);

        // Generate simple HTML for PDF
        return view('laporan.pdf', compact('surats', 'filterDesc'));
    }
}

