<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Usluga extends Model
{
    use HasFactory;

    protected $table = 'usluge';

    protected $fillable = [
        'naziv',
        'kategorija',
    ];

    public function aranzmani(): BelongsToMany
    {
        return $this->belongsToMany(Aranzman::class, 'aranzman_usluga');
    }
}
