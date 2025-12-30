<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nip',
        'no_hp',
        'jabatan',
        'pangkat',
        'bidang',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function surats()
    {
        return $this->hasMany(Surat::class);
    }

    /**
     * Check if user is pegawai
     */
    public function isPegawai(): bool
    {
        return $this->role === 'pegawai';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is operator
     */
    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    /**
     * Check if user is pemimpin
     */
    public function isPemimpin(): bool
    {
        return $this->role === 'pemimpin';
    }

    /**
     * Check if user can approve letters
     */
    public function canApprove(): bool
    {
        return in_array($this->role, ['admin', 'operator']);
    }

    /**
     * Get bidang abbreviation mapping for search
     */
    public static function getBidangAbbreviations(): array
    {
        return [
            'TIK' => 'Bidang TIK dan Persandian',
            'IKPS' => 'Bidang IKPS',
            'Aptika' => 'Bidang Aptika',
            'Sek' => 'Sekretariat',
            'Sekre' => 'Sekretariat',
        ];
    }

    /**
     * Get all bidang options
     */
    public static function getBidangOptions(): array
    {
        return [
            'Sekretariat',
            'Bidang TIK dan Persandian',
            'Bidang IKPS',
            'Bidang Aptika',
        ];
    }
}

