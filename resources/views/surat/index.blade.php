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
            @if(in_array(auth()->user()->role, ['admin', 'operator']))
                <a href="{{ route('surat.create') }}" class="btn btn-gradient">
                    <i class="bi bi-plus-circle me-2"></i> Buat Surat Baru
                </a>
            @endif
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
                    <form method="GET" action="{{ route('surat.index') }}" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-search me-1"></i>Cari</label>
                            <input type="text" name="search" class="form-control" placeholder="Nomor/Tujuan/Perihal..."
                                value="{{ request('search') }}">
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <label class="form-label"><i class="bi bi-flag me-1"></i>Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Disetujui</option>
                                <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <!-- Tipe Surat Filter -->
                        <div class="col-md-2">
                            <label class="form-label"><i class="bi bi-tag me-1"></i>Tipe Surat</label>
                            <select name="tipe_surat_id" class="form-select">
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
                            <button type="submit" class="btn btn-gradient">
                                <i class="bi bi-search me-2"></i>Cari
                            </button>
                            <a href="{{ route('surat.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Reset
                            </a>
                            @if($surats->total() > 0)
                                <span class="ms-3 text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Ditemukan {{ $surats->total() }} surat
                                </span>
                            @endif
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
                        <table class="table card-table">
                            <thead>
                                <tr>
                                    <th>Nomor Surat</th>
                                    <th>Tipe Surat</th>
                                    <th>Tanggal</th>
                                    <th>Tujuan</th>
                                    <th>Perihal</th>
                                    <th>Pembuat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surats as $surat)
                                    <tr>
                                        <td><strong>{{ $surat->nomor_surat_full }}</strong></td>
                                        <td>{{ $surat->tipeSurat->jenis_surat }}</td>
                                        <td>{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                                        <td>{{ Str::limit($surat->tujuan, 30) }}</td>
                                        <td>{{ Str::limit($surat->perihal, 40) }}</td>
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
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('surat.show', $surat) }}" class="btn btn-info" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                @if(in_array(auth()->user()->role, ['admin', 'operator']))
                                                    <a href="{{ route('surat.edit', $surat) }}" class="btn btn-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endif

                                                @if(in_array(auth()->user()->role, ['admin', 'pemimpin']) && $surat->status == '0')
                                                    <form action="{{ route('surat.approve', $surat) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success" title="Approve">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('surat.reject', $surat) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger" title="Reject">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($surat->status == '1')
                                                    <a href="{{ route('surat.cetak', $surat) }}" class="btn btn-secondary"
                                                        target="_blank" title="Print">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                @endif
                                            </div>
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
@endsection