@extends('layouts.app')

@section('title', 'Ubah Password')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mb-4">
                    <h2 class="mb-0">
                        <i class="bi bi-key me-2"></i>
                        Ubah Password
                    </h2>
                </div>

                <div class="card-glass">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-lock me-1"></i>Password Lama *
                                </label>
                                <input type="password" name="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror" required autofocus>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>Password Baru *
                                </label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                <small class="text-muted">Minimal 8 karakter dan berbeda dari password lama</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-shield-check me-1"></i>Konfirmasi Password Baru *
                                </label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Perhatian:</strong> Setelah mengubah password, Anda akan tetap login dengan session
                                saat ini.
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-gradient">
                                    <i class="bi bi-save me-2"></i>Ubah Password
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
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