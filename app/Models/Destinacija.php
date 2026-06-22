<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destinacija extends Model
{
    use HasFactory;

    protected $table = 'destinacije';

    protected $fillable = [
        'naziv',
        'drzava',
        'grad',
        'opis',
    ];

    public function aranzmani(): HasMany
    {
        return $this->hasMany(Aranzman::class);
    }
}
