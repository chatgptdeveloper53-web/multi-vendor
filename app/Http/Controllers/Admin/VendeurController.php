<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatutDossier;
use App\Http\Controllers\Controller;
use App\Models\Vendeur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendeurController extends Controller
{
    /**
     * Query de base : vendeurs avec leur user.
     */
    private function query()
    {
        return Vendeur::with('user');
    }

    /**
     * Liste tous les vendeurs avec recherche et filtre de statut.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $statut = $request->input('statut'); // EN_ATTENTE | VALIDE | REJETE | null

        $vendeurs = $this->query()
            ->withCount(['documents', 'logistiques'])
            ->when($statut, fn($q) => $q->where('statut_onboarding', $statut))
            ->when($search, fn($q) => $q->whereHas('user', fn($q2) => $q2
                ->where('nom', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
            ))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total'       => Vendeur::count(),
            'en_attente'  => Vendeur::where('statut_onboarding', 'EN_ATTENTE')->count(),
            'valides'     => Vendeur::where('statut_onboarding', 'VALIDE')->count(),
            'rejetes'     => Vendeur::where('statut_onboarding', 'REJETE')->count(),
        ];

        return view('admin.vendeurs.index', compact('vendeurs', 'stats', 'search', 'statut'));
    }

    /**
     * Affiche le profil complet d'un vendeur.
     */
    public function show(int $id): View
    {
        $vendeur = $this->query()
            ->with([
                'documents',
                'catalogue.produits',
                'logistiques',
                'user.commandes',
            ])
            ->findOrFail($id);

        $stats = [
            'nb_documents'  => $vendeur->documents->count(),
            'nb_produits'   => $vendeur->catalogue?->produits->count() ?? 0,
            'nb_logistiques'=> $vendeur->logistiques->count(),
            'profil_complet'=> $vendeur->profil_complet,
        ];

        return view('admin.vendeurs.show', compact('vendeur', 'stats'));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(int $id): View
    {
        $vendeur = $this->query()->findOrFail($id);

        return view('admin.vendeurs.edit', compact('vendeur'));
    }

    /**
     * Enregistre les modifications.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $vendeur = $this->query()->findOrFail($id);

        $request->validate([
            'nom'                 => ['required', 'string', 'max:255'],
            'email'               => ['required', 'email', 'max:255', 'unique:users,email,' . $vendeur->user->id],
            'coordonnees'         => ['nullable', 'string', 'max:500'],
            'rib'                 => ['nullable', 'string', 'max:255'],
            'informations_legales'=> ['nullable', 'string'],
        ]);

        // Update user fields
        $vendeur->user->update([
            'nom'   => $request->nom,
            'email' => $request->email,
        ]);

        // Update vendeur fields
        $vendeur->update([
            'coordonnees'          => $request->coordonnees,
            'rib'                  => $request->rib,
            'informations_legales' => $request->informations_legales,
        ]);

        return redirect()
            ->route('admin.vendeurs.show', $vendeur->id)
            ->with('success', "Le profil de {$vendeur->user->nom} a été mis à jour.");
    }

    /**
     * Valide le dossier du vendeur.
     */
    public function valider(int $id): RedirectResponse
    {
        $vendeur = Vendeur::findOrFail($id);
        $vendeur->update([
            'statut_onboarding' => StatutDossier::VALIDE,
            'profil_complet'    => true,
        ]);

        return redirect()
            ->route('admin.vendeurs.show', $vendeur->id)
            ->with('success', "Le dossier de « {$vendeur->user->nom} » a été validé.");
    }

    /**
     * Rejette le dossier du vendeur.
     */
    public function rejeter(int $id): RedirectResponse
    {
        $vendeur = Vendeur::with('user')->findOrFail($id);
        $vendeur->update([
            'statut_onboarding' => StatutDossier::REJETE,
        ]);

        return redirect()
            ->route('admin.vendeurs.show', $vendeur->id)
            ->with('warning', "Le dossier de « {$vendeur->user->nom} » a été rejeté.");
    }

    /**
     * Supprime définitivement un vendeur (+ son compte user).
     */
    public function destroy(int $id): RedirectResponse
    {
        $vendeur = Vendeur::with('user')->findOrFail($id);
        $nom     = $vendeur->user->nom;

        // Delete vendeur profile (user will remain unless we also delete it)
        $vendeur->user->delete(); // cascade: vendeur deleted via FK

        return redirect()
            ->route('admin.vendeurs.index')
            ->with('success', "Le compte de « {$nom} » a été supprimé définitivement.");
    }
}
