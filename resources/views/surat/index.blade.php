@extends('layouts.app')

@section('title', 'Daftar Surat')

@section('page-title', 'Daftar Surat')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-envelope me-2"></i>
                Daftar Surat
            </h2>
            <div class="d-flex gap-2">
                @if(in_array(auth()->user()->role, ['admin', 'operator', 'pegawai']))
                    <a href="{{ route('surat.create') }}" class="btn btn-gradient">
                        <i class="bi bi-plus-circle me-2"></i> Buat Surat Baru
                    </a>
                @endif
            </div>
        </div>

        <!-- Search & Filter Panel -->
        <div class="card-glass mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="bi bi-funnel me-2"></i>Pencarian & Filter
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterPanel">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </div>

                <div class="collapse show" id="filterPanel">
                    <form method="GET" action="{{ route('surat.index') }}" class="row g-3" id="filterForm">
                        <!-- Search -->
                        <div class="col-md-3">
                            <label class="form-label"><i class="bi bi-search me-1"></i>Cari</label>
                            <input type="text" name="search" class="form-control" placeholder="Nomor/Tujuan/Perihal..."
                                value="{{ request('search') }}">
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <label class="form-label"><i class="bi bi-flag me-1"></i>Status</label>
                            <select name="status" class="form-select searchable-select" data-placeholder="Semua Status">
                                <option value="">Semua</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                                <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <!-- Tipe Surat Filter -->
                        <div class="col-md-2">
                            <label class="form-label"><i class="bi bi-tag me-1"></i>Tipe Surat</label>
                            <select name="tipe_surat_id" class="form-select searchable-select"
                                data-placeholder="Semua Tipe">
                                <option value="">Semua</option>
                                @foreach($tipeSurats as $tipe)
                                    <option value="{{ $tipe->id }}" {{ request('tipe_surat_id') == $tipe->id ? 'selected' : '' }}>
                                        {{ $tipe->jenis_surat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date From -->
                        <div class="col-md-2">
                            <label class="form-label"><i class="bi bi-calendar me-1"></i>Dari Tanggal</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>

                        <!-- Date To -->
                        <div class="col-md-2">
                            <label class="form-label"><i class="bi bi-calendar-check me-1"></i>Sampai Tanggal</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="d-flex gap-2 align-items-center">
                                    <button type="submit" class="btn btn-gradient">
                                        <i class="bi bi-search me-2"></i>Cari
                                    </button>
                                    <a href="{{ route('surat.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle me-2"></i>Reset
                                    </a>
                                    @if($surats->total() > 0)
                                        <span class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Ditemukan {{ $surats->total() }} surat
                                        </span>
                                    @endif
                                </div>

                                <!-- Export Buttons -->
                                @if(in_array(auth()->user()->role, ['admin', 'pemimpin', 'operator']))
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('laporan.export.excel', request()->all()) }}" class="btn btn-success">
                                            <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                                        </a>
                                        <a href="{{ route('laporan.export.pdf', request()->all()) }}" class="btn btn-danger"
                                            target="_blank">
                                            <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="card-glass">
            <div class="card-body p-4">
                @if($surats->count() > 0)
                    <div class="table-responsive">
                        <table class="table card-table table-hover">
                            <thead>
                                <tr>
                                    <th style="min-width: 130px;">Nomor Surat</th>
                                    <th style="min-width: 90px;">Tanggal</th>
                                    <th style="min-width: 100px;">Jenis Surat</th>
                                    <th style="min-width: 150px;">Judul Surat</th>
                                    <th style="min-width: 180px;">Isi Surat</th>
                                    <th style="min-width: 100px;">Bidang</th>
                                    <th style="min-width: 100px;">Pembuat</th>
                                    <th style="min-width: 80px;">Metode</th>
                                    <th style="min-width: 80px;">Status</th>
                                    <th style="min-width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surats as $surat)
                                    <tr>
                                        <td>
                                            @if($surat->nomor_surat_full)
                                                <strong class="text-primary"
                                                    style="font-size: 0.85rem;">{{ $surat->nomor_surat_full }}</strong>
                                            @else
                                                <span class="badge bg-secondary">Draft</span>
                                            @endif
                                        </td>
                                        <td>{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-info text-dark">{{ $surat->tipeSurat->jenis_surat }}</span>
                                        </td>
                                        <td>
                                            <span title="{{ $surat->perihal }}">{{ Str::limit($surat->perihal, 35) }}</span>
                                        </td>
                                        <td>
                                            <div style="max-height: 60px; overflow-y: auto; font-size: 0.8rem;" class="text-muted">
                                                {{ Str::limit(strip_tags($surat->isi_surat ?? '-'), 100) }}
                                            </div>
                                        </td>
                                        <td>
                                            <small>{{ $surat->user->bidang ?? '-' }}</small>
                                        </td>
                                        <td>{{ $surat->user->name }}</td>
                                        <td>
                                            <small class="text-muted">{{ $surat->metode_pembuatan ?? 'Manual' }}</small>
                                        </td>
                                        <td>
                                            @if($surat->status == '0')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($surat->status == '1')
                                                <span class="badge bg-success">Disetujui</span>
                                            @else
                                                <span class="badge bg-danger" data-bs-toggle="tooltip"
                                                    title="{{ $surat->rejection_reason ?? 'Ditolak' }}">
                                                    Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('surat.show', $surat) }}" class="btn btn-info" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                {{-- Admin/Operator can edit --}}
                                                @if(in_array(auth()->user()->role, ['admin', 'operator']))
                                                    <a href="{{ route('surat.edit', $surat) }}" class="btn btn-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endif

                                                {{-- Pegawai can edit their own rejected letters --}}
                                                @if(auth()->user()->role === 'pegawai' && $surat->status == '2' && $surat->user_id == auth()->id())
                                                    <a href="{{ route('surat.edit', $surat) }}" class="btn btn-warning"
                                                        title="Perbaiki">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endif

                                                {{-- Admin/Pemimpin/Operator can approve/reject pending letters --}}
                                                @if(in_array(auth()->user()->role, ['admin', 'pemimpin', 'operator']) && $surat->status == '0')
                                                    <a href="{{ route('surat.approve.show', $surat) }}" class="btn btn-success"
                                                        title="Setujui">
                                                        <i class="bi bi-check-lg"></i>
                                                    </a>
                                                    <a href="{{ route('surat.reject.show', $surat) }}" class="btn btn-danger"
                                                        title="Tolak">
                                                        <i class="bi bi-x-lg"></i>
                                                    </a>
                                                @endif

                                                @if($surat->status == '1')
                                                    <a href="{{ route('surat.cetak', $surat) }}" class="btn btn-secondary"
                                                        target="_blank" title="Print">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                @endif
                                            </div>

                                            {{-- Rejection reason display for pegawai --}}
                                            @if($surat->status == '2' && $surat->rejection_reason && auth()->user()->role === 'pegawai')
                                                <div class="mt-2">
                                                    <small class="text-danger">
                                                        <i class="bi bi-exclamation-circle me-1"></i>
                                                        {{ Str::limit($surat->rejection_reason, 50) }}
                                                    </small>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $surats->links() }}
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

    <style>
        .table td {
            vertical-align: middle;
        }

        .table th {
            white-space: nowrap;
        }
    </style>
@endsection