<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $unitTypeId = $this->route('unit_type');

        return [

            'type_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('unit_types', 'type_name')
                    ->ignore($unitTypeId),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'integer',
                'in:0,1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'type_name.required' =>
                'Unit type name is required.',

            'type_name.unique' =>
                'This unit type already exists.',

            'status.required' =>
                'Please select a status.',

            'status.in' =>
                'Invalid status selected.',
        ];
    }
}