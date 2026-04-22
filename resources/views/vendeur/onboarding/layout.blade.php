<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inscription Vendeur') — Multi-Vendor</title>

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Iconify --}}
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>

    <style>
        :root {
            --brand:      #84b817;
            --brand-dark: #6a9413;
            --brand-pale: #f0f8e0;
            --step-done:  #84b817;
            --step-active:#84b817;
            --step-idle:  #d0d0d0;
        }

        body { background: #f5f6fa; font-family: 'Segoe UI', system-ui, sans-serif; min-height: 100vh; }

        /* ── Top bar ── */
        .ob-topbar {
            background: #fff;
            border-bottom: 1px solid #e8e8e8;
            padding: 12px 0;
            position: sticky; top: 0; z-index: 100;
        }
        .ob-topbar .logo { font-weight: 800; font-size: 1.2rem; color: var(--brand); letter-spacing: -0.5px; }
        .ob-topbar .logo span { color: #222; }

        /* ── Stepper ── */
        .stepper { display: flex; align-items: flex-start; justify-content: center; gap: 0; padding: 24px 16px 8px; }
        .step { display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 160px; position: relative; }
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px; left: calc(50% + 20px);
            width: calc(100% - 40px); height: 2px;
            background: var(--step-idle);
            z-index: 0;
            transition: background .3s;
        }
        .step.done:not(:last-child)::after,
        .step.active:not(:last-child)::after { background: var(--step-done); }

        .step-circle {
            width: 40px; height: 40px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .9rem;
            border: 2px solid var(--step-idle);
            background: #fff; color: #aaa;
            position: relative; z-index: 1;
            transition: all .3s;
        }
        .step.done  .step-circle { background: var(--step-done);  border-color: var(--step-done);  color: #fff; }
        .step.active .step-circle { background: #fff; border-color: var(--step-active); color: var(--step-active); box-shadow: 0 0 0 4px rgba(132,184,23,.15); }

        .step-label {
            font-size: .7rem; color: #aaa; text-align: center;
            margin-top: 6px; line-height: 1.2; font-weight: 500;
            display: none; /* hidden on xs */
        }
        .step.done .step-label, .step.active .step-label { color: #444; }
        @media(min-width: 480px) { .step-label { display: block; } }

        /* ── Progress bar (mobile supplement) ── */
        .ob-progress { height: 4px; background: #eee; }
        .ob-progress-bar { height: 4px; background: var(--brand); transition: width .4s ease; }

        /* ── Card ── */
        .ob-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,.07);
            padding: 32px;
        }
        @media(max-width: 575px) { .ob-card { padding: 20px 16px; border-radius: 8px; } }

        /* ── Section heading ── */
        .ob-section-title {
            font-size: .7rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1.5px; color: var(--brand); margin-bottom: 16px;
        }
        .ob-section { background: var(--brand-pale); border-radius: 8px; padding: 20px; margin-bottom: 24px; }

        /* ── Buttons ── */
        .btn-brand { background: var(--brand); border-color: var(--brand); color: #fff; font-weight: 600; }
        .btn-brand:hover { background: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }
        .btn-outline-brand { border-color: var(--brand); color: var(--brand); font-weight: 600; }
        .btn-outline-brand:hover { background: var(--brand-pale); }

        /* ── TVA badge ── */
        .vies-badge { display: inline-flex; align-items: center; gap: 6px; font-size: .8rem; padding: 4px 10px; border-radius: 20px; }
        .vies-valid { background: #e8f8e0; color: #4a8a00; }
        .vies-invalid { background: #fff3e0; color: #b35a00; }
        .vies-checking { background: #f0f0f0; color: #888; }

        /* ── Drop zone ── */
        .drop-zone {
            border: 2px dashed #d0d0d0; border-radius: 8px;
            padding: 32px 16px; text-align: center; cursor: pointer;
            transition: all .2s; background: #fafafa;
        }
        .drop-zone:hover, .drop-zone.dragover { border-color: var(--brand); background: var(--brand-pale); }
        .drop-zone input[type=file] { display: none; }

        /* ── File list ── */
        .file-list { list-style: none; padding: 0; margin: 12px 0 0; }
        .file-list li {
            display: flex; align-items: center; gap-8px;
            padding: 8px 12px; background: #f8f8f8; border-radius: 6px;
            margin-bottom: 6px; font-size: .85rem;
        }
        .file-list li .remove-file { margin-left: auto; cursor: pointer; color: #e00; font-size: 1rem; background: none; border: none; padding: 0; }

        /* ── Matrix table ── */
        .matrix-table th { font-size: .75rem; white-space: nowrap; }
        .matrix-table td input { font-size: .8rem; padding: 4px 6px; }
        .matrix-table .btn-remove-row { padding: 2px 6px; font-size: .75rem; }

        /* ── Save notice ── */
        .save-notice { font-size: .75rem; color: #888; }

        /* ── Footer nav ── */
        .ob-footer { padding: 24px 0 40px; }
    </style>
    @stack('styles')
</head>
<body>

    {{-- ── Top bar ── --}}
    <div class="ob-topbar">
        <div class="container-lg">
            <div class="d-flex align-items-center justify-content-between">
                <div class="logo">Multi<span>Vendor</span> <small class="text-muted fw-normal" style="font-size:.75rem;">— Espace Vendeur</small></div>
                <div class="d-flex align-items-center gap-3">
                    <span class="save-notice d-none d-md-inline">
                        <iconify-icon icon="solar:floppy-disk-line-duotone"></iconify-icon>
                        Sauvegarde automatique à chaque étape
                    </span>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">
                        <iconify-icon icon="solar:home-2-line-duotone" class="me-1"></iconify-icon>
                        Accueil
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Stepper ── --}}
    @php
        $etapeActuelle = $etapeNum ?? 1;
        $etapes = [
            0 => 'Compte',
            1 => 'Société',
            2 => 'Documents',
            3 => 'Certifications',
            4 => 'Logistique',
            5 => 'Confirmation',
        ];
    @endphp
    <div class="bg-white border-bottom">
        <div class="container-lg">
            <div class="stepper">
                @foreach($etapes as $num => $label)
                    @php
                        // Étape 0 (Compte) est toujours done
                        // Les autres étapes : done si < etapeActuelle, active si === etapeActuelle
                        $cls = ($num === 0 || $num < $etapeActuelle) ? 'done' : ($num === $etapeActuelle ? 'active' : '');
                    @endphp
                    <div class="step {{ $cls }}">
                        <div class="step-circle">
                            @if($num === 0 || $num < $etapeActuelle)
                                <iconify-icon icon="solar:check-circle-bold" style="font-size:1.1rem;"></iconify-icon>
                            @else
                                {{ $num }}
                            @endif
                        </div>
                        <div class="step-label">{{ $label }}</div>
                    </div>
                @endforeach
            </div>
            {{-- Progress bar mobile --}}
            <div class="ob-progress">
                <div class="ob-progress-bar" style="width: {{ round((($etapeActuelle - 1) / 5) * 100) }}%"></div>
            </div>
        </div>
    </div>

    {{-- ── Main content ── --}}
    <div class="container-lg py-4">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex gap-2 align-items-center" role="alert">
                <iconify-icon icon="solar:check-circle-line-duotone"></iconify-icon>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('vies_info'))
            <div class="alert alert-info alert-dismissible fade show d-flex gap-2 align-items-center" role="alert">
                <iconify-icon icon="solar:shield-check-line-duotone"></iconify-icon>
                {{ session('vies_info') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-warning alert-dismissible fade show d-flex gap-2 align-items-center" role="alert">
                <iconify-icon icon="solar:info-circle-line-duotone"></iconify-icon>
                {{ session('info') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Veuillez corriger les erreurs ci-dessous :</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    {{-- Bootstrap 5 --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
