<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AranzmanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'naziv'           => $this->naziv,
            'opis'            => $this->opis,
            'cena'            => number_format((float) $this->cena, 2, ',', '.') . ' RSD',
            'datum_pocetka'   => $this->datum_pocetka?->format('d.m.Y'),
            'datum_zavrsetka' => $this->datum_zavrsetka?->format('d.m.Y'),
            'slobodna_mesta'  => $this->slobodna_mesta,
            'destinacija'     => new DestinacijaResource($this->whenLoaded('destinacija')),
            'rezervacije'     => RezervacijaResource::collection($this->whenLoaded('rezervacije')),
            'kreirano'        => $this->created_at?->format('d.m.Y H:i'),
            'azurirano'       => $this->updated_at?->format('d.m.Y H:i'),
        ];
    }
}
