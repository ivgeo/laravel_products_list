<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'price_min' => ['nullable', 'numeric', 'min:0'],
            'price_max' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $min = $this->input('price_min');
            $max = $this->input('price_max');

            if ($min !== null && $max !== null && is_numeric($min) && is_numeric($max) && (float) $max < (float) $min) {
                $validator->errors()->add('price_max', 'The price max field must be greater than or equal to price min.');
            }
        });
    }
}
