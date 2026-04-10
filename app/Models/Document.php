<?php

namespace App\Models;

use App\Enums\TypeDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'vendeur_id',
        'type',
        'fichier',
        'nom_original',
        'valide',
        'commentaire_admin',
    ];

    protected function casts(): array
    {
        return [
            'type'   => TypeDocument::class,
            'valide' => 'boolean',
        ];
    }

    public function vendeur(): BelongsTo
    {
        return $this->belongsTo(Vendeur::class);
    }

    /**
     * URL publique du fichier (via storage:link).
     */
    public function url(): string
    {
        return asset('storage/' . $this->fichier);
    }
}
