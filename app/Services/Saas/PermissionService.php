<?php

namespace App\Services\Saas;

use App\Models\Saas\Role;
use App\Repositories\Saas\RoleRepository;
use Illuminate\Database\Eloquent\Collection;

class PermissionService
{
    public function __construct(
        protected RoleRepository $roleRepository,
    ) {}

    // Role <-> Permission
    public function attachPermissionsToRole(int $roleId, array $permissionIds): Role | Collection
    {
        $role = $this->roleRepository->findOrFail($roleId);
        $this->roleRepository->attachPermissions($role, $permissionIds);

        return $role->fresh(['permissions']);
    }

    public function detachPermissionsFromRole(int $roleId, array $permissionIds): Role | Collection
    {
        $role = $this->roleRepository->findOrFail($roleId);
        $this->roleRepository->detachPermissions($role, $permissionIds);

        return $role->fresh(['permissions']);
    }

    public function syncPermissionsForRole(int $roleId, array $permissionIds): Role | Collection
    {
        $role = $this->roleRepository->findOrFail($roleId);
        $this->roleRepository->syncPermissions($role, $permissionIds);

        return $role->fresh(['permissions']);
    }


}
