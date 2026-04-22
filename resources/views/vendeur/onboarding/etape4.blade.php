@extends('vendeur.onboarding.layout')
@php $etapeNum = 4; @endphp
@section('title', 'Étape 4 — Logistique lourde')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-9 col-xl-8">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">🚛 Logistique lourde & Cross-Border</h4>
        <p class="text-muted mb-0">
            Notre marketplace est optimisée pour le transport de marchandises sur palettes
            <strong>1,2 m × 2,2 m · poids ≥ 800 kg</strong>. Définissez vos zones de livraison et tarifs.
        </p>
    </div>

    <form method="POST" action="{{ route('vendeur.onboarding.save4') }}" enctype="multipart/form-data" id="form-logistique">
        @csrf

        {{-- ── Adresse d'expédition ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:map-point-line-duotone" class="me-1"></iconify-icon>
                Adresse d'expédition
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Adresse complète <span class="text-danger">*</span></label>
                <input type="text" name="adresse_expedition"
                       class="form-control @error('adresse_expedition') is-invalid @enderror"
                       value="{{ old('adresse_expedition', $vendeur->adresse_expedition) }}"
                       placeholder="Ex : 456 avenue de l'Industrie, 75001 Paris"
                       maxlength="500" required>
                @error('adresse_expedition') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-0">
                <label class="form-label fw-semibold">Poids maximum par palette <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" name="poids_max_palette"
                           class="form-control @error('poids_max_palette') is-invalid @enderror"
                           value="{{ old('poids_max_palette', $vendeur->poids_max_palette) }}"
                           placeholder="1200" step="0.1" min="1" required>
                    <span class="input-group-text">kg</span>
                </div>
                @error('poids_max_palette') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                <small class="text-muted">Notre marketplace optimisée pour palettes 1,2 m × 2,2 m.</small>
            </div>
        </div>

        {{-- ── Incoterms ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:global-line-duotone" class="me-1"></iconify-icon>
                Conditions de livraison (Incoterms)
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Incoterm principal <span class="text-danger">*</span></label>
                <div class="row g-2">
                    @php
                        $incoterms = [
                            'DDP'  => ['label' => 'DDP — Delivered Duty Paid',  'desc' => 'Vous prenez en charge livraison, douanes et taxes. Idéal pour les clients finaux B2C et PME.', 'badge' => 'bg-success-subtle text-success'],
                            'EXW'  => ['label' => 'EXW — Ex Works',              'desc' => "L'acheteur collecte depuis vos locaux. Courant pour les gros volumes pro et export hors UE.", 'badge' => 'bg-primary-subtle text-primary'],
                            'DAP'  => ['label' => 'DAP — Delivered At Place',    'desc' => 'Vous livrez jusqu\'au lieu convenu, acheteur gère les droits. Bon compromis cross-border.',  'badge' => 'bg-info-subtle text-info'],
                            'FCA'  => ['label' => 'FCA — Free Carrier',          'desc' => 'Vous remettez la marchandise au transporteur désigné par l\'acheteur.', 'badge' => 'bg-warning-subtle text-warning'],
                            'LIBRE'=> ['label' => 'Selon commande / À négocier', 'desc' => 'Incoterm défini au cas par cas avec chaque acheteur.', 'badge' => 'bg-secondary-subtle text-secondary'],
                        ];
                        $selected = old('incoterm_preference', $vendeur->incoterm_preference ?? '');
                    @endphp
                    @foreach($incoterms as $code => $inc)
                        <div class="col-12 col-sm-6">
                            <label class="d-block cursor-pointer">
                                <input type="radio" name="incoterm_preference" value="{{ $code }}"
                                       class="visually-hidden incoterm-radio"
                                       @checked($selected === $code) required>
                                <div class="incoterm-card p-3 rounded border h-100 {{ $selected === $code ? 'border-brand selected' : '' }}"
                                     style="cursor:pointer; transition: all .15s;">
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="mt-1">
                                            <span class="badge {{ $inc['badge'] }} mb-1">{{ $code }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size:.85rem;">{{ $inc['label'] }}</div>
                                            <div class="text-muted" style="font-size:.75rem; line-height:1.4;">{{ $inc['desc'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('incoterm_preference') <div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div> @enderror
            </div>

            <div class="mb-0">
                <label class="form-label fw-semibold">Précisions / conditions particulières <small class="text-muted fw-normal">(optionnel)</small></label>
                <textarea name="incoterm_notes" rows="3" class="form-control"
                          placeholder="Ex : DDP pour France métropolitaine, EXW pour export hors UE · Frais de mise en palette inclus pour commandes > 5 000 €…">{{ old('incoterm_notes', $vendeur->incoterm_notes) }}</textarea>
            </div>
        </div>

        {{-- ── Délais & MOQ ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:clock-circle-line-duotone" class="me-1"></iconify-icon>
                Délais & Minimum de commande
            </div>
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Délai de traitement moyen</label>
                    <div class="input-group">
                        <input type="number" name="delai_traitement_jours"
                               class="form-control @error('delai_traitement_jours') is-invalid @enderror"
                               value="{{ old('delai_traitement_jours', $vendeur->delai_traitement_jours) }}"
                               min="1" max="90" placeholder="5">
                        <span class="input-group-text">jours ouvrés</span>
                    </div>
                    <small class="text-muted">Délai entre réception de la commande et expédition.</small>
                    @error('delai_traitement_jours') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label fw-semibold">Minimum de commande (MOQ)</label>
                    <div class="input-group">
                        <input type="number" name="moq"
                               class="form-control @error('moq') is-invalid @enderror"
                               value="{{ old('moq', $vendeur->moq) }}"
                               min="1" placeholder="1">
                        <span class="input-group-text">unité(s)</span>
                    </div>
                    <small class="text-muted">Quantité minimale par commande.</small>
                    @error('moq') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Politique de retour & litiges <small class="text-muted fw-normal">(optionnel)</small></label>
                    <textarea name="politique_retour" rows="2" class="form-control"
                              placeholder="Ex : Retours acceptés sous 14 jours, palette non ouverte · Pour matériel défectueux, SAV assuré par…">{{ old('politique_retour', $vendeur->politique_retour) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── Matrice de transport ── --}}
        <div class="ob-card mb-4">
            <div class="ob-section-title">
                <iconify-icon icon="solar:map-line-duotone" class="me-1"></iconify-icon>
                Matrice de transport
                <small class="text-muted text-lowercase fw-normal">(zones · tarifs · délais)</small>
            </div>

            {{-- Import CSV --}}
            <div class="mb-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="form-label fw-semibold mb-0">
                        <iconify-icon icon="solar:file-text-line-duotone" class="me-1"></iconify-icon>
                        Import automatique (CSV / Excel)
                    </label>
                    <a href="#template-info" data-bs-toggle="collapse" class="btn btn-sm btn-outline-secondary">
                        <iconify-icon icon="solar:question-circle-line-duotone" class="me-1"></iconify-icon>
                        Format attendu
                    </a>
                </div>

                <div class="collapse mb-3" id="template-info">
                    <div class="alert alert-light border py-2" style="font-size:.78rem;">
                        <strong>Colonnes attendues (CSV, séparateur virgule ou point-virgule) :</strong><br>
                        <code>zone, poids_min_kg, poids_max_kg, prix_base_eur, prix_par_kg_eur, delai_jours, incoterm, description</code>
                        <hr class="my-2">
                        <strong>Exemple :</strong><br>
                        <code>France métropolitaine,0,500,150,0.15,3,DDP,Livraison domicile incluse</code><br>
                        <code>France métropolitaine,500,800,200,0.12,3,DDP,</code><br>
                        <code>Zone Europe 1 (BE/NL/LU),0,800,280,0.20,5,DAP,</code><br>
                        <code>Zone Europe 2 (DE/AT/CH),0,800,350,0.18,7,DAP,</code><br>
                        <code>Export hors UE,0,999,500,0.25,14,EXW,Dédouanement acheteur</code>
                    </div>
                </div>

                @if($vendeur->matrice_transport_fichier)
                    <div class="alert alert-success py-2 d-flex align-items-center gap-2 mb-2" style="font-size:.83rem;">
                        <iconify-icon icon="solar:check-circle-line-duotone"></iconify-icon>
                        Matrice importée : <strong>{{ basename($vendeur->matrice_transport_fichier) }}</strong>
                        <span class="ms-auto badge bg-success-subtle text-success">{{ $vendeur->logistiques->where('source','import')->count() }} lignes</span>
                    </div>
                @endif

                <div class="drop-zone" id="dz-csv" onclick="document.getElementById('file-csv').click()">
                    <iconify-icon icon="solar:cloud-upload-line-duotone" style="font-size:1.8rem;" class="text-muted d-block mb-2"></iconify-icon>
                    <strong>Glissez-déposez votre fichier CSV ou Excel</strong>
                    <div class="text-muted mt-1" style="font-size:.8rem;">CSV · XLSX · TXT · Max 2 Mo</div>
                    <input type="file" id="file-csv" name="matrice_transport" accept=".csv,.xlsx,.txt"
                           onchange="showFile(this, 'dz-csv', 'preview-csv')">
                </div>
                <div id="preview-csv"></div>
            </div>

            {{-- Saisie manuelle --}}
            <div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="form-label fw-semibold mb-0">
                        <iconify-icon icon="solar:pen-line-duotone" class="me-1"></iconify-icon>
                        Saisie manuelle des zones
                    </label>
                    <button type="button" class="btn btn-sm btn-outline-brand" onclick="ajouterLigne()">
                        <iconify-icon icon="solar:add-circle-line-duotone" class="me-1"></iconify-icon>
                        Ajouter une zone
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered matrix-table mb-0" id="matrice-table">
                        <thead class="table-light">
                            <tr>
                                <th>Zone de livraison</th>
                                <th>Poids min (kg)</th>
                                <th>Poids max (kg)</th>
                                <th>Prix fixe (€)</th>
                                <th>€/kg suppl.</th>
                                <th>Délai (j.)</th>
                                <th>Incoterm</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="matrice-body">
                            {{-- Lignes existantes --}}
                            @foreach($vendeur->logistiques->where('source','manual') as $i => $ligne)
                                <tr>
                                    <td><input type="text" name="lignes[{{ $i }}][zone]" class="form-control form-control-sm" value="{{ $ligne->zone }}" required></td>
                                    <td><input type="number" name="lignes[{{ $i }}][poids_min]" class="form-control form-control-sm" value="{{ $ligne->poids_min }}" min="0" step="1"></td>
                                    <td><input type="number" name="lignes[{{ $i }}][poids]" class="form-control form-control-sm" value="{{ $ligne->poids }}" min="0" step="1"></td>
                                    <td><input type="number" name="lignes[{{ $i }}][prix]" class="form-control form-control-sm" value="{{ $ligne->prix }}" min="0" step="0.01"></td>
                                    <td><input type="number" name="lignes[{{ $i }}][tarif_par_kg]" class="form-control form-control-sm" value="{{ $ligne->tarif_par_kg }}" min="0" step="0.01"></td>
                                    <td><input type="number" name="lignes[{{ $i }}][delai_jours]" class="form-control form-control-sm" value="{{ $ligne->delai_jours }}" min="1"></td>
                                    <td>
                                        <select name="lignes[{{ $i }}][incoterm]" class="form-select form-select-sm">
                                            @foreach(['DDP','EXW','DAP','FCA'] as $inc)
                                                <option value="{{ $inc }}" @selected(strtoupper($ligne->incoterm) === $inc)>{{ $inc }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><button type="button" class="btn btn-remove-row btn-danger btn-sm" onclick="this.closest('tr').remove()">×</button></td>
                                </tr>
                            @endforeach
                            {{-- Ligne vide si aucune --}}
                            @if($vendeur->logistiques->where('source','manual')->isEmpty())
                                <tr id="empty-row">
                                    <td colspan="8" class="text-center text-muted py-3" style="font-size:.82rem;">
                                        <iconify-icon icon="solar:map-line-duotone" class="d-block mb-1" style="font-size:1.5rem;"></iconify-icon>
                                        Aucune zone saisie — importez un CSV ou ajoutez des lignes manuellement.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <small class="text-muted">Les lignes importées via CSV et les lignes manuelles coexistent.</small>
            </div>
        </div>

        {{-- ── Navigation ── --}}
        <div class="ob-footer d-flex align-items-center justify-content-between">
            <a href="{{ route('vendeur.onboarding.etape', 3) }}" class="btn btn-outline-secondary">
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

@push('styles')
<style>
.cursor-pointer { cursor: pointer; }
.incoterm-card { border-color: #dee2e6 !important; }
.incoterm-card.selected, .incoterm-card:hover { border-color: var(--brand) !important; background: var(--brand-pale); }
.incoterm-radio:checked + .incoterm-card { border-color: var(--brand) !important; background: var(--brand-pale); box-shadow: 0 0 0 3px rgba(132,184,23,.15); }
</style>
@endpush

@push('scripts')
<script>
let ligneIndex = {{ $vendeur->logistiques->where('source','manual')->count() }};

function ajouterLigne() {
    const emptyRow = document.getElementById('empty-row');
    if (emptyRow) emptyRow.remove();

    const tbody = document.getElementById('matrice-body');
    const i = ligneIndex++;
    const incOptions = ['DDP','EXW','DAP','FCA'].map(v => `<option value="${v}">${v}</option>`).join('');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" name="lignes[${i}][zone]" class="form-control form-control-sm" placeholder="France métr." required></td>
        <td><input type="number" name="lignes[${i}][poids_min]" class="form-control form-control-sm" value="0" min="0" step="1"></td>
        <td><input type="number" name="lignes[${i}][poids]" class="form-control form-control-sm" placeholder="800" min="0" step="1"></td>
        <td><input type="number" name="lignes[${i}][prix]" class="form-control form-control-sm" placeholder="150" min="0" step="0.01"></td>
        <td><input type="number" name="lignes[${i}][tarif_par_kg]" class="form-control form-control-sm" placeholder="0.15" min="0" step="0.01"></td>
        <td><input type="number" name="lignes[${i}][delai_jours]" class="form-control form-control-sm" placeholder="3" min="1"></td>
        <td><select name="lignes[${i}][incoterm]" class="form-select form-select-sm">${incOptions}</select></td>
        <td><button type="button" class="btn btn-remove-row btn-danger btn-sm" onclick="this.closest('tr').remove()">×</button></td>
    `;
    tbody.appendChild(tr);
    tr.querySelector('input').focus();
}

// Incoterm card selection styling
document.querySelectorAll('.incoterm-radio').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.incoterm-card').forEach(c => c.classList.remove('selected'));
        if (radio.checked) radio.nextElementSibling.classList.add('selected');
    });
});

// Drop zone + file preview
document.querySelectorAll('.drop-zone').forEach(zone => {
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
    zone.addEventListener('drop', e => {
        e.preventDefault(); zone.classList.remove('dragover');
        const input = zone.querySelector('input[type=file]');
        if (e.dataTransfer.files[0]) {
            input.files = e.dataTransfer.files;
            showFile(input, zone.id, 'preview-csv');
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
            <span class="ms-auto text-muted">${(f.size/1024).toFixed(0)} Ko</span>
        </div>`;
}
</script>
@endpush
