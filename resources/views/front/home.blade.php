@extends('front.layouts.base')

@section('title', 'Accueil — Multi-Vendor Bio')

@section('content')

    {{-- ===================== HERO SLIDER ===================== --}}
    <div class="ltn__slider-area ltn__slider-3 section-bg-1">
        <div class="ltn__slide-one-active slick-slide-arrow-1 slick-slide-dots-1">

            {{-- Slide 1 --}}
            <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3">
                <div class="ltn__slide-item-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 align-self-center">
                                <div class="slide-item-info">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                        <h6 class="slide-sub-title animated">
                                            <img src="{{ asset('assets/img/icons/icon-img/1.png') }}" alt="#">
                                            100% Produits Authentiques
                                        </h6>
                                        <h1 class="slide-title animated">Savoureux &amp; Sains<br>Aliments Bio</h1>
                                        <div class="btn-wrapper animated">
                                            <a href="{{ route('shop.index') }}" class="theme-btn-1 btn btn-effect-1 text-uppercase">
                                                Explorer les Produits
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="slide-item-img">
                                    <img src="{{ asset('assets/img/slider/23.png') }}" alt="Produits Bio">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 2 --}}
            <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3">
                <div class="ltn__slide-item-inner text-right text-end">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 align-self-center">
                                <div class="slide-item-info">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                        <h6 class="slide-sub-title animated">
                                            <img src="{{ asset('assets/img/icons/icon-img/1.png') }}" alt="#">
                                            100% Produits Authentiques
                                        </h6>
                                        <h1 class="slide-title animated">
                                            Les Favoris<br>de Notre Jardin
                                        </h1>
                                        <div class="slide-brief animated">
                                            <p>Des produits cultivés avec soin, récoltés au bon moment et livrés frais chez vous.</p>
                                        </div>
                                        <div class="btn-wrapper animated">
                                            <a href="{{ route('shop.index') }}" class="theme-btn-1 btn btn-effect-1 text-uppercase">
                                                Explorer les Produits
                                            </a>
                                            <a href="{{ route('about') }}" class="btn btn-transparent btn-effect-3 text-uppercase">
                                                En savoir plus
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="slide-item-img slide-img-left">
                                    <img src="{{ asset('assets/img/slider/21.png') }}" alt="Produits Bio">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- Slider end --}}

    {{-- ===================== FEATURES ===================== --}}
    <div class="ltn__feature-area before-bg-bottom-2 mb--30--- plr--5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__feature-item-box-wrap ltn__border-between-column white-bg">
                        <div class="row">
                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="ltn__feature-item ltn__feature-item-8">
                                    <div class="ltn__feature-icon">
                                        <img src="{{ asset('assets/img/icons/icon-img/11.png') }}" alt="#">
                                    </div>
                                    <div class="ltn__feature-info">
                                        <h4>Produits Sélectionnés</h4>
                                        <p>Livraison gratuite pour toute commande supérieure à 100€</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="ltn__feature-item ltn__feature-item-8">
                                    <div class="ltn__feature-icon">
                                        <img src="{{ asset('assets/img/icons/icon-img/12.png') }}" alt="#">
                                    </div>
                                    <div class="ltn__feature-info">
                                        <h4>Fait Main</h4>
                                        <p>La qualité de nos produits est notre principale priorité</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="ltn__feature-item ltn__feature-item-8">
                                    <div class="ltn__feature-icon">
                                        <img src="{{ asset('assets/img/icons/icon-img/13.png') }}" alt="#">
                                    </div>
                                    <div class="ltn__feature-info">
                                        <h4>Paiement Sécurisé</h4>
                                        <p>Transactions sécurisées SSL 100%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="ltn__feature-item ltn__feature-item-8">
                                    <div class="ltn__feature-icon">
                                        <img src="{{ asset('assets/img/icons/icon-img/14.png') }}" alt="#">
                                    </div>
                                    <div class="ltn__feature-info">
                                        <h4>Retour Facile</h4>
                                        <p>Politique de retour sans tracas sous 30 jours</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Features end --}}

    {{-- ===================== FEATURED PRODUCTS ===================== --}}
    <div class="ltn__product-area ltn__product-gutter pb-100 go-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2--- text-center">
                        <h6 class="section-subtitle section-subtitle-2 ltn__secondary-color">Nos Produits</h6>
                        <h1 class="section-title">Produits en Vedette</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__product-slider-item-four-active slick-arrow-1">
                @forelse($produits ?? [] as $produit)
                    <div class="col-lg-12">
                        <div class="ltn__product-item ltn__product-item-3 text-center">
                            <div class="product-img">
                                <a href="{{ route('shop.show', $produit->id) }}">
                                    @if($produit->photos->count())
                                        <img src="{{ asset('storage/' . $produit->photos->first()->url) }}" alt="{{ $produit->nom }}">
                                    @else
                                        <img src="{{ asset('assets/img/product/1.png') }}" alt="{{ $produit->nom }}">
                                    @endif
                                </a>
                            </div>
                            <div class="product-info">
                                <h2 class="product-title">
                                    <a href="{{ route('shop.show', $produit->id) }}">{{ $produit->nom }}</a>
                                </h2>
                                <div class="product-price">
                                    <span>{{ number_format($produit->prix, 2) }}€</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Placeholder cards while no products exist --}}
                    @foreach([1, 2, 3, 4] as $i)
                        <div class="col-lg-12">
                            <div class="ltn__product-item ltn__product-item-3 text-center">
                                <div class="product-img">
                                    <img src="{{ asset('assets/img/product/' . $i . '.png') }}" alt="Produit">
                                </div>
                                <div class="product-info">
                                    <h2 class="product-title"><a href="#">Produit Bio {{ $i }}</a></h2>
                                    <div class="product-price"><span>{{ $i * 10 + 5 }}.00€</span></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </div>
    {{-- Featured products end --}}

    {{-- ===================== CALL TO ACTION ===================== --}}
    <div class="ltn__call-to-action-area call-to-action-6 before-bg-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="call-to-action-inner call-to-action-inner-6 ltn__secondary-bg position-relative text-center---">
                        <div class="coll-to-info text-color-white">
                            <h1>Livraison Gratuite</h1>
                            <p>Pour toute commande supérieure à 100€</p>
                        </div>
                        <div class="btn-wrapper">
                            <a class="btn btn-effect-3 btn-white" href="{{ route('shop.index') }}">
                                Explorer <i class="icon-next"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- CTA end --}}

@endsection
