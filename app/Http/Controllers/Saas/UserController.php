<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Saas\UserRequest;
use App\Http\Resources\Saas\UserResource;
use App\Repositories\Saas\UserRepository;
use App\Services\Saas\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $name = 'User';

    public function __construct(
        protected UserRepository $repository,
        protected UserService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('pageSize', 15); // default 15
        $page    = $request->input('page', 1);      // default 1

        $users = $this->repository->paginate(
            $perPage,
            ['*'],
            'page',
            $page
        );

        return response()->success(UserResource::collection($users), __('messages.fetched', ['resource' => $this->name]));
    }

    public function store(UserRequest $request): JsonResponse
    {
        $user = $this->service->create($request->validated());

        return response()->success(new UserResource($user), __('messages.created', ['resource' => $this->name]), 201);
    }

    public function show($id): JsonResponse
    {
        $user = $this->repository->find($id);

        return response()->success(new UserResource($user), __('messages.fetched', ['resource' => $this->name]));
    }

    public function update(UserRequest $request, $id): JsonResponse
    {
        $user = $this->service->update($id, $request->validated());

        return response()->success(new UserResource($user), __('messages.updated', ['resource' => $this->name]));
    }

    public function destroy($id): JsonResponse
    {
        $this->repository->delete($id);

        return response()->success([], __('messages.deleted', ['resource' => $this->name]), 204);
    }
}
