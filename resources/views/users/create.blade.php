@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h2 class="mb-0">
                <i class="bi bi-person-plus me-2"></i>
                Tambah User Baru
            </h2>
        </div>

        <div class="card-glass">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-person me-1"></i>Nama Lengkap *
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-envelope me-1"></i>Email *
                            </label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-lock me-1"></i>Password *
                            </label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            <small class="text-muted">Minimal 8 karakter</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-lock-fill me-1"></i>Konfirmasi Password *
                            </label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-shield me-1"></i>Role *
                            </label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">Pilih Role</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="admin_surat_masuk" {{ old('role') == 'admin_surat_masuk' ? 'selected' : '' }}>
                                    Admin Surat Masuk</option>
                                <option value="admin_surat_keluar" {{ old('role') == 'admin_surat_keluar' ? 'selected' : '' }}>Admin Surat Keluar</option>
                                <option value="pemimpin" {{ old('role') == 'pemimpin' ? 'selected' : '' }}>Pemimpin</option>
                                <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                                <option value="pegawai" {{ old('role') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-gradient">
                            <i class="bi bi-save me-2"></i>Simpan
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection