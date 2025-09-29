<?php

namespace App\Repositories\Saas;

use App\Models\Saas\Tenant;
use App\Repositories\TModel;

class TenantRepository extends TModel
{
    public function __construct(Tenant $tenant)
    {
        parent::__construct($tenant);
    }

    public function findBySubdomain(string $subdomain)
    {
        return $this->model->where('subdomain', $subdomain)->first();
    }
}
