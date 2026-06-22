<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rezervacija extends Model
{
    use HasFactory;

    protected $table = 'rezervacije';

    protected $fillable = [
        'korisnik_id',
        'aranzman_id',
        'broj_osoba',
        'ukupna_cena',
        'status',
    ];

    protected $casts = [
        'ukupna_cena' => 'decimal:2',
    ];

    public function korisnik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'korisnik_id');
    }

    public function aranzman(): BelongsTo
    {
        return $this->belongsTo(Aranzman::class);
    }
}
