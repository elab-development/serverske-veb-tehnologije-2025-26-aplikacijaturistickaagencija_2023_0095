<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PopustResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'naziv'     => $this->naziv,
            'tip'       => $this->tip,
            'procenat'  => $this->procenat . '%',
            'datum_od'  => $this->datum_od?->format('d.m.Y'),
            'datum_do'  => $this->datum_do?->format('d.m.Y'),
            'aktivan'   => $this->aktivan,
            'kreirano'  => $this->created_at?->format('d.m.Y H:i'),
        ];
    }
}
