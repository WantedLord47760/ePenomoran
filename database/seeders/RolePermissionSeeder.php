<?php

namespace Database\Seeders;

use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPermissions = [
            // Admin Surat Masuk - only surat masuk
            'admin_surat_masuk' => [
                'surat_masuk.view',
                'surat_masuk.create',
                'surat_masuk.manage',
            ],
            // Admin Surat Keluar - surat keluar + pegawai
            'admin_surat_keluar' => [
                'surat_keluar.view',
                'surat_keluar.create',
                'surat_keluar.approve',
                'pegawai.view',
                'pegawai.manage',
            ],
            // Pemimpin - all except manage
            'pemimpin' => [
                'surat_keluar.view',
                'surat_keluar.create',
                'surat_keluar.approve',
                'surat_masuk.view',
                'surat_masuk.create',
                'surat_masuk.manage',
                'pegawai.view',
                'pegawai.manage',
            ],
            // Operator - surat keluar + pegawai
            'operator' => [
                'surat_keluar.view',
                'surat_keluar.create',
                'surat_keluar.approve',
                'pegawai.view',
                'pegawai.manage',
            ],
            // Pegawai - only view and create surat keluar
            'pegawai' => [
                'surat_keluar.view',
                'surat_keluar.create',
            ],
        ];

        $allPermissions = collect(RolePermission::getAvailablePermissions())
            ->flatMap(fn($group) => array_keys($group['permissions']))
            ->toArray();

        foreach (array_keys(RolePermission::getConfigurableRoles()) as $role) {
            foreach ($allPermissions as $permission) {
                RolePermission::updateOrCreate(
                    ['role' => $role, 'permission' => $permission],
                    ['enabled' => in_array($permission, $defaultPermissions[$role] ?? [])]
                );
            }
        }

        $this->command->info('Role permissions seeded successfully!');
    }
}
