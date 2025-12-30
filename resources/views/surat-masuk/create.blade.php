@extends('layouts.app')

@section('title', 'Tambah Surat Masuk')

@section('page-title', 'Tambah Surat Masuk')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card-glass">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-envelope-plus me-2"></i>
                            Tambah Surat Masuk Baru
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <form action="{{ route('surat-masuk.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Nomor Surat -->
                                <div class="col-md-6 mb-3">
                                    <label for="nomor_surat" class="form-label">
                                        Nomor Surat <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('nomor_surat') is-invalid @enderror"
                                        id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat') }}"
                                        placeholder="Contoh: 001/SM/XII/2025" required>
                                    @error('nomor_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tanggal Surat -->
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_surat" class="form-label">
                                        Tanggal Surat <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror"
                                        id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required>
                                    @error('tanggal_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Jenis Surat -->
                                <div class="col-md-6 mb-3">
                                    <label for="jenis_surat" class="form-label">
                                        Jenis Surat <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('jenis_surat') is-invalid @enderror"
                                        id="jenis_surat" name="jenis_surat" value="{{ old('jenis_surat') }}"
                                        placeholder="Contoh: Surat Undangan, Surat Permohonan" required>
                                    @error('jenis_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Judul Surat -->
                                <div class="col-md-6 mb-3">
                                    <label for="judul_surat" class="form-label">
                                        Judul Surat <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('judul_surat') is-invalid @enderror"
                                        id="judul_surat" name="judul_surat" value="{{ old('judul_surat') }}"
                                        placeholder="Judul/Perihal surat masuk" required>
                                    @error('judul_surat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Isi Surat -->
                            <div class="mb-3">
                                <label for="isi_surat" class="form-label">Isi Surat</label>
                                <textarea class="form-control @error('isi_surat') is-invalid @enderror"
                                    id="isi_surat" name="isi_surat" rows="4"
                                    placeholder="Ringkasan atau isi surat masuk...">{{ old('isi_surat') }}</textarea>
                                @error('isi_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">
                            <h6 class="mb-3 text-muted">
                                <i class="bi bi-signpost-2 me-2"></i>Disposisi & Tindak Lanjut
                            </h6>

                            <div class="row">
                                <!-- Disposisi Pimpinan -->
                                <div class="col-md-8 mb-3">
                                    <label for="disposisi_pimpinan" class="form-label">Disposisi Pimpinan</label>
                                    <textarea class="form-control @error('disposisi_pimpinan') is-invalid @enderror"
                                        id="disposisi_pimpinan" name="disposisi_pimpinan" rows="2"
                                        placeholder="Instruksi atau arahan dari pimpinan...">{{ old('disposisi_pimpinan') }}</textarea>
                                    @error('disposisi_pimpinan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tanggal Disposisi -->
                                <div class="col-md-4 mb-3">
                                    <label for="tanggal_disposisi" class="form-label">Tanggal Disposisi</label>
                                    <input type="date" class="form-control @error('tanggal_disposisi') is-invalid @enderror"
                                        id="tanggal_disposisi" name="tanggal_disposisi" value="{{ old('tanggal_disposisi') }}">
                                    @error('tanggal_disposisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Status Tindak Lanjut -->
                                <div class="col-md-6 mb-3">
                                    <label for="status_tindak_lanjut" class="form-label">
                                        Status Tindak Lanjut <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status_tindak_lanjut') is-invalid @enderror"
                                        id="status_tindak_lanjut" name="status_tindak_lanjut" required>
                                        <option value="pending" {{ old('status_tindak_lanjut', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="proses" {{ old('status_tindak_lanjut') === 'proses' ? 'selected' : '' }}>Dalam Proses</option>
                                        <option value="selesai" {{ old('status_tindak_lanjut') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                    @error('status_tindak_lanjut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Posisi Tindak Lanjut -->
                                <div class="col-md-6 mb-3">
                                    <label for="posisi_tindak_lanjut" class="form-label">Posisi Tindak Lanjut</label>
                                    <input type="text" class="form-control @error('posisi_tindak_lanjut') is-invalid @enderror"
                                        id="posisi_tindak_lanjut" name="posisi_tindak_lanjut" value="{{ old('posisi_tindak_lanjut') }}"
                                        placeholder="Contoh: Bagian Umum, Bidang IT">
                                    @error('posisi_tindak_lanjut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-gradient">
                                    <i class="bi bi-save me-2"></i>Simpan
                                </button>
                                <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
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
