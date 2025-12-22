<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeSurat extends Model
{
    protected $fillable = [
        'jenis_surat',
        'format_penomoran',
        'nomor_terakhir',
        'last_reset_year',
    ];

    public function surats()
    {
        return $this->hasMany(Surat::class, 'tipe_surat_id');
    }
}
