@extends('layouts.app')

@section('title', 'Setujui Surat')

@section('page-title', 'Konfirmasi Persetujuan')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-glass">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0 fw-semibold text-success">
                            <i class="bi bi-check-circle me-2"></i>
                            Setujui Surat
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        {{-- Alert Info --}}
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Anda akan menyetujui surat ini. Setelah disetujui, surat akan mendapatkan nomor resmi.
                        </div>

                        {{-- Surat Details --}}
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Detail Surat:</h6>
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td width="150" class="text-muted">Tipe Surat</td>
                                    <td><strong>{{ $surat->tipeSurat->jenis_surat }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tanggal</td>
                                    <td>{{ $surat->tanggal_surat->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Perihal</td>
                                    <td>{{ $surat->perihal }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Tujuan</td>
                                    <td>{{ $surat->tujuan }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Pembuat</td>
                                    <td>{{ $surat->user->name }}</td>
                                </tr>
                                @if($surat->isi_surat)
                                    <tr>
                                        <td class="text-muted align-top">Isi Surat</td>
                                        <td>
                                            <div class="p-3 bg-light rounded" style="max-height: 200px; overflow-y: auto;">
                                                {!! nl2br(e($surat->isi_surat)) !!}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>

                        {{-- Action Buttons --}}
                        <form action="{{ route('surat.approve', $surat) }}" method="POST">
                            @csrf
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-lg me-2"></i>Ya, Setujui Surat Ini
                                </button>
                                <a href="{{ route('surat.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="bi bi-x-circle me-2"></i>Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection