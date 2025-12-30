@extends('layouts.app')

@section('title', 'Detail Surat Masuk')

@section('page-title', 'Detail Surat Masuk')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card-glass">
                    <div class="card-header bg-transparent border-0 p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">
                                <i class="bi bi-envelope-open me-2"></i>
                                Detail Surat Masuk
                            </h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('surat-masuk.edit', $suratMasuk) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </a>
                                <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <!-- Basic Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%" class="text-muted">Nomor Surat</td>
                                        <td><strong class="text-primary">{{ $suratMasuk->nomor_surat }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tanggal Surat</td>
                                        <td>{{ $suratMasuk->tanggal_surat->format('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Jenis Surat</td>
                                        <td><span class="badge bg-info text-dark">{{ $suratMasuk->jenis_surat }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Judul Surat</td>
                                        <td>{{ $suratMasuk->judul_surat }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%" class="text-muted">Status</td>
                                        <td>
                                            <span class="badge {{ $suratMasuk->getStatusBadgeClass() }} px-3 py-2">
                                                {{ $suratMasuk->getStatusLabel() }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Posisi Tindak Lanjut</td>
                                        <td>{{ $suratMasuk->posisi_tindak_lanjut ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Input oleh</td>
                                        <td>{{ $suratMasuk->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tanggal Input</td>
                                        <td>{{ $suratMasuk->created_at->format('d F Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Isi Surat -->
                        @if($suratMasuk->isi_surat)
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-file-text me-2"></i>Isi Surat
                                </h6>
                                <div class="p-3 bg-light rounded">
                                    {!! nl2br(e($suratMasuk->isi_surat)) !!}
                                </div>
                            </div>
                        @endif

                        <!-- Disposisi -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-signpost-2 me-2"></i>Disposisi & Tindak Lanjut
                            </h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="p-3 bg-light rounded">
                                        <strong>Disposisi Pimpinan:</strong><br>
                                        {{ $suratMasuk->disposisi_pimpinan ?? 'Belum ada disposisi' }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded">
                                        <strong>Tanggal Disposisi:</strong><br>
                                        {{ $suratMasuk->tanggal_disposisi ? $suratMasuk->tanggal_disposisi->format('d F Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection