<?php

namespace App\Http\Requests\Admin\Assets\Operations;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetLifecycleRequest extends FormRequest
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

            'lifecycle_stage' => [
                'required',
                'in:Planning,Acquisition,Active,Maintenance,Renewal,Replacement,Disposal,Disposed',
            ],

            'lifecycle_status' => [
                'required',
                'in:Planned,In Progress,Active,Due,Completed,Cancelled',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High,Critical',
            ],

            'acquisition_date' => [
                'nullable',
                'date',
            ],

            'commissioning_date' => [
                'nullable',
                'date',
                'after_or_equal:acquisition_date',
            ],

            'expected_life_years' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'remaining_life_years' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'planned_renewal_date' => [
                'nullable',
                'date',
            ],

            'planned_replacement_date' => [
                'nullable',
                'date',
            ],

            'actual_replacement_date' => [
                'nullable',
                'date',
            ],

            'acquisition_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'current_book_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'estimated_replacement_cost' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'residual_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'depreciation_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'lifecycle_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'recommended_action' => [
                'nullable',
                'in:Continue Operation,Monitor,Repair,Upgrade,Renew,Replace,Dispose',
            ],

            'last_reviewed_at' => [
                'nullable',
                'date',
            ],

            'next_review_date' => [
                'nullable',
                'date',
            ],

            'responsible_user_id' => [
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