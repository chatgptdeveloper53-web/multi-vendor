@extends('admin.layouts.app')
@section('title', 'Catalogues')

@section('content')

    @include('admin.components.breadcrumb', [
        'items' => ['Catalogues' => route('admin.catalogues.index')]
    ])

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Catalogues vendeurs</h4>
            <p class="mb-0 text-muted fs-2">{{ number_format($stats['total']) }} catalogues au total</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-none bg-primary-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="round-48 bg-primary d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                        <iconify-icon icon="solar:book-2-line-duotone" class="text-white fs-6"></iconify-icon>
                    </div>
                    <div>
                        <p class="mb-0 text-muted fs-2">Total catalogues</p>
                        <h4 class="mb-0 fw-semibold text-dark">{{ $stats['total'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="{{ route('admin.catalogues.index', ['actif' => '1']) }}" class="text-decoration-none">
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
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-none bg-warning-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="round-48 bg-warning d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                        <iconify-icon icon="solar:tag-line-duotone" class="text-white fs-6"></iconify-icon>
                    </div>
                    <div>
                        <p class="mb-0 text-muted fs-2">Vides (0 produit)</p>
                        <h4 class="mb-0 fw-semibold text-dark">{{ $stats['vides'] }}</h4>
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
                <form method="GET" action="{{ route('admin.catalogues.index') }}">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <iconify-icon icon="solar:magnifer-line-duotone"></iconify-icon>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0"
                                       placeholder="Nom du catalogue ou du vendeur…"
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
                            @if($search || $actif !== null)
                                <a href="{{ route('admin.catalogues.index') }}" class="btn btn-outline-secondary ms-1">
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
                            <th class="ps-3">#</th>
                            <th>Catalogue</th>
                            <th>Vendeur</th>
                            <th class="text-center">Produits</th>
                            <th class="text-center">Statut</th>
                            <th>Créé le</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($catalogues as $catalogue)
                            <tr>
                                <td class="ps-3 text-muted fs-2">#{{ $catalogue->id }}</td>

                                <td>
                                    <div class="fw-semibold">{{ $catalogue->nomAffiche() }}</div>
                                    @if($catalogue->description)
                                        <div class="text-muted fs-2"
                                             style="max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $catalogue->description }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    @if($catalogue->vendeur)
                                        <a href="{{ route('admin.vendeurs.show', $catalogue->vendeur->id) }}"
                                           class="text-decoration-none fw-semibold">
                                            {{ $catalogue->vendeur->user?->nom ?? '—' }}
                                        </a>
                                        <div class="text-muted fs-2">{{ $catalogue->vendeur->raison_sociale ?? '' }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.catalogues.show', $catalogue->id) }}"
                                       class="badge bg-primary-subtle text-primary text-decoration-none fw-semibold">
                                        {{ $catalogue->produits_count }}
                                    </a>
                                </td>

                                <td class="text-center">
                                    <form method="POST" action="{{ route('admin.catalogues.toggle', $catalogue->id) }}">
                                        @csrf
                                        <button type="submit"
                                                class="badge border-0 {{ $catalogue->actif ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}"
                                                style="cursor:pointer;font-size:.72rem;">
                                            {{ $catalogue->actif ? 'Actif' : 'Inactif' }}
                                        </button>
                                    </form>
                                </td>

                                <td class="text-muted">{{ $catalogue->created_at->format('d/m/Y') }}</td>

                                <td class="text-end pe-3">
                                    <a href="{{ route('admin.catalogues.show', $catalogue->id) }}"
                                       class="btn btn-sm btn-outline-primary" title="Voir le catalogue">
                                        <iconify-icon icon="solar:eye-line-duotone"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <iconify-icon icon="solar:book-2-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                                    Aucun catalogue trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($catalogues->hasPages())
                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top">
                    <p class="mb-0 text-muted fs-2">
                        {{ $catalogues->firstItem() }}–{{ $catalogues->lastItem() }} sur {{ $catalogues->total() }}
                    </p>
                    {{ $catalogues->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>

@endsection
