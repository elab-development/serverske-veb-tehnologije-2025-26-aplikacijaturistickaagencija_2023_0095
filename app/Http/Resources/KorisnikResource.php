<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KorisnikResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'ime'       => $this->name,
            'email'     => $this->email,
            'kreirano'  => $this->created_at?->format('d.m.Y H:i'),
        ];
    }
}
