@extends('layouts.app')

@section('title', isset($vehicleType) ? 'Edit Jenis Kendaraan' : 'Tambah Jenis Kendaraan')
@section('breadcrumb', isset($vehicleType) ? 'Edit Jenis' : 'Tambah Jenis')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex align-items-center">
          <h6 class="mb-0">{{ isset($vehicleType) ? 'Edit Tarif — ' . ucfirst($vehicleType->jenis) : 'Tambah Jenis Kendaraan' }}</h6>
          <a href="{{ route('vehicle-types.index') }}" class="btn btn-sm btn-outline-secondary ms-auto mb-0">
            Kembali
          </a>
        </div>
      </div>
      
      <div class="card-body">
        <form action="{{ isset($vehicleType) ? route('vehicle-types.update', $vehicleType->id) : route('vehicle-types.store') }}" method="POST">
          @csrf
          @if(isset($vehicleType))
            @method('PUT')
          @endif
          
          <div class="row">
            @if(isset($vehicleType))
              <input type="hidden" name="jenis" value="{{ $vehicleType->jenis }}">
            @else
              <div class="col-md-12 mb-3">
                <div class="form-group mb-3">
                  <label class="form-control-label font-weight-bold">Jenis Kendaraan</label>
                  <select name="jenis" class="form-control border px-3" required>
                    <option value="">-- Pilih Jenis --</option>
                    @php
                      $allJenis = ['motorcycle' => 'Motor', 'car' => 'Mobil', 'other' => 'Lainnya'];
                    @endphp
                    @foreach($allJenis as $key => $label)
                      <option value="{{ $key }}" {{ old('jenis') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                  </select>
                </div>
                @error('jenis')
                  <p class="text-danger text-xs mt-1 mb-0">{{ $message }}</p>
                @enderror
              </div>
            @endif

            <div class="col-md-4 mb-3">
              <div class="form-group mb-3">
                <label class="form-control-label font-weight-bold">Jam Pertama (Rp)</label>
                <input type="number" name="perjam_pertama" class="form-control border px-3" 
                       value="{{ old('perjam_pertama', $vehicleType->perjam_pertama ?? 0) }}" min="0" required>
              </div>
              @error('perjam_pertama')
                <p class="text-danger text-xs mt-1 mb-0">{{ $message }}</p>
              @enderror
            </div>

            <div class="col-md-4 mb-3">
              <div class="form-group mb-3">
                <label class="form-control-label font-weight-bold">Jam Berikutnya (Rp)</label>
                <input type="number" name="perjam_berikutnya" class="form-control border px-3" 
                       value="{{ old('perjam_berikutnya', $vehicleType->perjam_berikutnya ?? 0) }}" min="0" required>
              </div>
              @error('perjam_berikutnya')
                <p class="text-danger text-xs mt-1 mb-0">{{ $message }}</p>
              @enderror
            </div>

            <div class="col-md-4 mb-3">
              <div class="form-group mb-3">
                <label class="form-control-label font-weight-bold">Maksimal per Hari (Rp)</label>
                <input type="number" name="max_perhari" class="form-control border px-3" 
                       value="{{ old('max_perhari', $vehicleType->max_perhari ?? 0) }}" min="0" required>
              </div>
              @error('max_perhari')
                <p class="text-danger text-xs mt-1 mb-0">{{ $message }}</p>
              @enderror
            </div>
          </div>
          
          <div class="mt-4">
            <button type="submit" class="btn bg-gradient-primary">Simpan Data</button>
            <a href="{{ route('vehicle-types.index') }}" class="btn btn-outline-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
