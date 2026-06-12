<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RezervacijaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'broj_osoba'  => $this->broj_osoba,
            'ukupna_cena' => number_format((float) $this->ukupna_cena, 2, ',', '.') . ' RSD',
            'status'      => $this->status,
            'aranzman'    => new AranzmanResource($this->whenLoaded('aranzman')),
            'korisnik'    => new KorisnikResource($this->whenLoaded('korisnik')),
            'kreirano'    => $this->created_at?->format('d.m.Y H:i'),
            'azurirano'   => $this->updated_at?->format('d.m.Y H:i'),
        ];
    }
}
