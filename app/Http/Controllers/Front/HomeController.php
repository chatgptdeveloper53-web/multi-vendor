<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil avec les produits en vedette.
     */
    public function index(): View
    {
        $produits = Produit::with('photos')
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        return view('front.home', compact('produits'));
    }
}
