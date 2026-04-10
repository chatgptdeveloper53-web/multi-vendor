@extends('admin.layouts.app')

@section('title', 'Modifier — ' . ($vendeur->user->nom ?? '—'))

@section('content')

    {{-- ── Breadcrumb ── --}}
    @include('admin.components.breadcrumb', [
        'items' => [
            'Vendeurs'                              => route('admin.vendeurs.index'),
            $vendeur->user->nom ?? '—'             => route('admin.vendeurs.show', $vendeur->id),
            'Modifier'                              => '#',
        ]
    ])

    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- Errors summary --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.vendeurs.update', $vendeur->id) }}">
                @csrf
                @method('PUT')

                {{-- ── Informations du compte ── --}}
                <div class="card border-0 shadow-none mb-4">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-4">
                            <iconify-icon icon="solar:user-circle-line-duotone" class="me-2 text-primary"></iconify-icon>
                            Informations du compte
                        </h6>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nom"
                                   value="{{ old('nom', $vendeur->user->nom) }}"
                                   class="form-control @error('nom') is-invalid @enderror"
                                   placeholder="Nom complet"
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">Adresse email <span class="text-danger">*</span></label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $vendeur->user->email) }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="email@exemple.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ── Informations vendeur ── --}}
                <div class="card border-0 shadow-none mb-4">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-4">
                            <iconify-icon icon="solar:shop-line-duotone" class="me-2 text-warning"></iconify-icon>
                            Profil Vendeur
                        </h6>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Coordonnées</label>
                            <input type="text"
                                   name="coordonnees"
                                   value="{{ old('coordonnees', $vendeur->coordonnees) }}"
                                   class="form-control @error('coordonnees') is-invalid @enderror"
                                   placeholder="Adresse, ville, pays…">
                            @error('coordonnees')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">RIB / IBAN</label>
                            <input type="text"
                                   name="rib"
                                   value="{{ old('rib', $vendeur->rib) }}"
                                   class="form-control @error('rib') is-invalid @enderror"
                                   placeholder="FR76 XXXX XXXX…">
                            @error('rib')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">Informations légales</label>
                            <textarea name="informations_legales"
                                      rows="4"
                                      class="form-control @error('informations_legales') is-invalid @enderror"
                                      placeholder="Numéro SIRET, forme juridique, TVA intracommunautaire…">{{ old('informations_legales', $vendeur->informations_legales) }}</textarea>
                            @error('informations_legales')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ── Read-only info ── --}}
                <div class="card border-0 shadow-none bg-light mb-4">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3 text-muted">
                            <iconify-icon icon="solar:info-circle-line-duotone" class="me-2"></iconify-icon>
                            Informations non modifiables
                        </h6>
                        <div class="row g-2">
                            <div class="col-sm-4">
                                <p class="mb-0 fs-2 text-muted">Rôle</p>
                                <span class="badge bg-warning-subtle text-warning">Vendeur</span>
                            </div>
                            <div class="col-sm-4">
                                <p class="mb-0 fs-2 text-muted">Statut dossier</p>
                                @php
                                    $sv = $vendeur->statut_onboarding instanceof \App\Enums\StatutDossier
                                        ? $vendeur->statut_onboarding->value
                                        : $vendeur->statut_onboarding;
                                    [$sCls, $sLabel] = match($sv) {
                                        'EN_ATTENTE' => ['bg-warning-subtle text-warning', 'En attente'],
                                        'VALIDE'     => ['bg-success-subtle text-success', 'Validé'],
                                        'REJETE'     => ['bg-danger-subtle text-danger',   'Rejeté'],
                                        default      => ['bg-secondary-subtle text-secondary', $sv],
                                    };
                                @endphp
                                <span class="badge {{ $sCls }}">{{ $sLabel }}</span>
                            </div>
                            <div class="col-sm-4">
                                <p class="mb-0 fs-2 text-muted">Inscrit le</p>
                                <strong>{{ $vendeur->created_at->format('d/m/Y') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Buttons ── --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <iconify-icon icon="solar:floppy-disk-line-duotone" class="me-1"></iconify-icon>
                        Enregistrer les modifications
                    </button>
                    <a href="{{ route('admin.vendeurs.show', $vendeur->id) }}" class="btn btn-outline-secondary">
                        Annuler
                    </a>
                </div>

            </form>
        </div>
    </div>

@endsection
