@extends('layouts.app')

@section('title', 'Laporan Surat')

@section('page-title', 'Laporan Surat')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>Laporan Data Surat
                </h4>
                <p class="text-muted mb-0">Filter dan ekspor data surat</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('laporan.export.excel', request()->query()) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                </a>
                <a href="{{ route('laporan.export.pdf', request()->query()) }}" class="btn btn-danger" target="_blank">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-glass mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('laporan.index') }}" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label"><i class="bi bi-calendar me-1"></i>Dari Tanggal</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label"><i class="bi bi-calendar me-1"></i>Sampai Tanggal</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-building me-1"></i>Bidang</label>
                        <select name="bidang" class="form-select searchable-select" data-placeholder="Semua Bidang">
                            <option value="">Semua Bidang</option>
                            @foreach($bidangOptions as $bidang)
                                <option value="{{ $bidang }}" {{ request('bidang') == $bidang ? 'selected' : '' }}>
                                    {{ $bidang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-tag me-1"></i>Tipe Surat</label>
                        <select name="tipe_surat_id" class="form-select searchable-select" data-placeholder="Semua Tipe">
                            <option value="">Semua Tipe</option>
                            @foreach($tipeSurats as $tipe)
                                <option value="{{ $tipe->id }}" {{ request('tipe_surat_id') == $tipe->id ? 'selected' : '' }}>
                                    {{ $tipe->jenis_surat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-gradient w-100">
                            <i class="bi bi-funnel me-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card-glass">
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table card-table" id="laporanTable">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nomor Surat</th>
                                <th>Judul Surat</th>
                                <th>Nama Pembuat</th>
                                <th>Asal Bidang</th>
                                <th>Metode</th>
                                <th width="200">Isi Surat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surats as $index => $surat)
                                <tr>
                                    <td>{{ $surats->firstItem() + $index }}</td>
                                    <td>
                                        @if($surat->hasNumber())
                                            <strong>{{ $surat->nomor_surat_full }}</strong>
                                        @else
                                            <span class="badge bg-secondary">Draft/Menunggu</span>
                                        @endif
                                    </td>
                                    <td>{{ $surat->perihal }}</td>
                                    <td>{{ $surat->user->name }}</td>
                                    <td>{{ $surat->user->bidang ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $surat->metode_pembuatan ?? 'Manual' }}</span>
                                    </td>
                                    <td>
                                        @if($surat->isi_surat)
                                            <div class="isi-surat-cell">
                                                {!! nl2br(e(Str::limit($surat->isi_surat, 200))) !!}
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="text-muted mt-3 mb-0">Tidak ada data surat.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $surats->firstItem() ?? 0 }} - {{ $surats->lastItem() ?? 0 }}
                        dari {{ $surats->total() }} data
                    </div>
                    {{ $surats->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .isi-surat-cell {
            max-height: 100px;
            overflow-y: auto;
            font-size: 0.85rem;
            line-height: 1.4;
            padding-right: 5px;
        }

        .isi-surat-cell::-webkit-scrollbar {
            width: 4px;
        }

        .isi-surat-cell::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 2px;
        }
    </style>
@endsection