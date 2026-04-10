<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="/adminPanel/assets/images/logos/favicon.png">

  <!-- Core Css -->
  <link rel="stylesheet" href="/adminPanel/assets/css/styles.css">

  <title>@yield('title', 'Admin Dashboard') - EduRéussite</title>

  <style>
    .preloader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      opacity: 1;
      transition: opacity 0.3s ease-in-out;
    }
    
    .preloader.hidden {
      opacity: 0;
      pointer-events: none;
    }

    /* Submenu Styling */
    .sidebar-submenu {
      display: none;
      flex-direction: column;
      padding-left: 1rem;
      background-color: rgba(0, 0, 0, 0.02);
      border-left: 2px solid var(--bs-primary);
    }

    .sidebar-submenu.show {
      display: flex;
    }

    .sidebar-submenu li {
      list-style: none;
    }

    .sidebar-submenu .sidebar-link {
      padding: 0.65rem 1rem;
      font-size: 0.875rem;
      color: inherit;
      text-decoration: none;
      display: block;
      position: relative;
    }

    .sidebar-submenu .sidebar-link:hover {
      background-color: rgba(0, 0, 0, 0.05);
    }

    .sidebar-submenu .sidebar-link.active {
      color: var(--bs-primary);
      font-weight: 500;
      background-color: rgba(0, 0, 0, 0.08);
    }

    .sidebar-submenu .sidebar-link.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 3px;
      height: 3px;
      background-color: var(--bs-primary);
      border-radius: 50%;
    }

    /* Chevron rotation animation */
    .sidebar-link .ms-auto {
      transition: transform 0.3s ease;
    }

    .sidebar-link[aria-expanded="true"] .ms-auto {
      transform: rotate(90deg);
    }

    /* ── Force sidebar always visible ── */
    .side-mini-panel .sidebarmenu {
      display: block !important;
    }

    .side-mini-panel .sidebarmenu .sidebar-nav {
      display: block !important;
      position: fixed;
      left: 80px;
      top: 0;
      height: 100vh;
      width: 240px;
      overflow-y: auto;
      z-index: 98;
      background: var(--bs-white);
      padding: 0 20px 24px 20px;
      box-shadow: 2px 0 8px rgba(0,0,0,0.06);
    }

    /* Push page content to the right of both bars */
    .page-wrapper {
      margin-left: 320px !important; /* 80px iconbar + 240px sidebar */
    }
  </style>

  @stack('styles')
</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="/adminPanel/assets/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid">
  </div>

  <div id="main-wrapper">
    <!-- Sidebar -->
    @include('admin.components.sidebar')

    <!-- Page Wrapper Start -->
    <div class="page-wrapper">
      <!-- Header -->
      @include('admin.components.header')

      <!-- Main Content -->
      <div class="body-wrapper">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
    </div>
    <!-- Page Wrapper End -->
  </div>

  <!-- Scripts -->
  <script src="/adminPanel/assets/js/vendor.min.js"></script>
  <script src="/adminPanel/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/adminPanel/assets/js/theme/app.init.js"></script>
  <script src="/adminPanel/assets/js/theme/app.min.js"></script>
  <script src="/adminPanel/assets/js/theme/sidebarmenu.js"></script>
  <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>

  <script>
    // Hide preloader when page loads
    document.addEventListener('DOMContentLoaded', function() {
      const preloader = document.querySelector('.preloader');
      if (preloader) {
        setTimeout(function() {
          preloader.classList.add('hidden');
        }, 300);
      }

      // Handle submenu collapse/expand
      const submenuLinks = document.querySelectorAll('.sidebar-link[data-bs-toggle="collapse"]');
      submenuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          const isExpanded = this.getAttribute('aria-expanded') === 'true';
          this.setAttribute('aria-expanded', !isExpanded);
        });
      });
    });
    
    // Also hide on window load as backup
    window.addEventListener('load', function() {
      const preloader = document.querySelector('.preloader');
      if (preloader) {
        preloader.classList.add('hidden');
      }
    });
  </script>

  @stack('scripts')
</body>

</html>
