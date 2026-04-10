<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VendeurOnboardingMiddleware
{
    /**
     * Redirige les vendeurs dont le profil est incomplet vers le wizard d'onboarding.
     * Exempt : les routes d'onboarding elles-mêmes, logout, et les routes statiques.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Uniquement pour les vendeurs
        if ($user->role !== 'vendeur') {
            return $next($request);
        }

        $vendeur = $user->vendeur;

        // Pas encore de profil vendeur → créer et rediriger
        if (!$vendeur) {
            \App\Models\Vendeur::create([
                'user_id'           => $user->id,
                'profil_complet'    => false,
                'statut_onboarding' => 'EN_ATTENTE',
                'etape_onboarding'  => 1,
            ]);
            return redirect()->route('vendeur.onboarding.etape', 1);
        }

        // Profil non complété → rediriger vers l'étape courante
        if (!$vendeur->onboardingComplet()) {
            // Éviter la boucle infinie sur les routes d'onboarding
            if ($request->routeIs('vendeur.onboarding.*')) {
                return $next($request);
            }
            return redirect($vendeur->onboardingUrl())
                ->with('info', 'Veuillez finaliser votre profil vendeur pour accéder à la plateforme.');
        }

        // Profil complet mais en attente de validation → page d'attente
        $statutVal = $vendeur->statut_onboarding instanceof \App\Enums\StatutDossier
            ? $vendeur->statut_onboarding->value
            : $vendeur->statut_onboarding;

        if ($statutVal === 'EN_ATTENTE' && !$request->routeIs('vendeur.pending') && !$request->routeIs('vendeur.onboarding.*')) {
            return redirect()->route('vendeur.pending');
        }

        if ($statutVal === 'REJETE' && !$request->routeIs('vendeur.onboarding.*') && !$request->routeIs('vendeur.rejected')) {
            return redirect()->route('vendeur.rejected');
        }

        return $next($request);
    }
}
