@extends('layouts.app')

@section('title', isset($location) ? 'Edit Lokasi Parkir' : 'Tambah Lokasi Parkir')
@section('breadcrumb', isset($location) ? 'Edit Lokasi' : 'Tambah Lokasi')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex align-items-center">
          <h6 class="mb-0">{{ isset($location) ? 'Edit Lokasi Parkir' : 'Tambah Lokasi Parkir' }}</h6>
          <a href="{{ route('locations.index') }}" class="btn btn-sm btn-outline-secondary ms-auto mb-0">
            Kembali
          </a>
        </div>
      </div>
      
      <div class="card-body">
        <form action="{{ isset($location) ? route('locations.update', $location->id) : route('locations.store') }}" method="POST">
          @csrf
          @if(isset($location))
            @method('PUT')
          @endif
          
          <div class="form-group mb-3">
            <label class="form-control-label font-weight-bold">Nama Lokasi</label>
            <input type="text" name="location_name" class="form-control border px-3"
                   value="{{ old('location_name', $location->location_name ?? '') }}" required>
          </div>
          @error('location_name')
            <p class="text-danger text-xs mt-n2 mb-2 ms-1">{{ $message }}</p>
          @enderror
          
          <div class="row">
            <div class="col-md-4">
              <div class="form-group mb-3">
                <label class="form-control-label font-weight-bold">Slot Motor</label>
                <input type="number" name="max_motorcycle" class="form-control border px-3" 
                       value="{{ old('max_motorcycle', $location->max_motorcycle ?? 0) }}" min="0" required>
              </div>
              @error('max_motorcycle')
                <p class="text-danger text-xs mt-n2 mb-2 ms-1">{{ $message }}</p>
              @enderror
            </div>
            
            <div class="col-md-4">
              <div class="form-group mb-3">
                <label class="form-control-label font-weight-bold">Slot Mobil</label>
                <input type="number" name="max_car" class="form-control border px-3" 
                       value="{{ old('max_car', $location->max_car ?? 0) }}" min="0" required>
              </div>
              @error('max_car')
                <p class="text-danger text-xs mt-n2 mb-2 ms-1">{{ $message }}</p>
              @enderror
            </div>
            
            <div class="col-md-4">
              <div class="form-group mb-3">
                <label class="form-control-label font-weight-bold">Slot Lainnya</label>
                <input type="number" name="max_other" class="form-control border px-3" 
                       value="{{ old('max_other', $location->max_other ?? 0) }}" min="0" required>
              </div>
              @error('max_other')
                <p class="text-danger text-xs mt-n2 mb-2 ms-1">{{ $message }}</p>
              @enderror
            </div>
          </div>
          
          <div class="mt-4">
            <button type="submit" class="btn bg-gradient-primary">Simpan Data</button>
            <a href="{{ route('locations.index') }}" class="btn btn-outline-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
