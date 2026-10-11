<?php

namespace App\Http\Requests\Admin\Assets\Classification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetPurposeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $assetPurpose = $this->route('id');

        $assetPurposeId = $assetPurpose instanceof \App\Models\Assets\AssetPurpose
            ? $assetPurpose->id
            : $assetPurpose;

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
                Rule::unique('asset_purposes', 'code')
                    ->ignore($assetPurposeId),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                Rule::in([
                    'Active',
                    'Inactive',
                ]),
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'name'       => 'asset purpose name',
            'code'       => 'asset purpose code',
            'sort_order' => 'sort order',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the asset purpose name.',

            'name.max' => 'The asset purpose name may not exceed 150 characters.',

            'code.max' => 'The asset purpose code may not exceed 50 characters.',

            'code.unique' => 'This asset purpose code already exists.',

            'status.required' => 'Please select a status.',

            'status.in' => 'The selected status is invalid.',

            'sort_order.integer' => 'Sort order must be a number.',

            'sort_order.min' => 'Sort order cannot be negative.',
        ];
    }
}