<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuildingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $buildingId = $this->route('building');

        return [

            'mall_id' => [
                'required',
                'integer',
                'exists:malls,id',
            ],

            'building_code' => [
                'required',
                'string',
                'max:100',

                Rule::unique(
                    'buildings',
                    'building_code'
                )->ignore($buildingId),
            ],

            'building_name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'total_floors' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'total_units' => [
                'nullable',
                'integer',
                'min:0',
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

            'mall_id.required' =>
                'Please select a mall.',

            'mall_id.exists' =>
                'Selected mall does not exist.',

            'building_code.required' =>
                'Building code is required.',

            'building_code.unique' =>
                'This building code already exists.',

            'building_name.required' =>
                'Building name is required.',

            'status.in' =>
                'Invalid building status.',
        ];
    }
}