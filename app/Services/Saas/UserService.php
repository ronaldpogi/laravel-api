<?php

namespace App\Services\Saas;

use App\Models\Saas\User as UserModel;
use App\Repositories\Saas\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $name = 'User';

    public function __construct(
        protected UserRepository $userRepo,
    ) {}

    public function create(array $data): UserModel
    {
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepo->create($data);

        return $user;
    }

    public function update(string $id, array $data): Collection
    {
        if (empty($data['password'])) {
            unset($data['password']); // don't override password
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user = $this->userRepo->update($id, $data);

        return $user;
    }
}
