<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Popust extends Model
{
    use HasFactory;

    protected $table = 'popusti';

    protected $fillable = [
        'aranzman_id',
        'naziv',
        'tip',
        'procenat',
        'datum_od',
        'datum_do',
        'aktivan',
    ];

    protected $casts = [
        'datum_od' => 'date',
        'datum_do' => 'date',
        'aktivan'  => 'boolean',
    ];

    public function aranzman(): BelongsTo
    {
        return $this->belongsTo(Aranzman::class);
    }
}
