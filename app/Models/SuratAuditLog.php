<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratAuditLog extends Model
{
    protected $fillable = [
        'surat_id',
        'user_id',
        'action',
        'old_status',
        'new_status',
        'notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the surat that this audit log belongs to
     */
    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    /**
     * Get the user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create an audit log entry
     * 
     * @param int $suratId
     * @param string $action
     * @param string|null $oldStatus
     * @param string|null $newStatus
     * @param string|null $notes
     * @return self
     */
    public static function log(
        int $suratId,
        string $action,
        ?string $oldStatus = null,
        ?string $newStatus = null,
        ?string $notes = null
    ): self {
        return self::create([
            'surat_id' => $suratId,
            'user_id' => auth()->id(),
            'action' => $action,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $notes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
