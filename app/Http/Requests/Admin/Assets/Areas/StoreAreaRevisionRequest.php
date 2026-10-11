<?php

namespace App\Http\Requests\Admin\Assets\Areas;

use Illuminate\Foundation\Http\FormRequest;

class StoreAreaRevisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'new_gross_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'new_net_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'new_rentable_area' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'reason' => [
                'required',
                'string',
                'max:500',
            ],

            'revision_date' => [
                'required',
                'date',
            ],

            'effective_from' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ];
    }
}


/*
|--------------------------------------------------------------------------
| UpdateAreaRevisionRequest.php
|--------------------------------------------------------------------------
*/

class UpdateAreaRevisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'new_gross_area' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            'new_net_area' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            'new_rentable_area' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            'reason' => [
                'sometimes',
                'string',
                'max:500',
            ],

            'revision_date' => [
                'sometimes',
                'date',
            ],

            'effective_from' => [
                'sometimes',
                'nullable',
                'date',
            ],

            'remarks' => [
                'sometimes',
                'nullable',
                'string',
            ],
        ];
    }
}
