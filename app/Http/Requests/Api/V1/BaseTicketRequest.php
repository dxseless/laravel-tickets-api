<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseTicketRequest extends FormRequest
{
    public function messages()
    {
        return [
            'data.attributes.status' => 'The data.attributes.status is invalid. Please try Completed,Hold,Active or Cancelled',
        ];
    }

    public function mappedAttributes($optionalAttributes = [])
    {
        $attributeMap = array_merge([
            'data.attributes.title' => 'title',
            'data.attributes.description' => 'description',
            'data.attributes.status' => 'status',
            'data.attributes.createdAt' => 'created_at',
            'data.attributes.updatedAt' => 'updated_at',
            'data.relationships.user.data.id' => 'user_id',
        ], $optionalAttributes);

        $attributesToUpdate = [];

        foreach ($attributeMap as $key => $attribute) {
            if ($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
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
