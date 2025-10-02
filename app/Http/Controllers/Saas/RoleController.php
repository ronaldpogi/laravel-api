<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Saas\AttachRolesRequest;
use App\Http\Resources\Saas\UserResource;
use App\Services\Saas\RoleService;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    protected $name = 'Role';

    public function __construct(
        protected RoleService $roleService,
    ) {}

    // User <-> Role
    public function attachRolesToUser(AttachRolesRequest $request, int $user): JsonResponse
    {
        $updated = $this->roleService->attachRolesToUser($user, $request->validated('role_ids'));

        return response()->success(new UserResource($updated->load('roles')), __('messages.updated', ['resource' => $this->name]));
    }

    public function detachRolesFromUser(AttachRolesRequest $request, int $user): JsonResponse
    {
        $updated = $this->roleService->detachRolesFromUser($user, $request->validated('role_ids'));

        return response()->success(new UserResource($updated->load('roles')), __('messages.updated', ['resource' => $this->name]));
    }

    public function syncRolesForUser(AttachRolesRequest $request, int $user): JsonResponse
    {
        $updated = $this->roleService->syncRolesForUser($user, $request->validated('role_ids'));

        return response()->success(new UserResource($updated->load('roles')), __('messages.updated', ['resource' => $this->name]));
    }
}
