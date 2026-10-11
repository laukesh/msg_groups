<?php

namespace App\Http\Requests\Admin\Assets\Classification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->can('types.edit') ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name'       => ['required', 'string', 'max:150'],
            'code'       => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('asset_types', 'code')->ignore($id),
            ],
            'description'=> ['nullable', 'string'],
            'status'     => ['required', 'in:Active,Inactive'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}