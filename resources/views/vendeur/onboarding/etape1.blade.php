@extends('vendeur.onboarding.layout')
@php $etapeNum = 1; @endphp
@section('title', 'Étape 1 — Identité de l\'entreprise')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7 col-xl-6">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">🏢 Identité de l'entreprise</h4>
        <p class="text-muted mb-0">Ces informations permettent de vérifier votre existence légale et sont transmises à notre équipe de conformité.</p>
    </div>

    <form method="POST" action="{{ route('vendeur.onboarding.save1') }}" id="form-etape1">
        @csrf

        {{-- ── Informations légales ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:buildings-line-duotone" class="me-1"></iconify-icon>
                Identification légale
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Raison sociale <span class="text-danger">*</span></label>
                <input type="text" name="raison_sociale" class="form-control @error('raison_sociale') is-invalid @enderror"
                       value="{{ old('raison_sociale', $vendeur->raison_sociale) }}"
                       placeholder="Ex : SOLAR TECH SAS" required>
                @error('raison_sociale') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Forme juridique <span class="text-danger">*</span></label>
                    <select name="forme_juridique" class="form-select @error('forme_juridique') is-invalid @enderror" required>
                        <option value="">Sélectionner…</option>
                        @foreach(['SAS','SASU','SARL','EURL','SA','SNC','SCI','Micro-entreprise','Auto-entrepreneur','Autre'] as $forme)
                            <option value="{{ $forme }}" @selected(old('forme_juridique', $vendeur->forme_juridique) === $forme)>{{ $forme }}</option>
                        @endforeach
                    </select>
                    @error('forme_juridique') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Pays <span class="text-danger">*</span></label>
                    <select name="pays" class="form-select @error('pays') is-invalid @enderror" id="select-pays" required>
                        @php
                            $pays = [
                                'FR'=>'🇫🇷 France','DE'=>'🇩🇪 Allemagne','BE'=>'🇧🇪 Belgique',
                                'NL'=>'🇳🇱 Pays-Bas','ES'=>'🇪🇸 Espagne','IT'=>'🇮🇹 Italie',
                                'PT'=>'🇵🇹 Portugal','PL'=>'🇵🇱 Pologne','AT'=>'🇦🇹 Autriche',
                                'CH'=>'🇨🇭 Suisse','LU'=>'🇱🇺 Luxembourg',
                            ];
                            $selectedPays = old('pays', $vendeur->pays ?? 'FR');
                        @endphp
                        @foreach($pays as $code => $label)
                            <option value="{{ $code }}" @selected($selectedPays === $code)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('pays') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Numéro SIRET <span class="text-danger">*</span> <small class="text-muted fw-normal">(14 chiffres)</small></label>
                <input type="text" name="siret" id="input-siret"
                       class="form-control @error('siret') is-invalid @enderror"
                       value="{{ old('siret', $vendeur->siret) }}"
                       placeholder="12345678901234" maxlength="14" inputmode="numeric"
                       pattern="\d{14}" required>
                @error('siret') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">France uniquement. Pour les entreprises hors France, laissez votre équivalent national.</small>
            </div>

        </div>

        {{-- ── TVA Intracommunautaire + VIES ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:shield-check-line-duotone" class="me-1"></iconify-icon>
                TVA Intracommunautaire
                @if($vendeur->tva_verifiee)
                    <span class="vies-badge vies-valid ms-2">
                        <iconify-icon icon="solar:verified-check-line-duotone"></iconify-icon> Vérifié VIES
                    </span>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Numéro de TVA <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="text" name="numero_tva" id="input-tva"
                           class="form-control @error('numero_tva') is-invalid @enderror"
                           value="{{ old('numero_tva', $vendeur->numero_tva) }}"
                           placeholder="FR12345678901" style="text-transform:uppercase;">
                    <button type="button" class="btn btn-outline-secondary" id="btn-vies" onclick="verifierVies()">
                        <iconify-icon icon="solar:shield-check-line-duotone" class="me-1"></iconify-icon>
                        Vérifier
                    </button>
                    @error('numero_tva') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Résultat VIES --}}
                <div id="vies-result" class="mt-2" style="display:none;">
                    <div id="vies-badge" class="vies-badge vies-checking">
                        <iconify-icon icon="solar:hourglass-line-duotone" id="vies-icon"></iconify-icon>
                        <span id="vies-text">Vérification en cours…</span>
                    </div>
                    <div id="vies-detail" class="mt-1 text-muted" style="font-size:.8rem;"></div>
                </div>
            </div>

            <div class="alert alert-info py-2 d-flex gap-2" style="font-size:.82rem;">
                <iconify-icon icon="solar:info-circle-line-duotone" class="flex-shrink-0 mt-1"></iconify-icon>
                <div>
                    La vérification VIES interroge la base européenne en temps réel. Si votre numéro est temporairement
                    indisponible, votre dossier sera examiné manuellement par notre équipe de conformité.
                </div>
            </div>
        </div>

        {{-- ── Contact ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:phone-line-duotone" class="me-1"></iconify-icon>
                Contact & présence web
            </div>

            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Téléphone professionnel <span class="text-danger">*</span></label>
                    <input type="tel" name="telephone"
                           class="form-control @error('telephone') is-invalid @enderror"
                           value="{{ old('telephone', $vendeur->telephone) }}"
                           placeholder="+33 6 00 00 00 00">
                    @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Site web <small class="text-muted fw-normal">(optionnel)</small></label>
                    <input type="url" name="site_web"
                           class="form-control @error('site_web') is-invalid @enderror"
                           value="{{ old('site_web', $vendeur->site_web) }}"
                           placeholder="https://www.votreentreprise.fr">
                    @error('site_web') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- ── Navigation ── --}}
        <div class="ob-footer d-flex align-items-center justify-content-between">
            <span class="text-muted save-notice">
                <iconify-icon icon="solar:floppy-disk-line-duotone"></iconify-icon>
                Étape 1 / 5
            </span>
            <button type="submit" class="btn btn-brand px-5">
                Continuer
                <iconify-icon icon="solar:arrow-right-line-duotone" class="ms-1"></iconify-icon>
            </button>
        </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
async function verifierVies() {
    const tva = document.getElementById('input-tva').value.trim().toUpperCase();
    if (!tva) { alert('Saisissez votre numéro de TVA.'); return; }

    const result  = document.getElementById('vies-result');
    const badge   = document.getElementById('vies-badge');
    const icon    = document.getElementById('vies-icon');
    const text    = document.getElementById('vies-text');
    const detail  = document.getElementById('vies-detail');
    const btn     = document.getElementById('btn-vies');

    result.style.display = 'block';
    badge.className      = 'vies-badge vies-checking';
    icon.setAttribute('icon', 'solar:hourglass-line-duotone');
    text.textContent     = 'Vérification en cours…';
    detail.textContent   = '';
    btn.disabled         = true;

    try {
        const res  = await fetch(`{{ route('vendeur.vies.check') }}?tva=${encodeURIComponent(tva)}`, {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
        });
        const data = await res.json();

        if (data.error) {
            badge.className = 'vies-badge vies-invalid';
            icon.setAttribute('icon', 'solar:danger-triangle-line-duotone');
            text.textContent = data.error;
        } else if (data.valid) {
            badge.className = 'vies-badge vies-valid';
            icon.setAttribute('icon', 'solar:verified-check-line-duotone');
            text.textContent = '✅ TVA valide — ' + (data.name ?? '');
            if (data.address) detail.textContent = '📍 ' + data.address;
            // Pré-remplir raison sociale si vide
            const rs = document.querySelector('[name=raison_sociale]');
            if (data.name && !rs.value) rs.value = data.name;
        } else {
            badge.className = 'vies-badge vies-invalid';
            icon.setAttribute('icon', 'solar:close-circle-line-duotone');
            text.textContent = '⚠️ Numéro non reconnu par VIES — validation manuelle requise.';
        }
    } catch (e) {
        badge.className = 'vies-badge vies-invalid';
        text.textContent = 'Erreur réseau — réessayez.';
    } finally {
        btn.disabled = false;
    }
}

// Format automatique SIRET (groupes de 3-3-3-5 au besoin)
document.getElementById('input-siret').addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').slice(0, 14);
});
// TVA uppercase auto
document.getElementById('input-tva').addEventListener('input', function() {
    const pos = this.selectionStart;
    this.value = this.value.toUpperCase().replace(/\s/g, '');
    this.setSelectionRange(pos, pos);
});
</script>
@endpush
