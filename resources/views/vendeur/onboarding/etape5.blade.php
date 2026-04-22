@extends('vendeur.onboarding.layout')
@php $etapeNum = 5; @endphp
@section('title', 'Étape 5 — Récapitulatif & Confirmation')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8 col-xl-7">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">✅ Récapitulatif & Confirmation</h4>
        <p class="text-muted mb-0">
            Vérifiez toutes les informations ci-dessous. Une fois soumis, votre dossier sera examiné par notre équipe de conformité.
        </p>
    </div>

    {{-- ── Récapitulatif du dossier ── --}}
    <div class="ob-card mb-4">
        <div class="ob-section-title">
            <iconify-icon icon="solar:clipboard-check-line-duotone" class="me-1"></iconify-icon>
            Récapitulatif de votre dossier
        </div>

        @php
            // Documents obligatoires
            $kbis = $vendeur->documents->firstWhere('type', \App\Enums\TypeDocument::KBIS);
            $statuts = $vendeur->documents->firstWhere('type', \App\Enums\TypeDocument::STATUTS_SOCIETE);
            $pieceIdentite = $vendeur->documents->firstWhere('type', \App\Enums\TypeDocument::PIECE_IDENTITE_DIRIGEANT);
            $rib = $vendeur->documents->firstWhere('type', \App\Enums\TypeDocument::RIB_BANCAIRE);
            // Certifications
            $ceCount = $vendeur->documents->filter(fn($d) => $d->type === \App\Enums\TypeDocument::CERTIFICAT_CE)->count();
            $ppe2Count = $vendeur->documents->filter(fn($d) => $d->type === \App\Enums\TypeDocument::PPE2)->count();
            // Check all required docs
            $allDocsComplete = $kbis && $statuts && $pieceIdentite && $rib;
        @endphp

        <div class="row g-2 mb-3">
            {{-- Étape 1: Société --}}
            <div class="col-12">
                <div class="d-flex align-items-center gap-3 p-3 rounded"
                     style="background:var(--bs-success-bg-subtle,#d1e7dd);border:1px solid rgba(25,135,84,.2);">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:36px;height:36px;background:#198754;color:#fff;font-weight:700;font-size:.85rem;">1</div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.85rem;">Informations de l'entreprise</div>
                        <div class="text-muted" style="font-size:.76rem;">
                            {{ $vendeur->raison_sociale ?? '—' }} · {{ $vendeur->forme_juridique ?? '—' }} · SIRET {{ $vendeur->siret ?? '—' }}
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        @if($vendeur->tva_verifiee)
                            <span class="badge bg-success text-white" style="font-size:.68rem;">TVA ✓</span>
                        @else
                            <span class="badge bg-warning text-dark" style="font-size:.68rem;">TVA ⚠</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Étape 2: Documents --}}
            <div class="col-12">
                <div class="d-flex align-items-center gap-3 p-3 rounded {{ $allDocsComplete ? '' : 'border-warning' }}"
                     style="background:{{ $allDocsComplete ? 'var(--bs-success-bg-subtle,#d1e7dd);border:1px solid rgba(25,135,84,.2)' : 'var(--bs-warning-bg-subtle,#fff3cd);border:1px solid rgba(255,193,7,.4)' }};">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:36px;height:36px;background:{{ $allDocsComplete ? '#198754' : '#fd7e14' }};color:#fff;font-weight:700;font-size:.85rem;">2</div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.85rem;">Documents obligatoires</div>
                        <div class="text-muted" style="font-size:.76rem;">
                            Kbis {{ $kbis ? '✓' : '✗' }} · Statuts {{ $statuts ? '✓' : '✗' }} · ID {{ $pieceIdentite ? '✓' : '✗' }} · RIB {{ $rib ? '✓' : '✗' }}
                        </div>
                    </div>
                    @if(!$allDocsComplete)
                        <a href="{{ route('vendeur.onboarding.etape', 2) }}" class="btn btn-sm btn-warning flex-shrink-0"
                           style="font-size:.72rem;">Compléter</a>
                    @endif
                </div>
            </div>

            {{-- Étape 3: Certifications --}}
            <div class="col-12">
                <div class="d-flex align-items-center gap-3 p-3 rounded"
                     style="background:var(--bs-success-bg-subtle,#d1e7dd);border:1px solid rgba(25,135,84,.2);">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:36px;height:36px;background:#198754;color:#fff;font-weight:700;font-size:.85rem;">3</div>
                    <div>
                        <div class="fw-semibold" style="font-size:.85rem;">Certifications EnR</div>
                        <div class="text-muted" style="font-size:.76rem;">
                            {{ $ceCount }} CE · {{ $ppe2Count }} PPE2 (optionnel)
                        </div>
                    </div>
                </div>
            </div>

            {{-- Étape 4: Logistique --}}
            <div class="col-12">
                <div class="d-flex align-items-center gap-3 p-3 rounded"
                     style="background:var(--bs-success-bg-subtle,#d1e7dd);border:1px solid rgba(25,135,84,.2);">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:36px;height:36px;background:#198754;color:#fff;font-weight:700;font-size:.85rem;">4</div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.85rem;">Logistique & Transport</div>
                        <div class="text-muted" style="font-size:.76rem;">
                            Incoterm : {{ $vendeur->incoterm_preference ?? '—' }} · Poids max : {{ $vendeur->poids_max_palette ?? '—' }} kg ·
                            {{ $vendeur->logistiques->count() }} zone(s)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('vendeur.onboarding.save5') }}">
        @csrf

        {{-- ── Informations légales complémentaires ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:document-text-line-duotone" class="me-1"></iconify-icon>
                Informations légales complémentaires
            </div>

            <div class="mb-0">
                <label class="form-label fw-semibold">Notes additionnelles <small class="text-muted fw-normal">(optionnel)</small></label>
                <textarea name="informations_legales" rows="3" class="form-control"
                          placeholder="Numéro RCS, capital social, code NAF/APE, informations de contact supplémentaires…">{{ old('informations_legales', $vendeur->informations_legales) }}</textarea>
                <small class="text-muted">Ces informations figureront sur vos factures générées par la plateforme.</small>
            </div>
        </div>

        {{-- ── Consentement final ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:shield-check-line-duotone" class="me-1"></iconify-icon>
                Consentement & Soumission
            </div>

            <label class="d-flex align-items-start gap-3 p-3 rounded border" style="cursor:pointer;">
                <input type="checkbox" name="consent_final" value="1" class="form-check-input mt-0 flex-shrink-0"
                       style="width:18px;height:18px;" required id="cb-consent">
                <div>
                    <div class="fw-semibold mb-2" style="font-size:.85rem;">
                        J'accepte les conditions et j'autorise la soumission de mon dossier
                        <span class="text-danger">*</span>
                    </div>
                    <div class="text-muted" style="font-size:.76rem; line-height:1.5;">
                        En cochant cette case, je certifie que :
                        <ul class="mb-0 mt-2">
                            <li>Tous les documents fournis sont authentiques et à jour.</li>
                            <li>J'accepte les Conditions Générales de Vente et la charte vendeur.</li>
                            <li>Je consentis au traitement de mes données conformément au RGPD.</li>
                            <li>Je m'engage à maintenir mes informations à jour et à respecter les règles de la plateforme.</li>
                        </ul>
                    </div>
                </div>
            </label>

            @error('consent_final')
                <div class="text-danger mt-2" style="font-size:.82rem;">{{ $message }}</div>
            @enderror
        </div>

        {{-- ── Info délai ── --}}
        <div class="alert alert-info d-flex gap-3 align-items-start mb-4">
            <iconify-icon icon="solar:clock-circle-line-duotone" class="flex-shrink-0 fs-5 mt-1"></iconify-icon>
            <div style="font-size:.83rem; line-height:1.6;">
                <strong>Délai de traitement : 2 à 5 jours ouvrés.</strong><br>
                Après soumission, notre équipe de conformité examinera votre dossier.
                Vous recevrez une notification par email dès que votre compte sera activé ou si des
                informations complémentaires sont requises.
            </div>
        </div>

        {{-- ── Navigation ── --}}
        <div class="ob-footer d-flex align-items-center justify-content-between">
            <a href="{{ route('vendeur.onboarding.etape', 4) }}" class="btn btn-outline-secondary">
                <iconify-icon icon="solar:arrow-left-line-duotone" class="me-1"></iconify-icon>
                Retour
            </a>
            <button type="submit" class="btn btn-brand px-5" id="btn-submit" disabled>
                <iconify-icon icon="solar:send-line-duotone" class="me-1"></iconify-icon>
                Soumettre mon dossier
            </button>
        </div>

    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
// Activer le bouton submit uniquement si la case est cochée
const consent = document.getElementById('cb-consent');
const submitBtn = document.getElementById('btn-submit');

function updateBtn() {
    submitBtn.disabled = !consent.checked;
    submitBtn.style.opacity = consent.checked ? '1' : '.5';
}

consent.addEventListener('change', updateBtn);
updateBtn();
</script>
@endpush
