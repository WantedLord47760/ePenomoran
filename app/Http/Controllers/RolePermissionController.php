<?php

namespace App\Http\Controllers;

use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class RolePermissionController extends BaseController
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     * Display the permission matrix
     */
    public function index()
    {
        $roles = RolePermission::getConfigurableRoles();
        $permissionGroups = RolePermission::getAvailablePermissions();

        // Get current permissions for each role
        $currentPermissions = [];
        foreach (array_keys($roles) as $role) {
            $currentPermissions[$role] = RolePermission::getRolePermissions($role);
        }

        return view('admin.permissions', compact('roles', 'permissionGroups', 'currentPermissions'));
    }

    /**
     * Update permissions
     */
    public function update(Request $request)
    {
        $roles = array_keys(RolePermission::getConfigurableRoles());
        $allPermissions = collect(RolePermission::getAvailablePermissions())
            ->flatMap(fn($group) => array_keys($group['permissions']))
            ->toArray();

        // Get submitted permissions (checkboxes)
        $submittedPermissions = $request->input('permissions', []);

        foreach ($roles as $role) {
            foreach ($allPermissions as $permission) {
                $enabled = isset($submittedPermissions[$role][$permission]);

                RolePermission::updateOrCreate(
                    ['role' => $role, 'permission' => $permission],
                    ['enabled' => $enabled]
                );
            }

            // Clear cache for this role
            RolePermission::clearCache($role);
        }

        return redirect()->route('admin.permissions')
            ->with('success', 'Permission berhasil diperbarui.');
    }
}
