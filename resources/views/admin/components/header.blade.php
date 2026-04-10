<header class="topbar">
  <div class="with-vertical">
    <nav class="navbar navbar-expand-lg p-0">
      <ul class="navbar-nav">
        <li class="nav-item d-flex d-xl-none">
          <a class="nav-link nav-icon-hover-bg rounded-circle sidebartoggler" id="headerCollapse" href="javascript:void(0)">
            <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-6"></iconify-icon>
          </a>
        </li>
      </ul>

      <div class="d-block d-lg-none py-9 py-xl-0">
        <img src="/adminPanel/assets/images/logos/logo.svg" alt="logo">
      </div>

      <a class="navbar-toggler p-0 border-0 nav-icon-hover-bg rounded-circle" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <iconify-icon icon="solar:menu-dots-bold-duotone" class="fs-6"></iconify-icon>
      </a>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <div class="d-flex align-items-center justify-content-between">
          <ul class="navbar-nav flex-row mx-auto ms-lg-auto align-items-center justify-content-center">
            <li class="nav-item">
              <a class="nav-link moon dark-layout nav-icon-hover-bg rounded-circle" href="javascript:void(0)">
                <iconify-icon icon="solar:moon-line-duotone" class="moon fs-6"></iconify-icon>
              </a>
              <a class="nav-link sun light-layout nav-icon-hover-bg rounded-circle" href="javascript:void(0)" style="display: none">
                <iconify-icon icon="solar:sun-2-line-duotone" class="sun fs-6"></iconify-icon>
              </a>
            </li>

            <li class="nav-item dropdown nav-icon-hover-bg rounded-circle ms-3">
              <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <iconify-icon icon="solar:user-rounded-line-duotone" class="fs-6"></iconify-icon>
              </a>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="javascript:void(0)">
                  <h6 class="mb-1">{{ auth()->user()->nom }}</h6>
                  <small class="text-muted">{{ auth()->user()->email }}</small>
                </a>
                <hr class="my-2">
                <a class="dropdown-item" href="javascript:void(0)">
                  <iconify-icon icon="solar:settings-line-duotone" class="me-2"></iconify-icon>
                  Paramètres
                </a>
                <a class="dropdown-item" href="javascript:void(0)">
                  <iconify-icon icon="solar:user-rounded-outline" class="me-2"></iconify-icon>
                  Mon Profil
                </a>
                <hr class="my-2">
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                  @csrf
                  <button type="submit" class="dropdown-item w-100 text-start">
                    <iconify-icon icon="solar:logout-2-line-duotone" class="me-2"></iconify-icon>
                    Déconnexion
                  </button>
                </form>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
</header>
