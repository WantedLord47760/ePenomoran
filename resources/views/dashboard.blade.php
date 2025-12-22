@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-purple">
                    <div class="stat-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <h5>Total Surat</h5>
                    <h2>{{ $totalSurats }}</h2>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-amber">
                    <div class="stat-icon">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <h5>Pending</h5>
                    <h2>{{ $pendingSurats }}</h2>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-green">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h5>Approved</h5>
                    <h2>{{ $approvedSurats }}</h2>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-red">
                    <div class="stat-icon">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <h5>Rejected</h5>
                    <h2>{{ $rejectedSurats }}</h2>
                </div>
            </div>
        </div>

        <!-- Recent Letters Table -->
        <div class="card-glass">
            <div class="card-header bg-transparent border-0 p-4">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-file-text me-2"></i>
                    Surat Terbaru
                </h5>
            </div>
            <div class="card-body p-4 pt-0">
                @if($recentSurats->count() > 0)
                    <div class="table-responsive">
                        <table class="table card-table">
                            <thead>
                                <tr>
                                    <th>Nomor Surat</th>
                                    <th>Tipe Surat</th>
                                    <th>Tanggal</th>
                                    <th>Tujuan</th>
                                    <th>Pembuat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSurats as $surat)
                                    <tr>
                                        <td><strong>{{ $surat->nomor_surat_full }}</strong></td>
                                        <td>{{ $surat->tipeSurat->jenis_surat }}</td>
                                        <td>{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                                        <td>{{ Str::limit($surat->tujuan, 30) }}</td>
                                        <td>{{ $surat->user->name }}</td>
                                        <td>
                                            @if($surat->status == '0')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($surat->status == '1')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada surat.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection