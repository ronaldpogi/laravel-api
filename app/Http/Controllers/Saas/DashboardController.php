<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Saas\MembersResource;
use App\Http\Resources\Saas\TenantResource;
use App\Repositories\Saas\TenantRepository;
use App\Services\Saas\TenantService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        protected TenantRepository $tenantRepository,
        protected TenantService $tenantService
    ) {}

    public function tenants(): JsonResponse
    {

        $tenants = $this->tenantService->tenantsCount();

        return response()->success([
            'tenants' => TenantResource::collection($tenants['tenants']),
            'count'   => $tenants['count'],
        ], __('auth.registered'), 200);
    }

    public function members(): JsonResponse
    {
        $members = $this->tenantService->membersCount();

        return response()->success([
            'members' => MembersResource::collection($members['members']),
            'count'   => $members['count'],
        ], __('auth.registered'), 201);
    }
}
