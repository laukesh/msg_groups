<?php

namespace App\Http\Requests\Admin\Assets\Classification;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->can('types.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:150'],
            'code'       => ['nullable', 'string', 'max:50', 'unique:asset_types,code'],
            'description'=> ['nullable', 'string'],
            'status'     => ['required', 'in:Active,Inactive'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}