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
                        <p class="text-muted mb-0 mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Nomor surat akan digenerate otomatis saat disetujui
                        </p>
                    </div>
                    <div class="card-body p-4 pt-0">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tipe_surat_id" class="form-label">Tipe Surat <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select searchable-select @error('tipe_surat_id') is-invalid @enderror" id="tipe_surat_id"
                                        name="tipe_surat_id" data-placeholder="-- Pilih Tipe Surat --" required>
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

                                <div class="col-md-6 mb-3">
                                    <label for="metode_pembuatan" class="form-label">Metode Pembuatan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select searchable-select @error('metode_pembuatan') is-invalid @enderror" 
                                            id="metode_pembuatan" name="metode_pembuatan" data-placeholder="-- Pilih Metode --" required>
                                        <option value="Manual" {{ old('metode_pembuatan') == 'Manual' ? 'selected' : '' }}>Manual</option>
                                        <option value="Srikandi" {{ old('metode_pembuatan') == 'Srikandi' ? 'selected' : '' }}>Srikandi</option>
                                        <option value="TTE" {{ old('metode_pembuatan') == 'TTE' ? 'selected' : '' }}>TTE (Tanda Tangan Elektronik)</option>
                                    </select>
                                    @error('metode_pembuatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
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
                                    name="perihal" rows="2" required>{{ old('perihal') }}</textarea>
                                @error('perihal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="isi_surat" class="form-label">Isi Surat</label>
                                <textarea class="form-control @error('isi_surat') is-invalid @enderror" id="isi_surat"
                                    name="isi_surat" rows="5" placeholder="Isi ringkas surat (opsional)">{{ old('isi_surat') }}</textarea>
                                @error('isi_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="file_surat" class="form-label">Upload File Surat</label>
                                <input type="file" class="form-control @error('file_surat') is-invalid @enderror"
                                    id="file_surat" name="file_surat" accept=".doc,.docx,.pdf">
                                @error('file_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Format yang diterima: Word (.doc, .docx) atau PDF (.pdf). Maksimal
                                    10MB.</div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-gradient">
                                    <i class="bi bi-save me-2"></i>Simpan & Ajukan
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
