<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes — SIJA PARKING
|--------------------------------------------------------------------------
|
| Semua route di sini akan diberi prefix '/api' secara otomatis oleh Laravel.
| Contoh: Route::get('/locations') → dapat diakses di /api/locations
|
*/

// ── LOKASI PARKIR ──────────────────────────────────────────
Route::apiResource('locations', LocationController::class)->names('api.locations');
// Menghasilkan:
//   GET    /api/locations           → index()
//   POST   /api/locations           → store()
//   GET    /api/locations/{id}      → show()
//   PUT    /api/locations/{id}      → update()
//   DELETE /api/locations/{id}      → destroy()

// ── JENIS KENDARAAN ────────────────────────────────────────
Route::apiResource('vehicle-types', VehicleTypeController::class)->names('api.vehicle-types');
// Menghasilkan:
//   GET    /api/vehicle-types       → index()
//   POST   /api/vehicle-types       → store()
//   GET    /api/vehicle-types/{id}  → show()
//   PUT    /api/vehicle-types/{id}  → update()
//   DELETE /api/vehicle-types/{id}  → destroy()

// ── TRANSAKSI PARKIR ───────────────────────────────────────
// Rute khusus untuk proses masuk & keluar kendaraan
Route::post('transactions/enter', [TransactionController::class, 'enterVehicle'])
    ->name('api.transactions.enter');

Route::post('transactions/exit', [TransactionController::class, 'exitVehicle'])
    ->name('api.transactions.exit');

// Rute untuk melihat riwayat transaksi
Route::get('transactions', [TransactionController::class, 'index'])
    ->name('api.transactions.index');

Route::get('transactions/{transaction}', [TransactionController::class, 'show'])
    ->name('api.transactions.show');

// ── CEK TIKET (untuk form Kendaraan Keluar) ──────────────
Route::get('check-ticket', function (\Illuminate\Http\Request $request) {
    $noTiket = $request->query('no_tiket');

    if (!$noTiket) {
        return response()->json(['success' => false, 'message' => 'No tiket diperlukan.'], 400);
    }

    $trx = \App\Models\Transaction::with(['location', 'vehicleType'])
        ->where('no_tiket', $noTiket)
        ->whereNull('keluar')
        ->first();

    if (!$trx) {
        return response()->json(['success' => false, 'message' => 'Tiket tidak ditemukan atau sudah diproses.']);
    }

    $jenisLabel = ['motorcycle' => 'Motor', 'car' => 'Mobil', 'other' => 'Lainnya'];

    return response()->json([
        'success' => true,
        'data'    => [
            'no_tiket'      => $trx->no_tiket,
            'no_polisi'     => $trx->no_polisi,
            'location_name' => $trx->location->location_name ?? '-',
            'jenis'         => $jenisLabel[$trx->vehicleType->jenis ?? ''] ?? '-',
            'masuk'         => $trx->masuk ? $trx->masuk->format('d/m/Y H:i:s') : '-',
        ],
    ]);
})->name('api.check-ticket');
