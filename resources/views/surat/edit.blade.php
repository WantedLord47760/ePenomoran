@extends('layouts.app')

@section('title', 'Edit Surat')

@section('page-title', 'Edit Surat')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-glass">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-pencil-square me-2"></i>
                            @if(auth()->user()->role === 'pegawai' && $surat->status == '2')
                                Perbaiki Surat
                            @else
                                Edit Surat
                            @endif
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        {{-- Show rejection reason for pegawai --}}
                        @if($surat->status == '2' && $surat->rejection_reason)
                            <div class="alert alert-warning mb-4">
                                <h6 class="alert-heading mb-2">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Surat Ditolak
                                </h6>
                                <p class="mb-0"><strong>Alasan:</strong> {{ $surat->rejection_reason }}</p>
                                <hr>
                                <p class="mb-0 small">Silakan perbaiki surat sesuai feedback di atas, lalu klik "Simpan & Ajukan
                                    Ulang".</p>
                            </div>
                        @endif

                        <form action="{{ route('surat.update', $surat) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Only show nomor surat for approved letters being edited by admin/operator --}}
                            @if($surat->nomor_surat_full && in_array(auth()->user()->role, ['admin', 'operator']))
                                <div class="mb-3">
                                    <label for="nomor_surat_full" class="form-label">Nomor Surat</label>
                                    <input type="text" class="form-control @error('nomor_surat_full') is-invalid @enderror"
                                        id="nomor_surat_full" name="nomor_surat_full"
                                        value="{{ old('nomor_surat_full', $surat->nomor_surat_full) }}">
                                    @error('nomor_surat_full')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipe Surat</label>
                                    <input type="text" class="form-control" value="{{ $surat->tipeSurat->jenis_surat }}"
                                        disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Surat</label>
                                    <input type="text" class="form-control"
                                        value="{{ $surat->tanggal_surat->format('d/m/Y') }}" disabled>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="tujuan" class="form-label">Tujuan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('tujuan') is-invalid @enderror" id="tujuan"
                                    name="tujuan" value="{{ old('tujuan', $surat->tujuan) }}" required>
                                @error('tujuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="perihal" class="form-label">Perihal <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('perihal') is-invalid @enderror" id="perihal"
                                    name="perihal" rows="3" required>{{ old('perihal', $surat->perihal) }}</textarea>
                                @error('perihal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="isi_surat" class="form-label">Isi Surat</label>
                                <textarea class="form-control @error('isi_surat') is-invalid @enderror" id="isi_surat"
                                    name="isi_surat" rows="5"
                                    placeholder="Isi/konten surat...">{{ old('isi_surat', $surat->isi_surat) }}</textarea>
                                @error('isi_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="file_surat" class="form-label">File Surat</label>
                                @if($surat->file_surat)
                                    <div class="mb-2">
                                        <span class="text-muted">
                                            <i class="bi bi-file-earmark-text me-1"></i>
                                            File saat ini: {{ $surat->file_surat_original_name ?? 'File Surat' }}
                                        </span>
                                        <a href="{{ route('surat.download', $surat) }}"
                                            class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('file_surat') is-invalid @enderror"
                                    id="file_surat" name="file_surat" accept=".doc,.docx,.pdf">
                                @error('file_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Kosongkan jika tidak ingin mengganti file. Format: Word (.doc, .docx)
                                    atau PDF (.pdf). Maksimal 10MB.</div>
                            </div>

                            <div class="d-flex gap-2">
                                @if($surat->status == '2' && auth()->user()->role === 'pegawai')
                                    {{-- For pegawai editing rejected letter --}}
                                    <input type="hidden" name="resubmit" value="1">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-arrow-repeat me-2"></i>Simpan & Ajukan Ulang
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-gradient">
                                        <i class="bi bi-save me-2"></i>Update
                                    </button>
                                @endif
                                <a href="{{ route('surat.index') }}" class="btn btn-secondary">
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