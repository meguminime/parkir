<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('soft-ui/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('soft-ui/img/favicon.png') }}">

  <title>@yield('title', 'SIJA Parking') — SIJA Parking</title>

  {{-- Fonts --}}
  <link href="{{ asset('soft-ui/css/font.css') }}" rel="stylesheet" />
  {{-- Nucleo Icons --}}
  <link href="{{ asset('soft-ui/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('soft-ui/css/nucleo-svg.css') }}" rel="stylesheet" />
  {{-- Font Awesome --}}
  <script src="{{ asset('soft-ui/js/plugins/all.js') }}" crossorigin="anonymous"></script>
  {{-- Soft UI Dashboard CSS --}}
  <link id="pagestyle" href="{{ asset('soft-ui/css/soft-ui-dashboard.min.css') }}" rel="stylesheet" />

  @stack('styles')
</head>

<body class="g-sidenav-show bg-gray-100">

  {{-- ===================== SIDEBAR ===================== --}}
  @include('layouts.sidebar')

  {{-- ===================== MAIN CONTENT ===================== --}}
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

    {{-- ===================== NAVBAR ===================== --}}
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
              <a class="opacity-5 text-dark" href="{{ route('transactions.index') }}">SIJA Parking</a>
            </li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
              @yield('breadcrumb')
            </li>
          </ol>
          <h6 class="font-weight-bolder mb-0">@yield('breadcrumb')</h6>
        </nav>

        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center gap-2">
            @yield('header_actions')
          </div>
          <ul class="navbar-nav justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a href="#" class="nav-link text-body font-weight-bold px-0 d-flex align-items-center">
                <i class="fas fa-user me-2"></i>
                <span class="d-sm-inline d-none">Sign Out</span>
              </a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    {{-- ===================== END NAVBAR ===================== --}}

    {{-- ===================== PAGE CONTENT ===================== --}}
    <div class="container-fluid py-4">

      {{-- Flash Messages are now handled globally via Custom Popup --}}

      @yield('content')

      {{-- ===================== FOOTER ===================== --}}
      <footer class="footer py-4">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © {{ date('Y') }}
                <span class="font-weight-bold">SIJA Parking System</span>
                — Sistem Informasi Parkir
              </div>
            </div>
          </div>
        </div>
      </footer>
      {{-- ===================== END FOOTER ===================== --}}

    </div>
    {{-- ===================== END PAGE CONTENT ===================== --}}

  </main>

  {{-- ===================== SCRIPTS ===================== --}}
  {{-- Bootstrap --}}
  <script src="{{ asset('soft-ui/js/core/bootstrap.bundle.min.js') }}"></script>
  {{-- Perfect Scrollbar --}}
  <script src="{{ asset('soft-ui/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('soft-ui/js/plugins/smooth-scrollbar.min.js') }}"></script>
  {{-- Chart.js --}}
  <script src="{{ asset('soft-ui/js/plugins/chartjs.min.js') }}"></script>
  <script src="{{ asset('soft-ui/js/plugins/Chart.extension.js') }}"></script>
  {{-- Sweet Alert --}}
  <script src="{{ asset('soft-ui/js/plugins/sweetalert.js') }}"></script>
  {{-- Soft UI Dashboard JS --}}
  <script src="{{ asset('soft-ui/js/soft-ui-dashboard.min.js') }}"></script>

  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = { damping: '0.5' };
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>

  {{-- Global Custom Popup Overlay --}}
  <div id="customPopup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); z-index:99999; justify-content:center; align-items:center;">
    <div id="customPopupBox" style="background:#fff; border-radius:16px; padding:40px 50px; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.3); max-width:420px; width:90%; transform:scale(0); transition:transform 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
      <div id="customPopupIcon" style="width:60px; height:60px; border-radius:50%; margin:0 auto 20px; display:flex; align-items:center; justify-content:center; font-size:28px;"></div>
      <h4 id="customPopupTitle" style="margin:0 0 8px; font-weight:700; color:#344767; font-size:1.3rem;"></h4>
      <p id="customPopupText" style="margin:0 0 25px; color:#67748e; font-size:0.95rem;"></p>
      <button id="customPopupBtn" onclick="closePopup()" style="background:linear-gradient(310deg, #7928CA, #FF0080); color:#fff; border:none; padding:10px 40px; border-radius:8px; font-size:0.9rem; font-weight:600; cursor:pointer; transition:transform 0.2s; box-shadow:0 4px 12px rgba(121,40,202,0.4);">OK</button>
    </div>
  </div>

  <script>
    // Custom Popup functions (matching Swal API)
    function showSwal(options) {
      const popup = document.getElementById('customPopup');
      const box = document.getElementById('customPopupBox');
      const icon = document.getElementById('customPopupIcon');
      const titleEl = document.getElementById('customPopupTitle');
      const textEl = document.getElementById('customPopupText');
      const btnEl = document.getElementById('customPopupBtn');
      
      titleEl.innerText = options.title || '';
      
      if (options.html) {
        textEl.innerHTML = options.html;
      } else {
        textEl.innerText = options.text || '';
      }

      if (options.confirmText) {
        btnEl.innerText = options.confirmText;
      } else {
        btnEl.innerText = 'OK';
      }
      
      if (options.icon === 'success') {
        icon.innerHTML = '✓';
        icon.style.background = 'linear-gradient(310deg, #17ad37, #98ec2d)';
        icon.style.color = '#fff';
      } else if (options.icon === 'warning' || options.icon === 'error') {
        icon.innerHTML = '!';
        icon.style.background = 'linear-gradient(310deg, #f5365c, #f56036)';
        icon.style.color = '#fff';
      } else {
        icon.innerHTML = 'i';
        icon.style.background = 'linear-gradient(310deg, #2152ff, #21d4fd)';
        icon.style.color = '#fff';
      }
      
      popup.style.display = 'flex';
      // Trigger animation
      setTimeout(function() { box.style.transform = 'scale(1)'; }, 10);
    }
    
    function closePopup() {
      const popup = document.getElementById('customPopup');
      const box = document.getElementById('customPopupBox');
      box.style.transform = 'scale(0)';
      setTimeout(function() { popup.style.display = 'none'; }, 350);
    }

    // Handle global generic flashes automatically
    document.addEventListener('DOMContentLoaded', function() {
      @if(session('success'))
        showSwal({ title: 'Success!', text: '{{ session("success") }}', icon: 'success' });
      @endif
      @if(session('error'))
        showSwal({ title: 'Error!', text: '{{ session("error") }}', icon: 'error' });
      @endif
    });
  </script>

  @stack('scripts')
</body>

</html>