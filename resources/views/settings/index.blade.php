@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')

@section('page-title', 'Pengaturan Aplikasi')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="settings-card">
                    <div class="settings-header">
                        <h4 class="mb-0">
                            <i class="bi bi-gear me-2"></i>Pengaturan Sistem
                        </h4>
                        <p class="text-muted mb-0 mt-2">Kelola branding dan konfigurasi aplikasi</p>
                    </div>

                    <div class="settings-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- App Name -->
                            <div class="setting-group">
                                <label for="app_name" class="setting-label">
                                    <i class="bi bi-app-indicator me-2"></i>Nama Aplikasi
                                </label>
                                <input type="text" name="app_name" id="app_name" class="form-control setting-input"
                                    value="{{ $settings['app_name']->value ?? 'E-Num' }}" required>
                                <div class="setting-hint">Ditampilkan di header dan sidebar</div>
                            </div>

                            <!-- Institution -->
                            <div class="setting-group">
                                <label for="institution_name" class="setting-label">
                                    <i class="bi bi-building me-2"></i>Nama Instansi
                                </label>
                                <input type="text" name="institution_name" id="institution_name"
                                    class="form-control setting-input"
                                    value="{{ $settings['institution_name']->value ?? '' }}" required>
                            </div>

                            <!-- Logo -->
                            <div class="setting-group">
                                <label class="setting-label">
                                    <i class="bi bi-image me-2"></i>Logo Aplikasi
                                </label>

                                @if(isset($settings['app_logo']) && $settings['app_logo']->value)
                                    <div class="logo-preview mb-3">
                                        <img src="{{ asset('storage/' . $settings['app_logo']->value) }}" alt="Logo"
                                            class="current-logo">
                                        <a href="{{ route('settings.remove-logo') }}" class="btn btn-sm btn-outline-danger ms-3"
                                            onclick="return confirm('Hapus logo?')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </div>
                                @endif

                                <input type="file" name="app_logo" id="app_logo" class="form-control setting-input"
                                    accept="image/*">
                                <div class="setting-hint">Format: JPG, PNG, SVG. Maksimal 2MB.</div>
                            </div>

                            <!-- Footer Text -->
                            <div class="setting-group">
                                <label for="footer_text" class="setting-label">
                                    <i class="bi bi-card-text me-2"></i>Teks Footer
                                </label>
                                <textarea name="footer_text" id="footer_text" class="form-control setting-input"
                                    rows="2">{{ $settings['footer_text']->value ?? '' }}</textarea>
                            </div>

                            <div class="setting-actions">
                                <button type="submit" class="btn btn-primary-elegant">
                                    <i class="bi bi-save me-2"></i>Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .settings-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .settings-header {
            padding: 24px 32px;
            border-bottom: 1px solid #f1f3f4;
        }

        .settings-body {
            padding: 32px;
        }

        .setting-group {
            margin-bottom: 28px;
        }

        .setting-label {
            display: block;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 8px;
        }

        .setting-input {
            border: none;
            border-bottom: 2px solid #e9ecef;
            border-radius: 0;
            padding: 12px 0;
            background: transparent;
            transition: border-color 0.3s;
        }

        .setting-input:focus {
            border-bottom-color: #007bff;
            box-shadow: none;
            background: transparent;
        }

        .setting-hint {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 6px;
        }

        .logo-preview {
            display: flex;
            align-items: center;
        }

        .current-logo {
            max-height: 60px;
            max-width: 200px;
            border-radius: 8px;
        }

        .setting-actions {
            padding-top: 20px;
            border-top: 1px solid #f1f3f4;
        }

        .btn-primary-elegant {
            background: linear-gradient(135deg, #0056b3, #007bff);
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary-elegant:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }
    </style>
@endsection