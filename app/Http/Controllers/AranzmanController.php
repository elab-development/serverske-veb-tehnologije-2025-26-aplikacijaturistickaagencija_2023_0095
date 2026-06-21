<?php

namespace App\Http\Controllers;

use App\Http\Requests\AzurirajAranzmanRequest;
use App\Http\Requests\KreirajAranzmanRequest;
use App\Http\Resources\AranzmanResource;
use App\Models\Aranzman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AranzmanController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $upit = Aranzman::with('destinacija', 'prevoz', 'smestaj');

        if ($request->filled('destinacija_id')) {
            $upit->where('destinacija_id', $request->destinacija_id);
        }

        if ($request->filled('prevoz_id')) {
            $upit->where('prevoz_id', $request->prevoz_id);
        }

        if ($request->filled('smestaj_id')) {
            $upit->where('smestaj_id', $request->smestaj_id);
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

        $dozvoljenaSortiranjaPolja = ['naziv', 'cena', 'datum_pocetka', 'slobodna_mesta'];
        $sortiranjePo = in_array($request->sortiraj_po, $dozvoljenaSortiranjaPolja)
            ? $request->sortiraj_po
            : 'datum_pocetka';
        $redosled = $request->redosled === 'desc' ? 'desc' : 'asc';
        $upit->orderBy($sortiranjePo, $redosled);

        $poStranici = min((int) $request->get('po_stranici', 10), 100);

        return AranzmanResource::collection($upit->paginate($poStranici));
    }

    public function store(KreirajAranzmanRequest $request): JsonResponse
    {
        $aranzman = Aranzman::create($request->validated());
        $aranzman->load('destinacija', 'prevoz', 'smestaj');

        return (new AranzmanResource($aranzman))
            ->response()
            ->setStatusCode(201)
            ->withHeaders(['X-Poruka' => 'Aranžman je uspešno kreiran.']);
    }

    public function show(Aranzman $aranzman): AranzmanResource
    {
        $aranzman->load('destinacija', 'prevoz.partner', 'smestaj.partner', 'rezervacije', 'popusti');

        return new AranzmanResource($aranzman);
    }

    public function update(AzurirajAranzmanRequest $request, Aranzman $aranzman): AranzmanResource
    {
        $aranzman->update($request->validated());
        $aranzman->load('destinacija', 'prevoz', 'smestaj');

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
