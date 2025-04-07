<?php

namespace App\Policies\V1;

use App\Models\User;
use App\Permissions\V1\Abilities;

class UserPolicy
{
    public function delete(User $user, User $model)
    {
        return $user->tokenCan(Abilities::DeleteUser) && $user->id !== $model->id;
    }

    public function store(User $user)
    {
        return $user->tokenCan(Abilities::CreateUser);
    }

    public function update(User $user, User $model)
    {
        if ($user->tokenCan(Abilities::UpdateUser)) {
            return true;
        }

        return $user->id === $model->id;
    }
}
