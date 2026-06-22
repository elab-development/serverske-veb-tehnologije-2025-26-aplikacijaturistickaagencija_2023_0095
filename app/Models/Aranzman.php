<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aranzman extends Model
{
    use HasFactory;

    protected $table = 'aranzmani';

    protected $fillable = [
        'destinacija_id',
        'prevoz_id',
        'smestaj_id',
        'naziv',
        'tip',
        'opis',
        'cena',
        'popust',
        'datum_pocetka',
        'datum_zavrsetka',
        'slobodna_mesta',
    ];

    protected $casts = [
        'cena' => 'decimal:2',
        'datum_pocetka' => 'date',
        'datum_zavrsetka' => 'date',
    ];

    public function destinacija(): BelongsTo
    {
        return $this->belongsTo(Destinacija::class);
    }

    public function prevoz(): BelongsTo
    {
        return $this->belongsTo(Prevoz::class);
    }

    public function smestaj(): BelongsTo
    {
        return $this->belongsTo(Smestaj::class);
    }

    public function rezervacije(): HasMany
    {
        return $this->hasMany(Rezervacija::class);
    }

    public function popusti(): HasMany
    {
        return $this->hasMany(Popust::class);
    }

    public function usluge(): BelongsToMany
    {
        return $this->belongsToMany(Usluga::class, 'aranzman_usluga');
    }
}
