<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuks';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'jenis_surat',
        'judul_surat',
        'isi_surat',
        'disposisi_pimpinan',
        'tanggal_disposisi',
        'status_tindak_lanjut',
        'posisi_tindak_lanjut',
        'user_id',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_disposisi' => 'date',
    ];

    /**
     * Get the user who created this surat masuk
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return match ($this->status_tindak_lanjut) {
            'pending' => 'Pending',
            'proses' => 'Dalam Proses',
            'selesai' => 'Selesai',
            default => 'Unknown',
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status_tindak_lanjut) {
            'pending' => 'bg-warning text-dark',
            'proses' => 'bg-info',
            'selesai' => 'bg-success',
            default => 'bg-secondary',
        };
    }

    /**
     * Check if status is pending
     */
    public function isPending(): bool
    {
        return $this->status_tindak_lanjut === 'pending';
    }

    /**
     * Check if status is in process
     */
    public function isProses(): bool
    {
        return $this->status_tindak_lanjut === 'proses';
    }

    /**
     * Check if status is completed
     */
    public function isSelesai(): bool
    {
        return $this->status_tindak_lanjut === 'selesai';
    }
}
