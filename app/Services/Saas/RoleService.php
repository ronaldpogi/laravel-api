<?php

namespace App\Services\Saas;

use App\Models\Saas\User;
use App\Repositories\Saas\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {}

    public function attachRolesToUser(int $userId, array $roleIds): User|Collection
    {
        $user = $this->userRepository->findOrFail($userId);
        $this->userRepository->attachRoles($user, $roleIds);

        return $user->fresh(['roles']);
    }

    public function detachRolesFromUser(int $userId, array $roleIds): User|Collection
    {
        $user = $this->userRepository->findOrFail($userId);
        $this->userRepository->detachRoles($user, $roleIds);

        return $user->fresh(['roles']);
    }

    public function syncRolesForUser(int $userId, array $roleIds): User|Collection
    {
        $user = $this->userRepository->findOrFail($userId);
        $this->userRepository->syncRoles($user, $roleIds);

        return $user->fresh(['roles']);
    }
}
