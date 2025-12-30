<?php

namespace App\Services;

use App\Models\TipeSurat;
use Carbon\Carbon;

class LetterNumberingService
{
    /**
     * Generate letter number based on tipe surat format
     * 
     * Format rules:
     * - If {nomor} placeholder exists: replace it with the number
     * - SK HONORER: number at the BEGINNING (e.g., 001/KPTS/XII/2024)
     * - All other types: number at the END (e.g., 500.12/DKI/001)
     */
    public function generateLetterNumber(TipeSurat $tipeSurat, Carbon $tanggalSurat): array
    {
        // Get next number (padded with zeros)
        $nextNumber = $tipeSurat->nomor_terakhir + 1;
        $paddedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

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
            // Replace {nomor} placeholder with the number
            $nomorSuratFull = str_replace('{nomor}', $paddedNumber, $nomorSuratFull);
        } else {
            // Check if this is SK HONORER type (number at beginning)
            $isSkHonorer = stripos($tipeSurat->jenis_surat, 'SK HONORER') !== false
                || stripos($tipeSurat->jenis_surat, 'SURAT KEPUTUSAN') !== false
                || stripos($tipeSurat->jenis_surat, 'SK ') === 0;

            if ($isSkHonorer) {
                // SK HONORER: Number at the BEGINNING
                $nomorSuratFull = $paddedNumber . $nomorSuratFull;
            } else {
                // All other types: Number at the END
                // Remove trailing slash if exists, add number, then add trailing slash back if needed
                $hasTrailingSlash = substr($nomorSuratFull, -1) === '/';
                if ($hasTrailingSlash) {
                    $nomorSuratFull = rtrim($nomorSuratFull, '/');
                }
                $nomorSuratFull = $nomorSuratFull . '/' . $paddedNumber;
            }
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

    /**
     * Resequence letter numbers after deletion
     * Only affects letters of same tipe_surat_id and same year
     * 
     * @param int $tipeSuratId The tipe surat ID
     * @param int $year The year to resequence
     * @return int Number of letters resequenced
     */
    public function resequenceLetterNumbers(int $tipeSuratId, int $year): int
    {
        $tipeSurat = \App\Models\TipeSurat::findOrFail($tipeSuratId);

        // Get all non-deleted letters for this type and year, ordered by nomor_urut
        $letters = \App\Models\Surat::where('tipe_surat_id', $tipeSuratId)
            ->whereYear('tanggal_surat', $year)
            ->orderBy('nomor_urut', 'asc')
            ->get();

        $resequencedCount = 0;
        $newNomorUrut = 1;

        foreach ($letters as $letter) {
            if ($letter->nomor_urut !== $newNomorUrut) {
                $oldNomorUrut = $letter->nomor_urut;
                $oldNomorFull = $letter->nomor_surat_full;

                // Generate new full number
                $paddedNumber = str_pad($newNomorUrut, 3, '0', STR_PAD_LEFT);
                $tanggalSurat = \Carbon\Carbon::parse($letter->tanggal_surat);

                // Re-generate the full letter number
                $newLetterData = $this->generateLetterNumberWithSequence(
                    $tipeSurat,
                    $tanggalSurat,
                    $newNomorUrut
                );

                $letter->nomor_urut = $newNomorUrut;
                $letter->nomor_surat_full = $newLetterData['nomor_surat_full'];
                $letter->save();

                // Log the resequencing
                \App\Models\SuratAuditLog::log(
                    $letter->id,
                    'resequenced',
                    null,
                    null,
                    "Letter resequenced from #{$oldNomorUrut} ({$oldNomorFull}) to #{$newNomorUrut} ({$letter->nomor_surat_full})"
                );

                $resequencedCount++;
            }
            $newNomorUrut++;
        }

        // Update nomor_terakhir on tipe_surat
        $tipeSurat->nomor_terakhir = $letters->count();
        $tipeSurat->save();

        return $resequencedCount;
    }

    /**
     * Generate letter number with a specific sequence number
     * Used for resequencing existing letters
     */
    public function generateLetterNumberWithSequence(TipeSurat $tipeSurat, \Carbon\Carbon $tanggalSurat, int $sequenceNumber): array
    {
        $paddedNumber = str_pad($sequenceNumber, 3, '0', STR_PAD_LEFT);
        $romanMonth = $this->toRoman($tanggalSurat->month);
        $year = $tanggalSurat->year;

        $format = $tipeSurat->format_penomoran;
        $nomorSuratFull = str_replace('{romawi}', $romanMonth, $format);
        $nomorSuratFull = str_replace('{tahun}', $year, $nomorSuratFull);

        if (strpos($nomorSuratFull, '{nomor}') !== false) {
            $nomorSuratFull = str_replace('{nomor}', $paddedNumber, $nomorSuratFull);
        } else {
            $isSkHonorer = stripos($tipeSurat->jenis_surat, 'SK HONORER') !== false
                || stripos($tipeSurat->jenis_surat, 'SURAT KEPUTUSAN') !== false
                || stripos($tipeSurat->jenis_surat, 'SK ') === 0;

            if ($isSkHonorer) {
                $nomorSuratFull = $paddedNumber . $nomorSuratFull;
            } else {
                $hasTrailingSlash = substr($nomorSuratFull, -1) === '/';
                if ($hasTrailingSlash) {
                    $nomorSuratFull = rtrim($nomorSuratFull, '/');
                }
                $nomorSuratFull = $nomorSuratFull . '/' . $paddedNumber;
            }
        }

        return [
            'nomor_urut' => $sequenceNumber,
            'nomor_surat_full' => $nomorSuratFull,
        ];
    }
}
