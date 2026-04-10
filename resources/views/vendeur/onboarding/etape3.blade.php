@extends('vendeur.onboarding.layout')
@php $etapeNum = 3; @endphp
@section('title', 'Étape 3 — Documents & Certifications')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8 col-xl-7">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">📄 Documents & Certifications</h4>
        <p class="text-muted mb-0">Uploadez vos documents légaux et certifications produits. Les formats PDF sont acceptés.</p>
    </div>

    <form method="POST" action="{{ route('vendeur.onboarding.save3') }}" enctype="multipart/form-data" id="form-docs">
        @csrf

        {{-- ── Documents obligatoires ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:shield-warning-line-duotone" class="me-1"></iconify-icon>
                Documents obligatoires
            </div>

            @php
                $kbis   = $vendeur->documents->firstWhere('type', \App\Enums\TypeDocument::KBIS);
                $rcPro  = $vendeur->documents->firstWhere('type', \App\Enums\TypeDocument::RC_PRO);
            @endphp

            {{-- Kbis --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    <iconify-icon icon="solar:buildings-line-duotone" class="me-1 text-primary"></iconify-icon>
                    Extrait Kbis <span class="text-danger">*</span>
                    <small class="text-muted fw-normal">(de moins de 3 mois)</small>
                </label>
                @if($kbis)
                    <div class="alert alert-success py-2 d-flex align-items-center gap-2 mb-2" style="font-size:.83rem;">
                        <iconify-icon icon="solar:check-circle-line-duotone"></iconify-icon>
                        <span>{{ $kbis->nom_original ?? 'kbis.pdf' }}</span>
                        <button type="button" class="btn btn-sm btn-outline-danger ms-auto py-0 px-2"
                                onclick="supprimerDoc({{ $kbis->id }}, this)">
                            Supprimer
                        </button>
                    </div>
                    <p class="text-muted mb-0" style="font-size:.75rem;">Un nouveau fichier remplacera l'ancien.</p>
                @endif
                <div class="drop-zone mt-2" id="dz-kbis" onclick="document.getElementById('file-kbis').click()">
                    <iconify-icon icon="solar:cloud-upload-line-duotone" style="font-size:2rem;" class="text-muted d-block mb-2"></iconify-icon>
                    <strong>Glissez-déposez</strong> ou cliquez pour sélectionner
                    <div class="text-muted mt-1" style="font-size:.8rem;">PDF uniquement · Max 5 Mo</div>
                    <input type="file" id="file-kbis" name="kbis" accept=".pdf" onchange="showFile(this, 'dz-kbis', 'preview-kbis')" {{ $kbis ? '' : 'required' }}>
                </div>
                <div id="preview-kbis"></div>
                @error('kbis') <div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div> @enderror
            </div>

            {{-- RC Pro --}}
            <div class="mb-0">
                <label class="form-label fw-semibold">
                    <iconify-icon icon="solar:shield-warning-line-duotone" class="me-1 text-warning"></iconify-icon>
                    Attestation RC Professionnelle <span class="text-danger">*</span>
                    <small class="text-muted fw-normal">(en cours de validité)</small>
                </label>
                @if($rcPro)
                    <div class="alert alert-success py-2 d-flex align-items-center gap-2 mb-2" style="font-size:.83rem;">
                        <iconify-icon icon="solar:check-circle-line-duotone"></iconify-icon>
                        <span>{{ $rcPro->nom_original ?? 'rc_pro.pdf' }}</span>
                        <button type="button" class="btn btn-sm btn-outline-danger ms-auto py-0 px-2"
                                onclick="supprimerDoc({{ $rcPro->id }}, this)">
                            Supprimer
                        </button>
                    </div>
                @endif
                <div class="drop-zone mt-2" id="dz-rcpro" onclick="document.getElementById('file-rcpro').click()">
                    <iconify-icon icon="solar:cloud-upload-line-duotone" style="font-size:2rem;" class="text-muted d-block mb-2"></iconify-icon>
                    <strong>Glissez-déposez</strong> ou cliquez pour sélectionner
                    <div class="text-muted mt-1" style="font-size:.8rem;">PDF uniquement · Max 5 Mo</div>
                    <input type="file" id="file-rcpro" name="rc_pro" accept=".pdf" onchange="showFile(this, 'dz-rcpro', 'preview-rcpro')" {{ $rcPro ? '' : 'required' }}>
                </div>
                <div id="preview-rcpro"></div>
                @error('rc_pro') <div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- ── Certifications produits (multi-upload) ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:shield-check-line-duotone" class="me-1"></iconify-icon>
                Certifications produits
                <small class="text-muted text-lowercase fw-normal">(optionnel à cette étape, requis avant activation)</small>
            </div>

            {{-- CE --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    <iconify-icon icon="solar:star-rings-line-duotone" class="me-1 text-success"></iconify-icon>
                    Certificats CE
                    <span class="badge bg-success-subtle text-success ms-1">Multi-fichiers</span>
                </label>
                @php $ceList = $vendeur->documents->filter(fn($d) => $d->type === \App\Enums\TypeDocument::CERTIFICAT_CE); @endphp
                @if($ceList->isNotEmpty())
                    <ul class="list-group list-group-flush mb-2">
                        @foreach($ceList as $doc)
                            <li class="list-group-item py-1 px-0 d-flex align-items-center gap-2" style="font-size:.82rem;">
                                <iconify-icon icon="solar:file-text-line-duotone" class="text-success"></iconify-icon>
                                {{ $doc->nom_original ?? $doc->fichier }}
                                <button type="button" class="btn btn-sm btn-outline-danger ms-auto py-0 px-1"
                                        onclick="supprimerDoc({{ $doc->id }}, this)">×</button>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <div class="drop-zone" id="dz-ce" onclick="document.getElementById('file-ce').click()">
                    <iconify-icon icon="solar:cloud-upload-line-duotone" style="font-size:1.8rem;" class="text-muted d-block mb-2"></iconify-icon>
                    <strong>Déposez vos certificats CE</strong> (plusieurs fichiers acceptés)
                    <div class="text-muted mt-1" style="font-size:.8rem;">PDF · Max 10 Mo par fichier · 20 fichiers max</div>
                    <input type="file" id="file-ce" name="certificats_ce[]" accept=".pdf" multiple
                           onchange="showMultiple(this, 'preview-ce')">
                </div>
                <div id="preview-ce"></div>
            </div>

            {{-- PPE2 / Certisolis --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    <iconify-icon icon="solar:sun-line-duotone" class="me-1 text-warning"></iconify-icon>
                    Fiches Certisolis / PPE2
                    <span class="badge bg-warning-subtle text-warning ms-1">Multi-fichiers</span>
                </label>
                @php $ppe2List = $vendeur->documents->filter(fn($d) => $d->type === \App\Enums\TypeDocument::PPE2); @endphp
                @if($ppe2List->isNotEmpty())
                    <ul class="list-group list-group-flush mb-2">
                        @foreach($ppe2List as $doc)
                            <li class="list-group-item py-1 px-0 d-flex align-items-center gap-2" style="font-size:.82rem;">
                                <iconify-icon icon="solar:file-text-line-duotone" class="text-warning"></iconify-icon>
                                {{ $doc->nom_original ?? $doc->fichier }}
                                <button type="button" class="btn btn-sm btn-outline-danger ms-auto py-0 px-1"
                                        onclick="supprimerDoc({{ $doc->id }}, this)">×</button>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <div class="drop-zone" id="dz-ppe2" onclick="document.getElementById('file-ppe2').click()">
                    <iconify-icon icon="solar:cloud-upload-line-duotone" style="font-size:1.8rem;" class="text-muted d-block mb-2"></iconify-icon>
                    <strong>Déposez vos fiches Certisolis / PPE2</strong>
                    <div class="text-muted mt-1" style="font-size:.8rem;">PDF · Max 10 Mo par fichier</div>
                    <input type="file" id="file-ppe2" name="fiches_ppe2[]" accept=".pdf" multiple
                           onchange="showMultiple(this, 'preview-ppe2')">
                </div>
                <div id="preview-ppe2"></div>
            </div>

            {{-- Garanties constructeur --}}
            <div class="mb-0">
                <label class="form-label fw-semibold">
                    <iconify-icon icon="solar:medal-ribbons-star-line-duotone" class="me-1 text-info"></iconify-icon>
                    Garanties constructeur
                    <span class="badge bg-info-subtle text-info ms-1">Multi-fichiers</span>
                </label>
                @php $garantieList = $vendeur->documents->filter(fn($d) => $d->type === \App\Enums\TypeDocument::GARANTIE_CONSTRUCTEUR); @endphp
                @if($garantieList->isNotEmpty())
                    <ul class="list-group list-group-flush mb-2">
                        @foreach($garantieList as $doc)
                            <li class="list-group-item py-1 px-0 d-flex align-items-center gap-2" style="font-size:.82rem;">
                                <iconify-icon icon="solar:file-text-line-duotone" class="text-info"></iconify-icon>
                                {{ $doc->nom_original ?? $doc->fichier }}
                                <button type="button" class="btn btn-sm btn-outline-danger ms-auto py-0 px-1"
                                        onclick="supprimerDoc({{ $doc->id }}, this)">×</button>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <div class="drop-zone" id="dz-garantie" onclick="document.getElementById('file-garantie').click()">
                    <iconify-icon icon="solar:cloud-upload-line-duotone" style="font-size:1.8rem;" class="text-muted d-block mb-2"></iconify-icon>
                    <strong>Déposez vos garanties constructeur</strong>
                    <div class="text-muted mt-1" style="font-size:.8rem;">PDF · Max 10 Mo par fichier</div>
                    <input type="file" id="file-garantie" name="garanties_constructeur[]" accept=".pdf" multiple
                           onchange="showMultiple(this, 'preview-garantie')">
                </div>
                <div id="preview-garantie"></div>
            </div>
        </div>

        {{-- ── Navigation ── --}}
        <div class="ob-footer d-flex align-items-center justify-content-between">
            <a href="{{ route('vendeur.onboarding.etape', 2) }}" class="btn btn-outline-secondary">
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
        const previewId = input.id.replace('file-', 'preview-').replace('-', '_');
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files; // modern browsers only
            if (input.multiple) {
                showMultiple(input, previewId);
            } else {
                showFile(input, zone.id, previewId);
            }
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

function showMultiple(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!input.files.length) { preview.innerHTML = ''; return; }
    let html = '<ul class="list-group mt-2">';
    for (const f of input.files) {
        html += `<li class="list-group-item py-1 d-flex align-items-center gap-2" style="font-size:.8rem;">
            <iconify-icon icon="solar:file-text-line-duotone" class="text-muted"></iconify-icon>
            ${f.name} <span class="ms-auto text-muted">${(f.size/1024/1024).toFixed(2)} Mo</span>
        </li>`;
    }
    html += '</ul>';
    preview.innerHTML = html;
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
            btn.closest('li, .alert')?.remove();
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
