<?php

namespace App\Policies;

use App\Models\Surat;
use App\Models\User;

class SuratPolicy
{
    /**
     * Determine if the user can view any surats
     */
    public function viewAny(User $user): bool
    {
        // Exclude admin_surat_masuk - they should not see surat keluar
        return $user->role !== 'admin_surat_masuk';
    }

    /**
     * Determine if the user can view the surat
     */
    public function view(User $user, Surat $surat): bool
    {
        // Exclude admin_surat_masuk - they should not see surat keluar
        return $user->role !== 'admin_surat_masuk';
    }

    /**
     * Determine if the user can create surats
     */
    public function create(User $user): bool
    {
        // Admin, admin_surat_keluar, operator, and pegawai can create letters
        return in_array($user->role, ['admin', 'admin_surat_keluar', 'operator', 'pegawai']);
    }

    /**
     * Determine if the user can update the surat
     */
    public function update(User $user, Surat $surat): bool
    {
        // Admin, admin_surat_keluar, and operator can update any letter
        if (in_array($user->role, ['admin', 'admin_surat_keluar', 'operator'])) {
            return true;
        }

        // Pegawai can only update their own rejected letters (for resubmission)
        if ($user->role === 'pegawai') {
            return $surat->user_id === $user->id && $surat->status === '2';
        }

        return false;
    }

    /**
     * Determine if the user can delete the surat
     */
    public function delete(User $user, Surat $surat): bool
    {
        // Only admin and admin_surat_keluar can delete
        if (!in_array($user->role, ['admin', 'admin_surat_keluar'])) {
            return false;
        }

        // Cannot delete approved letters (legal requirement)
        if ($surat->isApproved()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the user can approve the surat
     */
    public function approve(User $user, Surat $surat): bool
    {
        // Admin, admin_surat_keluar, pemimpin, and operator can approve
        if (!in_array($user->role, ['admin', 'admin_surat_keluar', 'pemimpin', 'operator'])) {
            return false;
        }

        // Can only approve pending letters
        return $surat->status === '0';
    }

    /**
     * Determine if the user can reject the surat
     */
    public function reject(User $user, Surat $surat): bool
    {
        // Same roles as approve
        if (!in_array($user->role, ['admin', 'admin_surat_keluar', 'pemimpin', 'operator'])) {
            return false;
        }

        // Can only reject pending letters
        return $surat->status === '0';
    }

    /**
     * Determine if the user can resubmit a rejected letter
     */
    public function resubmit(User $user, Surat $surat): bool
    {
        // Pegawai can resubmit their own rejected letters
        if ($user->role === 'pegawai' && $surat->user_id === $user->id && $surat->status === '2') {
            return true;
        }

        // Admin and operator can resubmit any rejected letter
        return in_array($user->role, ['admin', 'operator']) && $surat->status === '2';
    }

    /**
     * Determine if the user can print the surat
     */
    public function print(User $user, Surat $surat): bool
    {
        // Can only print approved letters
        return $surat->isApproved();
    }
}

