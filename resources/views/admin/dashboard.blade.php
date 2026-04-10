@extends('admin.layouts.app')

@section('title', 'Tableau de Bord')

@section('content')

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="d-flex align-items-center mb-4">
        <div>
            <h4 class="mb-0">Tableau de Bord</h4>
            <p class="mb-0 text-muted">Bienvenue, {{ Auth::user()->nom }} — Vue d'ensemble de la plateforme</p>
        </div>
    </div>

    {{-- ===================== STAT CARDS ROW ===================== --}}
    <div class="row">

        {{-- Total Utilisateurs --}}
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <h5 class="card-subtitle mb-6 fw-normal text-muted">Utilisateurs</h5>
                    <h2 class="fw-semibold mb-3">{{ $stats['total_users'] }}</h2>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary-subtle text-primary">Tous rôles</span>
                    </div>
                </div>
                <div class="bg-primary p-1" style="height:4px;"></div>
            </div>
        </div>

        {{-- Total Vendeurs --}}
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <h5 class="card-subtitle mb-6 fw-normal text-muted">Vendeurs</h5>
                    <h2 class="fw-semibold mb-3">{{ $stats['total_vendeurs'] }}</h2>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning-subtle text-warning">
                            {{ $stats['vendeurs_en_attente'] }} en attente
                        </span>
                    </div>
                </div>
                <div class="bg-warning p-1" style="height:4px;"></div>
            </div>
        </div>

        {{-- Total Produits --}}
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <h5 class="card-subtitle mb-6 fw-normal text-muted">Produits</h5>
                    <h2 class="fw-semibold mb-3">{{ $stats['total_produits'] }}</h2>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success">En catalogue</span>
                    </div>
                </div>
                <div class="bg-success p-1" style="height:4px;"></div>
            </div>
        </div>

        {{-- Total Commandes --}}
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <h5 class="card-subtitle mb-6 fw-normal text-muted">Commandes</h5>
                    <h2 class="fw-semibold mb-3">{{ $stats['total_commandes'] }}</h2>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger-subtle text-danger">
                            {{ $stats['commandes_en_cours'] }} en cours
                        </span>
                    </div>
                </div>
                <div class="bg-danger p-1" style="height:4px;"></div>
            </div>
        </div>

    </div>
    {{-- Stat cards end --}}

    <div class="row mt-4">

        {{-- ===================== DERNIÈRES COMMANDES ===================== --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0">
                            <iconify-icon icon="solar:cart-line-duotone" class="fs-6 me-2"></iconify-icon>
                            Dernières Commandes
                        </h5>
                        <a href="{{ route('admin.commandes.index') }}" class="btn btn-sm btn-outline-primary">
                            Voir tout
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dernieres_commandes as $commande)
                                    <tr>
                                        <td><strong>#{{ $commande->id }}</strong></td>
                                        <td>{{ $commande->user->nom ?? '—' }}</td>
                                        <td>{{ $commande->date_commande->format('d/m/Y') }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match($commande->statut->value ?? $commande->statut) {
                                                    'EN_COURS' => 'bg-warning-subtle text-warning',
                                                    'LIVREE'   => 'bg-success-subtle text-success',
                                                    'ANNULEE'  => 'bg-danger-subtle text-danger',
                                                    default    => 'bg-secondary-subtle text-secondary',
                                                };
                                                $badgeLabel = match($commande->statut->value ?? $commande->statut) {
                                                    'EN_COURS' => 'En cours',
                                                    'LIVREE'   => 'Livrée',
                                                    'ANNULEE'  => 'Annulée',
                                                    default    => 'Inconnu',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Aucune commande pour le moment.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== VENDEURS EN ATTENTE ===================== --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0">
                            <iconify-icon icon="solar:user-check-line-duotone" class="fs-6 me-2"></iconify-icon>
                            Vendeurs en Attente
                        </h5>
                        <a href="{{ route('admin.vendeurs.index') }}" class="btn btn-sm btn-outline-warning">
                            Voir tout
                        </a>
                    </div>

                    @forelse($derniers_vendeurs as $vendeur)
                        <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                                     style="width:40px;height:40px;">
                                    <iconify-icon icon="solar:user-bold" class="text-primary"></iconify-icon>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $vendeur->user->nom ?? '—' }}</h6>
                                    <small class="text-muted">{{ $vendeur->user->email ?? '' }}</small>
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.vendeurs.valider', $vendeur->id) }}"
                                   class="btn btn-sm btn-success" title="Valider">
                                    <i class="ti ti-check"></i>
                                </a>
                                <a href="{{ route('admin.vendeurs.rejeter', $vendeur->id) }}"
                                   class="btn btn-sm btn-danger" title="Rejeter">
                                    <i class="ti ti-x"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <iconify-icon icon="solar:check-circle-line-duotone" class="fs-4 text-success"></iconify-icon>
                            <p class="mt-2 mb-0">Aucun vendeur en attente de validation.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

@endsection
