<?php

namespace App\Repositories\Saas;

use App\Models\Saas\Role;
use App\Repositories\TModel;
use Illuminate\Support\Collection;

class RoleRepository extends TModel
{
    public function __construct(Role $role)
    {
        parent::__construct($role);
    }

    public function permissions(Role $role): Collection
    {
        return $role->permissions()->get();
    }

    public function attachPermissions(Role $role, array $permissionIds): void
    {
        $role->permissions()->attach($permissionIds);
    }

    public function detachPermissions(Role $role, array $permissionIds): void
    {
        $role->permissions()->detach($permissionIds);
    }

    public function syncPermissions(Role $role, array $permissionIds, bool $detaching = true): void
    {
        $role->permissions()->sync($permissionIds, $detaching);
    }
}
