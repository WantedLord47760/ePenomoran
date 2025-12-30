@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h2 class="mb-0">
                <i class="bi bi-pencil-square me-2"></i>
                Edit User
            </h2>
        </div>

        <div class="card-glass">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-person me-1"></i>Nama Lengkap *
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-envelope me-1"></i>Email *
                            </label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-lock me-1"></i>Password Baru (Opsional)
                            </label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-lock-fill me-1"></i>Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-shield me-1"></i>Role *
                            </label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                                </option>
                                <option value="admin_surat_masuk" {{ old('role', $user->role) == 'admin_surat_masuk' ? 'selected' : '' }}>Admin Surat Masuk</option>
                                <option value="admin_surat_keluar" {{ old('role', $user->role) == 'admin_surat_keluar' ? 'selected' : '' }}>Admin Surat Keluar</option>
                                <option value="pemimpin" {{ old('role', $user->role) == 'pemimpin' ? 'selected' : '' }}>
                                    Pemimpin</option>
                                <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>
                                    Operator</option>
                                <option value="pegawai" {{ old('role', $user->role) == 'pegawai' ? 'selected' : '' }}>Pegawai
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-gradient">
                            <i class="bi bi-save me-2"></i>Update
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