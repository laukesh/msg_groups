<?php

namespace App\Http\Requests\Admin\Assets\Operations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOccupancyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'asset_id' => [
                'required',
                'integer',
                'exists:assets,id',
            ],

            'occupancy_status' => [
                'required',
                Rule::in([
                    'occupied',
                    'vacant',
                    'partially_occupied',
                    'under_maintenance',
                    'reserved',
                ]),
            ],

            'occupancy_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'tenant_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'lease_start_date' => [
                'nullable',
                'date',
            ],

            'lease_end_date' => [
                'nullable',
                'date',
                'after_or_equal:lease_start_date',
            ],

            'monthly_rent' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'asset_id.required' =>
                'Please select an asset.',

            'asset_id.exists' =>
                'The selected asset does not exist.',

            'occupancy_status.required' =>
                'Please select occupancy status.',

            'occupancy_percentage.required' =>
                'Please enter occupancy percentage.',

            'occupancy_percentage.max' =>
                'Occupancy percentage cannot exceed 100%.',

            'lease_end_date.after_or_equal' =>
                'Lease end date must be after or equal to lease start date.',
        ];
    }
}