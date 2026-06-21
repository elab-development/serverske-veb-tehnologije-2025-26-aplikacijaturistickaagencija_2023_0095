<?php

namespace App\Http\Controllers;

use App\Http\Resources\AranzmanResource;
use App\Models\Aranzman;
use App\Models\PartnerPrevoza;
use App\Models\PartnerSmestaja;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PartnerController extends Controller
{
    public function aranzmaniPrevoza(Request $request, PartnerPrevoza $partnerPrevoza): AnonymousResourceCollection
    {
        $aranzmani = Aranzman::with('destinacija', 'prevoz', 'smestaj')
            ->whereHas('prevoz', fn ($q) => $q->where('partner_prevoza_id', $partnerPrevoza->id))
            ->paginate(min((int) $request->get('po_stranici', 10), 100));

        return AranzmanResource::collection($aranzmani);
    }

    public function aranzmaniSmestaja(Request $request, PartnerSmestaja $partnerSmestaja): AnonymousResourceCollection
    {
        $aranzmani = Aranzman::with('destinacija', 'prevoz', 'smestaj')
            ->whereHas('smestaj', fn ($q) => $q->where('partner_smestaja_id', $partnerSmestaja->id))
            ->paginate(min((int) $request->get('po_stranici', 10), 100));

        return AranzmanResource::collection($aranzmani);
    }
}
