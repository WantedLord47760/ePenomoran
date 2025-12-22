<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\TipeSurat;
use Illuminate\Http\Request;

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

        return view('dashboard', compact(
            'totalSurats',
            'pendingSurats',
            'approvedSurats',
            'rejectedSurats',
            'totalTipeSurats',
            'recentSurats'
        ));
    }
}
