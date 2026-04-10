@extends('admin.layouts.app')

@section('title', 'Vendeur — ' . ($vendeur->user->nom ?? '—'))

@section('content')

    {{-- ── Breadcrumb ── --}}
    @include('admin.components.breadcrumb', [
        'items' => [
            'Vendeurs'            => route('admin.vendeurs.index'),
            $vendeur->user->nom ?? '—' => '#',
        ]
    ])

    {{-- ── Flash messages ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <iconify-icon icon="solar:check-circle-line-duotone" class="me-2"></iconify-icon>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            <iconify-icon icon="solar:danger-triangle-line-duotone" class="me-2"></iconify-icon>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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

    <div class="row g-4">

        {{-- ══════════════════════════════════════════════
             LEFT — Profile card + actions
        ══════════════════════════════════════════════ --}}
        <div class="col-lg-4">

            {{-- Profile card --}}
            <div class="card border-0 shadow-none text-center mb-3">
                <div class="card-body p-4">
                    {{-- Avatar --}}
                    <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3"
                         style="width:72px;height:72px;font-size:1.8rem;font-weight:700;
                                background:var(--bs-warning-bg-subtle);color:var(--bs-warning-text-emphasis);">
                        {{ strtoupper(substr($vendeur->user->nom ?? '?', 0, 1)) }}
                    </div>

                    <h5 class="fw-semibold mb-1">{{ $vendeur->user->nom ?? '—' }}</h5>
                    <p class="text-muted mb-2">{{ $vendeur->user->email ?? '—' }}</p>

                    <span class="badge {{ $badgeCls }} px-3 py-1 rounded-pill mb-3">
                        @if($statutVal === 'EN_ATTENTE')
                            <iconify-icon icon="solar:clock-circle-line-duotone" class="me-1"></iconify-icon>
                        @elseif($statutVal === 'VALIDE')
                            <iconify-icon icon="solar:check-circle-line-duotone" class="me-1"></iconify-icon>
                        @else
                            <iconify-icon icon="solar:close-circle-line-duotone" class="me-1"></iconify-icon>
                        @endif
                        {{ $badgeLabel }}
                    </span>

                    <hr class="my-3">

                    <div class="text-start">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <iconify-icon icon="solar:calendar-line-duotone" class="text-muted"></iconify-icon>
                            <span class="text-muted fs-2">Inscrit le</span>
                            <span class="ms-auto fw-semibold">{{ $vendeur->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <iconify-icon icon="solar:shield-check-line-duotone" class="text-muted"></iconify-icon>
                            <span class="text-muted fs-2">Profil complet</span>
                            <span class="ms-auto">
                                @if($vendeur->profil_complet)
                                    <span class="badge bg-success-subtle text-success">Oui</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Non</span>
                                @endif
                            </span>
                        </div>
                        @if($vendeur->coordonnees)
                            <div class="d-flex align-items-start gap-2 mb-2">
                                <iconify-icon icon="solar:map-point-line-duotone" class="text-muted mt-1"></iconify-icon>
                                <span class="text-muted fs-2">Coordonnées</span>
                                <span class="ms-auto fw-semibold text-end" style="max-width:160px;">{{ $vendeur->coordonnees }}</span>
                            </div>
                        @endif
                        @if($vendeur->rib)
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <iconify-icon icon="solar:card-line-duotone" class="text-muted"></iconify-icon>
                                <span class="text-muted fs-2">RIB</span>
                                <span class="ms-auto fw-semibold">{{ $vendeur->rib }}</span>
                            </div>
                        @endif
                    </div>

                    <hr class="my-3">

                    {{-- Stat mini-cards --}}
                    <div class="row g-2 text-center mb-3">
                        <div class="col-4">
                            <div class="p-2 rounded bg-info-subtle">
                                <h5 class="mb-0 fw-bold text-info">{{ $stats['nb_documents'] }}</h5>
                                <p class="mb-0 fs-2 text-muted">Docs</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-primary-subtle">
                                <h5 class="mb-0 fw-bold text-primary">{{ $stats['nb_produits'] }}</h5>
                                <p class="mb-0 fs-2 text-muted">Produits</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-secondary-subtle">
                                <h5 class="mb-0 fw-bold text-secondary">{{ $stats['nb_logistiques'] }}</h5>
                                <p class="mb-0 fs-2 text-muted">Logistique</p>
                            </div>
                        </div>
                    </div>

                    {{-- Action buttons --}}
                    <div class="d-flex gap-2 flex-column">
                        @if($statutVal === 'EN_ATTENTE')
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('admin.vendeurs.valider', $vendeur->id) }}" class="flex-fill">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                        <iconify-icon icon="solar:check-circle-line-duotone" class="me-1"></iconify-icon>
                                        Valider
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.vendeurs.rejeter', $vendeur->id) }}" class="flex-fill">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                        <iconify-icon icon="solar:close-circle-line-duotone" class="me-1"></iconify-icon>
                                        Rejeter
                                    </button>
                                </form>
                            </div>
                        @elseif($statutVal === 'REJETE')
                            <form method="POST" action="{{ route('admin.vendeurs.valider', $vendeur->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    <iconify-icon icon="solar:check-circle-line-duotone" class="me-1"></iconify-icon>
                                    Valider quand même
                                </button>
                            </form>
                        @elseif($statutVal === 'VALIDE')
                            <form method="POST" action="{{ route('admin.vendeurs.rejeter', $vendeur->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                    <iconify-icon icon="solar:close-circle-line-duotone" class="me-1"></iconify-icon>
                                    Révoquer la validation
                                </button>
                            </form>
                        @endif

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.vendeurs.edit', $vendeur->id) }}"
                               class="btn btn-primary btn-sm w-100">
                                <iconify-icon icon="solar:pen-line-duotone" class="me-1"></iconify-icon>
                                Modifier
                            </a>
                            <button type="button"
                                    class="btn btn-outline-danger btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ══════════════════════════════════════════════
             RIGHT — Documents + Catalogue + Infos légales
        ══════════════════════════════════════════════ --}}
        <div class="col-lg-8">

            {{-- Documents --}}
            <div class="card border-0 shadow-none mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0 fw-semibold">
                            <iconify-icon icon="solar:document-text-line-duotone" class="me-2 text-info"></iconify-icon>
                            Documents soumis
                        </h6>
                        <span class="badge bg-info-subtle text-info">{{ $stats['nb_documents'] }}</span>
                    </div>

                    @if($vendeur->documents->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Fichier</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendeur->documents as $doc)
                                        <tr>
                                            <td class="fw-semibold">{{ $doc->type_document ?? '—' }}</td>
                                            <td>
                                                @if($doc->fichier)
                                                    <a href="{{ asset('storage/' . $doc->fichier) }}"
                                                       target="_blank"
                                                       class="text-primary text-decoration-none">
                                                        <iconify-icon icon="solar:file-download-line-duotone" class="me-1"></iconify-icon>
                                                        Télécharger
                                                    </a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $docStatut = $doc->statut ?? 'EN_ATTENTE';
                                                    [$dBg, $dLabel] = match($docStatut) {
                                                        'EN_ATTENTE' => ['bg-warning-subtle text-warning', 'En attente'],
                                                        'VALIDE'     => ['bg-success-subtle text-success', 'Validé'],
                                                        'REJETE'     => ['bg-danger-subtle text-danger',   'Rejeté'],
                                                        default      => ['bg-secondary-subtle text-secondary', $docStatut],
                                                    };
                                                @endphp
                                                <span class="badge {{ $dBg }}">{{ $dLabel }}</span>
                                            </td>
                                            <td class="text-muted">{{ $doc->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <iconify-icon icon="solar:document-add-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                            Aucun document soumis.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Catalogue --}}
            <div class="card border-0 shadow-none mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0 fw-semibold">
                            <iconify-icon icon="solar:bag-5-line-duotone" class="me-2 text-primary"></iconify-icon>
                            Catalogue & Produits
                        </h6>
                        <span class="badge bg-primary-subtle text-primary">{{ $stats['nb_produits'] }} produit(s)</span>
                    </div>

                    @if($vendeur->catalogue && $vendeur->catalogue->produits->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-end">Prix</th>
                                        <th class="text-center">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendeur->catalogue->produits->take(10) as $produit)
                                        <tr>
                                            <td class="fw-semibold">{{ $produit->nom }}</td>
                                            <td class="text-end">{{ number_format($produit->prix, 2) }}€</td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    {{ $produit->stock ?? 0 }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($vendeur->catalogue->produits->count() > 10)
                            <p class="text-muted fs-2 text-end mt-2">
                                … et {{ $vendeur->catalogue->produits->count() - 10 }} autres produits
                            </p>
                        @endif
                    @else
                        <div class="text-center text-muted py-4">
                            <iconify-icon icon="solar:bag-cross-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                            Aucun produit dans le catalogue.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Informations légales --}}
            @if($vendeur->informations_legales)
                <div class="card border-0 shadow-none">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3">
                            <iconify-icon icon="solar:shield-keyhole-line-duotone" class="me-2 text-secondary"></iconify-icon>
                            Informations légales
                        </h6>
                        <p class="text-muted mb-0" style="white-space:pre-wrap;">{{ $vendeur->informations_legales }}</p>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- ── Delete modal ── --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold">Supprimer ce vendeur ?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="alert alert-danger d-flex gap-2 align-items-start">
                        <iconify-icon icon="solar:danger-triangle-line-duotone" class="flex-shrink-0 fs-5 mt-1"></iconify-icon>
                        <div>
                            <strong>Action irréversible.</strong><br>
                            Le compte de <strong>{{ $vendeur->user->nom ?? '—' }}</strong> sera supprimé
                            définitivement avec son profil, ses documents et son catalogue.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form method="POST" action="{{ route('admin.vendeurs.destroy', $vendeur->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <iconify-icon icon="solar:trash-bin-trash-line-duotone" class="me-1"></iconify-icon>
                            Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
