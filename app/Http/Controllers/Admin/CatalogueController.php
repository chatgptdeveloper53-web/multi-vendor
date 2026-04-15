<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    /*── Index ────────────────────────────────────────────────────*/
    public function index(Request $request)
    {
        $search = $request->input('search');
        $actif  = $request->input('actif');

        $catalogues = Catalogue::with(['vendeur.user', 'produits'])
            ->withCount('produits')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('nom', 'like', "%{$search}%")
                       ->orWhereHas('vendeur.user', fn($q3) => $q3->where('nom', 'like', "%{$search}%"));
                });
            })
            ->when($actif !== null && $actif !== '', fn($q) => $q->where('actif', (bool) $actif))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total'   => Catalogue::count(),
            'actifs'  => Catalogue::where('actif', true)->count(),
            'vides'   => Catalogue::doesntHave('produits')->count(),
        ];

        return view('admin.catalogues.index', compact('catalogues', 'stats', 'search', 'actif'));
    }

    /*── Show ─────────────────────────────────────────────────────*/
    public function show(int $id, Request $request)
    {
        $catalogue = Catalogue::with(['vendeur.user', 'logistique'])->findOrFail($id);

        $search = $request->input('search');
        $actif  = $request->input('actif');

        $produits = $catalogue->produits()
            ->with('photos')
            ->when($search, fn($q) => $q->where('nom', 'like', "%{$search}%")
                                        ->orWhere('reference', 'like', "%{$search}%"))
            ->when($actif !== null && $actif !== '', fn($q) => $q->where('actif', (bool) $actif))
            ->withCount('photos')
            ->paginate(20)
            ->withQueryString();

        return view('admin.catalogues.show', compact('catalogue', 'produits', 'search', 'actif'));
    }

    /*── Toggle actif ─────────────────────────────────────────────*/
    public function toggle(int $id)
    {
        $catalogue = Catalogue::findOrFail($id);
        $catalogue->update(['actif' => !$catalogue->actif]);

        return back()->with('success', 'Statut du catalogue mis à jour.');
    }
}
