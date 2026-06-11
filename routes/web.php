<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParkingController;

/*
|--------------------------------------------------------------------------
| Web Routes — SIJA PARKING
|--------------------------------------------------------------------------
*/

// ── REDIRECT ROOT & DASHBOARD → TRANSAKSI ────────────────
Route::get('/', fn() => redirect()->route('transactions.index'));
Route::get('/dashboard', fn() => redirect()->route('transactions.index'));

// ── TRANSAKSI ──────────────────────────────────────────────
Route::get('/transactions',            [ParkingController::class, 'transactionIndex'])->name('transactions.index');
Route::post('/parking/enter',          [ParkingController::class, 'enterVehicle'])->name('parking.enter');
Route::post('/parking/exit',           [ParkingController::class, 'exitVehicle'])->name('parking.exit');
Route::get('/parking/ticket/{id}',     [ParkingController::class, 'ticketPdf'])->name('parking.ticket.pdf');

// ── LOKASI PARKIR (CRUD) ───────────────────────────────────
Route::get('/locations',               [ParkingController::class, 'indexLocation'])->name('locations.index');
Route::get('/locations/create',        [ParkingController::class, 'createLocation'])->name('locations.create');
Route::post('/locations',              [ParkingController::class, 'storeLocation'])->name('locations.store');
Route::get('/locations/{id}/edit',     [ParkingController::class, 'editLocation'])->name('locations.edit');
Route::put('/locations/{id}',          [ParkingController::class, 'updateLocation'])->name('locations.update');
Route::delete('/locations/{id}',       [ParkingController::class, 'destroyLocation'])->name('locations.destroy');

// ── JENIS KENDARAAN (CRUD) ─────────────────────────────────
Route::get('/vehicle-types',           [ParkingController::class, 'indexVehicleType'])->name('vehicle-types.index');
Route::get('/vehicle-types/create',    [ParkingController::class, 'createVehicleType'])->name('vehicle-types.create');
Route::post('/vehicle-types',          [ParkingController::class, 'storeVehicleType'])->name('vehicle-types.store');
Route::get('/vehicle-types/{id}/edit', [ParkingController::class, 'editVehicleType'])->name('vehicle-types.edit');
Route::put('/vehicle-types/{id}',      [ParkingController::class, 'updateVehicleType'])->name('vehicle-types.update');
Route::delete('/vehicle-types/{id}',   [ParkingController::class, 'destroyVehicleType'])->name('vehicle-types.destroy');

// ── LAPORAN ────────────────────────────────────────────────
Route::get('/reports',                 [ParkingController::class, 'indexReport'])->name('reports.index');