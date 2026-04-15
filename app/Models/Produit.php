<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    protected $fillable = [
        'catalogue_id',
        'nom',
        'reference',
        'description',
        'prix',
        'poids_kg',
        'dimensions',
        'categorie',
        'stock',
        'actif',
    ];

    protected function casts(): array
    {
        return [
            'prix'      => 'float',
            'poids_kg'  => 'float',
            'stock'     => 'integer',
            'actif'     => 'boolean',
        ];
    }

    /*── Scopes ──────────────────────────────────────────────────────*/

    public function scopeActif(Builder $query): Builder
    {
        return $query->where('actif', true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('nom', 'like', "%{$term}%")
              ->orWhere('reference', 'like', "%{$term}%")
              ->orWhere('categorie', 'like', "%{$term}%");
        });
    }

    /*── Helpers ──────────────────────────────────────────────────────*/

    public function photoUrl(): ?string
    {
        $main = $this->photos->firstWhere('principale', true) ?? $this->photos->first();
        return $main ? asset('storage/' . $main->url) : null;
    }

    /*── Relations ───────────────────────────────────────────────────*/

    public function catalogue(): BelongsTo
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class)->orderBy('ordre');
    }

    public function paniers(): BelongsToMany
    {
        return $this->belongsToMany(Panier::class, 'panier_produit')
                    ->withPivot('quantite')
                    ->withTimestamps();
    }

    public function commandes(): BelongsToMany
    {
        return $this->belongsToMany(Commande::class, 'commande_produit')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }
}
