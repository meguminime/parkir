<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
  {{-- Header / Logo --}}
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
       aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="{{ route('transactions.index') }}">
      <img src="{{ asset('parkir.png') }}" class="navbar-brand-img h-100" alt="SIJA Parking Logo">
      <span class="ms-1 font-weight-bold">SIJA PARKING</span>
    </a>
  </div>

  <hr class="horizontal dark mt-0">

  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">

      {{-- Location --}}
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}"
           href="{{ route('locations.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-map-marker-alt text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Location</span>
        </a>
      </li>

      {{-- Transaction --}}
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('transactions.*') || request()->routeIs('parking.*') ? 'active' : '' }}"
           href="{{ route('transactions.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-exchange-alt text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Transaction</span>
        </a>
      </li>

      {{-- Vehicle Type --}}
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('vehicle-types.*') ? 'active' : '' }}"
           href="{{ route('vehicle-types.index') }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-car text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Vehicle Type</span>
        </a>
      </li>

      {{-- ── REPORTS ──────────────────────────────── --}}
      <li class="nav-item mt-3">
        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Reports</h6>
      </li>

      {{-- Location Report --}}
      <li class="nav-item">
        <a class="nav-link" href="#">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-file-alt text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Location Report</span>
        </a>
      </li>

      {{-- Transaction Report --}}
      <li class="nav-item">
        <a class="nav-link" href="#">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="fas fa-file-invoice-dollar text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Transaction Report</span>
        </a>
      </li>

    </ul>
  </div>


</aside>
