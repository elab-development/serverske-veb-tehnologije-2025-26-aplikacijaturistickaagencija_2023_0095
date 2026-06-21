<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AzurirajRezervacijuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'in:na_cekanju,potvrdjena,otkazana'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Status mora biti: na_cekanju, potvrdjena ili otkazana.',
        ];
    }
}
