@extends('layouts.app')

@section('title', 'Manajemen Pegawai')

@section('page-title', 'Manajemen Pegawai')

@section('content')
    <div class="container-fluid">
        <!-- Header Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">
                    <i class="bi bi-people me-2"></i>Daftar Pegawai
                </h4>
                <p class="text-muted mb-0">Kelola data pegawai dan pengguna sistem</p>
            </div>
            <a href="{{ route('pegawai.create') }}" class="btn btn-gradient">
                <i class="bi bi-plus-lg me-2"></i>Tambah Pegawai
            </a>
        </div>

        <!-- Filters -->
        <div class="card-glass mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('pegawai.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label"><i class="bi bi-search me-1"></i>Cari</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama, Email, NIP..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-person-badge me-1"></i>Role</label>
                        <select name="role" class="form-select">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                            <option value="pemimpin" {{ request('role') == 'pemimpin' ? 'selected' : '' }}>Pemimpin</option>
                            <option value="pegawai" {{ request('role') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><i class="bi bi-building me-1"></i>Bidang</label>
                        <select name="bidang" class="form-select">
                            <option value="">Semua Bidang</option>
                            @foreach(\App\Models\User::getBidangOptions() as $bidang)
                                <option value="{{ $bidang }}" {{ request('bidang') == $bidang ? 'selected' : '' }}>
                                    {{ $bidang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-gradient w-100">
                            <i class="bi bi-funnel me-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card-glass">
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table card-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Bidang</th>
                                <th>Jabatan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                @if($user->no_hp)
                                                    <br><small class="text-muted">{{ $user->no_hp }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->nip ?? '-' }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-danger',
                                                'operator' => 'bg-primary',
                                                'pemimpin' => 'bg-success',
                                                'pegawai' => 'bg-info',
                                            ];
                                        @endphp
                                        <span class="badge {{ $roleColors[$user->role] ?? 'bg-secondary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->bidang ?? '-' }}</td>
                                    <td>{{ $user->jabatan ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('pegawai.edit', $user) }}" class="btn btn-sm btn-outline-primary"
                                                title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if(auth()->user()->role === 'admin' && auth()->id() !== $user->id)
                                                <form action="{{ route('pegawai.destroy', $user) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-people" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="text-muted mt-3 mb-0">Tidak ada data pegawai.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
    </style>
@endsection