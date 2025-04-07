<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\UserFilter;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Permissions\V1\Abilities;
use Illuminate\Auth\Access\AuthorizationException;

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
        try {
            $this->isAble('store', User::class);

            $user = User::create($request->all());

            return new UserResource($user);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create this resource', 401);
        }
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
        try {
            $this->isAble('update', $user);

            $user->update($request->all());

            return new UserResource($user);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update this resource', 401);
        }
    }

    public function destroy(User $user)
    {
        try {
            $this->isAble('delete', $user);

            $user->delete();

            return $this->ok('User successfully deleted');
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to delete this resource', 401);
        }
    }
}