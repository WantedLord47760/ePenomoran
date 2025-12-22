@extends('layouts.app')

@section('title', 'Master Tipe Surat')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Master Tipe Surat</h2>
            <a href="{{ route('tipe-surat.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Tipe Surat
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if($tipeSurats->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Surat</th>
                                    <th>Format Penomoran</th>
                                    <th>Nomor Terakhir</th>
                                    <th>Jumlah Surat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tipeSurats as $index => $tipe)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $tipe->jenis_surat }}</strong></td>
                                        <td><code>{{ $tipe->format_penomoran }}</code></td>
                                        <td>{{ $tipe->nomor_terakhir }}</td>
                                        <td>{{ $tipe->surats_count }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('tipe-surat.edit', $tipe) }}" class="btn btn-warning">Edit</a>
                                                <form action="{{ route('tipe-surat.destroy', $tipe) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus tipe surat ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Belum ada tipe surat.</p>
                @endif
            </div>
        </div>
    </div>
@endsection