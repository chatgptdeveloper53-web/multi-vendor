@extends('admin.layouts.app')
@section('title', 'Modifier — ' . $produit->nom)

@section('content')

    @include('admin.components.breadcrumb', [
        'items' => [
            'Produits'       => route('admin.produits.index'),
            $produit->nom    => route('admin.produits.show', $produit->id),
            'Modifier'       => route('admin.produits.edit', $produit->id),
        ]
    ])

    <div class="d-flex align-items-center mb-4 gap-3">
        <a href="{{ route('admin.produits.show', $produit->id) }}" class="btn btn-outline-secondary btn-sm">
            <iconify-icon icon="solar:arrow-left-line-duotone"></iconify-icon>
        </a>
        <div>
            <h4 class="mb-0 fw-semibold">Modifier le produit</h4>
            <p class="mb-0 text-muted fs-2">{{ $produit->nom }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.produits.update', $produit->id) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-4">

            {{-- ── Main col ── --}}
            <div class="col-lg-8">

                {{-- Infos générales --}}
                <div class="card border-0 shadow-none mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">
                            <iconify-icon icon="solar:tag-line-duotone" class="me-1 text-primary"></iconify-icon>
                            Informations générales
                        </h6>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catalogue vendeur <span class="text-danger">*</span></label>
                            <select name="catalogue_id" class="form-select @error('catalogue_id') is-invalid @enderror" required>
                                @foreach($catalogues as $cat)
                                    <option value="{{ $cat->id }}"
                                            @selected(old('catalogue_id', $produit->catalogue_id) == $cat->id)>
                                        {{ $cat->nomAffiche() }}
                                        @if($cat->vendeur?->user) — {{ $cat->vendeur->user->nom }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('catalogue_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                       value="{{ old('nom', $produit->nom) }}" required>
                                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Référence / SKU</label>
                                <input type="text" name="reference" class="form-control"
                                       value="{{ old('reference', $produit->reference) }}">
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label fw-semibold">Catégorie</label>
                            <input type="text" name="categorie" class="form-control"
                                   value="{{ old('categorie', $produit->categorie) }}"
                                   list="categories-list">
                            <datalist id="categories-list">
                                <option value="Panneau solaire">
                                <option value="Onduleur">
                                <option value="Batterie / Stockage">
                                <option value="Câble & Connectique">
                                <option value="Structure de montage">
                                <option value="Régulateur de charge">
                                <option value="Accessoire">
                            </datalist>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" rows="5" class="form-control">{{ old('description', $produit->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Existing photos --}}
                <div class="card border-0 shadow-none mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">
                            <iconify-icon icon="solar:gallery-line-duotone" class="me-1 text-primary"></iconify-icon>
                            Photos existantes ({{ $produit->photos->count() }})
                        </h6>

                        @if($produit->photos->isNotEmpty())
                            <div class="d-flex flex-wrap gap-3" id="existing-photos">
                                @foreach($produit->photos as $photo)
                                    <div class="position-relative" id="photo-card-{{ $photo->id }}">
                                        <img src="{{ $photo->assetUrl() }}" alt=""
                                             style="width:96px;height:96px;object-fit:cover;border-radius:8px;
                                                    border:2px solid {{ $photo->principale ? '#84b817' : '#e2e8f0' }};">
                                        @if($photo->principale)
                                            <span class="position-absolute top-0 start-0 badge"
                                                  style="font-size:.6rem;background:#84b817;border-radius:4px 0 4px 0;">
                                                Principale
                                            </span>
                                        @endif
                                        <button type="button"
                                                onclick="deletePhoto({{ $photo->id }}, this)"
                                                class="position-absolute top-0 end-0 btn btn-danger btn-sm p-0"
                                                style="width:20px;height:20px;font-size:.6rem;border-radius:0 8px 0 4px;line-height:1;"
                                                title="Supprimer cette photo">
                                            ×
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted fs-2 mb-0">Aucune photo pour ce produit.</p>
                        @endif
                    </div>
                </div>

                {{-- Add more photos --}}
                <div class="card border-0 shadow-none">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">
                            <iconify-icon icon="solar:upload-minimalistic-line-duotone" class="me-1 text-primary"></iconify-icon>
                            Ajouter des photos
                        </h6>
                        <div id="drop-zone"
                             onclick="document.getElementById('photos-input').click()"
                             class="border rounded p-3 text-center"
                             style="border-style:dashed !important;cursor:pointer;">
                            <p class="mb-0 text-muted fs-2">Cliquer ou glisser-déposer · JPG, PNG, WEBP · max 5 Mo</p>
                        </div>
                        <input type="file" id="photos-input" name="photos[]"
                               multiple accept="image/jpeg,image/png,image/webp"
                               class="d-none" onchange="previewPhotos(this)">
                        <div id="photo-preview" class="d-flex flex-wrap gap-2 mt-3"></div>
                    </div>
                </div>
            </div>

            {{-- ── Side col ── --}}
            <div class="col-lg-4">

                {{-- Prix & Stock --}}
                <div class="card border-0 shadow-none mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">
                            <iconify-icon icon="solar:wallet-money-line-duotone" class="me-1 text-primary"></iconify-icon>
                            Prix & Stock
                        </h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Prix HT (€) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="prix" step="0.01" min="0"
                                       class="form-control @error('prix') is-invalid @enderror"
                                       value="{{ old('prix', $produit->prix) }}" required>
                                <span class="input-group-text">€</span>
                            </div>
                            @error('prix')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Stock <span class="text-danger">*</span></label>
                            <input type="number" name="stock" min="0"
                                   class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', $produit->stock) }}" required>
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Logistique --}}
                <div class="card border-0 shadow-none mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">
                            <iconify-icon icon="solar:delivery-line-duotone" class="me-1 text-primary"></iconify-icon>
                            Logistique lourde
                        </h6>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Poids (kg)</label>
                            <div class="input-group">
                                <input type="number" name="poids_kg" step="0.1" min="0"
                                       class="form-control"
                                       value="{{ old('poids_kg', $produit->poids_kg) }}" placeholder="0.0">
                                <span class="input-group-text">kg</span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Dimensions</label>
                            <input type="text" name="dimensions" class="form-control"
                                   value="{{ old('dimensions', $produit->dimensions) }}"
                                   placeholder="1200×800×40 mm">
                        </div>
                    </div>
                </div>

                {{-- Statut --}}
                <div class="card border-0 shadow-none mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">
                            <iconify-icon icon="solar:shield-check-line-duotone" class="me-1 text-primary"></iconify-icon>
                            Statut
                        </h6>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="actif" id="cb-actif" value="1"
                                   {{ old('actif', $produit->actif) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="cb-actif">Produit actif (visible)</label>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.produits.show', $produit->id) }}" class="btn btn-outline-secondary flex-fill">Annuler</a>
                    <button type="submit" class="btn btn-primary flex-fill">
                        <iconify-icon icon="solar:check-circle-line-duotone" class="me-1"></iconify-icon>
                        Enregistrer
                    </button>
                </div>
            </div>

        </div>
    </form>

@endsection

@push('scripts')
<script>
const CSRF = '{{ csrf_token() }}';

async function deletePhoto(id, btn) {
    if (!confirm('Supprimer cette photo ?')) return;
    try {
        const res = await fetch(`/admin/photos/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        });
        if (res.ok) {
            document.getElementById('photo-card-' + id)?.remove();
        } else {
            alert('Erreur lors de la suppression.');
        }
    } catch (e) {
        alert('Erreur réseau.');
    }
}

function previewPhotos(input) {
    const container = document.getElementById('photo-preview');
    container.innerHTML = '';
    [...input.files].forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.innerHTML = `<img src="${e.target.result}" alt=""
                style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid #e2e8f0;">`;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// Drag-drop
const dz = document.getElementById('drop-zone');
dz.addEventListener('dragover',  e => { e.preventDefault(); dz.style.background = '#f0f9e8'; });
dz.addEventListener('dragleave', () => { dz.style.background = ''; });
dz.addEventListener('drop', e => {
    e.preventDefault();
    dz.style.background = '';
    const inp = document.getElementById('photos-input');
    inp.files = e.dataTransfer.files;
    previewPhotos(inp);
});
</script>
@endpush
