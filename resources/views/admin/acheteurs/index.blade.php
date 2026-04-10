@extends('admin.layouts.app')

@section('title', 'Acheteurs')

@section('content')

    {{-- ── Breadcrumb ── --}}
    @include('admin.components.breadcrumb', [
        'items' => ['Acheteurs' => route('admin.acheteurs.index')]
    ])

    {{-- ── Page header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Gestion des Acheteurs</h4>
            <p class="mb-0 text-muted fs-2">{{ number_format($stats['total']) }} acheteurs inscrits</p>
        </div>
    </div>

    {{-- ── Flash messages ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <iconify-icon icon="solar:check-circle-line-duotone" class="me-2"></iconify-icon>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Stat cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-none bg-primary-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="round-48 bg-primary d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                        <iconify-icon icon="solar:users-group-rounded-line-duotone" class="text-white fs-6"></iconify-icon>
                    </div>
                    <div>
                        <p class="mb-0 text-muted fs-2">Total acheteurs</p>
                        <h4 class="mb-0 fw-semibold">{{ $stats['total'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-none bg-success-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="round-48 bg-success d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                        <iconify-icon icon="solar:calendar-add-line-duotone" class="text-white fs-6"></iconify-icon>
                    </div>
                    <div>
                        <p class="mb-0 text-muted fs-2">Ce mois-ci</p>
                        <h4 class="mb-0 fw-semibold">{{ $stats['ce_mois'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-none bg-warning-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="round-48 bg-warning d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                        <iconify-icon icon="solar:cart-large-minimalistic-line-duotone" class="text-white fs-6"></iconify-icon>
                    </div>
                    <div>
                        <p class="mb-0 text-muted fs-2">Avec commandes</p>
                        <h4 class="mb-0 fw-semibold">{{ $stats['avec_commandes'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-none bg-danger-subtle">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="round-48 bg-danger d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                        <iconify-icon icon="solar:user-cross-line-duotone" class="text-white fs-6"></iconify-icon>
                    </div>
                    <div>
                        <p class="mb-0 text-muted fs-2">Sans commandes</p>
                        <h4 class="mb-0 fw-semibold">{{ $stats['sans_commandes'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Table card ── --}}
    <div class="card border-0 shadow-none">
        <div class="card-body p-0">

            {{-- Search bar --}}
            <div class="p-3 border-bottom">
                <form method="GET" action="{{ route('admin.acheteurs.index') }}">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <iconify-icon icon="solar:magnifer-line-duotone"></iconify-icon>
                                </span>
                                <input type="text"
                                       name="search"
                                       class="form-control border-start-0"
                                       placeholder="Rechercher par nom ou email…"
                                       value="{{ $search ?? '' }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary px-4">
                                Rechercher
                            </button>
                            @if($search)
                                <a href="{{ route('admin.acheteurs.index') }}" class="btn btn-outline-secondary ms-1">
                                    Réinitialiser
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Data table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Acheteur</th>
                            <th>Email</th>
                            <th class="text-center">Commandes</th>
                            <th class="text-center">Panier</th>
                            <th>Inscrit le</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($acheteurs as $acheteur)
                            <tr>
                                {{-- ID --}}
                                <td class="ps-3 text-muted fs-2">#{{ $acheteur->id }}</td>

                                {{-- Avatar + nom --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="round-40 rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width:40px;height:40px;font-weight:600;color:var(--bs-primary);">
                                            {{ strtoupper(substr($acheteur->nom, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-semibold">{{ $acheteur->nom }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="text-muted">{{ $acheteur->email }}</td>

                                {{-- Commandes count --}}
                                <td class="text-center">
                                    @if($acheteur->commandes_count > 0)
                                        <span class="badge bg-primary-subtle text-primary fw-semibold">
                                            {{ $acheteur->commandes_count }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Panier items --}}
                                <td class="text-center">
                                    @if($acheteur->panier_items > 0)
                                        <span class="badge bg-warning-subtle text-warning fw-semibold">
                                            {{ $acheteur->panier_items }} article(s)
                                        </span>
                                    @else
                                        <span class="text-muted">Vide</span>
                                    @endif
                                </td>

                                {{-- Date inscription --}}
                                <td class="text-muted">{{ $acheteur->created_at->format('d/m/Y') }}</td>

                                {{-- Actions --}}
                                <td class="text-end pe-3">
                                    <div class="d-flex justify-content-end gap-1">
                                        {{-- Voir --}}
                                        <a href="{{ route('admin.acheteurs.show', $acheteur->id) }}"
                                           class="btn btn-sm btn-outline-primary" title="Voir le profil">
                                            <iconify-icon icon="solar:eye-line-duotone"></iconify-icon>
                                        </a>
                                        {{-- Modifier --}}
                                        <a href="{{ route('admin.acheteurs.edit', $acheteur->id) }}"
                                           class="btn btn-sm btn-outline-warning" title="Modifier">
                                            <iconify-icon icon="solar:pen-line-duotone"></iconify-icon>
                                        </a>
                                        {{-- Supprimer --}}
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Supprimer"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $acheteur->id }}">
                                            <iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
                                        </button>
                                    </div>

                                    {{-- Delete confirmation modal --}}
                                    <div class="modal fade" id="deleteModal{{ $acheteur->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-sm modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0 pb-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center pt-0">
                                                    <div class="mb-3 text-danger">
                                                        <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-1"></iconify-icon>
                                                    </div>
                                                    <h6 class="fw-semibold">Supprimer ce compte ?</h6>
                                                    <p class="text-muted fs-2 mb-3">
                                                        <strong>{{ $acheteur->nom }}</strong> sera supprimé définitivement
                                                        avec toutes ses commandes et son panier.
                                                    </p>
                                                </div>
                                                <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                                        Annuler
                                                    </button>
                                                    <form method="POST"
                                                          action="{{ route('admin.acheteurs.destroy', $acheteur->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <iconify-icon icon="solar:users-group-rounded-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                                    {{ $search ? 'Aucun acheteur trouvé pour « ' . $search . ' ».' : 'Aucun acheteur inscrit pour le moment.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($acheteurs->hasPages())
                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top">
                    <p class="mb-0 text-muted fs-2">
                        Affichage de {{ $acheteurs->firstItem() }} à {{ $acheteurs->lastItem() }}
                        sur {{ $acheteurs->total() }} acheteurs
                    </p>
                    {{ $acheteurs->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>

@endsection
