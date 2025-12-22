<?php

namespace App\Services;

use App\Models\TipeSurat;
use Carbon\Carbon;

class LetterNumberingService
{
    /**
     * Generate letter number based on tipe surat format
     */
    public function generateLetterNumber(TipeSurat $tipeSurat, Carbon $tanggalSurat): array
    {
        // Get next number
        $nextNumber = $tipeSurat->nomor_terakhir + 1;

        // Get month in Roman numerals
        $romanMonth = $this->toRoman($tanggalSurat->month);

        // Get year
        $year = $tanggalSurat->year;

        // Parse format
        $format = $tipeSurat->format_penomoran;

        // Replace placeholders
        $nomorSuratFull = str_replace('{romawi}', $romanMonth, $format);
        $nomorSuratFull = str_replace('{tahun}', $year, $nomorSuratFull);

        // Check if {nomor} placeholder exists
        if (strpos($nomorSuratFull, '{nomor}') !== false) {
            $nomorSuratFull = str_replace('{nomor}', $nextNumber, $nomorSuratFull);
        } else {
            // Append number at the end
            $nomorSuratFull = $nextNumber . $nomorSuratFull;
        }

        return [
            'nomor_urut' => $nextNumber,
            'nomor_surat_full' => $nomorSuratFull,
        ];
    }

    /**
     * Convert number to Roman numerals
     */
    public function toRoman(int $number): string
    {
        $map = [
            12 => 'XII',
            11 => 'XI',
            10 => 'X',
            9 => 'IX',
            8 => 'VIII',
            7 => 'VII',
            6 => 'VI',
            5 => 'V',
            4 => 'IV',
            3 => 'III',
            2 => 'II',
            1 => 'I',
        ];

        return $map[$number] ?? '';
    }
}
