@extends('layouts.app')

@section('title', 'Buat Surat Baru')

@section('page-title', 'Buat Surat Baru')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-glass">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-envelope-plus me-2"></i>
                            Buat Surat Baru
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <form action="{{ route('surat.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="tipe_surat_id" class="form-label">Tipe Surat <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('tipe_surat_id') is-invalid @enderror" id="tipe_surat_id"
                                    name="tipe_surat_id" required>
                                    <option value="">-- Pilih Tipe Surat --</option>
                                    @foreach($tipeSurats as $tipe)
                                        <option value="{{ $tipe->id }}" {{ old('tipe_surat_id') == $tipe->id ? 'selected' : '' }}>
                                            {{ $tipe->jenis_surat }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipe_surat_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_surat" class="form-label">Tanggal Surat <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror"
                                    id="tanggal_surat" name="tanggal_surat"
                                    value="{{ old('tanggal_surat', date('Y-m-d')) }}" required>
                                @error('tanggal_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tujuan" class="form-label">Tujuan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('tujuan') is-invalid @enderror" id="tujuan"
                                    name="tujuan" value="{{ old('tujuan') }}" required>
                                @error('tujuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="perihal" class="form-label">Perihal <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('perihal') is-invalid @enderror" id="perihal"
                                    name="perihal" rows="4" required>{{ old('perihal') }}</textarea>
                                @error('perihal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-gradient">
                                    <i class="bi bi-save me-2"></i>Simpan
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