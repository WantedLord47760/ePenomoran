@extends('layouts.app')

@section('title', 'Tambah Tipe Surat')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Tambah Tipe Surat</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tipe-surat.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="jenis_surat" class="form-label">Jenis Surat <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('jenis_surat') is-invalid @enderror"
                                    id="jenis_surat" name="jenis_surat" value="{{ old('jenis_surat') }}" required>
                                @error('jenis_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="format_penomoran" class="form-label">Format Penomoran <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('format_penomoran') is-invalid @enderror"
                                    id="format_penomoran" name="format_penomoran" value="{{ old('format_penomoran') }}"
                                    required>
                                <small class="form-text text-muted">
                                    Gunakan placeholder: {nomor}, {romawi}, {tahun}. Contoh: 500.12/DKI/{romawi}/{tahun}/
                                </small>
                                @error('format_penomoran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('tipe-surat.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection