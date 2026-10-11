<?php

namespace App\Http\Requests\Admin\Assets\Areas;

use Illuminate\Foundation\Http\FormRequest;

class StoreAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mall_id' => [
                'required',
                'integer',
                'exists:malls,id',
            ],

            'building_id' => [
                'nullable',
                'integer',
                'exists:buildings,id',
            ],

            'floor_id' => [
                'nullable',
                'integer',
                'exists:floors,id',
            ],

            'zone_id' => [
                'nullable',
                'integer',
                'exists:zones,id',
            ],

            'unit_id' => [
                'nullable',
                'integer',
                'exists:units,id',
            ],

            'area_type' => [
                'required',
                'string',
                'max:50',
            ],

            'area_category' => [
                'nullable',
                'string',
                'max:50',
            ],

            'gross_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'net_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'usable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'rentable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'chargeable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'common_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'service_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'measurement_unit' => [
                'required',
                'string',
                'max:20',
            ],

            'measurement_date' => [
                'nullable',
                'date',
            ],

            'effective_from' => [
                'nullable',
                'date',
            ],

            'effective_to' => [
                'nullable',
                'date',
                'after_or_equal:effective_from',
            ],

            'status' => [
                'nullable',
                'string',
                'max:30',
            ],

            'measurement_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'measurement_standard' => [
                'nullable',
                'string',
                'max:100',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ];
    }
}