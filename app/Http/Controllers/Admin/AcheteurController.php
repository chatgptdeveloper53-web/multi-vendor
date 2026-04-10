<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcheteurController extends Controller
{
    /**
     * Retourne une query de base : users avec role = acheteur.
     */
    private function query()
    {
        return User::where('role', 'acheteur');
    }

    /**
     * Liste tous les acheteurs avec recherche et pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $acheteurs = $this->query()
            ->withCount([
                'commandes',
                'panier as panier_items' => fn($q) => $q
                    ->join('panier_produit', 'paniers.id', '=', 'panier_produit.panier_id'),
            ])
            ->when($search, fn($q) => $q
                ->where(fn($q2) => $q2
                    ->where('nom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total'          => $this->query()->count(),
            'ce_mois'        => $this->query()->whereMonth('created_at', now()->month)->count(),
            'avec_commandes' => $this->query()->has('commandes')->count(),
            'sans_commandes' => $this->query()->doesntHave('commandes')->count(),
        ];

        return view('admin.acheteurs.index', compact('acheteurs', 'stats', 'search'));
    }

    /**
     * Affiche le profil complet d'un acheteur.
     */
    public function show(int $id): View
    {
        $acheteur = $this->query()
            ->with(['commandes.produits', 'panier.produits'])
            ->findOrFail($id);

        $stats = [
            'total_commandes' => $acheteur->commandes->count(),
            'commandes_cours' => $acheteur->commandes
                ->filter(fn($c) => ($c->statut->value ?? $c->statut) === 'EN_COURS')
                ->count(),
            'total_depense'   => $acheteur->commandes
                ->flatMap->produits
                ->sum(fn($p) => $p->pivot->prix_unitaire * $p->pivot->quantite),
            'panier_items'    => $acheteur->panier?->produits->sum('pivot.quantite') ?? 0,
        ];

        return view('admin.acheteurs.show', compact('acheteur', 'stats'));
    }

    /**
     * Affiche le formulaire de modification.
     */
    public function edit(int $id): View
    {
        $acheteur = $this->query()->findOrFail($id);

        return view('admin.acheteurs.edit', compact('acheteur'));
    }

    /**
     * Enregistre les modifications d'un acheteur.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $acheteur = $this->query()->findOrFail($id);

        $request->validate([
            'nom'   => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $acheteur->id],
        ]);

        $acheteur->update([
            'nom'   => $request->nom,
            'email' => $request->email,
        ]);

        return redirect()
            ->route('admin.acheteurs.show', $acheteur->id)
            ->with('success', "Le compte de {$acheteur->nom} a été mis à jour.");
    }

    /**
     * Supprime définitivement un acheteur et toutes ses données liées.
     */
    public function destroy(int $id): RedirectResponse
    {
        $acheteur = $this->query()->findOrFail($id);
        $nom      = $acheteur->nom;

        $acheteur->delete();

        return redirect()
            ->route('admin.acheteurs.index')
            ->with('success', "Le compte de « {$nom} » a été supprimé définitivement.");
    }
}
