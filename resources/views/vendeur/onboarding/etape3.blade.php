@extends('vendeur.onboarding.layout')
@php $etapeNum = 3; @endphp
@section('title', 'Étape 3 — Certifications EnR')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8 col-xl-7">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">🏆 Certifications EnR (optionnel)</h4>
        <p class="text-muted mb-0">Uploadez vos certifications de produits énergétiques si applicables. Cette étape est optionnelle.</p>
    </div>

    <form method="POST" action="{{ route('vendeur.onboarding.save3') }}" enctype="multipart/form-data" id="form-certifs">
        @csrf

        {{-- ── Certifications EnR ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:star-rings-line-duotone" class="me-1"></iconify-icon>
                Certifications de conformité
            </div>

            {{-- CE --}}
            <div class="mb-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="flex-shrink-0">
                        <div style="width:48px; height:48px; background: var(--brand-pale); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <iconify-icon icon="solar:shield-check-line-duotone" style="font-size:1.4rem;" class="text-primary"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <label class="form-label fw-semibold mb-1">
                            Certificats CE
                            <small class="text-muted fw-normal d-block">(multi-fichiers)</small>
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
                            <strong>Déposez vos certificats CE</strong>
                            <div class="text-muted mt-1" style="font-size:.8rem;">PDF · Max 10 Mo par fichier · 20 fichiers max</div>
                            <input type="file" id="file-ce" name="certificats_ce[]" accept=".pdf" multiple
                                   onchange="showMultiple(this, 'preview-ce')">
                        </div>
                        <div id="preview-ce"></div>
                        @error('certificats_ce') <div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- PPE2 / Certisolis --}}
            <div class="mb-0">
                <div class="d-flex align-items-start gap-3">
                    <div class="flex-shrink-0">
                        <div style="width:48px; height:48px; background: var(--brand-pale); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <iconify-icon icon="solar:sun-line-duotone" style="font-size:1.4rem;" class="text-warning"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <label class="form-label fw-semibold mb-1">
                            Fiches Certisolis / PPE2
                            <small class="text-muted fw-normal d-block">(multi-fichiers)</small>
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
                        @error('fiches_ppe2') <div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Info optionnel ── --}}
        <div class="alert alert-light border py-3 d-flex gap-3 mb-4">
            <iconify-icon icon="solar:info-circle-line-duotone" class="flex-shrink-0 mt-1 text-muted"></iconify-icon>
            <div style="font-size:.82rem;">
                <strong>Cette étape est optionnelle.</strong> Vous pouvez continuer sans fournir de certifications.
                Cependant, ces documents sont nécessaires pour certaines catégories de produits énergétiques.
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
        const previewId = input.id.replace('file-', 'preview-');
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            showMultiple(input, previewId);
        }
    });
});

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
            btn.closest('li')?.remove();
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
