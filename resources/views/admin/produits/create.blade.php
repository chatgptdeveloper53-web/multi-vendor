@extends('admin.layouts.app')
@section('title', 'Nouveau produit')

@section('content')

    @include('admin.components.breadcrumb', [
        'items' => [
            'Produits' => route('admin.produits.index'),
            'Nouveau produit' => route('admin.produits.create'),
        ]
    ])

    <div class="d-flex align-items-center mb-4 gap-3">
        <a href="{{ route('admin.produits.index') }}" class="btn btn-outline-secondary btn-sm">
            <iconify-icon icon="solar:arrow-left-line-duotone"></iconify-icon>
        </a>
        <div>
            <h4 class="mb-0 fw-semibold">Nouveau produit</h4>
            <p class="mb-0 text-muted fs-2">Créer une fiche produit dans un catalogue vendeur</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.produits.store') }}" enctype="multipart/form-data">
        @csrf

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
                                <option value="">— Sélectionner un catalogue —</option>
                                @foreach($catalogues as $cat)
                                    <option value="{{ $cat->id }}"
                                            @selected(old('catalogue_id', $selectedCatalogueId) == $cat->id)>
                                        {{ $cat->nomAffiche() }}
                                        @if($cat->vendeur?->user) — {{ $cat->vendeur->user->nom }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('catalogue_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nom du produit <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                       value="{{ old('nom') }}" placeholder="Panneau solaire 400W monocristallin" required>
                                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Référence / SKU</label>
                                <input type="text" name="reference" class="form-control @error('reference') is-invalid @enderror"
                                       value="{{ old('reference') }}" placeholder="SOL-400M-01">
                                @error('reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label fw-semibold">Catégorie</label>
                            <input type="text" name="categorie" class="form-control"
                                   value="{{ old('categorie') }}"
                                   placeholder="Panneau solaire, Onduleur, Batterie, Câble…"
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

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" rows="5" class="form-control"
                                      placeholder="Caractéristiques techniques, certifications, usage prévu…">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Photos --}}
                <div class="card border-0 shadow-none">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">
                            <iconify-icon icon="solar:gallery-line-duotone" class="me-1 text-primary"></iconify-icon>
                            Photos produit
                        </h6>

                        <div id="drop-zone"
                             onclick="document.getElementById('photos-input').click()"
                             class="border-dashed rounded p-4 text-center"
                             style="border:2px dashed #cbd5e1;cursor:pointer;transition:all .2s;">
                            <iconify-icon icon="solar:upload-minimalistic-line-duotone"
                                          style="font-size:2rem;color:#94a3b8;" class="d-block mb-2"></iconify-icon>
                            <p class="mb-1 fw-semibold" style="color:#64748b;">Glisser-déposer ou cliquer pour uploader</p>
                            <p class="mb-0 text-muted fs-2">JPG, PNG, WEBP · max 5 Mo par fichier · première photo = photo principale</p>
                        </div>
                        <input type="file" id="photos-input" name="photos[]"
                               multiple accept="image/jpeg,image/png,image/webp"
                               class="d-none" onchange="previewPhotos(this)">
                        @error('photos.*')<div class="text-danger mt-1 fs-2">{{ $message }}</div>@enderror

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
                                       value="{{ old('prix') }}" placeholder="0.00" required>
                                <span class="input-group-text">€</span>
                            </div>
                            @error('prix')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Stock disponible <span class="text-danger">*</span></label>
                            <input type="number" name="stock" min="0"
                                   class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', 0) }}" required>
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Logistique lourde --}}
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
                                       class="form-control @error('poids_kg') is-invalid @enderror"
                                       value="{{ old('poids_kg') }}" placeholder="0.0">
                                <span class="input-group-text">kg</span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Dimensions</label>
                            <input type="text" name="dimensions" class="form-control"
                                   value="{{ old('dimensions') }}"
                                   placeholder="1200×800×40 mm">
                            <small class="text-muted">Format libre : L×l×H mm ou cm</small>
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
                                   {{ old('actif', '1') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="cb-actif">Produit actif (visible)</label>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.produits.index') }}" class="btn btn-outline-secondary flex-fill">Annuler</a>
                    <button type="submit" class="btn btn-primary flex-fill">
                        <iconify-icon icon="solar:check-circle-line-duotone" class="me-1"></iconify-icon>
                        Créer le produit
                    </button>
                </div>
            </div>

        </div>
    </form>

@endsection

@push('scripts')
<script>
// Drag-drop styling
const dz = document.getElementById('drop-zone');
dz.addEventListener('dragover',  e => { e.preventDefault(); dz.style.borderColor = '#84b817'; dz.style.background = '#f0f9e8'; });
dz.addEventListener('dragleave', () => { dz.style.borderColor = '#cbd5e1'; dz.style.background = ''; });
dz.addEventListener('drop', e => {
    e.preventDefault();
    dz.style.borderColor = '#cbd5e1';
    dz.style.background = '';
    const inp = document.getElementById('photos-input');
    inp.files = e.dataTransfer.files;
    previewPhotos(inp);
});

function previewPhotos(input) {
    const container = document.getElementById('photo-preview');
    container.innerHTML = '';
    [...input.files].forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'position-relative';
            div.innerHTML = `
                <img src="${e.target.result}" alt=""
                     style="width:80px;height:80px;object-fit:cover;border-radius:8px;
                            border:2px solid ${i === 0 ? '#84b817' : '#e2e8f0'};">
                ${i === 0 ? '<span class="position-absolute top-0 start-0 badge" style="font-size:.6rem;background:#84b817;border-radius:4px 0 4px 0;">Principale</span>' : ''}
            `;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush
