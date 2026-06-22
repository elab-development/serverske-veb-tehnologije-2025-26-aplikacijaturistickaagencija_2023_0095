<?php

namespace App\Http\Controllers;

use App\Http\Resources\AranzmanResource;
use App\Http\Resources\DestinacijaResource;
use App\Models\Aranzman;
use App\Models\Destinacija;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PretragaController extends Controller
{
    public function pretraga(Request $request): JsonResponse
    {
        $request->validate([
            'q'          => ['required', 'string', 'min:2', 'max:100'],
            'tip'        => ['sometimes', 'in:letovanje,zimovanje,izlet,krstarenje,gradski_odmor'],
            'po_stranici' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ], [
            'q.required' => 'Unesite pojam za pretragu.',
            'q.min'      => 'Pojam za pretragu mora imati najmanje 2 karaktera.',
            'tip.in'     => 'Tip mora biti: letovanje, zimovanje, izlet, krstarenje ili gradski_odmor.',
        ]);

        $termin     = $request->q;
        $poStranici = min((int) $request->get('po_stranici', 10), 100);

        // Pretraga aranžmana po nazivu, opisu i destinaciji
        $upitAranzmani = Aranzman::with('destinacija')
            ->where(function ($upit) use ($termin) {
                $upit->where('naziv', 'like', '%' . $termin . '%')
                    ->orWhere('opis', 'like', '%' . $termin . '%')
                    ->orWhereHas('destinacija', function ($d) use ($termin) {
                        $d->where('naziv', 'like', '%' . $termin . '%')
                            ->orWhere('drzava', 'like', '%' . $termin . '%')
                            ->orWhere('grad', 'like', '%' . $termin . '%');
                    });
            });

        if ($request->filled('tip')) {
            $upitAranzmani->where('tip', $request->tip);
        }

        $aranzmani = $upitAranzmani->paginate($poStranici, ['*'], 'stranica_aranzmana');

        // Pretraga destinacija po nazivu, državi, gradu i opisu
        $destinacije = Destinacija::where('naziv', 'like', '%' . $termin . '%')
            ->orWhere('drzava', 'like', '%' . $termin . '%')
            ->orWhere('grad', 'like', '%' . $termin . '%')
            ->orWhere('opis', 'like', '%' . $termin . '%')
            ->paginate($poStranici, ['*'], 'stranica_destinacija');

        return response()->json([
            'termin'      => $termin,
            'aranzmani'   => [
                'podaci'      => AranzmanResource::collection($aranzmani),
                'ukupno'      => $aranzmani->total(),
                'stranica'    => $aranzmani->currentPage(),
                'poslednja'   => $aranzmani->lastPage(),
            ],
            'destinacije' => [
                'podaci'      => DestinacijaResource::collection($destinacije),
                'ukupno'      => $destinacije->total(),
                'stranica'    => $destinacije->currentPage(),
                'poslednja'   => $destinacije->lastPage(),
            ],
        ]);
    }
}
