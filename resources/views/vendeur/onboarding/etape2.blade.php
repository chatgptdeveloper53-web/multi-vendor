@extends('vendeur.onboarding.layout')
@php $etapeNum = 2; @endphp
@section('title', 'Étape 2 — Documents obligatoires')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8 col-xl-7">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">📄 Documents obligatoires</h4>
        <p class="text-muted mb-0">Veuillez fournir les 4 documents essentiels pour valider votre profil. Tous les fichiers doivent être au format PDF.</p>
    </div>

    <form method="POST" action="{{ route('vendeur.onboarding.save2') }}" enctype="multipart/form-data" id="form-docs">
        @csrf

        {{-- ── Les 4 documents obligatoires ── --}}
        <div class="ob-card mb-4">

            @php
                $kbis = $vendeur->documents->firstWhere('type', \App\Enums\TypeDocument::KBIS);
                $statuts = $vendeur->documents->firstWhere('type', \App\Enums\TypeDocument::STATUTS_SOCIETE);
                $pieceIdentite = $vendeur->documents->firstWhere('type', \App\Enums\TypeDocument::PIECE_IDENTITE_DIRIGEANT);
                $rib = $vendeur->documents->firstWhere('type', \App\Enums\TypeDocument::RIB_BANCAIRE);
            @endphp

            {{-- 1. KBIS --}}
            <div class="mb-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="flex-shrink-0">
                        <div style="width:48px; height:48px; background: var(--brand-pale); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <iconify-icon icon="solar:buildings-line-duotone" style="font-size:1.4rem;" class="text-primary"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <label class="form-label fw-semibold mb-1">
                            Extrait Kbis <span class="text-danger">*</span>
                            <small class="text-muted fw-normal d-block">(moins de 3 mois)</small>
                        </label>
                        @if($kbis)
                            <div class="alert alert-success py-2 d-flex align-items-center gap-2 mb-2" style="font-size:.83rem;">
                                <iconify-icon icon="solar:check-circle-line-duotone"></iconify-icon>
                                <span class="flex-grow-1">{{ $kbis->nom_original ?? 'kbis.pdf' }}</span>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-auto py-0 px-2"
                                        onclick="supprimerDoc({{ $kbis->id }}, this)">
                                    Supprimer
                                </button>
                            </div>
                        @endif
                        <div class="drop-zone" id="dz-kbis" onclick="document.getElementById('file-kbis').click()">
                            <iconify-icon icon="solar:cloud-upload-line-duotone" style="font-size:2rem;" class="text-muted d-block mb-2"></iconify-icon>
                            <strong>Glissez-déposez</strong> ou cliquez pour sélectionner
                            <div class="text-muted mt-1" style="font-size:.8rem;">PDF uniquement · Max 5 Mo</div>
                            <input type="file" id="file-kbis" name="kbis" accept=".pdf" onchange="showFile(this, 'dz-kbis', 'preview-kbis')" {{ $kbis ? '' : 'required' }}>
                        </div>
                        <div id="preview-kbis"></div>
                        @error('kbis') <div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- 2. Statuts de la société --}}
            <div class="mb-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="flex-shrink-0">
                        <div style="width:48px; height:48px; background: var(--brand-pale); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <iconify-icon icon="solar:document-add-line-duotone" style="font-size:1.4rem;" class="text-primary"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <label class="form-label fw-semibold mb-1">
                            Statuts de la société <span class="text-danger">*</span>
                            <small class="text-muted fw-normal d-block">(statuts actuels)</small>
                        </label>
                        @if($statuts)
                            <div class="alert alert-success py-2 d-flex align-items-center gap-2 mb-2" style="font-size:.83rem;">
                                <iconify-icon icon="solar:check-circle-line-duotone"></iconify-icon>
                                <span class="flex-grow-1">{{ $statuts->nom_original ?? 'statuts.pdf' }}</span>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-auto py-0 px-2"
                                        onclick="supprimerDoc({{ $statuts->id }}, this)">
                                    Supprimer
                                </button>
                            </div>
                        @endif
                        <div class="drop-zone" id="dz-statuts" onclick="document.getElementById('file-statuts').click()">
                            <iconify-icon icon="solar:cloud-upload-line-duotone" style="font-size:2rem;" class="text-muted d-block mb-2"></iconify-icon>
                            <strong>Glissez-déposez</strong> ou cliquez pour sélectionner
                            <div class="text-muted mt-1" style="font-size:.8rem;">PDF uniquement · Max 5 Mo</div>
                            <input type="file" id="file-statuts" name="statuts_societe" accept=".pdf" onchange="showFile(this, 'dz-statuts', 'preview-statuts')" {{ $statuts ? '' : 'required' }}>
                        </div>
                        <div id="preview-statuts"></div>
                        @error('statuts_societe') <div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- 3. Pièce d'identité du dirigeant --}}
            <div class="mb-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="flex-shrink-0">
                        <div style="width:48px; height:48px; background: var(--brand-pale); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <iconify-icon icon="solar:user-id-bold-duotone" style="font-size:1.4rem;" class="text-primary"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <label class="form-label fw-semibold mb-1">
                            Pièce d'identité du dirigeant <span class="text-danger">*</span>
                            <small class="text-muted fw-normal d-block">(recto-verso si possible)</small>
                        </label>
                        @if($pieceIdentite)
                            <div class="alert alert-success py-2 d-flex align-items-center gap-2 mb-2" style="font-size:.83rem;">
                                <iconify-icon icon="solar:check-circle-line-duotone"></iconify-icon>
                                <span class="flex-grow-1">{{ $pieceIdentite->nom_original ?? 'piece_identite.pdf' }}</span>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-auto py-0 px-2"
                                        onclick="supprimerDoc({{ $pieceIdentite->id }}, this)">
                                    Supprimer
                                </button>
                            </div>
                        @endif
                        <div class="drop-zone" id="dz-identite" onclick="document.getElementById('file-identite').click()">
                            <iconify-icon icon="solar:cloud-upload-line-duotone" style="font-size:2rem;" class="text-muted d-block mb-2"></iconify-icon>
                            <strong>Glissez-déposez</strong> ou cliquez pour sélectionner
                            <div class="text-muted mt-1" style="font-size:.8rem;">PDF uniquement · Max 5 Mo</div>
                            <input type="file" id="file-identite" name="piece_identite_dirigeant" accept=".pdf" onchange="showFile(this, 'dz-identite', 'preview-identite')" {{ $pieceIdentite ? '' : 'required' }}>
                        </div>
                        <div id="preview-identite"></div>
                        @error('piece_identite_dirigeant') <div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- 4. RIB bancaire --}}
            <div class="mb-0">
                <div class="d-flex align-items-start gap-3">
                    <div class="flex-shrink-0">
                        <div style="width:48px; height:48px; background: var(--brand-pale); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <iconify-icon icon="solar:card-2-line-duotone" style="font-size:1.4rem;" class="text-primary"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <label class="form-label fw-semibold mb-1">
                            RIB bancaire <span class="text-danger">*</span>
                            <small class="text-muted fw-normal d-block">(attestation d'ouverture ou relevé)</small>
                        </label>
                        @if($rib)
                            <div class="alert alert-success py-2 d-flex align-items-center gap-2 mb-2" style="font-size:.83rem;">
                                <iconify-icon icon="solar:check-circle-line-duotone"></iconify-icon>
                                <span class="flex-grow-1">{{ $rib->nom_original ?? 'rib.pdf' }}</span>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-auto py-0 px-2"
                                        onclick="supprimerDoc({{ $rib->id }}, this)">
                                    Supprimer
                                </button>
                            </div>
                        @endif
                        <div class="drop-zone" id="dz-rib" onclick="document.getElementById('file-rib').click()">
                            <iconify-icon icon="solar:cloud-upload-line-duotone" style="font-size:2rem;" class="text-muted d-block mb-2"></iconify-icon>
                            <strong>Glissez-déposez</strong> ou cliquez pour sélectionner
                            <div class="text-muted mt-1" style="font-size:.8rem;">PDF uniquement · Max 5 Mo</div>
                            <input type="file" id="file-rib" name="rib_bancaire" accept=".pdf" onchange="showFile(this, 'dz-rib', 'preview-rib')" {{ $rib ? '' : 'required' }}>
                        </div>
                        <div id="preview-rib"></div>
                        @error('rib_bancaire') <div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div> @enderror
                    </div>
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

@push('scripts')
<script>
// ── Drag & drop ──
document.querySelectorAll('.drop-zone').forEach(zone => {
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('dragover');
        const input = zone.querySelector('input[type=file]');
        const previewId = input.id.replace('file-', 'preview-');
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            showFile(input, zone.id, previewId);
        }
    });
});

function showFile(input, dzId, previewId) {
    const preview = document.getElementById(previewId);
    if (!input.files[0]) { preview.innerHTML = ''; return; }
    const f = input.files[0];
    preview.innerHTML = `
        <div class="d-flex align-items-center gap-2 mt-2 p-2 bg-success-subtle rounded" style="font-size:.82rem;">
            <iconify-icon icon="solar:file-text-bold-duotone" class="text-success"></iconify-icon>
            <span class="fw-semibold">${f.name}</span>
            <span class="ms-auto text-muted">${(f.size/1024/1024).toFixed(2)} Mo</span>
        </div>`;
}

// ── Suppression AJAX ──
async function supprimerDoc(id, btn) {
    if (!confirm('Supprimer ce document ?')) return;
    btn.disabled = true;
    try {
        const res = await fetch(`{{ url('vendeur/onboarding/document') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json',
            }
        });
        if (res.ok) {
            btn.closest('.alert')?.remove();
        } else {
            alert('Erreur lors de la suppression.');
            btn.disabled = false;
        }
    } catch (e) {
        alert('Erreur réseau.');
        btn.disabled = false;
    }
}
</script>
@endpush
