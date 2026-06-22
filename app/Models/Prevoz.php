<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prevoz extends Model
{
    use HasFactory;

    protected $table = 'prevozi';

    protected $fillable = [
        'partner_prevoza_id',
        'naziv',
        'tip',
        'polaziste',
        'odrediste',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(PartnerPrevoza::class, 'partner_prevoza_id');
    }

    public function aranzmani(): HasMany
    {
        return $this->hasMany(Aranzman::class);
    }
}
