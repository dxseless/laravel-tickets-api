<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\UserFilter;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;

class UserController extends ApiController
{
    public function index(UserFilter $filters)
    {
        if ($this->include('tickets')) {
            return UserResource::collection(
                User::filter($filters)
                    ->with('tickets')
                    ->get()
            );
        }

        return UserResource::collection(User::filter($filters)->get());
    }

    public function store(StoreUserRequest $request)
    {
        //
    }

    public function show(User $user)
    {
        if ($this->include('tickets')) {
            return new UserResource($user->load('tickets'));
        }

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }
}
