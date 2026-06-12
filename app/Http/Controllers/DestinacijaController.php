<?php

namespace App\Http\Controllers;

use App\Http\Resources\DestinacijaResource;
use App\Models\Destinacija;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DestinacijaController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $destinacije = Destinacija::all();

        return DestinacijaResource::collection($destinacije);
    }

    public function store(Request $request): JsonResponse
    {
        $validiraniPodaci = $request->validate([
            'naziv'  => ['required', 'string', 'max:255'],
            'drzava' => ['required', 'string', 'max:255'],
            'grad'   => ['required', 'string', 'max:255'],
            'opis'   => ['nullable', 'string'],
        ], [
            'naziv.required'  => 'Naziv destinacije je obavezan.',
            'drzava.required' => 'Država je obavezna.',
            'grad.required'   => 'Grad je obavezan.',
        ]);

        $destinacija = Destinacija::create($validiraniPodaci);

        return (new DestinacijaResource($destinacija))
            ->response()
            ->setStatusCode(201)
            ->withHeaders(['X-Poruka' => 'Destinacija je uspešno kreirana.']);
    }

    public function show(Destinacija $destinacija): DestinacijaResource
    {
        $destinacija->load('aranzmani');

        return new DestinacijaResource($destinacija);
    }

    public function update(Request $request, Destinacija $destinacija): DestinacijaResource
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

        return new DestinacijaResource($destinacija);
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
