<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Saas\OptionResource;
use App\Repositories\Saas\RoleRepository;
use Illuminate\Http\JsonResponse;

class OptionController extends Controller
{
    protected $name = 'Options';

    public function __construct(
        protected RoleRepository $roleRepository,
    ) {}

    // Role <-> Permission
    public function RoleOptions(): JsonResponse
    {
        return response()->success(OptionResource::collection($this->roleRepository->options()), __('messages.fetched', ['resource' => $this->name]));
    }
}
