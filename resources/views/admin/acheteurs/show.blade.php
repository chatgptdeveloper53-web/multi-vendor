@extends('admin.layouts.app')

@section('title', 'Profil — ' . $acheteur->nom)

@section('content')

    {{-- ── Breadcrumb ── --}}
    @include('admin.components.breadcrumb', [
        'items' => [
            'Acheteurs' => route('admin.acheteurs.index'),
            $acheteur->nom => '#',
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

    <div class="row g-4">

        {{-- ══════════════════════════════════════════════
             LEFT — Profile card + stats
        ══════════════════════════════════════════════ --}}
        <div class="col-lg-4">

            {{-- Profile card --}}
            <div class="card border-0 shadow-none text-center mb-3">
                <div class="card-body p-4">
                    {{-- Avatar initials --}}
                    <div class="mx-auto rounded-circle bg-primary d-flex align-items-center justify-content-center mb-3"
                         style="width:72px;height:72px;font-size:1.8rem;font-weight:700;color:#fff;">
                        {{ strtoupper(substr($acheteur->nom, 0, 1)) }}
                    </div>

                    <h5 class="fw-semibold mb-1">{{ $acheteur->nom }}</h5>
                    <p class="text-muted mb-3">{{ $acheteur->email }}</p>

                    <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">
                        <iconify-icon icon="solar:bag-heart-line-duotone" class="me-1"></iconify-icon>
                        Acheteur
                    </span>

                    <hr class="my-3">

                    <div class="text-start">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <iconify-icon icon="solar:calendar-line-duotone" class="text-muted"></iconify-icon>
                            <span class="text-muted fs-2">Inscrit le</span>
                            <span class="ms-auto fw-semibold">{{ $acheteur->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <iconify-icon icon="solar:clock-circle-line-duotone" class="text-muted"></iconify-icon>
                            <span class="text-muted fs-2">Dernière activité</span>
                            <span class="ms-auto fw-semibold">{{ $acheteur->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:shield-check-line-duotone" class="text-muted"></iconify-icon>
                            <span class="text-muted fs-2">Email vérifié</span>
                            <span class="ms-auto">
                                @if($acheteur->email_verified_at)
                                    <span class="badge bg-success-subtle text-success">Oui</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Non</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.acheteurs.edit', $acheteur->id) }}"
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

            {{-- Stat cards --}}
            <div class="row g-2">
                <div class="col-6">
                    <div class="card border-0 shadow-none bg-primary-subtle text-center p-3">
                        <h4 class="mb-0 fw-bold text-primary">{{ $stats['total_commandes'] }}</h4>
                        <p class="mb-0 text-muted fs-2 mt-1">Commandes</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-none bg-warning-subtle text-center p-3">
                        <h4 class="mb-0 fw-bold text-warning">{{ $stats['commandes_cours'] }}</h4>
                        <p class="mb-0 text-muted fs-2 mt-1">En cours</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-none bg-success-subtle text-center p-3">
                        <h4 class="mb-0 fw-bold text-success">{{ number_format($stats['total_depense'], 2) }}€</h4>
                        <p class="mb-0 text-muted fs-2 mt-1">Total dépensé</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-none bg-info-subtle text-center p-3">
                        <h4 class="mb-0 fw-bold text-info">{{ $stats['panier_items'] }}</h4>
                        <p class="mb-0 text-muted fs-2 mt-1">Panier actuel</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             RIGHT — Commandes + Panier
        ══════════════════════════════════════════════ --}}
        <div class="col-lg-8">

            {{-- Commandes table --}}
            <div class="card border-0 shadow-none mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0 fw-semibold">
                            <iconify-icon icon="solar:cart-large-minimalistic-line-duotone" class="me-2 text-primary"></iconify-icon>
                            Historique des commandes
                        </h6>
                        <span class="badge bg-primary-subtle text-primary">
                            {{ $acheteur->commandes->count() }} commande(s)
                        </span>
                    </div>

                    @if($acheteur->commandes->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th class="text-center">Produits</th>
                                        <th class="text-end">Montant</th>
                                        <th class="text-center">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($acheteur->commandes->sortByDesc('date_commande') as $commande)
                                        <tr>
                                            <td class="text-muted">#{{ $commande->id }}</td>
                                            <td>{{ $commande->date_commande->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    {{ $commande->produits->count() }} article(s)
                                                </span>
                                            </td>
                                            <td class="text-end fw-semibold">
                                                {{ number_format(
                                                    $commande->produits->sum(fn($p) => $p->pivot->prix_unitaire * $p->pivot->quantite),
                                                    2
                                                ) }}€
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $val = $commande->statut instanceof \App\Enums\StatutCommande
                                                        ? $commande->statut->value
                                                        : $commande->statut;
                                                    [$bg, $label] = match($val) {
                                                        'EN_COURS' => ['bg-warning-subtle text-warning', 'En cours'],
                                                        'LIVREE'   => ['bg-success-subtle text-success', 'Livrée'],
                                                        'ANNULEE'  => ['bg-danger-subtle text-danger',  'Annulée'],
                                                        default    => ['bg-secondary-subtle text-secondary', $val],
                                                    };
                                                @endphp
                                                <span class="badge {{ $bg }}">{{ $label }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <iconify-icon icon="solar:cart-cross-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                            Aucune commande passée.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Panier actuel --}}
            <div class="card border-0 shadow-none">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0 fw-semibold">
                            <iconify-icon icon="solar:bag-5-line-duotone" class="me-2 text-warning"></iconify-icon>
                            Panier actuel
                        </h6>
                        @if($acheteur->panier && $acheteur->panier->produits->isNotEmpty())
                            <span class="badge bg-warning-subtle text-warning">
                                {{ $acheteur->panier->produits->sum('pivot.quantite') }} article(s)
                            </span>
                        @endif
                    </div>

                    @if($acheteur->panier && $acheteur->panier->produits->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-center">Qté</th>
                                        <th class="text-end">Prix unitaire</th>
                                        <th class="text-end">Sous-total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($acheteur->panier->produits as $produit)
                                        <tr>
                                            <td class="fw-semibold">{{ $produit->nom }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-primary-subtle text-primary">
                                                    {{ $produit->pivot->quantite }}
                                                </span>
                                            </td>
                                            <td class="text-end">{{ number_format($produit->prix, 2) }}€</td>
                                            <td class="text-end fw-semibold">
                                                {{ number_format($produit->prix * $produit->pivot->quantite, 2) }}€
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-semibold">Total panier</td>
                                        <td class="text-end fw-bold text-primary">
                                            {{ number_format(
                                                $acheteur->panier->produits->sum(fn($p) => $p->prix * $p->pivot->quantite),
                                                2
                                            ) }}€
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <iconify-icon icon="solar:bag-cross-line-duotone" class="fs-1 d-block mb-2"></iconify-icon>
                            Le panier est vide.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- ── Delete confirmation modal ── --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold">Supprimer ce compte ?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="alert alert-danger d-flex gap-2 align-items-start">
                        <iconify-icon icon="solar:danger-triangle-line-duotone" class="flex-shrink-0 fs-5 mt-1"></iconify-icon>
                        <div>
                            <strong>Action irréversible.</strong><br>
                            Le compte de <strong>{{ $acheteur->nom }}</strong> sera supprimé définitivement
                            avec toutes ses commandes, son panier et ses notifications.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form method="POST" action="{{ route('admin.acheteurs.destroy', $acheteur->id) }}">
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
