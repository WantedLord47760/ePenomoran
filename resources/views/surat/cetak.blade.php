@extends('layouts.app')

@section('title', 'Cetak Surat')

@section('content')
    <div class="print-container">
        <div class="letterhead">
            <div class="logo-section">
                {{-- Add your institution logo here --}}
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

                <p class="mt-4">Yang bertanda tangan di bawah ini,</p>

                {{-- Letter body content - customize as needed --}}
                <div class="content-area">
                    <p>Dengan hormat,</p>
                    <p>{{ $surat->perihal }}</p>
                </div>

                <p class="mt-4">Demikian surat ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terima
                    kasih.</p>
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

            <div class="footer-info">
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i>
                    Dibuat oleh: {{ $surat->user->name }} |
                    Disetujui oleh: {{ $surat->approver->name ?? '-' }} pada
                    {{ $surat->approved_at ? $surat->approved_at->isoFormat('D MMMM YYYY HH:mm') : '-' }}
                </small>
            </div>
        </div>
    </div>

    <div class="print-actions">
        <button onclick="window.print()" class="btn btn-gradient">
            <i class="bi bi-printer me-2"></i>Cetak
        </button>
        <a href="{{ route('surat.show', $surat) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <style>
        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }

            .print-container,
            .print-container * {
                visibility: visible;
            }

            .print-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .print-actions {
                display: none;
            }

            .footer-info {
                display: none;
            }
        }

        /* Screen Styles */
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

        .logo-section {
            flex-shrink: 0;
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

        .header-text {
            flex-grow: 1;
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
            line-height: 1.4;
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
            vertical-align: top;
        }

        .letter-body {
            line-height: 1.8;
            text-align: justify;
            font-size: 1rem;
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

        .footer-info {
            margin-top: 3rem;
            padding-top: 1rem;
            border-top: 1px solid #ddd;
        }

        .print-actions {
            text-align: center;
            margin: 2rem auto;
            max-width: 21cm;
        }

        .print-actions .btn {
            margin: 0 0.5rem;
        }

        @page {
            size: A4;
            margin: 0;
        }
    </style>

@endsection