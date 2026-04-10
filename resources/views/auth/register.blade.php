@extends('front.layouts.base')

@section('title', 'Inscription — Multi-Vendor')

@php $headerDark = true; @endphp

@section('content')

    {{-- ── Breadcrumb ── --}}
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image"
         data-bg="{{ asset('assets/img/bg/14.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Créer un Compte</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Accueil</a></li>
                                <li>Inscription</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Register area ── --}}
    <div class="ltn__login-area pb-110 pt-60">
        <div class="container">

            {{-- Section title --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area text-center mb-40">
                        <h1 class="section-title">Rejoignez-nous</h1>
                        <p>Choisissez votre profil et créez votre compte en quelques secondes.</p>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════
                 STEP 1 — Role selector cards
            ══════════════════════════════════════════════════ --}}
            <div class="row justify-content-center mb-40" id="role-selector">
                <div class="col-lg-8">
                    <p class="text-center fw-bold mb-20" style="font-size:14px;letter-spacing:1px;text-transform:uppercase;color:#333;">
                        Je m'inscris en tant que :
                    </p>
                    <div class="row g-3">

                        {{-- Card Acheteur --}}
                        <div class="col-md-6">
                            <div class="role-card" id="card-acheteur" onclick="selectRole('acheteur')"
                                 style="border:2px solid #e5e5e5;border-radius:8px;padding:30px 20px;
                                        text-align:center;cursor:pointer;transition:all .25s;">
                                <div style="font-size:48px;margin-bottom:12px;">🛒</div>
                                <h5 style="font-weight:700;margin-bottom:8px;">Acheteur</h5>
                                <p style="color:#777;font-size:13px;margin:0;">
                                    Parcourez et commandez des produits auprès de nos vendeurs certifiés.
                                </p>
                                <div class="mt-15">
                                    <span style="background:#84b817;color:#fff;padding:4px 14px;border-radius:20px;font-size:12px;">
                                        Accès immédiat
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Vendeur --}}
                        <div class="col-md-6">
                            <div class="role-card" id="card-vendeur" onclick="selectRole('vendeur')"
                                 style="border:2px solid #e5e5e5;border-radius:8px;padding:30px 20px;
                                        text-align:center;cursor:pointer;transition:all .25s;">
                                <div style="font-size:48px;margin-bottom:12px;">🏪</div>
                                <h5 style="font-weight:700;margin-bottom:8px;">Vendeur</h5>
                                <p style="color:#777;font-size:13px;margin:0;">
                                    Créez votre boutique, gérez vos produits et développez votre activité.
                                </p>
                                <div class="mt-15">
                                    <span style="background:#f5a623;color:#fff;padding:4px 14px;border-radius:20px;font-size:12px;">
                                        Validation requise
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                    @error('role')
                        <p class="text-danger text-center mt-10">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════
                 STEP 2 — Registration form (hidden until role chosen)
            ══════════════════════════════════════════════════ --}}
            <div class="row justify-content-center" id="register-form-wrapper" style="display:none !important;">
                <div class="col-lg-6">
                    <div class="account-login-inner">

                        {{-- Selected role banner --}}
                        <div id="role-banner" class="text-center mb-20"
                             style="padding:12px;border-radius:6px;background:#f8f8f8;border:1px solid #eee;">
                            <span id="role-banner-text" style="font-weight:600;font-size:14px;"></span>
                            <a href="#" onclick="resetRole(event)"
                               style="margin-left:12px;font-size:12px;color:#84b817;text-decoration:underline;">
                                Changer
                            </a>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="ltn__form-box contact-form-box">
                            @csrf

                            {{-- Hidden role field --}}
                            <input type="hidden" name="role" id="role-input" value="{{ old('role') }}">

                            {{-- Errors summary --}}
                            @if($errors->any())
                                <div style="background:#fff3f3;border:1px solid #f5c6cb;padding:12px 16px;
                                            border-radius:6px;margin-bottom:20px;">
                                    <ul style="margin:0;padding-left:16px;color:#721c24;font-size:13px;">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Nom --}}
                            <input type="text"
                                   name="nom"
                                   value="{{ old('nom') }}"
                                   placeholder="Nom complet *"
                                   required autofocus>
                            @error('nom')
                                <p class="text-danger" style="font-size:12px;margin-top:-14px;margin-bottom:12px;">{{ $message }}</p>
                            @enderror

                            {{-- Email --}}
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="Adresse email *"
                                   required>
                            @error('email')
                                <p class="text-danger" style="font-size:12px;margin-top:-14px;margin-bottom:12px;">{{ $message }}</p>
                            @enderror

                            {{-- Password --}}
                            <input type="password"
                                   name="password"
                                   placeholder="Mot de passe *"
                                   required>
                            @error('password')
                                <p class="text-danger" style="font-size:12px;margin-top:-14px;margin-bottom:12px;">{{ $message }}</p>
                            @enderror

                            {{-- Confirm password --}}
                            <input type="password"
                                   name="password_confirmation"
                                   placeholder="Confirmer le mot de passe *"
                                   required>

                            {{-- ── Extra fields for Vendeur only ── --}}
                            <div id="vendeur-fields" style="display:none;">
                                <div style="background:#fffbf0;border:1px solid #f5a623;border-radius:6px;
                                            padding:16px 16px 4px;margin-bottom:20px;">
                                    <p style="font-size:12px;font-weight:600;color:#f5a623;
                                               text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">
                                        🏪 Informations Vendeur
                                    </p>
                                    <input type="text"
                                           name="coordonnees"
                                           value="{{ old('coordonnees') }}"
                                           placeholder="Coordonnées (adresse, ville…)">

                                    <p style="font-size:11px;color:#999;margin-top:-12px;margin-bottom:16px;">
                                        ℹ️ Votre dossier sera examiné par notre équipe avant validation.
                                    </p>
                                </div>
                            </div>

                            {{-- CGU --}}
                            <label class="checkbox-inline">
                                <input type="checkbox" name="consent_terms" value="1" required>
                                J'accepte les <a href="#" style="color:#84b817;">Conditions Générales</a>
                                et la <a href="#" style="color:#84b817;">Politique de Confidentialité</a>. *
                            </label>

                            <div class="btn-wrapper mt-20">
                                <button class="theme-btn-1 btn reverse-color btn-block" type="submit">
                                    CRÉER MON COMPTE
                                </button>
                            </div>
                        </form>

                        <div class="by-agree text-center mt-20">
                            <div class="go-to-btn">
                                <a href="{{ route('login') }}">VOUS AVEZ DÉJÀ UN COMPTE ?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Restore role from old() on validation error
    document.addEventListener('DOMContentLoaded', function () {
        const oldRole = '{{ old('role') }}';
        if (oldRole) selectRole(oldRole, true);
    });

    function selectRole(role, silent) {
        // Update hidden input
        document.getElementById('role-input').value = role;

        // Highlight selected card
        document.querySelectorAll('.role-card').forEach(c => {
            c.style.borderColor  = '#e5e5e5';
            c.style.background   = '#fff';
            c.style.transform    = 'none';
        });
        const selected = document.getElementById('card-' + role);
        selected.style.borderColor = role === 'vendeur' ? '#f5a623' : '#84b817';
        selected.style.background  = role === 'vendeur' ? '#fffbf0' : '#f8fff0';
        selected.style.transform   = 'translateY(-4px)';

        // Update banner
        const banners = {
            acheteur: '🛒 Inscription en tant qu\'Acheteur',
            vendeur:  '🏪 Inscription en tant que Vendeur — dossier soumis à validation',
        };
        document.getElementById('role-banner-text').textContent = banners[role];

        // Show / hide vendeur extra fields
        document.getElementById('vendeur-fields').style.display =
            role === 'vendeur' ? 'block' : 'none';

        // Show the form
        if (!silent) {
            document.getElementById('register-form-wrapper').style.setProperty('display', 'block', 'important');
            document.getElementById('register-form-wrapper').scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            document.getElementById('register-form-wrapper').style.setProperty('display', 'block', 'important');
        }
    }

    function resetRole(e) {
        e.preventDefault();
        document.getElementById('role-input').value = '';
        document.querySelectorAll('.role-card').forEach(c => {
            c.style.borderColor = '#e5e5e5';
            c.style.background  = '#fff';
            c.style.transform   = 'none';
        });
        document.getElementById('register-form-wrapper').style.setProperty('display', 'none', 'important');
        document.getElementById('role-selector').scrollIntoView({ behavior: 'smooth' });
    }
</script>
@endpush
