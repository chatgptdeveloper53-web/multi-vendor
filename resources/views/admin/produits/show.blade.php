@extends('admin.layouts.app')
@section('title', $produit->nom)

@section('content')

    @include('admin.components.breadcrumb', [
        'items' => [
            'Produits' => route('admin.produits.index'),
            $produit->nom => route('admin.produits.show', $produit->id),
        ]
    ])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- ── Left col ── --}}
        <div class="col-lg-4">

            {{-- Photo gallery --}}
            <div class="card border-0 shadow-none mb-4">
                <div class="card-body p-3">

                    @if($produit->photos->isNotEmpty())
                        {{-- Main photo --}}
                        @php $main = $produit->photos->firstWhere('principale', true) ?? $produit->photos->first(); @endphp
                        <div class="rounded overflow-hidden mb-2" style="aspect-ratio:4/3;background:#f8fafc;">
                            <img id="main-photo" src="{{ $main->assetUrl() }}" alt="{{ $produit->nom }}"
                                 style="width:100%;height:100%;object-fit:cover;">
                        </div>

                        {{-- Thumbnails --}}
                        @if($produit->photos->count() > 1)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($produit->photos as $photo)
                                    <div class="position-relative">
                                        <img src="{{ $photo->assetUrl() }}" alt=""
                                             onclick="document.getElementById('main-photo').src='{{ $photo->assetUrl() }}'"
                                             style="width:52px;height:52px;object-fit:cover;border-radius:6px;
                                                    cursor:pointer;border:2px solid {{ $photo->principale ? '#84b817' : '#e2e8f0' }};">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="d-flex align-items-center justify-content-center rounded"
                             style="aspect-ratio:4/3;background:#f8fafc;">
                            <div class="text-center text-muted">
                                <iconify-icon icon="solar:gallery-minimalistic-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                                Aucune photo
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="card border-0 shadow-none">
                <div class="card-body p-3 d-flex flex-column gap-2">
                    <a href="{{ route('admin.produits.edit', $produit->id) }}" class="btn btn-primary">
                        <iconify-icon icon="solar:pen-line-duotone" class="me-1"></iconify-icon>
                        Modifier le produit
                    </a>
                    <form method="POST" action="{{ route('admin.produits.toggle', $produit->id) }}">
                        @csrf
                        <button type="submit"
                                class="btn w-100 {{ $produit->actif ? 'btn-outline-warning' : 'btn-outline-success' }}">
                            <iconify-icon icon="{{ $produit->actif ? 'solar:eye-closed-line-duotone' : 'solar:eye-line-duotone' }}" class="me-1"></iconify-icon>
                            {{ $produit->actif ? 'Désactiver' : 'Activer' }}
                        </button>
                    </form>
                    <button type="button" class="btn btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <iconify-icon icon="solar:trash-bin-trash-line-duotone" class="me-1"></iconify-icon>
                        Supprimer
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Right col ── --}}
        <div class="col-lg-8">

            {{-- Header --}}
            <div class="card border-0 shadow-none mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <h4 class="fw-bold mb-1">{{ $produit->nom }}</h4>
                            @if($produit->reference)
                                <span class="badge bg-secondary-subtle text-secondary">REF: {{ $produit->reference }}</span>
                            @endif
                            @if($produit->categorie)
                                <span class="badge bg-primary-subtle text-primary ms-1">{{ $produit->categorie }}</span>
                            @endif
                        </div>
                        <span class="badge {{ $produit->actif ? 'bg-success' : 'bg-secondary' }} fs-3 flex-shrink-0">
                            {{ $produit->actif ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-4">
                            <p class="text-muted fs-2 mb-1">Prix unitaire</p>
                            <h5 class="fw-bold mb-0">{{ number_format($produit->prix, 2, ',', ' ') }} €</h5>
                        </div>
                        <div class="col-sm-4">
                            <p class="text-muted fs-2 mb-1">Stock</p>
                            <h5 class="fw-bold mb-0 {{ $produit->stock === 0 ? 'text-danger' : '' }}">
                                {{ $produit->stock }} unités
                            </h5>
                        </div>
                        @if($produit->poids_kg)
                            <div class="col-sm-4">
                                <p class="text-muted fs-2 mb-1">Poids</p>
                                <h5 class="fw-bold mb-0">{{ $produit->poids_kg }} kg</h5>
                            </div>
                        @endif
                        @if($produit->dimensions)
                            <div class="col-sm-4">
                                <p class="text-muted fs-2 mb-1">Dimensions</p>
                                <h5 class="fw-bold mb-0 fs-3">{{ $produit->dimensions }}</h5>
                            </div>
                        @endif
                    </div>

                    @if($produit->description)
                        <hr class="my-3">
                        <p class="text-muted fs-2 mb-1">Description</p>
                        <div style="font-size:.88rem;line-height:1.7;">{{ $produit->description }}</div>
                    @endif
                </div>
            </div>

            {{-- Catalogue / Vendeur --}}
            <div class="card border-0 shadow-none mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">
                        <iconify-icon icon="solar:book-2-line-duotone" class="me-1 text-primary"></iconify-icon>
                        Catalogue & Vendeur
                    </h6>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <p class="text-muted fs-2 mb-1">Catalogue</p>
                            <a href="{{ route('admin.catalogues.show', $produit->catalogue_id) }}"
                               class="fw-semibold text-decoration-none">
                                {{ $produit->catalogue->nomAffiche() }}
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted fs-2 mb-1">Vendeur</p>
                            @if($produit->catalogue->vendeur)
                                <a href="{{ route('admin.vendeurs.show', $produit->catalogue->vendeur->id) }}"
                                   class="fw-semibold text-decoration-none">
                                    {{ $produit->catalogue->vendeur->user?->nom ?? '—' }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Photos management --}}
            @if($produit->photos->isNotEmpty())
                <div class="card border-0 shadow-none">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-semibold mb-0">
                                <iconify-icon icon="solar:gallery-line-duotone" class="me-1 text-primary"></iconify-icon>
                                Photos ({{ $produit->photos->count() }})
                            </h6>
                            <a href="{{ route('admin.produits.edit', $produit->id) }}" class="btn btn-sm btn-outline-primary">
                                Gérer les photos
                            </a>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($produit->photos as $photo)
                                <div class="position-relative">
                                    <img src="{{ $photo->assetUrl() }}" alt=""
                                         style="width:80px;height:80px;object-fit:cover;border-radius:8px;
                                                border:2px solid {{ $photo->principale ? '#84b817' : '#e2e8f0' }};">
                                    @if($photo->principale)
                                        <span class="position-absolute top-0 start-0 badge"
                                              style="font-size:.6rem;background:#84b817;border-radius:4px 0 4px 0;">
                                            ✓ Principale
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Delete modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center pt-0">
                    <div class="mb-3 text-danger">
                        <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-1"></iconify-icon>
                    </div>
                    <h6 class="fw-semibold">Supprimer ce produit ?</h6>
                    <p class="text-muted fs-2 mb-3">
                        <strong>{{ $produit->nom }}</strong> et toutes ses photos seront supprimés définitivement.
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <form method="POST" action="{{ route('admin.produits.destroy', $produit->id) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
