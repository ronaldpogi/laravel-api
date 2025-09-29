<?php

namespace App\Services\Saas;

use App\Repositories\Saas\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $name = 'User';

    public function __construct(
        protected UserRepository $userRepo,
    ) {}

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepo->create($data);

        return $user;
    }

    public function update($id, array $data)
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
