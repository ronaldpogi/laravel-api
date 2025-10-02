<?php

namespace App\Listeners\Saas;

use App\Enums\Role;
use App\Events\Saas\TenantRegistered;
use App\Models\Saas\Tenant;
use App\Models\Saas\User;
use App\Repositories\Saas\PermissionRepository;
use App\Repositories\Saas\RoleRepository;
use App\Services\Saas\RoleService;
use Illuminate\Support\Facades\DB;

class BootstrapTenant
{
    public function __construct(
        protected RoleRepository $roleRepository,
        protected PermissionRepository $permissionRepository
    ) {}

    public function handle(TenantRegistered $event): void
    {
        $tenant = $event->tenant;
        $user   = $event->user;

        DB::transaction(function () use ($tenant, $user) {

            // Seed Permissions from Routes
            foreach (\Route::getRoutes() as $route) {
                if ($name = $route->getName()) {
                    $this->permissionRepository->firstOrCreate([
                        'name'      => $name,
                        'tenant_id' => $tenant->id,
                    ]);
                }
            }

            // Seed Roles
            foreach (Role::cases() as $roleCase) {
                $this->roleRepository->firstOrCreate([
                    'name'      => $roleCase->value,
                    'tenant_id' => $tenant->id,
                ]);
            }

            // Get the tenant role as a model
            $tenantRole = $this->roleRepository->findWhere([
                'tenant_id' => $tenant->id,
                'name'      => Role::TENANT->value,
            ])->first();

            if ($tenantRole) {
                // Attach all permissions to this role
                $tenantPermissions = $this->permissionRepository
                    ->findWhere(['tenant_id' => $tenant->id])
                    ->pluck('id')
                    ->toArray();

                $tenantRole->permissions()->sync($tenantPermissions);

                // Assign role to user
                $user->roles()->sync([$tenantRole->id]);
            }

        });
    }
}
