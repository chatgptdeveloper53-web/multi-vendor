{{-- ============================================================
     FRONT HEADER — Broccoli Template
     Comportement :
       - Page d'accueil  → header transparent (ltn__header-5)
       - Pages auth       → sticky noir  (ltn__sticky-bg-black)
     Variables Blade attendues (optionnelles) :
       $headerClass    → classe supplémentaire sur <header>
       $headerDark     → true pour forcer le style sombre
============================================================ --}}

@php
    $isDark  = isset($headerDark) ? $headerDark : false;
    $bgClass = $isDark ? 'ltn__sticky-bg-black' : 'ltn__sticky-bg-white';
    $logoFile= $isDark ? 'logo-2.png' : 'logo.png';
    $menuColor = $isDark ? 'menu-color-white' : '';
@endphp

<header class="ltn__header-area ltn__header-5 ltn__header-transparent gradient-color-4 {{ $headerClass ?? '' }}">

    {{-- ── Top bar ── --}}
    <div class="ltn__header-top-area {{ $isDark ? 'd-none' : '' }}">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="ltn__top-bar-menu">
                        <ul>
                            <li><a href="#"><i class="icon-placeholder"></i> 15/A, Nest Tower, NYC</a></li>
                            <li><a href="mailto:contact@multivendor.com"><i class="icon-mail"></i> contact@multivendor.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="top-bar-right text-end">
                        <div class="ltn__top-bar-menu">
                            <ul>
                                <li>
                                    <div class="ltn__drop-menu ltn__currency-menu ltn__language-menu">
                                        <ul>
                                            <li>
                                                <a href="#" class="dropdown-toggle">
                                                    <span class="active-currency">Français</span>
                                                </a>
                                                <ul>
                                                    <li><a href="#">English</a></li>
                                                    <li><a href="#">Français</a></li>
                                                    <li><a href="#">Español</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li>
                                    <div class="ltn__social-media">
                                        <ul>
                                            <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                            <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Top bar end --}}

    {{-- ── Middle header (logo + nav + actions) ── --}}
    <div class="ltn__header-middle-area ltn__header-sticky {{ $bgClass }} ltn__logo-right-menu-option plr--9---">
        <div class="container">
            <div class="row">
                {{-- Logo --}}
                <div class="col">
                    <div class="site-logo-wrap">
                        <div class="site-logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('assets/img/' . $logoFile) }}" alt="Logo">
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="col header-menu-column {{ $menuColor }}">
                    <div class="header-menu d-none d-xl-block">
                        <nav>
                            <div class="ltn__main-menu">
                                <ul>
                                    <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                                        <a href="{{ route('home') }}">Accueil</a>
                                    </li>
                                    <li class="menu-icon {{ request()->routeIs('shop.*') ? 'active' : '' }}">
                                        <a href="{{ route('shop.index') }}">Boutique</a>
                                        <ul>
                                            <li><a href="{{ route('shop.index') }}">Tous les produits</a></li>
                                            <li><a href="{{ route('cart.index') }}">Panier</a></li>
                                            <li><a href="{{ route('checkout.index') }}">Commander</a></li>
                                        </ul>
                                    </li>
                                    <li class="{{ request()->routeIs('about') ? 'active' : '' }}">
                                        <a href="{{ route('about') }}">À propos</a>
                                    </li>
                                    <li class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                                        <a href="{{ route('contact') }}">Contact</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>

                {{-- Header actions --}}
                <div class="ltn__header-options ltn__header-options-2">
                    {{-- Search --}}
                    <div class="header-search-wrap">
                        <div class="header-search-1">
                            <div class="search-icon">
                                <i class="icon-search for-search-show"></i>
                                <i class="icon-cancel for-search-close"></i>
                            </div>
                        </div>
                        <div class="header-search-1-form">
                            <form method="GET" action="{{ route('shop.index') }}">
                                <input type="text" name="search" placeholder="Rechercher...">
                                <button type="submit"><span><i class="icon-search"></i></span></button>
                            </form>
                        </div>
                    </div>

                    {{-- User menu --}}
                    <div class="ltn__drop-menu user-menu">
                        <ul>
                            <li>
                                <a href="#"><i class="icon-user"></i></a>
                                <ul>
                                    @guest
                                        <li><a href="{{ route('login') }}">Se connecter</a></li>
                                        <li><a href="{{ route('register') }}">S'inscrire</a></li>
                                    @else
                                        <li><a href="{{ route('profile.edit') }}">{{ Auth::user()->nom }}</a></li>
                                        @if(Auth::user()->isAdmin())
                                            <li><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                                        @endif
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                                                @csrf
                                                <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;width:100%;text-align:left">
                                                    Déconnexion
                                                </button>
                                            </form>
                                        </li>
                                    @endguest
                                </ul>
                            </li>
                        </ul>
                    </div>

                    {{-- Mini cart --}}
                    <div class="mini-cart-icon">
                        <a href="#ltn__utilize-cart-menu" class="ltn__utilize-toggle">
                            <i class="icon-shopping-cart"></i>
                            <sup>{{ Auth::check() ? (Auth::user()->panier?->produits?->count() ?? 0) : 0 }}</sup>
                        </a>
                    </div>

                    {{-- Mobile menu toggle --}}
                    <div class="mobile-menu-toggle d-xl-none">
                        <a href="#ltn__utilize-mobile-menu" class="ltn__utilize-toggle">
                            <svg viewBox="0 0 800 600">
                                <path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200" id="top"></path>
                                <path d="M300,320 L540,320" id="middle"></path>
                                <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190" id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318)"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                {{-- Header actions end --}}

            </div>
        </div>
    </div>
    {{-- Middle header end --}}

</header>
