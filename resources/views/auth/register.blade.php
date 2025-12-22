@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 90vh;">
            <div class="col-md-5 col-lg-4">
                <div class="card-glass animate-slide-in">
                    <div class="card-body p-5">
                        <!-- Logo/Icon -->
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px; background: var(--gradient-primary); border-radius: 20px; box-shadow: var(--shadow-lg);">
                                <i class="bi bi-person-plus" style="font-size: 2.5rem; color: white;"></i>
                            </div>
                        </div>

                        <h3 class="text-center mb-2 fw-bold">Daftar Akun</h3>
                        <p class="text-center text-muted mb-4">Buat akun baru Anda</p>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person me-2"></i>Nama Lengkap
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}" placeholder="Nama lengkap Anda" required
                                    autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-2"></i>Email
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-2"></i>Password
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="••••••••" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock-fill me-2"></i>Konfirmasi Password
                                </label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="••••••••" required>
                            </div>

                            <button type="submit" class="btn btn-gradient w-100 mb-3">
                                <i class="bi bi-person-check me-2"></i>Daftar
                            </button>

                            <p class="text-center mb-0">
                                Sudah punya akun? <a href="{{ route('login') }}"
                                    class="text-decoration-none fw-semibold">Login di sini</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection