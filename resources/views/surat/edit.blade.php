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
                            Edit Surat
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <form action="{{ route('surat.update', $surat) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Nomor Surat</label>
                                <input type="text" class="form-control" value="{{ $surat->nomor_surat_full }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipe Surat</label>
                                <input type="text" class="form-control" value="{{ $surat->tipeSurat->jenis_surat }}"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Surat</label>
                                <input type="text" class="form-control" value="{{ $surat->tanggal_surat->format('d/m/Y') }}"
                                    disabled>
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
                                    name="perihal" rows="4" required>{{ old('perihal', $surat->perihal) }}</textarea>
                                @error('perihal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-gradient">
                                    <i class="bi bi-save me-2"></i>Update
                                </button>
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