@extends('layouts.app')

@section('title', 'Cetak Surat')

@section('content')
    {{-- Letter Information Header --}}
    <div class="letter-info-card">
        <div class="card-glass p-4 mb-4">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td width="150"><strong>Nomor Surat</strong></td>
                            <td width="20">:</td>
                            <td><strong class="text-primary">{{ $surat->nomor_surat_full }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal</strong></td>
                            <td>:</td>
                            <td>{{ $surat->tanggal_surat->isoFormat('D MMMM YYYY') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tipe Surat</strong></td>
                            <td>:</td>
                            <td>{{ $surat->tipeSurat->jenis_surat }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td width="150"><strong>Kepada</strong></td>
                            <td width="20">:</td>
                            <td>{{ $surat->tujuan }}</td>
                        </tr>
                        <tr>
                            <td><strong>Perihal</strong></td>
                            <td>:</td>
                            <td>{{ $surat->perihal }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>:</td>
                            <td><span class="badge bg-success">Approved</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- File Preview Section --}}
    @if($surat->file_surat)
        @php
            $fileExtension = strtolower(pathinfo($surat->file_surat, PATHINFO_EXTENSION));
            $fileUrl = asset('storage/' . $surat->file_surat);
        @endphp

        <div class="file-preview-container">
            @if($fileExtension === 'pdf')
                {{-- PDF Preview --}}
                <div class="pdf-preview">
                    <embed src="{{ $fileUrl }}" type="application/pdf" width="100%" height="800px">
                </div>
            @else
                {{-- Word Document Preview using Google Docs Viewer --}}
                <div class="doc-preview">
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Preview file Word. Jika tidak tampil, silakan download file untuk melihat isi lengkap.
                    </div>
                    <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($fileUrl) }}" width="100%"
                        height="800px" frameborder="0">
                    </iframe>
                </div>
            @endif
        </div>
    @else
        {{-- No File Uploaded - Show Original Template --}}
        <div class="print-container">
            <div class="letterhead">
                <div class="logo-section">
                    <div class="logo-placeholder">
                        <i class="bi bi-building" style="font-size: 3rem;"></i>
                    </div>
                </div>
                <div class="header-text">
                    <h2 class="institution-name">NAMA INSTITUSI</h2>
                    <p class="institution-address">
                        Alamat Institusi<br>
                        Telepon: (021) 123-4567 | Email: info@institusi.go.id
                    </p>
                </div>
            </div>

            <hr class="header-divider">

            <div class="letter-content">
                <table class="letter-meta">
                    <tr>
                        <td width="150">Nomor</td>
                        <td width="20">:</td>
                        <td><strong>{{ $surat->nomor_surat_full }}</strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td>{{ $surat->tanggal_surat->isoFormat('D MMMM YYYY') }}</td>
                    </tr>
                    <tr>
                        <td>Tipe Surat</td>
                        <td>:</td>
                        <td>{{ $surat->tipeSurat->jenis_surat }}</td>
                    </tr>
                    <tr>
                        <td>Kepada</td>
                        <td>:</td>
                        <td>{{ $surat->tujuan }}</td>
                    </tr>
                </table>

                <div class="letter-body">
                    <p><strong>Perihal: {{ $surat->perihal }}</strong></p>
                    <div class="content-area">
                        <p><em>(File surat belum diupload)</em></p>
                    </div>
                </div>

                <div class="signature-section">
                    <div class="signature-box">
                        <p class="mb-1">{{ $surat->tipeSurat->jenis_surat }}</p>
                        <p class="mb-5"><i>{{ $surat->approved_at ? $surat->approved_at->isoFormat('D MMMM YYYY') : '' }}</i>
                        </p>
                        <p class="mt-5 pt-5"><strong><u>{{ $surat->approver->name ?? '-' }}</u></strong></p>
                        <p>{{ $surat->approver ? ucfirst($surat->approver->role) : '' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Action Buttons --}}
    <div class="print-actions">
        @if($surat->file_surat)
            <a href="{{ route('surat.download', $surat) }}" class="btn btn-info btn-lg">
                <i class="bi bi-download me-2"></i>Download File Surat
            </a>
        @endif
        <a href="{{ route('surat.show', $surat) }}" class="btn btn-secondary btn-lg">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <style>
        /* Letter Info Card */
        .letter-info-card {
            max-width: 900px;
            margin: 0 auto;
        }

        /* File Preview Styles */
        .file-preview-container {
            max-width: 900px;
            margin: 0 auto 2rem auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .pdf-preview embed,
        .doc-preview iframe {
            display: block;
            border: none;
        }

        /* Print Container for No-File fallback */
        .print-container {
            background: white;
            max-width: 21cm;
            min-height: 29.7cm;
            margin: 2rem auto;
            padding: 2.5cm 2cm;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .letterhead {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }

        .logo-placeholder {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ddd;
            border-radius: 10px;
            background: #f8f9fa;
        }

        .institution-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
            color: #2c3e50;
            text-transform: uppercase;
        }

        .institution-address {
            margin: 0.5rem 0 0 0;
            font-size: 0.875rem;
            color: #666;
        }

        .header-divider {
            border: none;
            border-top: 3px double #333;
            margin: 1rem 0 2rem 0;
        }

        .letter-meta {
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .letter-meta td {
            padding: 0.25rem 0;
        }

        .content-area {
            margin: 2rem 0;
            min-height: 200px;
        }

        .signature-section {
            margin-top: 3rem;
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            text-align: center;
            min-width: 200px;
        }

        /* Action Buttons */
        .print-actions {
            text-align: center;
            margin: 2rem auto;
            max-width: 900px;
        }

        .print-actions .btn {
            margin: 0 0.5rem;
        }

        /* Print Styles */
        @media print {

            .letter-info-card,
            .print-actions {
                display: none !important;
            }

            .file-preview-container {
                box-shadow: none;
                margin: 0;
                max-width: 100%;
            }

            .pdf-preview embed,
            .doc-preview iframe {
                height: 100vh;
            }
        }
    </style>
@endsection