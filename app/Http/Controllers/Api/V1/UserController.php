<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;

class UserController extends ApiController
{
    public function index()
    {
        if ($this->include('ticket')) {
            return UserResource::collection(User::with('ticket')->get());
        }

        return UserResource::collection(User::all());
    }

    public function store(StoreUserRequest $request)
    {
        //
    }

    public function show(User $user)
    {
        if ($this->include('ticket')) {
            return new UserResource($user->load('ticket'));
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
