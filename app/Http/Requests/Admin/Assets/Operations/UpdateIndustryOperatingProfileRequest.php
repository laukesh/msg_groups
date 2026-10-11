<?php

namespace App\Http\Requests\Admin\Assets\Operations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIndustryOperatingProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $profileId = $this->route('industryOperatingProfile')
            ? $this->route('industryOperatingProfile')->id
            : $this->route('industry_profile');

        return [
            'profile_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique(
                    'industry_operating_profiles',
                    'profile_code'
                )->ignore($profileId),
            ],

            'profile_name' => [
                'required',
                'string',
                'max:150',
            ],

            'industry_type' => [
                'required',
                Rule::in([
                    'Commercial / Retail',
                    'Hospitality',
                    'Residential / Housing',
                    'Industrial / Logistics',
                    'Community / Institutional',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'occupancy_target' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'vacancy_threshold' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'performance_target' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'utilization_target' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'availability_target' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'efficiency_target' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'condition_review_frequency_months' => [
                'nullable',
                'integer',
                'min:1',
                'max:120',
            ],

            'condition_warning_threshold' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'condition_critical_threshold' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'revenue_tracking' => [
                'nullable',
                'boolean',
            ],

            'operating_expense_tracking' => [
                'nullable',
                'boolean',
            ],

            'capex_tracking' => [
                'nullable',
                'boolean',
            ],

            'default_asset_life_years' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'renewal_period_years' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'replacement_threshold' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'lifecycle_review_frequency_months' => [
                'nullable',
                'integer',
                'min:1',
                'max:120',
            ],

            'primary_kpi' => [
                'nullable',
                'string',
                'max:100',
            ],

            'secondary_kpi' => [
                'nullable',
                'string',
                'max:100',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }
}