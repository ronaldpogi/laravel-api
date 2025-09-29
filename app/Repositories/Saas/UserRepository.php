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

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }
}
