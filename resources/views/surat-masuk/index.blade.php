@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('page-title', 'Daftar Surat Masuk')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-envelope-open me-2"></i>
                Daftar Surat Masuk
            </h2>
            <a href="{{ route('surat-masuk.create') }}" class="btn btn-gradient">
                <i class="bi bi-plus-circle me-2"></i> Tambah Surat Masuk
            </a>
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
                    <form method="GET" action="{{ route('surat-masuk.index') }}" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-3">
                            <label class="form-label"><i class="bi bi-search me-1"></i>Cari</label>
                            <input type="text" name="search" class="form-control" placeholder="Nomor/Judul/Jenis..."
                                value="{{ request('search') }}">
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <label class="form-label"><i class="bi bi-flag me-1"></i>Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Dalam Proses
                                </option>
                                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
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
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-gradient">
                                <i class="bi bi-search me-2"></i>Cari
                            </button>
                            <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="card-glass">
            <div class="card-body p-4">
                @if($suratMasuks->count() > 0)
                    <div class="table-responsive">
                        <table class="table card-table table-hover">
                            <thead>
                                <tr>
                                    <th style="min-width: 120px;">Nomor Surat</th>
                                    <th style="min-width: 90px;">Tanggal</th>
                                    <th style="min-width: 100px;">Jenis Surat</th>
                                    <th style="min-width: 150px;">Judul Surat</th>
                                    <th style="min-width: 150px;">Disposisi</th>
                                    <th style="min-width: 90px;">Tgl Disposisi</th>
                                    <th style="min-width: 100px;">Status</th>
                                    <th style="min-width: 120px;">Posisi</th>
                                    <th style="min-width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suratMasuks as $sm)
                                    <tr>
                                        <td><strong class="text-primary">{{ $sm->nomor_surat }}</strong></td>
                                        <td>{{ $sm->tanggal_surat->format('d/m/Y') }}</td>
                                        <td><span class="badge bg-info text-dark">{{ $sm->jenis_surat }}</span></td>
                                        <td>
                                            <span title="{{ $sm->judul_surat }}">{{ Str::limit($sm->judul_surat, 30) }}</span>
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($sm->disposisi_pimpinan ?? '-', 40) }}</small>
                                        </td>
                                        <td>{{ $sm->tanggal_disposisi ? $sm->tanggal_disposisi->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            <span class="badge {{ $sm->getStatusBadgeClass() }}">
                                                {{ $sm->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td><small>{{ $sm->posisi_tindak_lanjut ?? '-' }}</small></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('surat-masuk.show', $sm) }}" class="btn btn-info" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('surat-masuk.edit', $sm) }}" class="btn btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('surat-masuk.destroy', $sm) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus surat masuk ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $suratMasuks->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada surat masuk.</p>
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