@extends('admin.layouts.app')
@section('title', $catalogue->nomAffiche())

@section('content')

    @include('admin.components.breadcrumb', [
        'items' => [
            'Catalogues'             => route('admin.catalogues.index'),
            $catalogue->nomAffiche() => route('admin.catalogues.show', $catalogue->id),
        ]
    ])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- ── Left: Catalogue info ── --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-none mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">{{ $catalogue->nomAffiche() }}</h5>
                            <span class="badge {{ $catalogue->actif ? 'bg-success' : 'bg-secondary' }}">
                                {{ $catalogue->actif ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                        <div class="text-muted fs-2">#{{ $catalogue->id }}</div>
                    </div>

                    @if($catalogue->description)
                        <p class="text-muted" style="font-size:.84rem;line-height:1.6;">{{ $catalogue->description }}</p>
                    @endif

                    <hr class="my-3">

                    {{-- Vendeur info --}}
                    <div class="mb-3">
                        <p class="text-muted fs-2 mb-1">Vendeur</p>
                        @if($catalogue->vendeur)
                            <a href="{{ route('admin.vendeurs.show', $catalogue->vendeur->id) }}"
                               class="fw-semibold text-decoration-none">
                                {{ $catalogue->vendeur->user?->nom ?? '—' }}
                            </a>
                            @if($catalogue->vendeur->raison_sociale)
                                <div class="text-muted fs-2">{{ $catalogue->vendeur->raison_sociale }}</div>
                            @endif
                        @else
                            <span class="text-muted">Aucun vendeur associé</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <p class="text-muted fs-2 mb-1">Produits</p>
                        <h5 class="fw-bold mb-0">{{ $produits->total() }}</h5>
                    </div>

                    <div>
                        <p class="text-muted fs-2 mb-1">Créé le</p>
                        <div>{{ $catalogue->created_at->format('d/m/Y') }}</div>
                    </div>

                    <hr class="my-3">

                    {{-- Actions --}}
                    <form method="POST" action="{{ route('admin.catalogues.toggle', $catalogue->id) }}">
                        @csrf
                        <button type="submit"
                                class="btn w-100 {{ $catalogue->actif ? 'btn-outline-warning' : 'btn-outline-success' }}">
                            <iconify-icon icon="{{ $catalogue->actif ? 'solar:eye-closed-line-duotone' : 'solar:eye-line-duotone' }}" class="me-1"></iconify-icon>
                            {{ $catalogue->actif ? 'Désactiver' : 'Activer' }} le catalogue
                        </button>
                    </form>

                    <a href="{{ route('admin.produits.create', ['catalogue_id' => $catalogue->id]) }}"
                       class="btn btn-primary w-100 mt-2">
                        <iconify-icon icon="solar:add-circle-line-duotone" class="me-1"></iconify-icon>
                        Ajouter un produit
                    </a>
                </div>
            </div>

            {{-- Logistique associée --}}
            @if($catalogue->logistique)
                <div class="card border-0 shadow-none">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">
                            <iconify-icon icon="solar:map-arrow-right-line-duotone" class="me-1 text-primary"></iconify-icon>
                            Logistique associée
                        </h6>
                        <div class="row g-2">
                            @if($catalogue->logistique->incoterm)
                                <div class="col-6">
                                    <p class="text-muted fs-2 mb-0">Incoterm</p>
                                    <div class="fw-semibold">{{ $catalogue->logistique->incoterm }}</div>
                                </div>
                            @endif
                            @if($catalogue->logistique->zone)
                                <div class="col-6">
                                    <p class="text-muted fs-2 mb-0">Zone</p>
                                    <div class="fw-semibold">{{ $catalogue->logistique->zone }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- ── Right: Products list ── --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-none">
                <div class="card-body p-0">

                    {{-- Filters --}}
                    <div class="p-3 border-bottom">
                        <form method="GET" action="{{ route('admin.catalogues.show', $catalogue->id) }}">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <iconify-icon icon="solar:magnifer-line-duotone"></iconify-icon>
                                        </span>
                                        <input type="text" name="search" class="form-control border-start-0"
                                               placeholder="Nom, référence…"
                                               value="{{ $search ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select name="actif" class="form-select">
                                        <option value="">Tous statuts</option>
                                        <option value="1" @selected($actif === '1')>Actifs</option>
                                        <option value="0" @selected($actif === '0')>Inactifs</option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">Filtrer</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Products table --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width:52px;">Photo</th>
                                    <th>Produit</th>
                                    <th class="text-end">Prix</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center">Photos</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produits as $produit)
                                    <tr>
                                        <td class="ps-3">
                                            @php $thumb = $produit->photoUrl(); @endphp
                                            @if($thumb)
                                                <img src="{{ $thumb }}" alt=""
                                                     style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center rounded"
                                                     style="width:40px;height:40px;background:#f1f5f9;">
                                                    <iconify-icon icon="solar:gallery-minimalistic-line-duotone"
                                                                  style="color:#94a3b8;font-size:1rem;"></iconify-icon>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $produit->nom }}</div>
                                            <div class="text-muted fs-2">
                                                @if($produit->reference) REF: {{ $produit->reference }} @endif
                                                @if($produit->categorie)
                                                    <span class="badge bg-secondary-subtle text-secondary">{{ $produit->categorie }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-end fw-semibold">{{ number_format($produit->prix, 2, ',', ' ') }} €</td>
                                        <td class="text-center">
                                            <span class="badge {{ $produit->stock > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                                {{ $produit->stock }}
                                            </span>
                                        </td>
                                        <td class="text-center text-muted">{{ $produit->photos_count }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $produit->actif ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                                {{ $produit->actif ? 'Actif' : 'Inactif' }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <div class="d-flex justify-content-end gap-1">
                                                <a href="{{ route('admin.produits.show', $produit->id) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <iconify-icon icon="solar:eye-line-duotone"></iconify-icon>
                                                </a>
                                                <a href="{{ route('admin.produits.edit', $produit->id) }}"
                                                   class="btn btn-sm btn-outline-warning">
                                                    <iconify-icon icon="solar:pen-line-duotone"></iconify-icon>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <iconify-icon icon="solar:tag-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                                            Aucun produit dans ce catalogue.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($produits->hasPages())
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top">
                            <p class="mb-0 text-muted fs-2">
                                {{ $produits->firstItem() }}–{{ $produits->lastItem() }} sur {{ $produits->total() }}
                            </p>
                            {{ $produits->links('pagination::bootstrap-5') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection
