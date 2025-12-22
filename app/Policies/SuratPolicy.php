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
        // All authenticated users can view the list
        return true;
    }

    /**
     * Determine if the user can view the surat
     */
    public function view(User $user, Surat $surat): bool
    {
        // All authenticated users can view individual letters
        return true;
    }

    /**
     * Determine if the user can create surats
     */
    public function create(User $user): bool
    {
        // Only admin and operator can create letters
        return in_array($user->role, ['admin', 'operator']);
    }

    /**
     * Determine if the user can update the surat
     */
    public function update(User $user, Surat $surat): bool
    {
        // Only admin and operator can update
        if (!in_array($user->role, ['admin', 'operator'])) {
            return false;
        }

        // Cannot update approved letters (immutability rule)
        if ($surat->isApproved()) {
            return false;
        }

        return true;
    }

    /**
     * Determine if the user can delete the surat
     */
    public function delete(User $user, Surat $surat): bool
    {
        // Only admin can delete
        if ($user->role !== 'admin') {
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
        // Only admin and pemimpin can approve
        if (!in_array($user->role, ['admin', 'pemimpin'])) {
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
        // Same rules as approve
        return $this->approve($user, $surat);
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
