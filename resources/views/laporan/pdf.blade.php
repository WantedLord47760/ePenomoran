<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Surat - {{ date('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
            color: #0056b3;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 5px;
        }

        .filter-info {
            text-align: center;
            background: #e7f1ff;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 11px;
            color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }

        th {
            background-color: #0056b3;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        .badge-draft {
            background: #6c757d;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }

        .badge-approved {
            background: #28a745;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }

        .badge-rejected {
            background: #dc3545;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }

        .print-btn {
            background: #0056b3;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .print-btn:hover {
            background: #004094;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                margin: 10px;
            }
        }
    </style>
</head>

<body>
    <button class="print-btn" onclick="window.print()">
        🖨️ Cetak / Simpan PDF
    </button>

    <h1>LAPORAN DATA SURAT</h1>
    <p class="subtitle">Dicetak pada: {{ date('d F Y H:i') }}</p>

    <div class="filter-info">
        <strong>Filter:</strong> {{ $filterDesc ?? 'Semua Data' }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="25">No</th>
                <th width="90">Nomor Surat</th>
                <th width="75">Tipe</th>
                <th width="60">Tanggal</th>
                <th width="100">Tujuan</th>
                <th>Perihal</th>
                <th width="80">Pembuat</th>
                <th width="50">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($surats as $index => $surat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if($surat->hasNumber())
                            <span style="font-size: 9px;">{{ $surat->nomor_surat_full }}</span>
                        @else
                            <span class="badge-draft">Draft</span>
                        @endif
                    </td>
                    <td>{{ $surat->tipeSurat->jenis_surat ?? '-' }}</td>
                    <td>{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                    <td>{{ Str::limit($surat->tujuan, 25) }}</td>
                    <td>{{ Str::limit($surat->perihal, 35) }}</td>
                    <td>{{ Str::limit($surat->user->name ?? '-', 15) }}</td>
                    <td>
                        @if($surat->status == '1')
                            <span class="badge-approved">OK</span>
                        @elseif($surat->status == '2')
                            <span class="badge-rejected">X</span>
                        @else
                            <span class="badge-draft">...</span>
                        @endif
                    </td>
                </tr>
                {{-- Isi Surat Row --}}
                <tr style="background: #f8f9fa;">
                    <td colspan="8" style="padding: 8px 12px; font-size: 9px; border-top: none;">
                        <strong style="color: #0056b3;">Isi Surat:</strong>
                        <div style="margin-top: 4px; line-height: 1.4; text-align: justify;">
                            {{ strip_tags($surat->isi_surat ?? '-') }}
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total: {{ $surats->count() }} surat
    </div>
</body>

</html>