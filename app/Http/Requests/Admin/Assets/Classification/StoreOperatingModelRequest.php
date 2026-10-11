<?php

namespace App\Http\Requests\Admin\Assets\Classification;

use Illuminate\Foundation\Http\FormRequest;

class StoreOperatingModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->can('operating_models.create');
    }

    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'code' => [
                'nullable',
                'string',
                'max:50',
                'unique:operating_models,code',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Active,Inactive',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'operating model name',
            'code' => 'operating model code',
        ];
    }
}