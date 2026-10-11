<?php

namespace App\Http\Requests\Admin\Assets\Classification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssetPurposeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->can('purposes.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:150'],
            'code'        => ['nullable', 'string', 'max:50', 'unique:asset_purposes,code'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:Active,Inactive'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ];
    }
}