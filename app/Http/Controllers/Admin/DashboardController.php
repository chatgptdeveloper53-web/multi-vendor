<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Vendeur;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord administrateur avec les statistiques clés.
     */
    public function index(): View
    {
        $stats = [
            'total_users'     => User::count(),
            'total_vendeurs'  => Vendeur::count(),
            'total_produits'  => Produit::count(),
            'total_commandes' => Commande::count(),
            'commandes_en_cours' => Commande::where('statut', 'EN_COURS')->count(),
            'vendeurs_en_attente' => Vendeur::where('statut_onboarding', 'EN_ATTENTE')->count(),
        ];

        $dernieres_commandes = Commande::with('user')
            ->latest()
            ->take(5)
            ->get();

        $derniers_vendeurs = Vendeur::with('user')
            ->where('statut_onboarding', 'EN_ATTENTE')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'dernieres_commandes',
            'derniers_vendeurs'
        ));
    }
}
