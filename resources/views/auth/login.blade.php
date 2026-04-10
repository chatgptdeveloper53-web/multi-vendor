@extends('front.layouts.base')

@section('title', 'Connexion — Multi-Vendor')

{{-- Force dark header on auth pages --}}
@php $headerDark = true; @endphp

@section('content')

    {{-- ===================== BREADCRUMB ===================== --}}
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image"
         data-bg="{{ asset('assets/img/bg/14.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Se Connecter</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Accueil</a></li>
                                <li>Connexion</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Breadcrumb end --}}

    {{-- ===================== LOGIN FORM ===================== --}}
    <div class="ltn__login-area pb-65">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area text-center">
                        <h1 class="section-title">Connexion à<br>Votre Compte</h1>
                        <p>Accédez à votre espace personnel pour gérer vos commandes et votre profil.</p>
                    </div>
                </div>
            </div>

            {{-- Session status --}}
            @if (session('status'))
                <div class="alert alert-success text-center mb-3">{{ session('status') }}</div>
            @endif

            <div class="row">
                {{-- Login form --}}
                <div class="col-lg-6">
                    <div class="account-login-inner">
                        <form method="POST" action="{{ route('login') }}" class="ltn__form-box contact-form-box">
                            @csrf

                            {{-- Email --}}
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="Adresse email *"
                                   required
                                   autofocus>
                            @error('email')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror

                            {{-- Password --}}
                            <input type="password"
                                   name="password"
                                   placeholder="Mot de passe *"
                                   required>
                            @error('password')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror

                            {{-- Remember me --}}
                            <div class="go-to-btn mt-10 mb-10">
                                <label>
                                    <input type="checkbox" name="remember">
                                    &nbsp; Se souvenir de moi
                                </label>
                            </div>

                            <div class="btn-wrapper mt-0">
                                <button class="theme-btn-1 btn btn-block" type="submit">
                                    SE CONNECTER
                                </button>
                            </div>

                            <div class="go-to-btn mt-20">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}">
                                        <small>MOT DE PASSE OUBLIÉ ?</small>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Create account CTA --}}
                <div class="col-lg-6">
                    <div class="account-create text-center pt-50">
                        <h4>PAS ENCORE DE COMPTE ?</h4>
                        <p>Créez votre compte pour profiter de toutes nos offres, suivre vos commandes et gérer vos favoris.</p>
                        <div class="btn-wrapper">
                            <a href="{{ route('register') }}" class="theme-btn-1 btn black-btn">
                                CRÉER UN COMPTE
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Login area end --}}

@endsection
