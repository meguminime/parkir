@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>Transaction Input Form</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('parking.enter') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="id_lokasi" class="form-control-label">Location</label>
                        <select class="form-control" name="id_lokasi" id="id_lokasi" required>
                            <option value="" disabled selected>-- Select Location --</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->location_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="id_jenis" class="form-control-label">Vehicle Type</label>
                        <select class="form-control" name="id_jenis" id="id_jenis" required>
                            <option value="" disabled selected>-- Select Vehicle Type --</option>
                            @foreach($vehicleTypes as $vt)
                                <option value="{{ $vt->id }}">{{ strtoupper($vt->jenis) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn bg-gradient-info mt-4 mb-0">ENTER VEHICLE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>Transaction Exit Form</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('parking.exit') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="no_tiket" class="form-control-label">Ticket Number</label>
                        <input class="form-control" type="text" name="no_tiket" id="no_tiket" placeholder="Scan or Enter Ticket Number" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="no_polisi" class="form-control-label">Police Number</label>
                        <input class="form-control" type="text" name="no_polisi" id="no_polisi" placeholder="e.g. F 1234 ABC" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn bg-gradient-danger mt-4 mb-0">EXIT VEHICLE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection