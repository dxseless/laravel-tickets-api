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
        $currentUser = $this->user();

        $rules = [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.($user ? $user->id : 'NULL'),
            'password' => 'sometimes|min:8|confirmed',
            'is_manager' => 'sometimes|boolean',
        ];

        if ($currentUser && ! $currentUser->tokenCan(Abilities::UpdateUser)) {
            $rules['is_manager'] = 'prohibited';
        }

        return $rules;
    }

    public function bodyParameters()
    {
        return [
            'data.attributes.title' => [
                'description' => 'Title of the ticket',
                'example' => 'Server down',
            ],
            'data.attributes.description' => [
                'description' => 'Detailed description of the issue',
                'example' => 'The main server is not responding to ping',
            ],
            'data.attributes.status' => [
                'description' => 'Status of the ticket',
                'example' => 'Active',
            ],
            'data.relationships.user.data.id' => [
                'description' => 'ID of the user who owns the ticket',
                'example' => 1,
            ],
        ];
    }
}
