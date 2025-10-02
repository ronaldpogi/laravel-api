<?php

namespace App\Events\Saas;

use App\Models\Saas\Tenant;
use App\Models\Saas\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TenantRegistered
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Tenant $tenant,
        public User $user
    ) {}
}
