<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'is_manager' => 'sometimes|boolean',
        ];

        $user = $this->user();
        if ($user && $user->tokenCan(Abilities::CreateUser)) {
            $rules['is_manager'] = 'sometimes|boolean';
        } else {
            $rules['is_manager'] = 'prohibited';
        }

        return $rules;
    }
}