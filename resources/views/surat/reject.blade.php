@extends('layouts.app')

@section('title', 'Tolak Surat')

@section('page-title', 'Tolak Surat')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-glass">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0 fw-semibold text-danger">
                            <i class="bi bi-x-circle me-2"></i>
                            Tolak Surat
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        {{-- Alert Warning --}}
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Anda akan menolak surat ini. Harap berikan alasan penolakan agar pembuat surat dapat
                            memperbaikinya.
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

                        {{-- Rejection Form --}}
                        <form action="{{ route('surat.reject', $surat) }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="reason" class="form-label">
                                    <i class="bi bi-chat-left-text me-1"></i>
                                    Alasan Penolakan <span class="text-danger">*</span>
                                </label>
                                <textarea name="reason" id="reason" rows="4"
                                    class="form-control @error('reason') is-invalid @enderror"
                                    placeholder="Jelaskan alasan penolakan surat ini agar pembuat dapat memperbaikinya..."
                                    required>{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Alasan ini akan dikirimkan ke pembuat surat sebagai feedback untuk perbaikan.
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="bi bi-x-lg me-2"></i>Tolak Surat
                                </button>
                                <a href="{{ route('surat.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="bi bi-arrow-left me-2"></i>Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection