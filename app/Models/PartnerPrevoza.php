<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerPrevoza extends Model
{
    use HasFactory;

    protected $table = 'partneri_prevoza';

    protected $fillable = [
        'user_id',
        'naziv',
        'kontakt_email',
        'kontakt_telefon',
    ];

    public function korisnik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prevozi(): HasMany
    {
        return $this->hasMany(Prevoz::class, 'partner_prevoza_id');
    }
}
