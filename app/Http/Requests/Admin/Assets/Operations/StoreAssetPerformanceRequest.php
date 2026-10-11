<?php

namespace App\Http\Requests\Admin\Assets\Operations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssetPerformanceRequest extends FormRequest
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

            'performance_date' => [
                'required',
                'date',
            ],

            'performance_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'availability_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'utilization_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'efficiency_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'downtime_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'operating_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'maintenance_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'operating_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'revenue_generated' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'target_value' => [
                'nullable',
                'numeric',
            ],

            'actual_value' => [
                'nullable',
                'numeric',
            ],

            'performance_status' => [
                'required',
                Rule::in([
                    'Excellent',
                    'Good',
                    'Average',
                    'Poor',
                    'Critical',
                ]),
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
}