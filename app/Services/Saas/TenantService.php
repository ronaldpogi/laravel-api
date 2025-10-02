<?php

namespace App\Services\Saas;

use App\Models\Saas\Tenant as TenantModel;
use App\Repositories\Saas\TenantRepository;
use App\Repositories\Saas\UserRepository;
use Illuminate\Support\Facades\Hash;

class TenantService
{
    protected $name = 'Tenant';

    public function __construct(
        protected TenantRepository $tenantRepository,
        protected UserRepository $userRepository
    ) {}

    public function create(array $data): TenantModel
    {
        $tenant['name']      = $data['name'];
        $tenant['address']   = $data['address'];
        $tenant['subdomain'] = $data['subdomain'];
        $tenant['settings']  = $data['settings'];

        $tenant = $this->tenantRepository->create($tenant);

        $user['tenant_id'] = $tenant->id;
        $user['email']     = $data['email'];
        $user['phone']     = $data['phone'];
        $user['password']  = Hash::make($data['password']);

        $this->userRepository->create($user);

        return $tenant;
    }

    public function tenantsCount(): array
    {
        $tenants = $this->tenantRepository->all();

        $count = $tenants->count();

        return [
            'count' => $count,
            'tenants' => $tenants,
        ];
    }

    public function membersCount(): array
    {
        $tenants = $this->tenantRepository->all()->load('users');

        $members = $tenants->flatMap(fn ($tenant) => $tenant->users);

        $count = $members->count();

        return [
            'count' => $count,
            'members' => $members,
        ];
    }
}
