<?php

namespace App\Models;

use App\Enums\StatutDossier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vendeur extends Model
{
    protected $fillable = [
        // Identité entreprise (étape 1)
        'user_id',
        'raison_sociale',
        'forme_juridique',
        'siret',
        'numero_tva',
        'tva_verifiee',
        'pays',
        'site_web',
        'telephone',
        'adresse_siege',

        // Représentant légal (étape 1 – fusionné)
        'nom_dirigeant',
        'fonction_dirigeant',
        'email_commercial',

        // Coordonnées / légal (legacy)
        'coordonnees',
        'rib',
        'informations_legales',

        // Logistique (étape 4)
        'incoterm_preference',
        'incoterm_notes',
        'moq',
        'delai_traitement_jours',
        'politique_retour',
        'matrice_transport_fichier',
        'adresse_expedition',
        'poids_max_palette',

        // Statut
        'statut_onboarding',
        'etape_onboarding',
        'profil_complet',
    ];

    protected function casts(): array
    {
        return [
            'profil_complet'         => 'boolean',
            'tva_verifiee'           => 'boolean',
            'statut_onboarding'      => StatutDossier::class,
            'etape_onboarding'       => 'integer',
            'moq'                    => 'integer',
            'delai_traitement_jours' => 'integer',
            'poids_max_palette'      => 'float',
        ];
    }

    // ─── Relations ────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function catalogue(): HasOne
    {
        return $this->hasOne(Catalogue::class);
    }

    public function logistiques(): HasMany
    {
        return $this->hasMany(Logistique::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────

    public function onboardingUrl(): string
    {
        $etape = min($this->etape_onboarding ?? 1, 5);
        return route('vendeur.onboarding.etape', $etape);
    }

    public function onboardingProgress(): int
    {
        return (int) round((($this->etape_onboarding - 1) / 5) * 100);
    }

    public function onboardingComplet(): bool
    {
        return $this->etape_onboarding > 5 || $this->profil_complet;
    }
}
