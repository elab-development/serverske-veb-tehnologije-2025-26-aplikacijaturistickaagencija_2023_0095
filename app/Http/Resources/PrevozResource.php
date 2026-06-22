<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrevozResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'naziv'     => $this->naziv,
            'tip'       => $this->tip,
            'polaziste' => $this->polaziste,
            'odrediste' => $this->odrediste,
            'partner'   => $this->when(
                $this->relationLoaded('partner'),
                fn () => [
                    'id'    => $this->partner?->id,
                    'naziv' => $this->partner?->naziv,
                ]
            ),
            'kreirano'  => $this->created_at?->format('d.m.Y H:i'),
            'azurirano' => $this->updated_at?->format('d.m.Y H:i'),
        ];
    }
}
