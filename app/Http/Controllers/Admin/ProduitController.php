<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Models\Photo;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    /*── Index — list with filters ────────────────────────────────*/
    public function index(Request $request)
    {
        $search     = $request->input('search');
        $catalogueId = $request->input('catalogue_id');
        $actif      = $request->input('actif');
        $categorie  = $request->input('categorie');

        $produits = Produit::with(['catalogue.vendeur', 'photos'])
            ->search($search)
            ->when($catalogueId, fn($q) => $q->where('catalogue_id', $catalogueId))
            ->when($actif !== null && $actif !== '', fn($q) => $q->where('actif', (bool) $actif))
            ->when($categorie, fn($q) => $q->where('categorie', $categorie))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $catalogues = Catalogue::with('vendeur')->orderBy('id')->get();
        $categories = Produit::whereNotNull('categorie')->distinct()->pluck('categorie')->sort()->values();

        $stats = [
            'total'  => Produit::count(),
            'actifs' => Produit::where('actif', true)->count(),
            'sans_photo' => Produit::doesntHave('photos')->count(),
            'stock_zero' => Produit::where('stock', 0)->count(),
        ];

        return view('admin.produits.index', compact(
            'produits', 'catalogues', 'categories', 'stats',
            'search', 'catalogueId', 'actif', 'categorie'
        ));
    }

    /*── Show ─────────────────────────────────────────────────────*/
    public function show(int $id)
    {
        $produit = Produit::with(['catalogue.vendeur', 'photos'])->findOrFail($id);
        return view('admin.produits.show', compact('produit'));
    }

    /*── Create form ──────────────────────────────────────────────*/
    public function create(Request $request)
    {
        $catalogues = Catalogue::with('vendeur')->orderBy('id')->get();
        $selectedCatalogueId = $request->input('catalogue_id');
        return view('admin.produits.create', compact('catalogues', 'selectedCatalogueId'));
    }

    /*── Store ────────────────────────────────────────────────────*/
    public function store(Request $request)
    {
        $data = $request->validate([
            'catalogue_id' => ['required', 'exists:catalogues,id'],
            'nom'          => ['required', 'string', 'max:255'],
            'reference'    => ['nullable', 'string', 'max:100'],
            'description'  => ['nullable', 'string'],
            'prix'         => ['required', 'numeric', 'min:0'],
            'poids_kg'     => ['nullable', 'numeric', 'min:0'],
            'dimensions'   => ['nullable', 'string', 'max:100'],
            'categorie'    => ['nullable', 'string', 'max:100'],
            'stock'        => ['required', 'integer', 'min:0'],
            'actif'        => ['nullable', 'boolean'],
            'photos.*'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data['actif'] = $request->boolean('actif', true);

        $produit = Produit::create($data);
        $this->handlePhotoUpload($request, $produit);

        return redirect()->route('admin.produits.show', $produit->id)
                         ->with('success', 'Produit créé avec succès.');
    }

    /*── Edit form ────────────────────────────────────────────────*/
    public function edit(int $id)
    {
        $produit    = Produit::with(['photos', 'catalogue'])->findOrFail($id);
        $catalogues = Catalogue::with('vendeur')->orderBy('id')->get();
        return view('admin.produits.edit', compact('produit', 'catalogues'));
    }

    /*── Update ───────────────────────────────────────────────────*/
    public function update(Request $request, int $id)
    {
        $produit = Produit::findOrFail($id);

        $data = $request->validate([
            'catalogue_id' => ['required', 'exists:catalogues,id'],
            'nom'          => ['required', 'string', 'max:255'],
            'reference'    => ['nullable', 'string', 'max:100'],
            'description'  => ['nullable', 'string'],
            'prix'         => ['required', 'numeric', 'min:0'],
            'poids_kg'     => ['nullable', 'numeric', 'min:0'],
            'dimensions'   => ['nullable', 'string', 'max:100'],
            'categorie'    => ['nullable', 'string', 'max:100'],
            'stock'        => ['required', 'integer', 'min:0'],
            'actif'        => ['nullable', 'boolean'],
            'photos.*'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data['actif'] = $request->boolean('actif', true);
        $produit->update($data);
        $this->handlePhotoUpload($request, $produit);

        return redirect()->route('admin.produits.show', $produit->id)
                         ->with('success', 'Produit mis à jour.');
    }

    /*── Toggle actif (POST, JSON-friendly) ───────────────────────*/
    public function toggle(int $id)
    {
        $produit = Produit::findOrFail($id);
        $produit->update(['actif' => !$produit->actif]);

        if (request()->expectsJson()) {
            return response()->json(['actif' => $produit->actif]);
        }

        return back()->with('success', 'Statut du produit mis à jour.');
    }

    /*── Destroy ──────────────────────────────────────────────────*/
    public function destroy(int $id)
    {
        $produit = Produit::with('photos')->findOrFail($id);

        foreach ($produit->photos as $photo) {
            $photo->deleteFile();
        }
        $produit->delete();

        return redirect()->route('admin.produits.index')
                         ->with('success', 'Produit supprimé.');
    }

    /*── Destroy single photo (AJAX) ──────────────────────────────*/
    public function destroyPhoto(int $id)
    {
        $photo = Photo::findOrFail($id);
        $photo->deleteFile();
        $photo->delete();

        return response()->json(['ok' => true]);
    }

    /*── Private: upload + attach photos ──────────────────────────*/
    private function handlePhotoUpload(Request $request, Produit $produit): void
    {
        if (!$request->hasFile('photos')) return;

        $existingCount = $produit->photos()->count();

        foreach ($request->file('photos') as $i => $file) {
            $path = $file->store('produits/' . $produit->id, 'public');
            $produit->photos()->create([
                'url'        => $path,
                'ordre'      => $existingCount + $i,
                'principale' => ($existingCount === 0 && $i === 0),
            ]);
        }
    }
}
