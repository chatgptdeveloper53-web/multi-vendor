{{-- ============================================================
     FRONT FOOTER — Broccoli Template
============================================================ --}}

<footer class="ltn__footer-area">
    <div class="footer-top-area section-bg-1 plr--5">
        <div class="container-fluid">
            <div class="row">
                {{-- About --}}
                <div class="col-xl-3 col-md-6 col-sm-6 col-12">
                    <div class="footer-widget footer-about-widget">
                        <div class="footer-logo">
                            <div class="site-logo">
                                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
                            </div>
                        </div>
                        <p>Votre marché bio de confiance. Des produits frais, locaux et certifiés directement chez vous.</p>
                        <div class="footer-address">
                            <ul>
                                <li>
                                    <div class="footer-address-icon"><i class="icon-placeholder"></i></div>
                                    <div class="footer-address-info"><p>Paris, France</p></div>
                                </li>
                                <li>
                                    <div class="footer-address-icon"><i class="icon-call"></i></div>
                                    <div class="footer-address-info"><p><a href="tel:+330123456789">+33 01 23 45 67 89</a></p></div>
                                </li>
                                <li>
                                    <div class="footer-address-icon"><i class="icon-mail"></i></div>
                                    <div class="footer-address-info"><p><a href="mailto:contact@multivendor.com">contact@multivendor.com</a></p></div>
                                </li>
                            </ul>
                        </div>
                        <div class="ltn__social-media mt-20">
                            <ul>
                                <li><a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                                <li><a href="#" title="Youtube"><i class="fab fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Company links --}}
                <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                    <div class="footer-widget footer-menu-widget clearfix">
                        <h4 class="footer-title">Entreprise</h4>
                        <div class="footer-menu">
                            <ul>
                                <li><a href="{{ route('about') }}">À propos</a></li>
                                <li><a href="{{ route('shop.index') }}">Tous les produits</a></li>
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Services --}}
                <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                    <div class="footer-widget footer-menu-widget clearfix">
                        <h4 class="footer-title">Services</h4>
                        <div class="footer-menu">
                            <ul>
                                <li><a href="{{ route('shop.index') }}">Boutique</a></li>
                                <li><a href="{{ route('cart.index') }}">Mon Panier</a></li>
                                <li><a href="{{ route('login') }}">Se connecter</a></li>
                                <li><a href="{{ route('register') }}">S'inscrire</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Customer care --}}
                <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                    <div class="footer-widget footer-menu-widget clearfix">
                        <h4 class="footer-title">Assistance</h4>
                        <div class="footer-menu">
                            <ul>
                                <li><a href="{{ route('contact') }}">Nous contacter</a></li>
                                <li><a href="#">Politique de retour</a></li>
                                <li><a href="#">Conditions générales</a></li>
                                <li><a href="#">Politique de confidentialité</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Newsletter --}}
                <div class="col-xl-3 col-md-6 col-sm-12 col-12">
                    <div class="footer-widget footer-newsletter-widget">
                        <h4 class="footer-title">Newsletter</h4>
                        <p>Abonnez-vous pour recevoir nos offres et actualités bio.</p>
                        <div class="footer-newsletter">
                            <form action="#" method="POST">
                                @csrf
                                <div class="input-item input-item-email ltn__custom-icon">
                                    <input type="email" name="email" placeholder="Votre email*">
                                </div>
                                <div class="btn-wrapper">
                                    <button class="theme-btn-1 btn" type="submit">
                                        <i class="fas fa-location-arrow"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <h5 class="mt-30">Paiements acceptés</h5>
                        <img src="{{ asset('assets/img/icons/payment-4.png') }}" alt="Paiement">
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- Footer top end --}}

    <div class="ltn__copyright-area ltn__copyright-2 section-bg-2 plr--5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="ltn__copyright-design clearfix">
                        <p>Tous droits réservés @ Multi-Vendor <span class="current-year">{{ date('Y') }}</span></p>
                    </div>
                </div>
                <div class="col-md-6 col-12 align-self-center">
                    <div class="ltn__copyright-menu text-end">
                        <ul>
                            <li><a href="#">Conditions Générales</a></li>
                            <li><a href="#">Confidentialité</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
