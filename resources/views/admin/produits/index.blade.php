@extends('admin.layouts.app')
@section('title', 'Produits')

@section('content')

    @include('admin.components.breadcrumb', [
        'items' => ['Produits' => route('admin.produits.index')]
    ])

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Gestion des Produits</h4>
            <p class="mb-0 text-muted fs-2">{{ number_format($stats['total']) }} produits au total</p>
        </div>
        <a href="{{ route('admin.produits.create') }}" class="btn btn-primary">
            <iconify-icon icon="solar:add-circle-line-duotone" class="me-1"></iconify-icon>
            Nouveau produit
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <iconify-icon icon="solar:check-circle-line-duotone" class="me-2"></iconify-icon>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-none bg-primary-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="round-48 bg-primary d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                        <iconify-icon icon="solar:tag-line-duotone" class="text-white fs-6"></iconify-icon>
                    </div>
                    <div>
                        <p class="mb-0 text-muted fs-2">Total produits</p>
                        <h4 class="mb-0 fw-semibold text-dark">{{ $stats['total'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('admin.produits.index', ['actif' => '1']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-none bg-success-subtle">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="round-48 bg-success d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                            <iconify-icon icon="solar:check-circle-line-duotone" class="text-white fs-6"></iconify-icon>
                        </div>
                        <div>
                            <p class="mb-0 text-muted fs-2">Actifs</p>
                            <h4 class="mb-0 fw-semibold text-dark">{{ $stats['actifs'] }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('admin.produits.index', ['actif' => '0']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-none bg-warning-subtle">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="round-48 bg-warning d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                            <iconify-icon icon="solar:gallery-minimalistic-line-duotone" class="text-white fs-6"></iconify-icon>
                        </div>
                        <div>
                            <p class="mb-0 text-muted fs-2">Sans photo</p>
                            <h4 class="mb-0 fw-semibold text-dark">{{ $stats['sans_photo'] }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-none bg-danger-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="round-48 bg-danger d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                        <iconify-icon icon="solar:cart-cross-line-duotone" class="text-white fs-6"></iconify-icon>
                    </div>
                    <div>
                        <p class="mb-0 text-muted fs-2">Stock zéro</p>
                        <h4 class="mb-0 fw-semibold text-dark">{{ $stats['stock_zero'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table card --}}
    <div class="card border-0 shadow-none">
        <div class="card-body p-0">

            {{-- Filters --}}
            <div class="p-3 border-bottom">
                <form method="GET" action="{{ route('admin.produits.index') }}">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <iconify-icon icon="solar:magnifer-line-duotone"></iconify-icon>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0"
                                       placeholder="Nom, référence, catégorie…"
                                       value="{{ $search ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="catalogue_id" class="form-select">
                                <option value="">Tous les catalogues</option>
                                @foreach($catalogues as $cat)
                                    <option value="{{ $cat->id }}" @selected($catalogueId == $cat->id)>
                                        {{ $cat->nomAffiche() }}
                                        @if($cat->vendeur?->user)— {{ $cat->vendeur->user->nom }}@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="actif" class="form-select">
                                <option value="">Tous statuts</option>
                                <option value="1" @selected($actif === '1')>Actifs</option>
                                <option value="0" @selected($actif === '0')>Inactifs</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="categorie" class="form-select">
                                <option value="">Toutes catégories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" @selected($categorie === $cat)>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                            @if($search || $catalogueId || $actif !== null || $categorie)
                                <a href="{{ route('admin.produits.index') }}" class="btn btn-outline-secondary ms-1">
                                    Réinitialiser
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:60px;">#</th>
                            <th style="width:56px;">Photo</th>
                            <th>Produit</th>
                            <th>Catalogue / Vendeur</th>
                            <th class="text-end">Prix</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Statut</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produits as $produit)
                            <tr>
                                <td class="ps-3 text-muted fs-2">#{{ $produit->id }}</td>

                                {{-- Miniature --}}
                                <td>
                                    @php $thumb = $produit->photoUrl(); @endphp
                                    @if($thumb)
                                        <img src="{{ $thumb }}" alt=""
                                             style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center rounded"
                                             style="width:40px;height:40px;background:#f1f5f9;">
                                            <iconify-icon icon="solar:gallery-minimalistic-line-duotone"
                                                          style="color:#94a3b8;font-size:1.1rem;"></iconify-icon>
                                        </div>
                                    @endif
                                </td>

                                {{-- Nom + ref + catégorie --}}
                                <td>
                                    <div class="fw-semibold">{{ $produit->nom }}</div>
                                    <div class="text-muted fs-2">
                                        @if($produit->reference) REF: {{ $produit->reference }} · @endif
                                        @if($produit->categorie)<span class="badge bg-secondary-subtle text-secondary">{{ $produit->categorie }}</span>@endif
                                    </div>
                                </td>

                                {{-- Catalogue --}}
                                <td>
                                    <a href="{{ route('admin.catalogues.show', $produit->catalogue_id) }}"
                                       class="text-decoration-none fw-semibold">
                                        {{ $produit->catalogue->nomAffiche() }}
                                    </a>
                                    <div class="text-muted fs-2">
                                        {{ $produit->catalogue->vendeur?->user?->nom ?? '—' }}
                                    </div>
                                </td>

                                {{-- Prix --}}
                                <td class="text-end fw-semibold">
                                    {{ number_format($produit->prix, 2, ',', ' ') }} €
                                    @if($produit->poids_kg)
                                        <div class="text-muted fs-2">{{ $produit->poids_kg }} kg</div>
                                    @endif
                                </td>

                                {{-- Stock --}}
                                <td class="text-center">
                                    <span class="badge {{ $produit->stock > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} fw-semibold">
                                        {{ $produit->stock }}
                                    </span>
                                </td>

                                {{-- Statut actif --}}
                                <td class="text-center">
                                    <form method="POST" action="{{ route('admin.produits.toggle', $produit->id) }}">
                                        @csrf
                                        <button type="submit"
                                                class="badge border-0 {{ $produit->actif ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}"
                                                style="cursor:pointer;font-size:.72rem;"
                                                title="Cliquer pour {{ $produit->actif ? 'désactiver' : 'activer' }}">
                                            {{ $produit->actif ? 'Actif' : 'Inactif' }}
                                        </button>
                                    </form>
                                </td>

                                {{-- Actions --}}
                                <td class="text-end pe-3">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.produits.show', $produit->id) }}"
                                           class="btn btn-sm btn-outline-primary" title="Voir">
                                            <iconify-icon icon="solar:eye-line-duotone"></iconify-icon>
                                        </a>
                                        <a href="{{ route('admin.produits.edit', $produit->id) }}"
                                           class="btn btn-sm btn-outline-warning" title="Modifier">
                                            <iconify-icon icon="solar:pen-line-duotone"></iconify-icon>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#delModal{{ $produit->id }}">
                                            <iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
                                        </button>
                                    </div>

                                    {{-- Delete modal --}}
                                    <div class="modal fade" id="delModal{{ $produit->id }}" tabindex="-1">
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
                                                        <strong>{{ $produit->nom }}</strong> et toutes ses photos
                                                        seront supprimés définitivement.
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <iconify-icon icon="solar:tag-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                                    Aucun produit trouvé.
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

@endsection
