@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('page-title', 'Edit Pegawai')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-glass">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0">
                            <i class="bi bi-person-gear me-2"></i>Edit Pegawai: {{ $pegawai->name }}
                        </h5>
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

                        <form action="{{ route('pegawai.update', $pegawai) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-person me-1"></i>Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ old('name', $pegawai->name) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nip" class="form-label">
                                        <i class="bi bi-card-text me-1"></i>NIP
                                    </label>
                                    <input type="text" name="nip" id="nip" class="form-control"
                                        value="{{ old('nip', $pegawai->nip) }}" placeholder="Opsional">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope me-1"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ old('email', $pegawai->email) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="no_hp" class="form-label">
                                        <i class="bi bi-phone me-1"></i>No. HP
                                    </label>
                                    <input type="text" name="no_hp" id="no_hp" class="form-control"
                                        value="{{ old('no_hp', $pegawai->no_hp) }}" placeholder="Opsional">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">
                                        <i class="bi bi-key me-1"></i>Password Baru
                                    </label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Kosongkan jika tidak ingin mengubah">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="bi bi-key-fill me-1"></i>Konfirmasi Password Baru
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">
                                        <i class="bi bi-person-badge me-1"></i>Role <span class="text-danger">*</span>
                                    </label>
                                    <select name="role" id="role" class="form-select searchable-select"
                                        data-placeholder="Pilih Role" required>
                                        <option value="pegawai" {{ old('role', $pegawai->role) == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                        <option value="operator" {{ old('role', $pegawai->role) == 'operator' ? 'selected' : '' }}>Operator</option>
                                        <option value="pemimpin" {{ old('role', $pegawai->role) == 'pemimpin' ? 'selected' : '' }}>Pemimpin</option>
                                        @if(auth()->user()->role === 'admin')
                                            <option value="admin" {{ old('role', $pegawai->role) == 'admin' ? 'selected' : '' }}>
                                                Admin</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bidang" class="form-label">
                                        <i class="bi bi-building me-1"></i>Bidang
                                    </label>
                                    <select name="bidang" id="bidang" class="form-select searchable-select"
                                        data-placeholder="Pilih Bidang">
                                        <option value="">Pilih Bidang (Opsional)</option>
                                        @foreach($bidangOptions as $bidang)
                                            <option value="{{ $bidang }}" {{ old('bidang', $pegawai->bidang) == $bidang ? 'selected' : '' }}>
                                                {{ $bidang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="jabatan" class="form-label">
                                        <i class="bi bi-briefcase me-1"></i>Jabatan
                                    </label>
                                    <input type="text" name="jabatan" id="jabatan" class="form-control"
                                        value="{{ old('jabatan', $pegawai->jabatan) }}" placeholder="Opsional">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pangkat" class="form-label">
                                        <i class="bi bi-award me-1"></i>Pangkat/Golongan
                                    </label>
                                    <input type="text" name="pangkat" id="pangkat" class="form-control"
                                        value="{{ old('pangkat', $pegawai->pangkat) }}" placeholder="Opsional">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('pegawai.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-gradient">
                                    <i class="bi bi-save me-2"></i>Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection