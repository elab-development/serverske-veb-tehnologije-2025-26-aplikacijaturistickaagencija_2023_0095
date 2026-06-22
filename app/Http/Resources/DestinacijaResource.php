<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestinacijaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'naziv'     => $this->naziv,
            'drzava'    => $this->drzava,
            'grad'      => $this->grad,
            'opis'      => $this->opis,
            'aranzmani' => AranzmanResource::collection($this->whenLoaded('aranzmani')),
            'kreirano'  => $this->created_at?->format('d.m.Y H:i'),
            'azurirano' => $this->updated_at?->format('d.m.Y H:i'),
        ];
    }
}
