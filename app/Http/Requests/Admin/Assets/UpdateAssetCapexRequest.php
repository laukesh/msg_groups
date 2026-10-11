<?php

namespace App\Http\Requests\Admin\Assets;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetCapexRequest extends FormRequest
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

            'capex_code' => [
                'nullable',
                'string',
                'max:100',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'priority' => [
                'required',
                'in:low,medium,high,critical',
            ],

            'status' => [
                'required',
                'in:planned,approved,in_progress,completed,cancelled',
            ],

            'planned_date' => [
                'nullable',
                'date',
            ],

            'start_date' => [
                'nullable',
                'date',
                'after_or_equal:planned_date',
            ],

            'completion_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'estimated_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'approved_budget' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'actual_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'vendor_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ];
    }
}