<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class UpdateTicketRequest extends BaseTicketRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'data.attributes.title' => 'sometimes|string',
            'data.attributes.description' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:Active,Completed,Hold,Cancelled',
            'data.relationships.user.data.id' => 'sometimes|integer',
        ];

        $user = $this->user();

        if ($user && $user->tokenCan(Abilities::UpdateOwnTicket)) {
            $rules['data.relationships.user.data.id'] = 'prohibited';
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
