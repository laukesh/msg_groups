<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MallRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $mallId = $this->route('mall');

        return [
            'mall_code' => [
                'required',
                'string',
                'max:50',
                'unique:malls,mall_code,' . $mallId,
            ],

            'mall_name' => [
                'required',
                'string',
                'max:255',
            ],

            'mall_type' => [
                'nullable',
                'string',
                'max:100',
            ],

            'address_line1' => [
                'required',
                'string',
                'max:255',
            ],

            'address_line2' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'country' => [
                'required',
                'string',
                'max:100',
            ],

            'postal_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            'opening_date' => [
                'nullable',
                'date',
            ],

            'total_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'leasable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'parking_capacity' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contact_number' => [
                'nullable',
                'string',
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'website' => [
                'nullable',
                'url',
                'max:255',
            ],
            'status' => [
                'required',
                'integer',
                'in:0,1',
            ],
        
        ];
    }
}