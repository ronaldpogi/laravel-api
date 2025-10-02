<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Saas\AttachPermissionsRequest;
use App\Http\Resources\Saas\RoleResource;
use App\Services\Saas\PermissionService;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    protected $name = 'Permission';

    public function __construct(
        protected PermissionService $permissionService,
    ) {}

    // Role <-> Permission
    public function attachPermissionsToRole(AttachPermissionsRequest $request, int $role): JsonResponse
    {
        $updated = $this->permissionService->attachPermissionsToRole($role, $request->validated('permission_ids'));

        return response()->success(new RoleResource($updated->load('permissions')), __('messages.updated', ['resource' => $this->name]));
    }

    public function detachPermissionsFromRole(AttachPermissionsRequest $request, int $role): JsonResponse
    {
        $updated = $this->permissionService->detachPermissionsFromRole($role, $request->validated('permission_ids'));

        return response()->success(new RoleResource($updated->load('permissions')), __('messages.updated', ['resource' => $this->name]));
    }

    public function syncPermissionsForRole(AttachPermissionsRequest $request, int $role): JsonResponse
    {
        $updated = $this->permissionService->syncPermissionsForRole($role, $request->validated('permission_ids'));

        return response()->success(new RoleResource($updated->load('permissions')), __('messages.updated', ['resource' => $this->name]));
    }
}
