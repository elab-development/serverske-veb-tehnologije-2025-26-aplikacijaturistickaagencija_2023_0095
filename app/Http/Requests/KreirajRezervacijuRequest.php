<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KreirajRezervacijuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'aranzman_id' => ['required', 'integer', 'exists:aranzmani,id'],
            'broj_osoba'  => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'aranzman_id.required' => 'Aranžman je obavezan.',
            'aranzman_id.exists'   => 'Odabrani aranžman ne postoji.',
            'broj_osoba.required'  => 'Broj osoba je obavezan.',
            'broj_osoba.min'       => 'Broj osoba mora biti najmanje 1.',
        ];
    }
}
