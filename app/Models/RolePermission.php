<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class RolePermission extends Model
{
    protected $fillable = ['role', 'permission', 'enabled'];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * Available permissions with labels
     */
    public static function getAvailablePermissions(): array
    {
        return [
            'surat_keluar' => [
                'label' => 'Surat Keluar',
                'permissions' => [
                    'surat_keluar.view' => 'Lihat Surat Keluar',
                    'surat_keluar.create' => 'Buat Surat Keluar',
                    'surat_keluar.approve' => 'Approve/Reject Surat',
                ],
            ],
            'surat_masuk' => [
                'label' => 'Surat Masuk',
                'permissions' => [
                    'surat_masuk.view' => 'Lihat Surat Masuk',
                    'surat_masuk.create' => 'Buat Surat Masuk',
                    'surat_masuk.manage' => 'Kelola Surat Masuk',
                ],
            ],
            'pegawai' => [
                'label' => 'Pegawai',
                'permissions' => [
                    'pegawai.view' => 'Lihat Pegawai',
                    'pegawai.manage' => 'Kelola Pegawai',
                ],
            ],
        ];
    }

    /**
     * Configurable roles (admin always has full access)
     */
    public static function getConfigurableRoles(): array
    {
        return [
            'admin_surat_masuk' => 'Admin Surat Masuk',
            'admin_surat_keluar' => 'Admin Surat Keluar',
            'pemimpin' => 'Pemimpin',
            'operator' => 'Operator',
            'pegawai' => 'Pegawai',
        ];
    }

    /**
     * Check if role has permission (with caching)
     */
    public static function hasPermission(string $role, string $permission): bool
    {
        // Admin always has full access
        if ($role === 'admin') {
            return true;
        }

        $cacheKey = "role_permission.{$role}.{$permission}";

        return Cache::remember($cacheKey, 3600, function () use ($role, $permission) {
            $record = self::where('role', $role)
                ->where('permission', $permission)
                ->first();

            return $record ? $record->enabled : false;
        });
    }

    /**
     * Clear cache for a role
     */
    public static function clearCache(string $role): void
    {
        $permissions = collect(self::getAvailablePermissions())
            ->flatMap(fn($group) => array_keys($group['permissions']))
            ->toArray();

        foreach ($permissions as $permission) {
            Cache::forget("role_permission.{$role}.{$permission}");
        }
    }

    /**
     * Get all permissions for a role
     */
    public static function getRolePermissions(string $role): array
    {
        return self::where('role', $role)
            ->where('enabled', true)
            ->pluck('permission')
            ->toArray();
    }
}
