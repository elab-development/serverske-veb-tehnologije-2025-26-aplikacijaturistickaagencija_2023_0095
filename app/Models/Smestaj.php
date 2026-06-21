<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Smestaj extends Model
{
    use HasFactory;

    protected $table = 'smestaji';

    protected $fillable = [
        'partner_smestaja_id',
        'naziv',
        'tip',
        'adresa',
        'broj_zvezdica',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(PartnerSmestaja::class, 'partner_smestaja_id');
    }

    public function aranzmani(): HasMany
    {
        return $this->hasMany(Aranzman::class);
    }
}
