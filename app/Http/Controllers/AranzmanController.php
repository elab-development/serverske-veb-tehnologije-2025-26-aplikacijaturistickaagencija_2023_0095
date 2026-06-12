<?php

namespace App\Http\Controllers;

use App\Models\Aranzman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AranzmanController extends Controller
{
    public function index(): JsonResponse
    {
        $aranzmani = Aranzman::with('destinacija')->get();

        return response()->json([
            'podaci' => $aranzmani,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validiraniPodaci = $request->validate([
            'destinacija_id'   => ['required', 'integer', 'exists:destinacije,id'],
            'naziv'            => ['required', 'string', 'max:255'],
            'opis'             => ['nullable', 'string'],
            'cena'             => ['required', 'numeric', 'min:0'],
            'datum_pocetka'    => ['required', 'date'],
            'datum_zavrsetka'  => ['required', 'date', 'after:datum_pocetka'],
            'slobodna_mesta'   => ['required', 'integer', 'min:0'],
        ], [
            'destinacija_id.required'  => 'Destinacija je obavezna.',
            'destinacija_id.exists'    => 'Odabrana destinacija ne postoji.',
            'naziv.required'           => 'Naziv aranžmana je obavezan.',
            'cena.required'            => 'Cena je obavezna.',
            'cena.numeric'             => 'Cena mora biti broj.',
            'datum_pocetka.required'   => 'Datum početka je obavezan.',
            'datum_zavrsetka.required' => 'Datum završetka je obavezan.',
            'datum_zavrsetka.after'    => 'Datum završetka mora biti posle datuma početka.',
            'slobodna_mesta.required'  => 'Broj slobodnih mesta je obavezan.',
            'slobodna_mesta.integer'   => 'Slobodna mesta moraju biti ceo broj.',
        ]);

        $aranzman = Aranzman::create($validiraniPodaci);
        $aranzman->load('destinacija');

        return response()->json([
            'poruka' => 'Aranžman je uspešno kreiran.',
            'podaci' => $aranzman,
        ], 201);
    }

    public function show(Aranzman $aranzman): JsonResponse
    {
        $aranzman->load('destinacija', 'rezervacije');

        return response()->json([
            'podaci' => $aranzman,
        ]);
    }

    public function update(Request $request, Aranzman $aranzman): JsonResponse
    {
        $validiraniPodaci = $request->validate([
            'destinacija_id'   => ['sometimes', 'integer', 'exists:destinacije,id'],
            'naziv'            => ['sometimes', 'string', 'max:255'],
            'opis'             => ['nullable', 'string'],
            'cena'             => ['sometimes', 'numeric', 'min:0'],
            'datum_pocetka'    => ['sometimes', 'date'],
            'datum_zavrsetka'  => ['sometimes', 'date', 'after:datum_pocetka'],
            'slobodna_mesta'   => ['sometimes', 'integer', 'min:0'],
        ], [
            'destinacija_id.exists'    => 'Odabrana destinacija ne postoji.',
            'cena.numeric'             => 'Cena mora biti broj.',
            'datum_zavrsetka.after'    => 'Datum završetka mora biti posle datuma početka.',
            'slobodna_mesta.integer'   => 'Slobodna mesta moraju biti ceo broj.',
        ]);

        $aranzman->update($validiraniPodaci);
        $aranzman->load('destinacija');

        return response()->json([
            'poruka' => 'Aranžman je uspešno ažuriran.',
            'podaci' => $aranzman,
        ]);
    }

    public function destroy(Aranzman $aranzman): JsonResponse
    {
        $aranzman->delete();

        return response()->json([
            'poruka' => 'Aranžman je uspešno obrisan.',
        ]);
    }
}
