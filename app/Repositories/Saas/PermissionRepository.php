<?php

namespace App\Repositories\Saas;

use App\Models\Saas\Permission;
use App\Repositories\TModel;

class PermissionRepository extends TModel
{
    public function __construct(Permission $permission)
    {
        parent::__construct($permission);
    }
}
