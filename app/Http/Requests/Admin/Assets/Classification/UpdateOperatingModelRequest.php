<?php

namespace App\Http\Requests\Admin\Assets\Classification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOperatingModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->can('operating_models.edit');
    }

    public function rules(): array
    {
        $id = $this->route('id');

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
                Rule::unique(
                    'operating_models',
                    'code'
                )->ignore($id),
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