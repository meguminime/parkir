@extends('layouts.app')

@section('title', 'Lokasi Parkir')
@section('breadcrumb', 'Lokasi Parkir')

@section('content')

  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <div class="d-flex align-items-center">
            <div>
              <h6 class="mb-0"><span class="text-primary">Location</span> Data Table</h6>
            </div>
            <a href="{{ route('locations.create') }}" class="btn btn-sm bg-gradient-primary ms-auto mb-0" style="text-transform: uppercase;">
              <i class="fas fa-plus me-1"></i> ADD NEW LOCATION
            </a>
          </div>
        </div>

        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 5%">NO.</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">LOCATION NAME</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">MAX MOTORCYCLE</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">MAX CAR</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">MAX TRUCK/BUS/OTHER</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                @forelse($locations as $loc)
                  <tr>
                    <td>
                      <p class="text-xs font-weight-bold mb-0 ms-3">{{ $loop->iteration }}</p>
                    </td>
                    <td>
                      <p class="text-sm font-weight-bold mb-0 ms-3">{{ $loc->location_name }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="text-xs font-weight-bold text-secondary">
                        {{ $loc->max_motorcycle }}
                      </span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="text-xs font-weight-bold text-secondary">
                        {{ $loc->max_car }}
                      </span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="text-xs font-weight-bold text-secondary">
                        {{ $loc->max_other }}
                      </span>
                    </td>
                    <td class="align-middle">
                      <a href="{{ route('locations.edit', $loc->id) }}" class="text-secondary font-weight-bold text-xs me-3">
                        Edit
                      </a>
                      <form action="{{ route('locations.destroy', $loc->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Hapus lokasi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger font-weight-bold text-xs p-0 m-0">
                          Hapus
                        </button>
                      </form>
                    </td>
                  </tr>



                @empty
                  <tr>
                    <td colspan="6" class="text-center py-5 text-secondary">
                      <i class="fas fa-map-marker-alt fa-3x mb-3 opacity-3"></i>
                      <h6 class="text-secondary">Belum ada data lokasi parkir</h6>
                      <p class="text-xs">Klik tombol "Tambah Lokasi" untuk menambahkan.</p>
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