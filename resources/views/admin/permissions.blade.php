@extends('layouts.app')

@section('title', 'Pengaturan Role')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-shield-lock me-2"></i>
                Pengaturan Role & Permission
            </h2>
        </div>

        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="bi bi-info-circle me-2 fs-5"></i>
            <div>
                <strong>Info:</strong> Role <strong>Admin</strong> selalu memiliki akses penuh dan tidak dapat dibatasi.
                Centang permission yang ingin diberikan untuk setiap role.
            </div>
        </div>

        <div class="card-glass">
            <div class="card-body p-4">
                <form action="{{ route('admin.permissions.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 200px;">Permission</th>
                                    @foreach($roles as $roleKey => $roleName)
                                        <th class="text-center" style="min-width: 120px;">
                                            <small>{{ $roleName }}</small>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissionGroups as $groupKey => $group)
                                    <tr class="table-secondary">
                                        <td colspan="{{ count($roles) + 1 }}">
                                            <strong><i class="bi bi-folder me-2"></i>{{ $group['label'] }}</strong>
                                        </td>
                                    </tr>
                                    @foreach($group['permissions'] as $permKey => $permLabel)
                                        <tr>
                                            <td class="ps-4">{{ $permLabel }}</td>
                                            @foreach($roles as $roleKey => $roleName)
                                                <td class="text-center">
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input type="checkbox" 
                                                            class="form-check-input" 
                                                            name="permissions[{{ $roleKey }}][{{ $permKey }}]"
                                                            value="1"
                                                            {{ in_array($permKey, $currentPermissions[$roleKey] ?? []) ? 'checked' : '' }}
                                                            style="width: 1.3rem; height: 1.3rem; cursor: pointer;">
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-gradient">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Legend -->
        <div class="card-glass mt-4">
            <div class="card-body p-4">
                <h6 class="mb-3"><i class="bi bi-question-circle me-2"></i>Keterangan Permission</h6>
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-primary">Surat Keluar</h6>
                        <ul class="small">
                            <li><strong>Lihat:</strong> Melihat daftar surat keluar</li>
                            <li><strong>Buat:</strong> Membuat surat keluar baru</li>
                            <li><strong>Approve:</strong> Menyetujui/menolak surat</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-primary">Surat Masuk</h6>
                        <ul class="small">
                            <li><strong>Lihat:</strong> Melihat daftar surat masuk</li>
                            <li><strong>Buat:</strong> Input surat masuk baru</li>
                            <li><strong>Kelola:</strong> Edit/hapus surat masuk</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-primary">Pegawai</h6>
                        <ul class="small">
                            <li><strong>Lihat:</strong> Melihat daftar pegawai</li>
                            <li><strong>Kelola:</strong> Tambah/edit/hapus pegawai</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
