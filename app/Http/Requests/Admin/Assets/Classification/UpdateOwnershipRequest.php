<?php

namespace App\Http\Requests\Admin\Assets\Classification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOwnershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->can('ownerships.edit');
    }

    public function rules(): array
    {
        $ownershipId = $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('ownerships', 'code')
                    ->ignore($ownershipId),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                Rule::in(['Active', 'Inactive']),
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'sort_order' => 'sort order',
        ];
    }
}