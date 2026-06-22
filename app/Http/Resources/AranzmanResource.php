<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AranzmanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $cenaSaPopustom = $this->popust > 0
            ? $this->cena * (1 - $this->popust / 100)
            : null;

        return [
            'id'               => $this->id,
            'naziv'            => $this->naziv,
            'tip'              => $this->tip,
            'opis'             => $this->opis,
            'cena'             => number_format((float) $this->cena, 2, ',', '.') . ' RSD',
            'popust'           => $this->popust . '%',
            'cena_sa_popustom' => $cenaSaPopustom
                ? number_format($cenaSaPopustom, 2, ',', '.') . ' RSD'
                : null,
            'datum_pocetka'    => $this->datum_pocetka?->format('d.m.Y'),
            'datum_zavrsetka'  => $this->datum_zavrsetka?->format('d.m.Y'),
            'slobodna_mesta'   => $this->slobodna_mesta,
            'destinacija'      => new DestinacijaResource($this->whenLoaded('destinacija')),
            'prevoz'           => new PrevozResource($this->whenLoaded('prevoz')),
            'smestaj'          => new SmestajResource($this->whenLoaded('smestaj')),
            'popusti'          => PopustResource::collection($this->whenLoaded('popusti')),
            'rezervacije'      => RezervacijaResource::collection($this->whenLoaded('rezervacije')),
            'kreirano'         => $this->created_at?->format('d.m.Y H:i'),
            'azurirano'        => $this->updated_at?->format('d.m.Y H:i'),
        ];
    }
}
