<?php

namespace App\Services\Saas;

use App\Models\Saas\Role as RoleModel;
use App\Models\Saas\User as UserModel;
use App\Repositories\Saas\PermissionRepository;
use App\Repositories\Saas\RoleRepository;
use App\Repositories\Saas\TenantRepository;
use App\Repositories\Saas\UserRepository;

class AccessControlService
{
    protected $name = 'Access Control';

    public function __construct(
        protected RoleRepository $roleRepository,
        protected PermissionRepository $permissionRepository,
        protected UserRepository $userRepository,
        protected TenantRepository $tenantRepository,
    ) {}

    // Role <-> Permission
    public function attachPermissionsToRole(int $roleId, array $permissionIds): RoleModel
    {
        $role = $this->roleRepository->findOrFail($roleId);
        $this->roleRepository->attachPermissions($role, $permissionIds);

        return $role->fresh(['permissions']);
    }

    public function detachPermissionsFromRole(int $roleId, array $permissionIds): RoleModel
    {
        $role = $this->roleRepository->findOrFail($roleId);
        $this->roleRepository->detachPermissions($role, $permissionIds);

        return $role->fresh(['permissions']);
    }

    public function syncPermissionsForRole(int $roleId, array $permissionIds): RoleModel
    {
        $role = $this->roleRepository->findOrFail($roleId);
        $this->roleRepository->syncPermissions($role, $permissionIds);

        return $role->fresh(['permissions']);
    }

    // User <-> Role
    public function attachRolesToUser(int $userId, array $roleIds): UserModel
    {
        $user = $this->userRepository->findOrFail($userId);
        $this->userRepository->attachRoles($user, $roleIds);

        return $user->fresh(['roles']);
    }

    public function detachRolesFromUser(int $userId, array $roleIds): UserModel
    {
        $user = $this->userRepository->findOrFail($userId);
        $this->userRepository->detachRoles($user, $roleIds);

        return $user->fresh(['roles']);
    }

    public function syncRolesForUser(int $userId, array $roleIds): UserModel
    {
        $user = $this->userRepository->findOrFail($userId);
        $this->userRepository->syncRoles($user, $roleIds);

        return $user->fresh(['roles']);
    }
}
