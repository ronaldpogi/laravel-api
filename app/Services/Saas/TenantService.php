<?php

namespace App\Services\Saas;

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

    public function create(array $data)
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
}
