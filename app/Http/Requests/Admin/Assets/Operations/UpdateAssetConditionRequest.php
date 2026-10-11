<?php

namespace App\Http\Requests\Admin\Assets\Operations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetConditionRequest extends FormRequest
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

            'inspection_date' => [
                'required',
                'date',
            ],

            'condition' => [
                'required',
                Rule::in([
                    'Excellent',
                    'Good',
                    'Fair',
                    'Poor',
                    'Critical',
                ]),
            ],

            'condition_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'structural_condition' => [
                'nullable',
                'string',
                'max:100',
            ],

            'electrical_condition' => [
                'nullable',
                'string',
                'max:100',
            ],

            'mechanical_condition' => [
                'nullable',
                'string',
                'max:100',
            ],

            'safety_condition' => [
                'nullable',
                'string',
                'max:100',
            ],

            'estimated_repair_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'recommended_action' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'inspector_id' => [
                'nullable',
                'integer',
                'exists:users,id',
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

            'inspection_date.required' =>
                'Please select an inspection date.',

            'condition.required' =>
                'Please select the asset condition.',

            'condition_score.max' =>
                'Condition score cannot exceed 100.',

            'estimated_repair_cost.min' =>
                'Repair cost cannot be negative.',

            'inspector_id.exists' =>
                'The selected inspector does not exist.',
        ];
    }
}