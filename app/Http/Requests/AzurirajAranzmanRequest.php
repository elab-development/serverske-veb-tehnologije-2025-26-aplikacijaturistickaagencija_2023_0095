<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AzurirajAranzmanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'destinacija_id'   => ['sometimes', 'integer', 'exists:destinacije,id'],
            'prevoz_id'        => ['sometimes', 'nullable', 'integer', 'exists:prevozi,id'],
            'smestaj_id'       => ['sometimes', 'nullable', 'integer', 'exists:smestaji,id'],
            'naziv'            => ['sometimes', 'string', 'max:255'],
            'tip'              => ['sometimes', 'in:letovanje,zimovanje,izlet,krstarenje,gradski_odmor'],
            'opis'             => ['nullable', 'string'],
            'cena'             => ['sometimes', 'numeric', 'min:0'],
            'popust'           => ['sometimes', 'integer', 'min:0', 'max:100'],
            'datum_pocetka'    => ['sometimes', 'date'],
            'datum_zavrsetka'  => ['sometimes', 'date', 'after:datum_pocetka'],
            'slobodna_mesta'   => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'destinacija_id.exists'    => 'Odabrana destinacija ne postoji.',
            'prevoz_id.exists'         => 'Odabrani prevoz ne postoji.',
            'smestaj_id.exists'        => 'Odabrani smeštaj ne postoji.',
            'tip.in'                   => 'Tip mora biti: letovanje, zimovanje, izlet, krstarenje ili gradski_odmor.',
            'cena.numeric'             => 'Cena mora biti broj.',
            'popust.max'               => 'Popust ne može biti veći od 100%.',
            'datum_zavrsetka.after'    => 'Datum završetka mora biti posle datuma početka.',
            'slobodna_mesta.integer'   => 'Slobodna mesta moraju biti ceo broj.',
        ];
    }
}
