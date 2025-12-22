<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipeSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['jenis_surat' => 'SURAT KELUAR', 'format_penomoran' => '500.12/DKI/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SPT', 'format_penomoran' => '000.1.2.3/SPT/', 'nomor_terakhir' => 2],
            ['jenis_surat' => 'UNDANGAN', 'format_penomoran' => '500.1.2/DKI/UND/{romawi}/{tahun}/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SK HONORER', 'format_penomoran' => '/KPTS/{romawi}/{tahun}', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'NOTA DINAS', 'format_penomoran' => '000.1/DKI/ND/{tahun}/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SURAT CUTI SAKIT', 'format_penomoran' => '800.1.11.2/DKI/{romawi}/{tahun}/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SURAT CUTI BERSALIN', 'format_penomoran' => '800.1.11.3/DKI/{romawi}/{tahun}/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SURAT CUTI TAHUNAN', 'format_penomoran' => '800.1.11.4/DKI/{romawi}/{tahun}/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SURAT CUTI ALASAN PENTING', 'format_penomoran' => '800.1.11.5/DKI/{romawi}/{tahun}/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SURAT CUTI BESAR', 'format_penomoran' => '800.1.11.6/DKI/{romawi}/{tahun}/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SURAT CUTI DILUAR TANGGUNGAN', 'format_penomoran' => '800.1.11.7/DKI/{romawi}/{tahun}/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SURAT IZIN PEGAWAI', 'format_penomoran' => '800.1.11/DKI/{romawi}/{tahun}/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SURAT DARI PA KE PP/POKJA', 'format_penomoran' => '000.3.1/DKI/{tahun}/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SPPBJ (Nilai 0 s/d 200jt)', 'format_penomoran' => '000.3.2/DKI/SPPBJ/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SPPBJ (Nilai > 200jt)', 'format_penomoran' => '000.3.3/DKI/SPPBJ/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SPMK (KONSULTANSI 0 s/d 100Juta)', 'format_penomoran' => '000.3.2/DKI/SPMK/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SPMK (KONSULTANSI > 100Juta)', 'format_penomoran' => '000.3.3/DKI/SPMK/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'SPK (Konstruksi > 200jt)', 'format_penomoran' => '000.3.2/DKI/SPK/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'S-PER (Lainnya/Konstruksi > 200jt)', 'format_penomoran' => '000.3.3/DKI/S-PER/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'KONTRAK SP - ECATALOG', 'format_penomoran' => '000.3.2/DKI/SP-ECATALOG/', 'nomor_terakhir' => 0],
            ['jenis_surat' => 'KONTRAK SP - NON ECATALOG', 'format_penomoran' => '000.3.2/DKI/SP/', 'nomor_terakhir' => 0],
        ];

        foreach ($data as $item) {
            \App\Models\TipeSurat::create($item);
        }
    }
}
