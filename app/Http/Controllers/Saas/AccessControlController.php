<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Saas\AttachPermissionsRequest;
use App\Http\Requests\Saas\AttachRolesRequest;
use App\Http\Resources\Saas\RoleResource;
use App\Http\Resources\Saas\UserResource;
use App\Services\Saas\AccessControlService;
use Illuminate\Http\JsonResponse;

class AccessControlController extends Controller
{
    protected $name = 'Access Control';

    public function __construct(
        protected AccessControlService $service,
    ) {}

    // Role <-> Permission
    public function attachPermissionsToRole(AttachPermissionsRequest $request, int $role): JsonResponse
    {
        $updated = $this->service->attachPermissionsToRole($role, $request->validated('permission_ids'));

        return response()->success(new RoleResource($updated->load('permissions')), __('messages.updated', ['resource' => $this->name]));
    }

    public function detachPermissionsFromRole(AttachPermissionsRequest $request, int $role): JsonResponse
    {
        $updated = $this->service->detachPermissionsFromRole($role, $request->validated('permission_ids'));

        return response()->success(new RoleResource($updated->load('permissions')), __('messages.updated', ['resource' => $this->name]));
    }

    public function syncPermissionsForRole(AttachPermissionsRequest $request, int $role): JsonResponse
    {
        $updated = $this->service->syncPermissionsForRole($role, $request->validated('permission_ids'));

        return response()->success(new RoleResource($updated->load('permissions')), __('messages.updated', ['resource' => $this->name]));
    }

    // User <-> Role
    public function attachRolesToUser(AttachRolesRequest $request, int $user): JsonResponse
    {
        $updated = $this->service->attachRolesToUser($user, $request->validated('role_ids'));

        return response()->success(new UserResource($updated->load('roles')), __('messages.updated', ['resource' => $this->name]));
    }

    public function detachRolesFromUser(AttachRolesRequest $request, int $user): JsonResponse
    {
        $updated = $this->service->detachRolesFromUser($user, $request->validated('role_ids'));

        return response()->success(new UserResource($updated->load('roles')), __('messages.updated', ['resource' => $this->name]));
    }

    public function syncRolesForUser(AttachRolesRequest $request, int $user): JsonResponse
    {
        $updated = $this->service->syncRolesForUser($user, $request->validated('role_ids'));

        return response()->success(new UserResource($updated->load('roles')), __('messages.updated', ['resource' => $this->name]));
    }
}
