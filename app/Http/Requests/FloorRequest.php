<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FloorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $floorId = $this->route('floor');

        return [

            'building_id' => [
                'required',
                'integer',
                'exists:buildings,id',
            ],

            'floor_code' => [
                'required',
                'string',
                'max:50',

                Rule::unique('floors', 'floor_code')
                    ->where(function ($query) {
                        return $query->where(
                            'building_id',
                            $this->building_id
                        );
                    })
                    ->ignore($floorId),
            ],

            'floor_name' => [
                'required',
                'string',
                'max:255',
            ],

            'floor_number' => [
                'required',
                'integer',
                'min:0',
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
            'building_id.required' =>
                'Please select a building.',

            'building_id.exists' =>
                'The selected building does not exist.',

            'floor_code.unique' =>
                'This floor code already exists in the selected building.',

            'floor_number.min' =>
                'Floor number cannot be negative.',
        ];
    }
}