@extends('layouts.app')

@section('title', 'Jenis Kendaraan')
@section('breadcrumb', 'Jenis Kendaraan')

@section('content')

  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <div class="d-flex align-items-center">
            <div>
              <h6 class="mb-0"><span class="text-primary">Vehicle Type</span> Data Table</h6>
            </div>
            <a href="{{ route('vehicle-types.create') }}" class="btn btn-sm bg-gradient-primary ms-auto mb-0" style="text-transform: uppercase;">
              <i class="fas fa-plus me-1"></i> ADD NEW VEHICLE TYPE
            </a>
          </div>
        </div>

        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 5%">NO.</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">VEHICLE TYPE</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">FIRST HOUR CHARGES</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NEXT HOURLY CHARGES</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">MAX COST PER DAY</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($vehicleTypes as $vt)
                  @php
                    $jenisLabel = ['motorcycle' => 'Motor', 'car' => 'Mobil', 'other' => 'Lainnya'];
                    $jenisColor = ['motorcycle' => 'bg-gradient-warning', 'car' => 'bg-gradient-info', 'other' => 'bg-gradient-secondary'];
                    $jenisIcon  = ['motorcycle' => 'ni-delivery-fast', 'car' => 'ni-bus-front-12', 'other' => 'ni-box-2'];
                  @endphp
                  <tr>
                    <td>
                      <p class="text-xs font-weight-bold mb-0 ms-3">{{ $loop->iteration }}</p>
                    </td>
                    <td>
                      <p class="text-sm font-weight-bold mb-0 ms-3">{{ $jenisLabel[$vt->jenis] ?? ucfirst($vt->jenis) }}</p>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-xs font-weight-bold text-secondary">
                        Rp {{ number_format($vt->perjam_pertama, 0, ',', '.') }}
                      </span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-xs font-weight-bold text-secondary">
                        Rp {{ number_format($vt->perjam_berikutnya, 0, ',', '.') }}
                      </span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-xs font-weight-bold text-secondary">
                        Rp {{ number_format($vt->max_perhari, 0, ',', '.') }}
                      </span>
                    </td>
                    <td class="align-middle">
                      <a href="{{ route('vehicle-types.edit', $vt->id) }}" class="text-secondary font-weight-bold text-xs me-3">
                        Edit
                      </a>
                      <form action="{{ route('vehicle-types.destroy', $vt->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Hapus jenis kendaraan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger font-weight-bold text-xs p-0 m-0">
                          Hapus
                        </button>
                      </form>
                    </td>
                  </tr>



                @empty
                  <tr>
                    <td colspan="5" class="text-center py-5 text-secondary">
                      <i class="ni ni-delivery-fast fa-3x mb-3 d-block opacity-3" style="font-size:3rem;"></i>
                      <h6 class="text-secondary">Belum ada data jenis kendaraan</h6>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>



@endsection
