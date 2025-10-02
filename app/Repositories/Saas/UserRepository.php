<?php

namespace App\Repositories\Saas;

use App\Models\Saas\User;
use App\Repositories\TModel;

class UserRepository extends TModel
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function attachRoles(User $user, array $roleIds): void
    {
        $user->roles()->attach($roleIds);
    }

    public function detachRoles(User $user, array $roleIds): void
    {
        $user->roles()->detach($roleIds);
    }

    public function syncRoles(User $user, array $roleIds, bool $detaching = true): void
    {
        $user->roles()->sync($roleIds, $detaching);
    }
}
