<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logistique extends Model
{
    protected $fillable = [
        'vendeur_id',
        'catalogue_id',
        'zone',
        'poids_min',
        'poids',       // poids_max
        'prix',        // tarif de base
        'tarif_par_kg',
        'delai_jours',
        'incoterm',
        'description',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'poids_min'    => 'float',
            'poids'        => 'float',
            'prix'         => 'float',
            'tarif_par_kg' => 'float',
            'delai_jours'  => 'integer',
        ];
    }

    public function vendeur(): BelongsTo
    {
        return $this->belongsTo(Vendeur::class);
    }

    public function catalogue(): BelongsTo
    {
        return $this->belongsTo(Catalogue::class);
    }
}
