<?php

namespace App\Http\Controllers;

use App\Exceptions\NedovoljnoMestaException;
use App\Http\Requests\AzurirajRezervacijuRequest;
use App\Http\Requests\KreirajRezervacijuRequest;
use App\Http\Resources\RezervacijaResource;
use App\Models\Aranzman;
use App\Models\Rezervacija;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class RezervacijaController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $upit = Rezervacija::with(['aranzman.destinacija', 'korisnik'])
            ->where('korisnik_id', $request->user()->id);

        if ($request->filled('status')) {
            $upit->where('status', $request->status);
        }

        $poStranici = min((int) $request->get('po_stranici', 10), 100);

        return RezervacijaResource::collection($upit->paginate($poStranici));
    }

    public function store(KreirajRezervacijuRequest $request): JsonResponse
    {
        $podaci = $request->validated();

        try {
            $rezervacija = DB::transaction(function () use ($podaci, $request) {
                $aranzman = Aranzman::findOrFail($podaci['aranzman_id']);

                if ($aranzman->slobodna_mesta < $podaci['broj_osoba']) {
                    throw new NedovoljnoMestaException(
                        'Nema dovoljno slobodnih mesta. Dostupno: ' . $aranzman->slobodna_mesta
                    );
                }

                $cenaPoDan = $aranzman->popust > 0
                    ? $aranzman->cena * (1 - $aranzman->popust / 100)
                    : $aranzman->cena;

                $rezervacija = Rezervacija::create([
                    'korisnik_id' => $request->user()->id,
                    'aranzman_id' => $podaci['aranzman_id'],
                    'broj_osoba'  => $podaci['broj_osoba'],
                    'ukupna_cena' => $cenaPoDan * $podaci['broj_osoba'],
                    'status'      => 'na_cekanju',
                ]);

                $aranzman->decrement('slobodna_mesta', $podaci['broj_osoba']);

                return $rezervacija;
            });
        } catch (NedovoljnoMestaException $e) {
            return response()->json(['poruka' => $e->getMessage()], 422);
        }

        $rezervacija->load('aranzman.destinacija', 'korisnik');

        return (new RezervacijaResource($rezervacija))
            ->response()
            ->setStatusCode(201)
            ->withHeaders(['X-Poruka' => 'Rezervacija je uspešno kreirana.']);
    }

    public function show(Request $request, Rezervacija $rezervacija): JsonResponse|RezervacijaResource
    {
        if ($rezervacija->korisnik_id !== $request->user()->id) {
            return response()->json([
                'poruka' => 'Nemate dozvolu za pregled ove rezervacije.',
            ], 403);
        }

        $rezervacija->load('aranzman.destinacija', 'korisnik');

        return new RezervacijaResource($rezervacija);
    }

    public function update(AzurirajRezervacijuRequest $request, Rezervacija $rezervacija): JsonResponse|RezervacijaResource
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

        $podaci = $request->validated();

        if (isset($podaci['status']) && $podaci['status'] === 'otkazana') {
            $rezervacija->aranzman->increment('slobodna_mesta', $rezervacija->broj_osoba);
        }

        $rezervacija->update($podaci);

        return new RezervacijaResource($rezervacija);
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
