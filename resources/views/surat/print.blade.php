<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Surat - {{ $surat->nomor_surat_full }}</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 40px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 5px 0;
        }

        .nomor-surat {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }

        .content {
            margin: 20px 0;
        }

        .content table {
            width: 100%;
            margin-bottom: 20px;
        }

        .content table td {
            padding: 5px;
            vertical-align: top;
        }

        .content table td:first-child {
            width: 150px;
        }

        .footer {
            margin-top: 50px;
        }

        .signature {
            float: right;
            text-align: center;
            width: 200px;
        }

        .signature-line {
            margin-top: 80px;
            border-top: 1px solid #000;
        }
    </style>
</head>

<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
        <button onclick="window.close()" class="btn btn-secondary">Close</button>
    </div>

    <div class="header">
        <h2>PEMERINTAH PROVINSI DKI JAKARTA</h2>
        <h3>DINAS KOMUNIKASI, INFORMATIKA DAN STATISTIK</h3>
        <p>Jl. Medan Merdeka Selatan No. 8-9 Jakarta Pusat 10110</p>
    </div>

    <div class="nomor-surat">
        Nomor: {{ $surat->nomor_surat_full }}
    </div>

    <div class="content">
        <table>
            <tr>
                <td>Tanggal</td>
                <td>: {{ $surat->tanggal_surat->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Kepada</td>
                <td>: {{ $surat->tujuan }}</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>: {{ $surat->perihal }}</td>
            </tr>
        </table>

        <p>Dengan hormat,</p>
        <p style="text-indent: 40px;">
            {{ $surat->perihal }}
        </p>
        <p>Demikian surat ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
    </div>

    <div class="footer">
        <div class="signature">
            <p>Jakarta, {{ $surat->tanggal_surat->format('d F Y') }}</p>
            <p>Kepala Dinas,</p>
            <div class="signature-line"></div>
            <p><strong>{{ $surat->user->name }}</strong></p>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>

</html>