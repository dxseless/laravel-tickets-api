<?php

namespace App\Http\Requests\Api\V1;

class ReplaceTicketRequest extends BaseTicketRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
        return [
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:Completed,Hold,Active,Cancelled',
            'data.relationships.user.data.id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'data.attributes.status' => 'The data.attributes.status is invalid. Please try Completed,Hold,Active or Cancelled',
        ];
    }
}
