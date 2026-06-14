<?php

namespace App\Http\Controllers;

use App\Http\Resources\AranzmanResource;
use App\Models\Aranzman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AranzmanController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $upit = Aranzman::with('destinacija');

        // Filtriranje
        if ($request->filled('destinacija_id')) {
            $upit->where('destinacija_id', $request->destinacija_id);
        }

        if ($request->filled('tip')) {
            $upit->where('tip', $request->tip);
        }

        if ($request->filled('cena_min')) {
            $upit->where('cena', '>=', $request->cena_min);
        }

        if ($request->filled('cena_max')) {
            $upit->where('cena', '<=', $request->cena_max);
        }

        if ($request->filled('slobodna_mesta_min')) {
            $upit->where('slobodna_mesta', '>=', $request->slobodna_mesta_min);
        }

        if ($request->filled('datum_od')) {
            $upit->where('datum_pocetka', '>=', $request->datum_od);
        }

        if ($request->filled('datum_do')) {
            $upit->where('datum_pocetka', '<=', $request->datum_do);
        }

        // Sortiranje
        $dozvoljenaSortiranjaPolja = ['naziv', 'cena', 'datum_pocetka', 'slobodna_mesta'];
        $sortiranjePo = in_array($request->sortiraj_po, $dozvoljenaSortiranjaPolja)
            ? $request->sortiraj_po
            : 'datum_pocetka';
        $redosled = $request->redosled === 'desc' ? 'desc' : 'asc';
        $upit->orderBy($sortiranjePo, $redosled);

        // Paginacija
        $poStranici = min((int) $request->get('po_stranici', 10), 100);

        return AranzmanResource::collection($upit->paginate($poStranici));
    }

    public function store(Request $request): JsonResponse
    {
        $validiraniPodaci = $request->validate([
            'destinacija_id'   => ['required', 'integer', 'exists:destinacije,id'],
            'naziv'            => ['required', 'string', 'max:255'],
            'tip'              => ['required', 'in:letovanje,zimovanje,izlet,krstarenje,gradski_odmor'],
            'opis'             => ['nullable', 'string'],
            'cena'             => ['required', 'numeric', 'min:0'],
            'popust'           => ['sometimes', 'integer', 'min:0', 'max:100'],
            'datum_pocetka'    => ['required', 'date'],
            'datum_zavrsetka'  => ['required', 'date', 'after:datum_pocetka'],
            'slobodna_mesta'   => ['required', 'integer', 'min:0'],
        ], [
            'destinacija_id.required'  => 'Destinacija je obavezna.',
            'destinacija_id.exists'    => 'Odabrana destinacija ne postoji.',
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
        ]);

        $aranzman = Aranzman::create($validiraniPodaci);
        $aranzman->load('destinacija');

        return (new AranzmanResource($aranzman))
            ->response()
            ->setStatusCode(201)
            ->withHeaders(['X-Poruka' => 'Aranžman je uspešno kreiran.']);
    }

    public function show(Aranzman $aranzman): AranzmanResource
    {
        $aranzman->load('destinacija', 'rezervacije');

        return new AranzmanResource($aranzman);
    }

    public function update(Request $request, Aranzman $aranzman): AranzmanResource
    {
        $validiraniPodaci = $request->validate([
            'destinacija_id'   => ['sometimes', 'integer', 'exists:destinacije,id'],
            'naziv'            => ['sometimes', 'string', 'max:255'],
            'tip'              => ['sometimes', 'in:letovanje,zimovanje,izlet,krstarenje,gradski_odmor'],
            'opis'             => ['nullable', 'string'],
            'cena'             => ['sometimes', 'numeric', 'min:0'],
            'popust'           => ['sometimes', 'integer', 'min:0', 'max:100'],
            'datum_pocetka'    => ['sometimes', 'date'],
            'datum_zavrsetka'  => ['sometimes', 'date', 'after:datum_pocetka'],
            'slobodna_mesta'   => ['sometimes', 'integer', 'min:0'],
        ], [
            'destinacija_id.exists'    => 'Odabrana destinacija ne postoji.',
            'tip.in'                   => 'Tip mora biti: letovanje, zimovanje, izlet, krstarenje ili gradski_odmor.',
            'cena.numeric'             => 'Cena mora biti broj.',
            'popust.max'               => 'Popust ne može biti veći od 100%.',
            'datum_zavrsetka.after'    => 'Datum završetka mora biti posle datuma početka.',
            'slobodna_mesta.integer'   => 'Slobodna mesta moraju biti ceo broj.',
        ]);

        $aranzman->update($validiraniPodaci);
        $aranzman->load('destinacija');

        return new AranzmanResource($aranzman);
    }

    public function destroy(Aranzman $aranzman): JsonResponse
    {
        $aranzman->delete();

        return response()->json([
            'poruka' => 'Aranžman je uspešno obrisan.',
        ]);
    }
}
