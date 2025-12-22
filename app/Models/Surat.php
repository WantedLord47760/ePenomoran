<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Surat extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'tipe_surat_id',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'nomor_urut',
        'nomor_surat_full',
        'status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tipeSurat()
    {
        return $this->belongsTo(TipeSurat::class, 'tipe_surat_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function auditLogs()
    {
        return $this->hasMany(SuratAuditLog::class);
    }

    /**
     * Check if letter is approved
     */
    public function isApproved(): bool
    {
        return $this->status === '1';
    }

    /**
     * Check if letter can be modified
     */
    public function canBeModified(): bool
    {
        return $this->status !== '1';
    }
}
