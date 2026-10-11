<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ZoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $zoneId = $this->route('zone');

        return [

            'floor_id' => [
                'required',
                'integer',
                'exists:floors,id',
            ],

            'zone_code' => [
                'required',
                'string',
                'max:50',

                Rule::unique('zones', 'zone_code')
                    ->where(function ($query) {
                        return $query->where(
                            'floor_id',
                            $this->floor_id
                        );
                    })
                    ->ignore($zoneId),
            ],

            'zone_name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'floor_id.required' =>
                'Please select a floor.',

            'floor_id.exists' =>
                'The selected floor does not exist.',

            'zone_code.unique' =>
                'This zone code already exists on the selected floor.',

            'zone_name.required' =>
                'Zone name is required.',
        ];
    }
}