@extends('layouts.app')

@section('title', 'Transaction')
@section('breadcrumb', 'Transaction')

@section('content')

@section('header_actions')
  <div class="d-flex align-items-center gap-2">
      @foreach($vehicleTypes as $vt)
        @php $vtLabel = ['motorcycle'=>'MOTORCYCLE', 'car'=>'CAR', 'other'=>'OTHER']; @endphp
        <button type="button" class="btn btn-outline-dark btn-sm mb-0 px-4 shadow-sm btn-vt-select" data-id="{{ $vt->id }}" style="text-transform: uppercase;">
            {{ $vtLabel[$vt->jenis] ?? strtoupper($vt->jenis) }}
        </button>
      @endforeach
      <button type="button" id="btn-enter-vehicle" class="btn bg-gradient-primary btn-sm mb-0 px-4 rounded-pill shadow-sm ms-2" style="text-transform: uppercase;">
          + ENTER VEHICLE
      </button>
  </div>
@endsection


  <div class="row">
    {{-- LEFT COLUMN: Clock + Locations + Form --}}
    <div class="col-lg-8">
      
      {{-- CARDS ROW --}}
      <div class="row">
        {{-- CLOCK CARD --}}
        <div class="col-md-4 mb-4">
          <div class="card bg-gradient-dark h-100 shadow text-center p-4 border-radius-xl">
            <div class="card-body d-flex flex-column justify-content-center align-items-center p-0">
              <i class="fas fa-building text-white opacity-8 mb-3" style="font-size: 2.5rem;"></i>
              <h5 class="text-white mb-1 font-weight-bolder" id="clock-day">Monday</h5>
              <p class="text-white text-xs mb-4 opacity-8" id="clock-date">8 December 2025</p>
              <h4 class="text-white font-weight-bolder mb-0" id="clock-time">10 : 30 : 03</h4>
            </div>
          </div>
        </div>

        {{-- LOCATION CARDS --}}
        @foreach($locations as $loc)
          @php
            $active_motor = $loc->transactions()->whereNull('keluar')->whereHas('vehicleType', fn($q) => $q->where('jenis', 'motorcycle'))->count();
            $active_car   = $loc->transactions()->whereNull('keluar')->whereHas('vehicleType', fn($q) => $q->where('jenis', 'car'))->count();
            $active_other = $loc->transactions()->whereNull('keluar')->whereHas('vehicleType', fn($q) => $q->where('jenis', 'other'))->count();

            $avail_motor = $loc->max_motorcycle;
            $avail_car   = $loc->max_car;
            $avail_other = $loc->max_other;

            $total_motor = $avail_motor + $active_motor;
            $total_car   = $avail_car + $active_car;
            $total_other = $avail_other + $active_other;
          @endphp
          <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-radius-xl card-location-select" data-id="{{ $loc->id }}" style="cursor: pointer; transition: 0.3s;">
              <div class="card-body text-center p-3 d-flex flex-column justify-content-center">
                <div class="icon icon-shape bg-gradient-primary shadow mx-auto mb-3 border-radius-md d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                  <i class="fas fa-landmark text-white text-lg"></i>
                </div>
                <h6 class="mb-3 font-weight-bolder text-dark">{{ $loc->location_name }}</h6>
                
                {{-- Total Capacity Row --}}
                <div class="d-flex justify-content-center align-items-center gap-3 mb-2 text-secondary text-xs font-weight-bold">
                  <span><i class="fas fa-motorcycle text-dark opacity-6 me-1"></i> {{ $total_motor }}</span>
                  <span><i class="fas fa-car text-dark opacity-6 me-1"></i> {{ $total_car }}</span>
                  <span><i class="fas fa-truck text-dark opacity-6 me-1"></i> {{ $total_other }}</span>
                </div>

                {{-- Available Slots Row --}}
                <div class="d-flex justify-content-center align-items-center gap-3 text-xs font-weight-bolder">
                  <span class="{{ $avail_motor > 0 ? 'text-success' : 'text-danger' }}">
                    <i class="fas fa-motorcycle me-1"></i> {{ $avail_motor }}
                  </span>
                  <span class="{{ $avail_car > 0 ? 'text-success' : 'text-danger' }}">
                    <i class="fas fa-car me-1"></i> {{ $avail_car }}
                  </span>
                  <span class="{{ $avail_other > 0 ? 'text-success' : 'text-danger' }}">
                    <i class="fas fa-truck me-1"></i> {{ $avail_other }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- TRANSACTION INPUT FORM (EXIT) --}}
      <div class="card shadow-sm border-radius-xl mt-2 mb-4">
        <div class="card-header pb-0 bg-transparent border-0 d-flex justify-content-between align-items-center">
          <h5 class="mb-0 font-weight-bolder" style="font-size: 1.1rem;">
            <span class="text-primary">Transaction</span>
            <span class="text-secondary font-weight-normal ms-1">Input Form</span>
          </h5>
          <button type="submit" form="form-exit" class="btn bg-gradient-dark btn-sm mb-0 rounded-pill px-4 shadow-sm" style="text-transform: uppercase;">
            + Exit Vehicle
          </button>
        </div>
        <div class="card-body">
          <form id="form-exit" action="{{ route('parking.exit') }}" method="POST">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-control-label font-weight-bold" style="color: #e91e63;">Ticket Number</label>
                  <input type="text" id="exit_no_tiket" name="no_tiket" class="form-control border px-3" style="border-color: #e91e63 !important;" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label class="form-control-label font-weight-bold">Police Number</label>
                  <input type="text" id="exit_no_polisi" name="no_polisi" class="form-control border px-3" placeholder="Optional">
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>

    {{-- RIGHT COLUMN: Tickets --}}
    <div class="col-lg-4 mb-4">
      <div class="card h-100 shadow-sm border-radius-xl">
        <div class="card-header pb-0 bg-transparent border-0 d-flex justify-content-between align-items-center">
          <h6 class="mb-0 text-dark font-weight-bolder">Tickets</h6>
          <button type="button" class="btn btn-outline-primary btn-sm mb-0 px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#allTransactionsModal" style="border-color: #e91e63; color: #e91e63;">VIEW ALL</button>
        </div>
        <div class="card-body p-3 overflow-auto" style="max-height: 500px;">
          <ul class="list-group">
            @forelse($transactions->whereNull('keluar')->take(8) as $trx)
            <li class="list-group-item border-0 d-flex justify-content-between ps-2 mb-2 border-radius-lg ticket-item" 
                style="background-color: #f8f9fa; cursor: pointer;"
                data-tiket="{{ $trx->no_tiket }}" 
                data-polisi="{{ $trx->no_polisi ?: '-' }}">
              <div class="d-flex flex-column">
                <h6 class="mb-1 text-dark text-sm font-weight-bolder">{{ $trx->no_tiket }}</h6>
                <span class="text-xs font-weight-bold text-secondary">{{ $trx->no_polisi }} | {{ $trx->location->location_name ?? '' }}</span>
              </div>
              <div class="d-flex align-items-center">
                <div class="d-flex flex-column text-end me-3">
                  <span class="text-xs font-weight-bold text-primary">{{ $trx->masuk->format('H:i:s') }}</span>
                </div>
                <a href="{{ route('parking.ticket.pdf', $trx->id) }}" target="_blank" class="btn btn-link text-dark p-0 m-0" title="Cetak Tiket" onclick="event.stopPropagation();">
                  <i class="fas fa-file-pdf text-lg text-danger"></i> PDF
                </a>
              </div>
            </li>
            @empty
              <li class="list-group-item border-0 text-center text-sm text-secondary py-4">
                No active tickets right now.
              </li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>

  {{-- HIDDEN FORM FOR ENTER VEHICLE --}}
  <form id="form-enter" action="{{ route('parking.enter') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="id_jenis" id="input_id_jenis">
    <input type="hidden" name="id_lokasi" id="input_id_lokasi">
    <input type="hidden" name="no_polisi" id="input_no_polisi" value="-">
  </form>

  {{-- MODAL: ALL TRANSACTIONS --}}
  <div class="modal fade" id="allTransactionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content border-radius-xl">
        <div class="modal-header">
          <h5 class="modal-title font-weight-bolder text-primary">All Transactions</h5>
          <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-0">
          <div class="table-responsive">
            <table class="table align-items-center mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">NO.</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TICKET NUMBER</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">POLICE NUMBER</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">LOCATION NAME</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">VEHICLE TYPE</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TIME IN</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">TIME OUT</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">FIRST HOUR CHARGES</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">NEXT HOURLY CHARGES</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">MAX COST PER DAY</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">TOTAL HOURS</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">TOTAL DAYS</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">TOTAL PAYS</th>
                </tr>
              </thead>
              <tbody>
                @foreach($transactions as $trx)
                <tr>
                  <td class="text-center text-sm font-weight-bold">{{ $loop->iteration }}</td>
                  <td class="text-sm font-weight-bold">
                    <a href="{{ route('parking.ticket.pdf', $trx->id) }}" target="_blank" class="text-danger me-1" title="View PDF"><i class="fas fa-file-pdf"></i> PDF</a>
                    {{ $trx->no_tiket }}
                  </td>
                  <td class="text-sm font-weight-bold">{{ $trx->no_polisi ?: '-' }}</td>
                  <td class="text-sm font-weight-bold">{{ $trx->location->location_name ?? '' }}</td>
                  <td class="text-sm font-weight-bold">{{ $trx->vehicleType->jenis ?? '' }}</td>
                  <td class="text-sm font-weight-bold">{{ $trx->masuk->format('Y-m-d H:i:s') }}</td>
                  <td class="text-sm font-weight-bold">{{ $trx->keluar ? $trx->keluar->format('Y-m-d H:i:s') : '-' }}</td>
                  <td class="text-center text-sm font-weight-bold">Rp {{ number_format($trx->perjam_pertama, 0, ',', '.') }}</td>
                  <td class="text-center text-sm font-weight-bold">Rp {{ number_format($trx->perjam_berikutnya, 0, ',', '.') }}</td>
                  <td class="text-center text-sm font-weight-bold">Rp {{ number_format($trx->max_perhari, 0, ',', '.') }}</td>
                  <td class="text-center text-sm font-weight-bold">{{ $trx->total_jam ?? 0 }}</td>
                  <td class="text-center text-sm font-weight-bold">{{ $trx->total_jam ? floor($trx->total_jam / 24) : 0 }}</td>
                  <td class="text-center text-sm font-weight-bold">{{ $trx->total_bayar ? 'Rp ' . number_format($trx->total_bayar, 0, ',', '.') : '-' }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn bg-gradient-dark mb-0" data-bs-dismiss="modal">CLOSE</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>

  function updateClock() {
    const now = new Date();
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    
    document.getElementById('clock-day').innerText = days[now.getDay()];
    document.getElementById('clock-date').innerText = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
    
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('clock-time').innerText = h + ' : ' + m + ' : ' + s;
  }
  
  setInterval(updateClock, 1000);
  updateClock();

  // UI State selection
  let selectedVtId = null;
  let selectedLocId = null;

  const btnVts = document.querySelectorAll('.btn-vt-select');
  btnVts.forEach(btn => {
    btn.addEventListener('click', function() {
      btnVts.forEach(b => {
        b.classList.remove('bg-gradient-dark');
        b.classList.remove('text-white');
        b.classList.add('btn-outline-dark');
      });
      this.classList.remove('btn-outline-dark');
      this.classList.add('bg-gradient-dark');
      this.classList.add('text-white');
      selectedVtId = this.getAttribute('data-id');
    });
  });

  const cardLocs = document.querySelectorAll('.card-location-select');
  cardLocs.forEach(card => {
    card.addEventListener('click', function() {
      cardLocs.forEach(c => {
        c.style.border = 'none';
        c.style.boxShadow = '';
      });
      this.style.border = '2px solid #e91e63';
      this.style.boxShadow = '0 4px 6px rgba(233, 30, 99, 0.3)';
      selectedLocId = this.getAttribute('data-id');
    });
  });

  // Handle Enter Vehicle
  document.getElementById('btn-enter-vehicle').addEventListener('click', function() {
    if (!selectedVtId || !selectedLocId) {
      showSwal({ title: 'Perhatian!', text: 'Silakan pilih Jenis Kendaraan dan Lokasi Parkir terlebih dahulu!', icon: 'warning' });
      return;
    }
    
    document.getElementById('input_id_jenis').value = selectedVtId;
    document.getElementById('input_id_lokasi').value = selectedLocId;
    document.getElementById('form-enter').submit();
  });

  // Ticket Click handler
  const ticketItems = document.querySelectorAll('.ticket-item');
  ticketItems.forEach(item => {
    item.addEventListener('click', function() {
      const ticketNo = this.getAttribute('data-tiket');
      const polisiNo = this.getAttribute('data-polisi');
      document.getElementById('exit_no_tiket').value = ticketNo;
      document.getElementById('exit_no_polisi').value = polisiNo !== '-' ? polisiNo : '';
      
      const inputTiket = document.getElementById('exit_no_tiket');
      inputTiket.style.backgroundColor = '#fdecf1';
      setTimeout(() => inputTiket.style.backgroundColor = '', 500);
    });
  });

  @if(session('masuk_success'))
      showSwal({ title: 'Success!', text: 'Kendaraan berhasil masuk.', icon: 'success' });
  @endif

  @if(session('error'))
      showSwal({ title: 'Error!', text: '{{ session("error") }}', icon: 'error' });
  @endif

  @if(session('keluar_success'))
      showSwal({ 
          title: 'Transaction Success', 
          html: '<div style="font-size:14px;text-align:center;">Total Bayar : <b>Rp {{ number_format(session("keluar_success")->total_bayar, 0, ",", ".") }}</b><br>Durasi: {{ session("keluar_success")->total_jam }} Menit</div>', 
          icon: 'success', 
          confirmText: 'Selesai' 
      });
  @endif
</script>
@endpush