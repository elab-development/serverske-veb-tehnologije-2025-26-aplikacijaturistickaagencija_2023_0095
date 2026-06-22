<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmestajResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'naziv'         => $this->naziv,
            'tip'           => $this->tip,
            'adresa'        => $this->adresa,
            'broj_zvezdica' => $this->broj_zvezdica,
            'partner'       => $this->when(
                $this->relationLoaded('partner'),
                fn () => [
                    'id'    => $this->partner?->id,
                    'naziv' => $this->partner?->naziv,
                ]
            ),
            'kreirano'      => $this->created_at?->format('d.m.Y H:i'),
            'azurirano'     => $this->updated_at?->format('d.m.Y H:i'),
        ];
    }
}
