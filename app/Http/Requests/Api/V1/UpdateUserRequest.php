<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        $rules = [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:8|confirmed',
            'is_manager' => 'sometimes|boolean',
        ];

        $currentUser = $this->user();
        if (!$currentUser->tokenCan(Abilities::UpdateUser)) {
            $rules['is_manager'] = 'prohibited';
        }

        return $rules;
    }
}