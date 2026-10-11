<?php

namespace App\Http\Requests\Admin\Assets\Areas;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mall_id' => [
                'sometimes',
                'integer',
                'exists:malls,id',
            ],

            'building_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:buildings,id',
            ],

            'floor_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:floors,id',
            ],

            'zone_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:zones,id',
            ],

            'unit_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:units,id',
            ],

            'area_type' => [
                'sometimes',
                'string',
                'max:50',
            ],

            'area_category' => [
                'sometimes',
                'nullable',
                'string',
                'max:50',
            ],

            'gross_area' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            'net_area' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            'usable_area' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            'rentable_area' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            'chargeable_area' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            'common_area' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            'service_area' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            'measurement_unit' => [
                'sometimes',
                'string',
                'max:20',
            ],

            'measurement_date' => [
                'sometimes',
                'nullable',
                'date',
            ],

            'effective_from' => [
                'sometimes',
                'nullable',
                'date',
            ],

            'effective_to' => [
                'sometimes',
                'nullable',
                'date',
                'after_or_equal:effective_from',
            ],

            'status' => [
                'sometimes',
                'string',
                'max:30',
            ],

            'measurement_method' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
            ],

            'measurement_standard' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
            ],

            'remarks' => [
                'sometimes',
                'nullable',
                'string',
            ],
        ];
    }
}