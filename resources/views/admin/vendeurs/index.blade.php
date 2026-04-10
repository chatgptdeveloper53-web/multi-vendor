@extends('admin.layouts.app')

@section('title', 'Vendeurs')

@section('content')

    {{-- ── Breadcrumb ── --}}
    @include('admin.components.breadcrumb', [
        'items' => ['Vendeurs' => route('admin.vendeurs.index')]
    ])

    {{-- ── Page header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Gestion des Vendeurs</h4>
            <p class="mb-0 text-muted fs-2">{{ number_format($stats['total']) }} vendeurs inscrits</p>
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
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <iconify-icon icon="solar:danger-triangle-line-duotone" class="me-2"></iconify-icon>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Stat cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('admin.vendeurs.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-none bg-primary-subtle">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="round-48 bg-primary d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                            <iconify-icon icon="solar:shop-line-duotone" class="text-white fs-6"></iconify-icon>
                        </div>
                        <div>
                            <p class="mb-0 text-muted fs-2">Total vendeurs</p>
                            <h4 class="mb-0 fw-semibold text-dark">{{ $stats['total'] }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('admin.vendeurs.index', ['statut' => 'EN_ATTENTE']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-none bg-warning-subtle">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="round-48 bg-warning d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                            <iconify-icon icon="solar:clock-circle-line-duotone" class="text-white fs-6"></iconify-icon>
                        </div>
                        <div>
                            <p class="mb-0 text-muted fs-2">En attente</p>
                            <h4 class="mb-0 fw-semibold text-dark">{{ $stats['en_attente'] }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('admin.vendeurs.index', ['statut' => 'VALIDE']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-none bg-success-subtle">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="round-48 bg-success d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                            <iconify-icon icon="solar:check-circle-line-duotone" class="text-white fs-6"></iconify-icon>
                        </div>
                        <div>
                            <p class="mb-0 text-muted fs-2">Validés</p>
                            <h4 class="mb-0 fw-semibold text-dark">{{ $stats['valides'] }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('admin.vendeurs.index', ['statut' => 'REJETE']) }}" class="text-decoration-none">
                <div class="card border-0 shadow-none bg-danger-subtle">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="round-48 bg-danger d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                            <iconify-icon icon="solar:close-circle-line-duotone" class="text-white fs-6"></iconify-icon>
                        </div>
                        <div>
                            <p class="mb-0 text-muted fs-2">Rejetés</p>
                            <h4 class="mb-0 fw-semibold text-dark">{{ $stats['rejetes'] }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- ── Table card ── --}}
    <div class="card border-0 shadow-none">
        <div class="card-body p-0">

            {{-- Filters bar --}}
            <div class="p-3 border-bottom">
                <form method="GET" action="{{ route('admin.vendeurs.index') }}">
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
                        <div class="col-md-3">
                            <select name="statut" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="EN_ATTENTE" @selected($statut === 'EN_ATTENTE')>En attente</option>
                                <option value="VALIDE"     @selected($statut === 'VALIDE')>Validé</option>
                                <option value="REJETE"     @selected($statut === 'REJETE')>Rejeté</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary px-4">Filtrer</button>
                            @if($search || $statut)
                                <a href="{{ route('admin.vendeurs.index') }}" class="btn btn-outline-secondary ms-1">
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
                            <th>Vendeur</th>
                            <th>Email</th>
                            <th class="text-center">Documents</th>
                            <th class="text-center">Statut dossier</th>
                            <th class="text-center">Profil</th>
                            <th>Inscrit le</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendeurs as $vendeur)
                            <tr>
                                {{-- ID --}}
                                <td class="ps-3 text-muted fs-2">#{{ $vendeur->id }}</td>

                                {{-- Avatar + nom --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width:40px;height:40px;font-weight:600;font-size:1rem;
                                                    background:var(--bs-warning-bg-subtle);color:var(--bs-warning-text-emphasis);">
                                            {{ strtoupper(substr($vendeur->user->nom ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-semibold">{{ $vendeur->user->nom ?? '—' }}</p>
                                            @if($vendeur->coordonnees)
                                                <p class="mb-0 text-muted fs-2" style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                    {{ $vendeur->coordonnees }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td class="text-muted">{{ $vendeur->user->email ?? '—' }}</td>

                                {{-- Documents --}}
                                <td class="text-center">
                                    @if($vendeur->documents_count > 0)
                                        <span class="badge bg-info-subtle text-info fw-semibold">
                                            {{ $vendeur->documents_count }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Statut --}}
                                <td class="text-center">
                                    @php
                                        $statutVal = $vendeur->statut_onboarding instanceof \App\Enums\StatutDossier
                                            ? $vendeur->statut_onboarding->value
                                            : $vendeur->statut_onboarding;
                                        [$badgeCls, $badgeLabel] = match($statutVal) {
                                            'EN_ATTENTE' => ['bg-warning-subtle text-warning',  'En attente'],
                                            'VALIDE'     => ['bg-success-subtle text-success',  'Validé'],
                                            'REJETE'     => ['bg-danger-subtle text-danger',    'Rejeté'],
                                            default      => ['bg-secondary-subtle text-secondary', $statutVal],
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeCls }} fw-semibold">{{ $badgeLabel }}</span>
                                </td>

                                {{-- Profil complet --}}
                                <td class="text-center">
                                    @if($vendeur->profil_complet)
                                        <iconify-icon icon="solar:check-circle-bold-duotone" class="text-success fs-5"></iconify-icon>
                                    @else
                                        <iconify-icon icon="solar:close-circle-bold-duotone" class="text-danger fs-5"></iconify-icon>
                                    @endif
                                </td>

                                {{-- Date --}}
                                <td class="text-muted">{{ $vendeur->created_at->format('d/m/Y') }}</td>

                                {{-- Actions --}}
                                <td class="text-end pe-3">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.vendeurs.show', $vendeur->id) }}"
                                           class="btn btn-sm btn-outline-primary" title="Voir le profil">
                                            <iconify-icon icon="solar:eye-line-duotone"></iconify-icon>
                                        </a>
                                        <a href="{{ route('admin.vendeurs.edit', $vendeur->id) }}"
                                           class="btn btn-sm btn-outline-warning" title="Modifier">
                                            <iconify-icon icon="solar:pen-line-duotone"></iconify-icon>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Supprimer"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $vendeur->id }}">
                                            <iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
                                        </button>
                                    </div>

                                    {{-- Delete modal --}}
                                    <div class="modal fade" id="deleteModal{{ $vendeur->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-sm modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0 pb-0">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center pt-0">
                                                    <div class="mb-3 text-danger">
                                                        <iconify-icon icon="solar:trash-bin-trash-bold-duotone" class="fs-1"></iconify-icon>
                                                    </div>
                                                    <h6 class="fw-semibold">Supprimer ce vendeur ?</h6>
                                                    <p class="text-muted fs-2 mb-3">
                                                        <strong>{{ $vendeur->user->nom ?? '—' }}</strong> sera supprimé
                                                        définitivement avec tout son profil.
                                                    </p>
                                                </div>
                                                <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                                                        Annuler
                                                    </button>
                                                    <form method="POST"
                                                          action="{{ route('admin.vendeurs.destroy', $vendeur->id) }}">
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
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <iconify-icon icon="solar:shop-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                                    {{ $search || $statut ? 'Aucun vendeur trouvé pour ces critères.' : 'Aucun vendeur inscrit pour le moment.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($vendeurs->hasPages())
                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top">
                    <p class="mb-0 text-muted fs-2">
                        Affichage de {{ $vendeurs->firstItem() }} à {{ $vendeurs->lastItem() }}
                        sur {{ $vendeurs->total() }} vendeurs
                    </p>
                    {{ $vendeurs->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>

@endsection
