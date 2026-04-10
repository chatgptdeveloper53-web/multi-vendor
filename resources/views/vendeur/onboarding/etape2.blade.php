@extends('vendeur.onboarding.layout')
@php $etapeNum = 2; @endphp
@section('title', 'Étape 2 — Représentant légal')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7 col-xl-6">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">👤 Représentant légal & Contact commercial</h4>
        <p class="text-muted mb-0">Informations sur la personne responsable du compte et le contact pour les acheteurs.</p>
    </div>

    <form method="POST" action="{{ route('vendeur.onboarding.save2') }}">
        @csrf

        {{-- ── Dirigeant ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:user-circle-line-duotone" class="me-1"></iconify-icon>
                Dirigeant / Gérant
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-7">
                    <label class="form-label fw-semibold">Nom & prénom <span class="text-danger">*</span></label>
                    <input type="text" name="nom_dirigeant"
                           class="form-control @error('nom_dirigeant') is-invalid @enderror"
                           value="{{ old('nom_dirigeant', $vendeur->nom_dirigeant) }}"
                           placeholder="Jean Dupont" required>
                    @error('nom_dirigeant') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-5">
                    <label class="form-label fw-semibold">Fonction <span class="text-danger">*</span></label>
                    <select name="fonction_dirigeant" class="form-select @error('fonction_dirigeant') is-invalid @enderror" required>
                        <option value="">Sélectionner…</option>
                        @foreach(['Gérant','Président','Directeur Général','Directeur Commercial','PDG','Co-fondateur','Autre'] as $f)
                            <option value="{{ $f }}" @selected(old('fonction_dirigeant', $vendeur->fonction_dirigeant) === $f)>{{ $f }}</option>
                        @endforeach
                    </select>
                    @error('fonction_dirigeant') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="alert alert-light border py-2 d-flex gap-2" style="font-size:.82rem;">
                <iconify-icon icon="solar:lock-line-duotone" class="flex-shrink-0 mt-1 text-muted"></iconify-icon>
                <div>
                    Ces informations sont confidentielles et ne sont pas affichées aux acheteurs.
                    Elles servent uniquement à notre processus de vérification KYC.
                </div>
            </div>
        </div>

        {{-- ── Contact commercial ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:mailbox-line-duotone" class="me-1"></iconify-icon>
                Contact commercial <small class="text-muted text-lowercase fw-normal">(optionnel)</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email commercial</label>
                <input type="email" name="email_commercial"
                       class="form-control @error('email_commercial') is-invalid @enderror"
                       value="{{ old('email_commercial', $vendeur->email_commercial) }}"
                       placeholder="commercial@votreentreprise.fr">
                @error('email_commercial') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Distinct de votre email de connexion. Sera utilisé pour les notifications commandes.</small>
            </div>

            {{-- Info compte --}}
            <div class="p-3 bg-light rounded d-flex align-items-center gap-2" style="font-size:.83rem;">
                <iconify-icon icon="solar:info-circle-line-duotone" class="text-muted flex-shrink-0"></iconify-icon>
                <div>
                    Votre email de connexion : <strong>{{ Auth::user()->email }}</strong>
                    recevra toutes les notifications importantes concernant votre dossier.
                </div>
            </div>
        </div>

        {{-- ── Navigation ── --}}
        <div class="ob-footer d-flex align-items-center justify-content-between">
            <a href="{{ route('vendeur.onboarding.etape', 1) }}" class="btn btn-outline-secondary">
                <iconify-icon icon="solar:arrow-left-line-duotone" class="me-1"></iconify-icon>
                Retour
            </a>
            <button type="submit" class="btn btn-brand px-5">
                Continuer
                <iconify-icon icon="solar:arrow-right-line-duotone" class="ms-1"></iconify-icon>
            </button>
        </div>
    </form>
</div>
</div>
@endsection
