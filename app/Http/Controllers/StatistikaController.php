<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StatistikaController extends Controller
{
    public function aranzmani(): JsonResponse
    {
        $redovi = DB::table('aranzmani')
            ->leftJoin('rezervacije', 'aranzmani.id', '=', 'rezervacije.aranzman_id')
            ->leftJoin('destinacije', 'aranzmani.destinacija_id', '=', 'destinacije.id')
            ->leftJoin('prevozi', 'aranzmani.prevoz_id', '=', 'prevozi.id')
            ->leftJoin('smestaji', 'aranzmani.smestaj_id', '=', 'smestaji.id')
            ->select([
                'aranzmani.id',
                'aranzmani.naziv',
                'aranzmani.tip',
                'destinacije.naziv as destinacija',
                'prevozi.naziv as naziv_prevoza',
                'prevozi.tip as tip_prevoza',
                'smestaji.naziv as naziv_smestaja',
                'smestaji.tip as tip_smestaja',
                DB::raw('COUNT(rezervacije.id) as broj_rezervacija'),
                DB::raw('COALESCE(SUM(rezervacije.broj_osoba), 0) as ukupno_putnika'),
                DB::raw('COALESCE(SUM(rezervacije.ukupna_cena), 0) as ukupan_prihod'),
            ])
            ->groupBy(
                'aranzmani.id',
                'aranzmani.naziv',
                'aranzmani.tip',
                'destinacije.naziv',
                'prevozi.naziv',
                'prevozi.tip',
                'smestaji.naziv',
                'smestaji.tip'
            )
            ->orderByDesc(DB::raw('COUNT(rezervacije.id)'))
            ->get();

        return response()->json([
            'statistika' => $redovi->map(fn ($r) => [
                'id'               => $r->id,
                'naziv'            => $r->naziv,
                'tip'              => $r->tip,
                'destinacija'      => $r->destinacija,
                'prevoz'           => $r->naziv_prevoza
                    ? ['naziv' => $r->naziv_prevoza, 'tip' => $r->tip_prevoza]
                    : null,
                'smestaj'          => $r->naziv_smestaja
                    ? ['naziv' => $r->naziv_smestaja, 'tip' => $r->tip_smestaja]
                    : null,
                'broj_rezervacija' => (int) $r->broj_rezervacija,
                'ukupno_putnika'   => (int) $r->ukupno_putnika,
                'ukupan_prihod'    => number_format((float) $r->ukupan_prihod, 2, ',', '.') . ' RSD',
            ]),
            'zbir' => [
                'ukupno_aranzmana'   => $redovi->count(),
                'ukupno_rezervacija' => (int) $redovi->sum('broj_rezervacija'),
                'ukupno_putnika'     => (int) $redovi->sum('ukupno_putnika'),
                'ukupan_prihod'      => number_format((float) $redovi->sum('ukupan_prihod'), 2, ',', '.') . ' RSD',
            ],
        ]);
    }
}
