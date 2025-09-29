<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Saas\MembersResource;
use App\Http\Resources\Saas\TenantResource;
use App\Repositories\Saas\TenantRepository;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        protected TenantRepository $tenantRepository,
    ) {}

    public function tenants(): JsonResponse
    {
        $tenants = $this->tenantRepository->all();

        $count = $tenants->count();

        return response()->success([
            'tenants' => TenantResource::collection($tenants),
            'count'   => $count,
        ], __('auth.registered'), 200);
    }

    public function members(): JsonResponse
    {
        $tenants = $this->tenantRepository->all()->load('users');

        $members = $tenants->flatMap(fn ($tenant) => $tenant->users);

        $count = $members->count();

        return response()->success([
            'members' => MembersResource::collection($members),
            'count'   => $count,
        ], __('auth.registered'), 201);
    }
}
