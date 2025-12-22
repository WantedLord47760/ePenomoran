@extends('layouts.app')

@section('title', 'Laporan Surat')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h2 class="mb-0">
                <i class="bi bi-bar-chart me-2"></i>
                Laporan & Statistik Surat
            </h2>
        </div>

        <!-- Filter Panel -->
        <div class="card-glass mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('laporan.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-calendar me-1"></i>Tahун</label>
                        <select name="year" class="form-select">
                            @foreach($years as $y)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-calendar3 me-1"></i>Bulan (Opsional)</label>
                        <select name="month" class="form-select">
                            <option value="">Semua Bulan</option>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="bi bi-tag me-1"></i>Tipe Surat (Opsional)</label>
                        <select name="tipe_surat_id" class="form-select">
                            <option value="">Semua Tipe</option>
                            @foreach($tipeSurats as $tipe)
                                <option value="{{ $tipe->id }}" {{ $tipeSuratId == $tipe->id ? 'selected' : '' }}>
                                    {{ $tipe->jenis_surat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-gradient w-100">
                            <i class="bi bi-search me-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card stat-purple">
                    <div class="stat-icon"><i class="bi bi-envelope-fill"></i></div>
                    <h5>Total Surat</h5>
                    <h2>{{ $total }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card stat-green">
                    <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <h5>Disetujui</h5>
                    <h2>{{ $approved }}</h2>
                    @if($total > 0)
                        <small class="text-muted">{{ number_format(($approved / $total) * 100, 1) }}%</small>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card stat-yellow">
                    <div class="stat-icon"><i class="bi bi-clock-fill"></i></div>
                    <h5>Pending</h5>
                    <h2>{{ $pending }}</h2>
                    @if($total > 0)
                        <small class="text-muted">{{ number_format(($pending / $total) * 100, 1) }}%</small>
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card stat-red">
                    <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
                    <h5>Ditolak</h5>
                    <h2>{{ $rejected }}</h2>
                    @if($total > 0)
                        <small class="text-muted">{{ number_format(($rejected / $total) * 100, 1) }}%</small>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Monthly Breakdown -->
            <div class="col-lg-7">
                <div class="card-glass">
                    <div class="card-body p-4">
                        <h5 class="mb-4"><i class="bi bi-graph-up me-2"></i>Data Bulanan {{ $year }}</h5>
                        <div class="table-responsive">
                            <table class="table card-table">
                                <thead>
                                    <tr>
                                        <th>Bulan</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Disetujui</th>
                                        <th class="text-center">Pending</th>
                                        <th class="text-center">Ditolak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($m = 1; $m <= 12; $m++)
                                        @php
                                            $data = $monthlyData->get($m);
                                            $monthTotal = $data->total ?? 0;
                                            $monthApproved = $data->approved ?? 0;
                                            $monthPending = $data->pending ?? 0;
                                            $monthRejected = $data->rejected ?? 0;
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}</td>
                                            <td class="text-center"><strong>{{ $monthTotal }}</strong></td>
                                            <td class="text-center">
                                                <span class="badge bg-success">{{ $monthApproved }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning">{{ $monthPending }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger">{{ $monthRejected }}</span>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-5">
                <!-- By Tipe Surat -->
                <div class="card-glass mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-4"><i class="bi bi-pie-chart me-2"></i>Berdasarkan Tipe Surat</h5>
                        <div class="table-responsive">
                            <table class="table card-table">
                                <thead>
                                    <tr>
                                        <th>Tipe Surat</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($byTipe as $item)
                                        <tr>
                                            <td>{{ $item->tipeSurat->jenis_surat }}</td>
                                            <td class="text-center"><strong>{{ $item->total }}</strong></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top Creators -->
                <div class="card-glass">
                    <div class="card-body p-4">
                        <h5 class="mb-4"><i class="bi bi-trophy me-2"></i>Top 10 Pembuat Surat</h5>
                        <div class="table-responsive">
                            <table class="table card-table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topCreators as $creator)
                                        <tr>
                                            <td>
                                                <i class="bi bi-person-circle me-2"></i>{{ $creator->user->name }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $creator->total }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection