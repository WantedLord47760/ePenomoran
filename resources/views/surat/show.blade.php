@extends('layouts.app')

@section('title', 'Detail Surat')

@section('page-title', 'Detail Surat')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-glass">
                    <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-file-text me-2"></i>
                            Detail Surat
                        </h5>
                        <div>
                            @if($surat->status == '0')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($surat->status == '1')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Nomor Surat</th>
                                <td><strong>{{ $surat->nomor_surat_full }}</strong></td>
                            </tr>
                            <tr>
                                <th>Tipe Surat</th>
                                <td>{{ $surat->tipeSurat->jenis_surat }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Surat</th>
                                <td>{{ $surat->tanggal_surat->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Tujuan</th>
                                <td>{{ $surat->tujuan }}</td>
                            </tr>
                            <tr>
                                <th>Perihal</th>
                                <td>{{ $surat->perihal }}</td>
                            </tr>
                            <tr>
                                <th>Pembuat</th>
                                <td>{{ $surat->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>{{ $surat->created_at->format('d F Y H:i') }}</td>
                            </tr>
                        </table>

                        <div class="d-flex gap-2 mt-4">
                            <a href="{{ route('surat.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>

                            @if(in_array(auth()->user()->role, ['admin', 'operator']))
                                <a href="{{ route('surat.edit', $surat) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-2"></i>Edit
                                </a>
                            @endif

                            @if(in_array(auth()->user()->role, ['admin', 'pemimpin']) && $surat->status == '0')
                                <form action="{{ route('surat.approve', $surat) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-lg me-2"></i>Approve
                                    </button>
                                </form>
                                <form action="{{ route('surat.reject', $surat) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-x-lg me-2"></i>Reject
                                    </button>
                                </form>
                            @endif

                            @if($surat->status == '1')
                                <a href="{{ route('surat.cetak', $surat) }}" class="btn btn-gradient" target="_blank">
                                    <i class="bi bi-printer me-2"></i>Print
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection