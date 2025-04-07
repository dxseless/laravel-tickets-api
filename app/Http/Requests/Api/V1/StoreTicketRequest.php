<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class StoreTicketRequest extends BaseTicketRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userIdAttribute = $this->routeIs('tickets.store')
            ? 'data.relationships.user.data.id'
            : 'user';

        $rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:Active,Completed,Hold,Cancelled',
            $userIdAttribute => 'required|integer|exists:users,id',
        ];

        $user = $this->user();

        if ($user && $user->tokenCan(Abilities::CreateOwnTicket)) {
            $rules[$userIdAttribute] .= '|size:'.$user->id;
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->routeIs('users.tickets.store')) {
            $this->merge([
                'user' => $this->route('user'),
            ]);
        }
    }

    public function messages()
    {
        return [
            'data.attributes.status' => 'The data.attributes.status is invalid. Please try Completed,Hold,Active or Cancelled',
        ];
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
