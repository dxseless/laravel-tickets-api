<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

class StoreTicketRequest extends BaseTicketRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userIdAttribute = $this->routeIs('tickets.store') ?
            'data.relationships.user.data.id' :
            'user';

        $rules = [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:Active,Completed,Hold,Cancelled',
            $userIdAttribute => 'required|integer|exists:users,id',
        ];

        $user = $this->user();

        if ($user->tokenCan(Abilities::CreateOwnTicket)) {
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
}
