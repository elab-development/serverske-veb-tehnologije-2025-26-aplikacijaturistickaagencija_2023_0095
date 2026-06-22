<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KreirajAranzmanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'destinacija_id'   => ['required', 'integer', 'exists:destinacije,id'],
            'prevoz_id'        => ['nullable', 'integer', 'exists:prevozi,id'],
            'smestaj_id'       => ['nullable', 'integer', 'exists:smestaji,id'],
            'naziv'            => ['required', 'string', 'max:255'],
            'tip'              => ['required', 'in:letovanje,zimovanje,izlet,krstarenje,gradski_odmor'],
            'opis'             => ['nullable', 'string'],
            'cena'             => ['required', 'numeric', 'min:0'],
            'popust'           => ['sometimes', 'integer', 'min:0', 'max:100'],
            'datum_pocetka'    => ['required', 'date'],
            'datum_zavrsetka'  => ['required', 'date', 'after:datum_pocetka'],
            'slobodna_mesta'   => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'destinacija_id.required'  => 'Destinacija je obavezna.',
            'destinacija_id.exists'    => 'Odabrana destinacija ne postoji.',
            'prevoz_id.exists'         => 'Odabrani prevoz ne postoji.',
            'smestaj_id.exists'        => 'Odabrani smeštaj ne postoji.',
            'naziv.required'           => 'Naziv aranžmana je obavezan.',
            'tip.required'             => 'Tip aranžmana je obavezan.',
            'tip.in'                   => 'Tip mora biti: letovanje, zimovanje, izlet, krstarenje ili gradski_odmor.',
            'cena.required'            => 'Cena je obavezna.',
            'cena.numeric'             => 'Cena mora biti broj.',
            'popust.max'               => 'Popust ne može biti veći od 100%.',
            'datum_pocetka.required'   => 'Datum početka je obavezan.',
            'datum_zavrsetka.required' => 'Datum završetka je obavezan.',
            'datum_zavrsetka.after'    => 'Datum završetka mora biti posle datuma početka.',
            'slobodna_mesta.required'  => 'Broj slobodnih mesta je obavezan.',
            'slobodna_mesta.integer'   => 'Slobodna mesta moraju biti ceo broj.',
        ];
    }
}
