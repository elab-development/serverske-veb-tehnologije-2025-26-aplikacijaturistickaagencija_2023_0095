<?php

namespace App\Http\Controllers;

use App\Models\Aranzman;
use App\Models\Rezervacija;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RezervacijaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $rezervacije = Rezervacija::with(['aranzman', 'korisnik'])
            ->where('korisnik_id', $request->user()->id)
            ->get();

        return response()->json([
            'podaci' => $rezervacije,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validiraniPodaci = $request->validate([
            'aranzman_id' => ['required', 'integer', 'exists:aranzmani,id'],
            'broj_osoba'  => ['required', 'integer', 'min:1'],
        ], [
            'aranzman_id.required' => 'Aranžman je obavezan.',
            'aranzman_id.exists'   => 'Odabrani aranžman ne postoji.',
            'broj_osoba.required'  => 'Broj osoba je obavezan.',
            'broj_osoba.min'       => 'Broj osoba mora biti najmanje 1.',
        ]);

        $aranzman = Aranzman::findOrFail($validiraniPodaci['aranzman_id']);

        if ($aranzman->slobodna_mesta < $validiraniPodaci['broj_osoba']) {
            return response()->json([
                'poruka' => 'Nema dovoljno slobodnih mesta. Dostupno: ' . $aranzman->slobodna_mesta,
            ], 422);
        }

        $ukupnaCena = $aranzman->cena * $validiraniPodaci['broj_osoba'];

        $rezervacija = Rezervacija::create([
            'korisnik_id'  => $request->user()->id,
            'aranzman_id'  => $validiraniPodaci['aranzman_id'],
            'broj_osoba'   => $validiraniPodaci['broj_osoba'],
            'ukupna_cena'  => $ukupnaCena,
            'status'       => 'na_cekanju',
        ]);

        $aranzman->decrement('slobodna_mesta', $validiraniPodaci['broj_osoba']);

        $rezervacija->load('aranzman', 'korisnik');

        return response()->json([
            'poruka' => 'Rezervacija je uspešno kreirana.',
            'podaci' => $rezervacija,
        ], 201);
    }

    public function show(Request $request, Rezervacija $rezervacija): JsonResponse
    {
        if ($rezervacija->korisnik_id !== $request->user()->id) {
            return response()->json([
                'poruka' => 'Nemate dozvolu za pregled ove rezervacije.',
            ], 403);
        }

        $rezervacija->load('aranzman.destinacija', 'korisnik');

        return response()->json([
            'podaci' => $rezervacija,
        ]);
    }

    public function update(Request $request, Rezervacija $rezervacija): JsonResponse
    {
        if ($rezervacija->korisnik_id !== $request->user()->id) {
            return response()->json([
                'poruka' => 'Nemate dozvolu za izmenu ove rezervacije.',
            ], 403);
        }

        if ($rezervacija->status === 'otkazana') {
            return response()->json([
                'poruka' => 'Otkazana rezervacija ne može biti izmenjena.',
            ], 422);
        }

        $validiraniPodaci = $request->validate([
            'status' => ['sometimes', 'in:na_cekanju,potvrdjena,otkazana'],
        ], [
            'status.in' => 'Status mora biti: na_cekanju, potvrdjana ili otkazana.',
        ]);

        if (isset($validiraniPodaci['status']) && $validiraniPodaci['status'] === 'otkazana') {
            $rezervacija->aranzman->increment('slobodna_mesta', $rezervacija->broj_osoba);
        }

        $rezervacija->update($validiraniPodaci);

        return response()->json([
            'poruka' => 'Rezervacija je uspešno ažurirana.',
            'podaci' => $rezervacija,
        ]);
    }

    public function destroy(Request $request, Rezervacija $rezervacija): JsonResponse
    {
        if ($rezervacija->korisnik_id !== $request->user()->id) {
            return response()->json([
                'poruka' => 'Nemate dozvolu za brisanje ove rezervacije.',
            ], 403);
        }

        if ($rezervacija->status !== 'otkazana') {
            $rezervacija->aranzman->increment('slobodna_mesta', $rezervacija->broj_osoba);
        }

        $rezervacija->delete();

        return response()->json([
            'poruka' => 'Rezervacija je uspešno obrisana.',
        ]);
    }
}
