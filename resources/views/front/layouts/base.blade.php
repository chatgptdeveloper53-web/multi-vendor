<!doctype html>
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Multi-Vendor — Produits Bio')</title>
    <meta name="description" content="@yield('meta_description', 'Découvrez nos produits biologiques frais.')">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">

    {{-- Broccoli CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

    @stack('styles')
</head>

<body>

<div class="body-wrapper">

    {{-- ===================== HEADER ===================== --}}
    @include('front.partials.header')

    {{-- ===================== CART SIDEBAR ===================== --}}
    <div id="ltn__utilize-cart-menu" class="ltn__utilize ltn__utilize-cart-menu">
        <div class="ltn__utilize-menu-inner ltn__scrollbar">
            <div class="ltn__utilize-menu-head">
                <span class="ltn__utilize-menu-title">Panier</span>
                <button class="ltn__utilize-close">×</button>
            </div>
            <div class="mini-cart-product-area ltn__scrollbar">
                {{-- Items loaded dynamically --}}
            </div>
            <div class="mini-cart-footer">
                <div class="mini-cart-sub-total">
                    <h5>Sous-total: <span>€0.00</span></h5>
                </div>
                <div class="btn-wrapper">
                    <a href="{{ route('cart.index') }}" class="theme-btn-1 btn btn-effect-1">Voir le Panier</a>
                    <a href="{{ route('checkout.index') }}" class="theme-btn-2 btn btn-effect-2">Commander</a>
                </div>
                <p>Livraison gratuite pour toute commande supérieure à 100€ !</p>
            </div>
        </div>
    </div>
    {{-- Cart Sidebar End --}}

    {{-- ===================== MOBILE MENU ===================== --}}
    <div id="ltn__utilize-mobile-menu" class="ltn__utilize ltn__utilize-mobile-menu">
        <div class="ltn__utilize-menu-inner ltn__scrollbar">
            <div class="ltn__utilize-menu-head">
                <div class="site-logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
                    </a>
                </div>
                <button class="ltn__utilize-close">×</button>
            </div>
            <div class="ltn__utilize-menu-search-form">
                <form action="#">
                    <input type="text" placeholder="Rechercher...">
                    <button><i class="fas fa-search"></i></button>
                </form>
            </div>
            <div class="ltn__utilize-menu">
                <ul>
                    <li><a href="{{ route('home') }}">Accueil</a></li>
                    <li><a href="{{ route('shop.index') }}">Boutique</a></li>
                    <li><a href="{{ route('about') }}">À propos</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
            <div class="ltn__utilize-buttons ltn__utilize-buttons-2">
                <ul>
                    @guest
                        <li><a href="{{ route('login') }}"><i class="icon-user"></i> Se connecter</a></li>
                        <li><a href="{{ route('register') }}"><i class="icon-user"></i> S'inscrire</a></li>
                    @else
                        <li><a href="{{ route('profile.edit') }}"><i class="icon-user"></i> {{ Auth::user()->nom }}</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-transparent"><i class="icon-logout"></i> Déconnexion</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
    {{-- Mobile Menu End --}}

    <div class="ltn__utilize-overlay"></div>

    {{-- ===================== PAGE CONTENT ===================== --}}
    @yield('content')

    {{-- ===================== FOOTER ===================== --}}
    @include('front.partials.footer')

</div>
{{-- body-wrapper end --}}

{{-- ===================== BACK TO TOP ===================== --}}
<div class="ltn__back-top">
    <a href="#ltn__back-top"><i class="icon-up-arrow"></i></a>
</div>

{{-- ===================== SCRIPTS ===================== --}}
<script src="{{ asset('assets/js/plugins.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

@stack('scripts')

</body>
</html>
