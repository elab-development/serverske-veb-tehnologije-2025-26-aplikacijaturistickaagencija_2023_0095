<?php

namespace App\Http\Controllers;

use App\Models\Destinacija;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DestinacijaController extends Controller
{
    public function index(): JsonResponse
    {
        $destinacije = Destinacija::all();

        return response()->json([
            'podaci' => $destinacije,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validiraniPodaci = $request->validate([
            'naziv'   => ['required', 'string', 'max:255'],
            'drzava'  => ['required', 'string', 'max:255'],
            'grad'    => ['required', 'string', 'max:255'],
            'opis'    => ['nullable', 'string'],
        ], [
            'naziv.required'  => 'Naziv destinacije je obavezan.',
            'drzava.required' => 'Država je obavezna.',
            'grad.required'   => 'Grad je obavezan.',
        ]);

        $destinacija = Destinacija::create($validiraniPodaci);

        return response()->json([
            'poruka' => 'Destinacija je uspešno kreirana.',
            'podaci' => $destinacija,
        ], 201);
    }

    public function show(Destinacija $destinacija): JsonResponse
    {
        $destinacija->load('aranzmani');

        return response()->json([
            'podaci' => $destinacija,
        ]);
    }

    public function update(Request $request, Destinacija $destinacija): JsonResponse
    {
        $validiraniPodaci = $request->validate([
            'naziv'  => ['sometimes', 'string', 'max:255'],
            'drzava' => ['sometimes', 'string', 'max:255'],
            'grad'   => ['sometimes', 'string', 'max:255'],
            'opis'   => ['nullable', 'string'],
        ], [
            'naziv.string'  => 'Naziv mora biti tekst.',
            'drzava.string' => 'Država mora biti tekst.',
            'grad.string'   => 'Grad mora biti tekst.',
        ]);

        $destinacija->update($validiraniPodaci);

        return response()->json([
            'poruka' => 'Destinacija je uspešno ažurirana.',
            'podaci' => $destinacija,
        ]);
    }

    public function destroy(Destinacija $destinacija): JsonResponse
    {
        if ($destinacija->aranzmani()->exists()) {
            return response()->json([
                'poruka' => 'Nije moguće obrisati destinaciju jer ima aktivnih aranžmana.',
            ], 422);
        }

        $destinacija->delete();

        return response()->json([
            'poruka' => 'Destinacija je uspešno obrisana.',
        ]);
    }
}
