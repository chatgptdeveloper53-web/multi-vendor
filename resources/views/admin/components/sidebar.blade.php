<aside class="side-mini-panel with-vertical">
  <div class="iconbar">
    <div>

      {{-- ======================================================
           MINI ICON BAR (collapsed state tooltips)
      ====================================================== --}}
      <div class="mini-nav">
        <div class="brand-logo d-flex align-items-center justify-content-center">
          <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
            <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-7"></iconify-icon>
          </a>
        </div>

        <ul class="mini-nav-ul" data-simplebar="">
          {{-- Dashboard --}}
          <li class="mini-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
               data-bs-placement="right" data-bs-title="Tableau de bord">
              <iconify-icon icon="solar:layers-line-duotone" class="fs-7"></iconify-icon>
            </a>
          </li>

          <li><span class="sidebar-divider"></span></li>

          {{-- Utilisateurs --}}
          <li class="mini-nav-item {{ request()->routeIs('admin.users.*', 'admin.acheteurs.*') ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
               data-bs-placement="right" data-bs-title="Utilisateurs">
              <iconify-icon icon="solar:users-group-rounded-line-duotone" class="fs-7"></iconify-icon>
            </a>
          </li>

          {{-- Vendeurs --}}
          <li class="mini-nav-item {{ request()->routeIs('admin.vendeurs.*', 'admin.documents.*') ? 'active' : '' }}">
            <a href="{{ route('admin.vendeurs.index') }}" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
               data-bs-placement="right" data-bs-title="Vendeurs">
              <iconify-icon icon="solar:shop-line-duotone" class="fs-7"></iconify-icon>
            </a>
          </li>

          {{-- Produits --}}
          <li class="mini-nav-item {{ request()->routeIs('admin.produits.*', 'admin.catalogues.*', 'admin.photos.*') ? 'active' : '' }}">
            <a href="{{ route('admin.produits.index') }}" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
               data-bs-placement="right" data-bs-title="Produits">
              <iconify-icon icon="solar:bag-5-line-duotone" class="fs-7"></iconify-icon>
            </a>
          </li>

          {{-- Commandes --}}
          <li class="mini-nav-item {{ request()->routeIs('admin.commandes.*', 'admin.logistiques.*') ? 'active' : '' }}">
            <a href="{{ route('admin.commandes.index') }}" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
               data-bs-placement="right" data-bs-title="Commandes">
              <iconify-icon icon="solar:cart-large-minimalistic-line-duotone" class="fs-7"></iconify-icon>
            </a>
          </li>

          {{-- Notifications --}}
          <li class="mini-nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
            <a href="{{ route('admin.notifications.index') }}" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
               data-bs-placement="right" data-bs-title="Notifications">
              <iconify-icon icon="solar:bell-line-duotone" class="fs-7"></iconify-icon>
            </a>
          </li>
        </ul>
      </div>

      {{-- ======================================================
           FULL SIDEBAR MENU (expanded state)
      ====================================================== --}}
      <div class="sidebarmenu">
        <div class="brand-logo d-flex align-items-center nav-logo">
          <a href="{{ route('admin.dashboard') }}" class="text-nowrap logo-img">
            <img src="/adminPanel/assets/images/logos/logo.svg" class="dark-logo" alt="Logo">
            <img src="/adminPanel/assets/images/logos/light-logo.svg" class="light-logo" alt="Logo">
          </a>
        </div>

        <nav class="sidebar-nav" data-simplebar="">
          <ul class="sidebar-menu" id="sidebarnav">

            {{-- ────────────────────────────────────────────────
                 SECTION : PRINCIPAL
            ──────────────────────────────────────────────── --}}
            <li class="nav-small-cap">
              <iconify-icon icon="solar:minus-circle-line-duotone" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Principal</span>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                 href="{{ route('admin.dashboard') }}" aria-expanded="false">
                <iconify-icon icon="solar:layers-line-duotone"></iconify-icon>
                <span class="hide-menu">Tableau de bord</span>
              </a>
            </li>

            <li><span class="sidebar-divider"></span></li>

            {{-- ────────────────────────────────────────────────
                 SECTION : GESTION DES UTILISATEURS
            ──────────────────────────────────────────────── --}}
            <li class="nav-small-cap">
              <iconify-icon icon="solar:minus-circle-line-duotone" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Utilisateurs</span>
            </li>

            {{-- Tous les utilisateurs / Acheteurs --}}
            @php
              $usersActive = request()->routeIs('admin.users.*', 'admin.acheteurs.*');
            @endphp
            <li class="sidebar-item">
              <a class="sidebar-link {{ $usersActive ? 'active' : '' }}"
                 href="javascript:void(0)"
                 data-bs-toggle="collapse" data-bs-target="#usersMenu"
                 aria-expanded="{{ $usersActive ? 'true' : 'false' }}">
                <iconify-icon icon="solar:users-group-rounded-line-duotone"></iconify-icon>
                <span class="hide-menu">Utilisateurs</span>
                <iconify-icon icon="solar:chevron-right-line-duotone" class="ms-auto"></iconify-icon>
              </a>
              <ul class="sidebar-submenu collapse {{ $usersActive ? 'show' : '' }}" id="usersMenu">
                <li>
                  <a href="{{ route('admin.users.index') }}"
                     class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <iconify-icon icon="solar:user-line-duotone" class="me-2"></iconify-icon>
                    Tous les utilisateurs
                  </a>
                </li>
                <li>
                  <a href="{{ route('admin.acheteurs.index') }}"
                     class="sidebar-link {{ request()->routeIs('admin.acheteurs.*') ? 'active' : '' }}">
                    <iconify-icon icon="solar:bag-heart-line-duotone" class="me-2"></iconify-icon>
                    Acheteurs
                  </a>
                </li>
              </ul>
            </li>

            {{-- Vendeurs & Onboarding --}}
            @php
              $vendeursActive = request()->routeIs('admin.vendeurs.*', 'admin.documents.*');
            @endphp
            <li class="sidebar-item">
              <a class="sidebar-link {{ $vendeursActive ? 'active' : '' }}"
                 href="javascript:void(0)"
                 data-bs-toggle="collapse" data-bs-target="#vendeursMenu"
                 aria-expanded="{{ $vendeursActive ? 'true' : 'false' }}">
                <iconify-icon icon="solar:shop-line-duotone"></iconify-icon>
                <span class="hide-menu">Vendeurs</span>
                @php
                  $pending = \App\Models\Vendeur::where('statut_onboarding', 'EN_ATTENTE')->count();
                @endphp
                @if($pending > 0)
                  <span class="badge bg-warning text-dark ms-auto">{{ $pending }}</span>
                @else
                  <iconify-icon icon="solar:chevron-right-line-duotone" class="ms-auto"></iconify-icon>
                @endif
              </a>
              <ul class="sidebar-submenu collapse {{ $vendeursActive ? 'show' : '' }}" id="vendeursMenu">
                <li>
                  <a href="{{ route('admin.vendeurs.index') }}"
                     class="sidebar-link {{ request()->routeIs('admin.vendeurs.index') ? 'active' : '' }}">
                    <iconify-icon icon="solar:shop-2-line-duotone" class="me-2"></iconify-icon>
                    Tous les vendeurs
                  </a>
                </li>
                <li>
                  <a href="{{ route('admin.vendeurs.pending') }}"
                     class="sidebar-link {{ request()->routeIs('admin.vendeurs.pending') ? 'active' : '' }}">
                    <iconify-icon icon="solar:clock-circle-line-duotone" class="me-2"></iconify-icon>
                    En attente de validation
                    @if($pending > 0)
                      <span class="badge bg-warning text-dark ms-auto">{{ $pending }}</span>
                    @endif
                  </a>
                </li>
                <li>
                  <a href="{{ route('admin.documents.index') }}"
                     class="sidebar-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                    <iconify-icon icon="solar:document-text-line-duotone" class="me-2"></iconify-icon>
                    Documents ({{ \App\Models\Document::count() }})
                  </a>
                </li>
              </ul>
            </li>

            <li><span class="sidebar-divider"></span></li>

            {{-- ────────────────────────────────────────────────
                 SECTION : CATALOGUE & PRODUITS
            ──────────────────────────────────────────────── --}}
            <li class="nav-small-cap">
              <iconify-icon icon="solar:minus-circle-line-duotone" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Catalogue</span>
            </li>

            @php
              $produitsActive = request()->routeIs('admin.produits.*', 'admin.catalogues.*', 'admin.photos.*');
            @endphp
            <li class="sidebar-item">
              <a class="sidebar-link {{ $produitsActive ? 'active' : '' }}"
                 href="javascript:void(0)"
                 data-bs-toggle="collapse" data-bs-target="#produitsMenu"
                 aria-expanded="{{ $produitsActive ? 'true' : 'false' }}">
                <iconify-icon icon="solar:bag-5-line-duotone"></iconify-icon>
                <span class="hide-menu">Produits</span>
                <iconify-icon icon="solar:chevron-right-line-duotone" class="ms-auto"></iconify-icon>
              </a>
              <ul class="sidebar-submenu collapse {{ $produitsActive ? 'show' : '' }}" id="produitsMenu">
                <li>
                  <a href="{{ route('admin.produits.index') }}"
                     class="sidebar-link {{ request()->routeIs('admin.produits.*') ? 'active' : '' }}">
                    <iconify-icon icon="solar:tag-line-duotone" class="me-2"></iconify-icon>
                    Tous les produits
                  </a>
                </li>
                <li>
                  <a href="{{ route('admin.catalogues.index') }}"
                     class="sidebar-link {{ request()->routeIs('admin.catalogues.*') ? 'active' : '' }}">
                    <iconify-icon icon="solar:book-2-line-duotone" class="me-2"></iconify-icon>
                    Catalogues
                  </a>
                </li>
                <li>
                  <a href="{{ route('admin.photos.index') }}"
                     class="sidebar-link {{ request()->routeIs('admin.photos.*') ? 'active' : '' }}">
                    <iconify-icon icon="solar:gallery-wide-line-duotone" class="me-2"></iconify-icon>
                    Photos
                  </a>
                </li>
              </ul>
            </li>

            <li><span class="sidebar-divider"></span></li>

            {{-- ────────────────────────────────────────────────
                 SECTION : COMMANDES & LOGISTIQUE
            ──────────────────────────────────────────────── --}}
            <li class="nav-small-cap">
              <iconify-icon icon="solar:minus-circle-line-duotone" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Commerce</span>
            </li>

            @php
              $commandesActive = request()->routeIs('admin.commandes.*', 'admin.logistiques.*');
            @endphp
            <li class="sidebar-item">
              <a class="sidebar-link {{ $commandesActive ? 'active' : '' }}"
                 href="javascript:void(0)"
                 data-bs-toggle="collapse" data-bs-target="#commandesMenu"
                 aria-expanded="{{ $commandesActive ? 'true' : 'false' }}">
                <iconify-icon icon="solar:cart-large-minimalistic-line-duotone"></iconify-icon>
                <span class="hide-menu">Commandes</span>
                @php
                  $enCours = \App\Models\Commande::where('statut', 'EN_COURS')->count();
                @endphp
                @if($enCours > 0)
                  <span class="badge bg-primary ms-auto">{{ $enCours }}</span>
                @else
                  <iconify-icon icon="solar:chevron-right-line-duotone" class="ms-auto"></iconify-icon>
                @endif
              </a>
              <ul class="sidebar-submenu collapse {{ $commandesActive ? 'show' : '' }}" id="commandesMenu">
                <li>
                  <a href="{{ route('admin.commandes.index') }}"
                     class="sidebar-link {{ request()->routeIs('admin.commandes.index') ? 'active' : '' }}">
                    <iconify-icon icon="solar:list-check-line-duotone" class="me-2"></iconify-icon>
                    Toutes les commandes
                  </a>
                </li>
                <li>
                  <a href="{{ route('admin.commandes.en-cours') }}"
                     class="sidebar-link {{ request()->routeIs('admin.commandes.en-cours') ? 'active' : '' }}">
                    <iconify-icon icon="solar:delivery-line-duotone" class="me-2"></iconify-icon>
                    En cours
                    @if($enCours > 0)
                      <span class="badge bg-primary ms-auto">{{ $enCours }}</span>
                    @endif
                  </a>
                </li>
                <li>
                  <a href="{{ route('admin.logistiques.index') }}"
                     class="sidebar-link {{ request()->routeIs('admin.logistiques.*') ? 'active' : '' }}">
                    <iconify-icon icon="solar:map-arrow-right-line-duotone" class="me-2"></iconify-icon>
                    Logistique & Zones
                  </a>
                </li>
              </ul>
            </li>

            {{-- Notifications --}}
            <li class="sidebar-item">
              <a class="sidebar-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}"
                 href="{{ route('admin.notifications.index') }}" aria-expanded="false">
                <iconify-icon icon="solar:bell-line-duotone"></iconify-icon>
                <span class="hide-menu">Notifications</span>
                @php
                  $unread = \App\Models\Notification::whereDate('date_envoi', today())->count();
                @endphp
                @if($unread > 0)
                  <span class="badge bg-danger ms-auto">{{ $unread }}</span>
                @endif
              </a>
            </li>

            <li><span class="sidebar-divider lg"></span></li>

            {{-- ────────────────────────────────────────────────
                 SECTION : COMPTE
            ──────────────────────────────────────────────── --}}
            <li class="nav-small-cap">
              <iconify-icon icon="solar:minus-circle-line-duotone" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Compte</span>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}"
                 href="{{ route('admin.settings') }}" aria-expanded="false">
                <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                <span class="hide-menu">Paramètres</span>
              </a>
            </li>

            <li class="sidebar-item">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link"
                        style="border:none;background:none;width:100%;text-align:left;
                               padding:0.75rem 1.5rem;display:flex;align-items:center;gap:1rem;cursor:pointer;">
                  <iconify-icon icon="solar:logout-2-line-duotone"></iconify-icon>
                  <span class="hide-menu">Déconnexion</span>
                </button>
              </form>
            </li>

          </ul>
        </nav>
      </div>
      {{-- sidebarmenu end --}}

    </div>
  </div>
</aside>
