<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\VehicleType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ParkingController extends Controller
{
    // =========================================================
    // TRANSAKSI INDEX
    // =========================================================

    public function transactionIndex(Request $request)
    {
        $today        = Carbon::today();
        $locations    = Location::all();
        $vehicleTypes = VehicleType::all();

        $query = Transaction::with(['location', 'vehicleType'])->latest();

        // Filter status
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->whereNull('keluar');
            } elseif ($request->status === 'selesai') {
                $query->whereNotNull('keluar');
            }
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('masuk', $request->tanggal);
        }

        $transactions       = $query->paginate(15)->appends($request->query());
        $totalAktif         = Transaction::whereNull('keluar')->count();
        $totalKeluarHariIni = Transaction::whereDate('keluar', $today)->whereNotNull('keluar')->count();
        $pendapatanHariIni  = Transaction::whereDate('keluar', $today)->whereNotNull('keluar')->sum('total_bayar');

        return view('transactions.index', compact(
            'locations',
            'vehicleTypes',
            'transactions',
            'totalAktif',
            'totalKeluarHariIni',
            'pendapatanHariIni'
        ));
    }

    // =========================================================
    // ENTER VEHICLE — Kendaraan Masuk
    // =========================================================

    public function enterVehicle(Request $request)
    {
        $request->validate([
            'no_polisi' => 'nullable|string|max:20',
            'id_lokasi' => 'required|exists:parkir_locations,id',
            'id_jenis'  => 'required|exists:parkir_vehicle_types,id',
        ]);

        return DB::transaction(function () use ($request) {
            $location    = Location::findOrFail($request->id_lokasi);
            $vehicleType = VehicleType::findOrFail($request->id_jenis);

            if (!$location->hasAvailableSlot($vehicleType->jenis)) {
                return back()
                    ->withInput()
                    ->with('error', "Slot parkir {$vehicleType->jenis} di {$location->location_name} sudah penuh!");
            }

            $noTiket = now()->format('YmdHis') . rand(1, 9);

            $transaction = Transaction::create([
                'id_lokasi'         => $location->id,
                'no_tiket'          => $noTiket,
                'no_polisi'         => strtoupper($request->no_polisi),
                'id_jenis'          => $vehicleType->id,
                'masuk'             => now(),
                'keluar'            => null,
                'perjam_pertama'    => $vehicleType->perjam_pertama,
                'perjam_berikutnya' => $vehicleType->perjam_berikutnya,
                'max_perhari'       => $vehicleType->max_perhari,
                'total_jam'         => null,
                'total_bayar'       => null,
            ]);

            $location->decrementSlot($vehicleType->jenis);

            // Generate and save PDF
            $pdf = Pdf::loadView('pdf.ticket', compact('transaction'));
            Storage::disk('public')->put('tickets/' . $noTiket . '.pdf', $pdf->output());

            return redirect()->route('transactions.index')
                ->with('masuk_success', true);
        });
    }

    // =========================================================
    // TICKET PDF
    // =========================================================

    public function ticketPdf($id)
    {
        $transaction = Transaction::with(['location', 'vehicleType'])->findOrFail($id);
        
        // Cek apakah file fisik ada di storage/public/tickets
        $path = 'tickets/' . $transaction->no_tiket . '.pdf';
        if (Storage::disk('public')->exists($path)) {
            // Bisa return response()->file(...) jika symlink ada, atau download
            return response()->file(storage_path('app/public/' . $path));
        }

        // Fallback: generate ulang jika file hilang
        $pdf = Pdf::loadView('pdf.ticket', compact('transaction'));
        return $pdf->stream('Tiket-' . $transaction->no_tiket . '.pdf');
    }

    // =========================================================
    // EXIT VEHICLE — Kendaraan Keluar
    // =========================================================

    public function exitVehicle(Request $request)
    {
        $request->validate([
            'no_tiket' => 'required|string',
        ]);

        return DB::transaction(function () use ($request) {
            $transaction = Transaction::with(['location', 'vehicleType'])
                ->where('no_tiket', $request->no_tiket)
                ->first();

            if (!$transaction) {
                return back()->with('error', 'Nomor tiket tidak ditemukan.');
            }

            if ($transaction->keluar !== null) {
                return back()->with('error', 'Kendaraan dengan tiket ini sudah keluar.');
            }

            $waktuKeluar = now();
            $totalMenit  = $transaction->masuk->diffInMinutes($waktuKeluar);
            // 1 real-world minute = 1 parking hour for testing purposes
            $totalJam    = max(1, $totalMenit);

            $totalBayar = $this->hitungBiayaParkir(
                $totalJam,
                $transaction->perjam_pertama,
                $transaction->perjam_berikutnya,
                $transaction->max_perhari
            );

            $transaction->update([
                'keluar'      => $waktuKeluar,
                'total_jam'   => $totalJam,
                'total_bayar' => $totalBayar,
            ]);

            $transaction->location->incrementSlot($transaction->vehicleType->jenis);

            $totalFormatted = 'Rp ' . number_format($totalBayar, 0, ',', '.');

            return redirect()->route('transactions.index')
                ->with('keluar_success', $transaction);
        });
    }

    // =========================================================
    // LOKASI — CRUD
    // =========================================================

    public function indexLocation()
    {
        $locations = Location::withCount([
            'transactions as aktif_count' => fn($q) => $q->whereNull('keluar'),
        ])->get();

        return view('locations.index', compact('locations'));
    }

    public function createLocation()
    {
        return view('locations.form');
    }

    public function storeLocation(Request $request)
    {
        $request->validate([
            'location_name'  => 'required|string|max:100|unique:parkir_locations,location_name',
            'max_motorcycle' => 'required|integer|min:0',
            'max_car'        => 'required|integer|min:0',
            'max_other'      => 'required|integer|min:0',
        ]);

        Location::create($request->only(['location_name', 'max_motorcycle', 'max_car', 'max_other']));

        return redirect()->route('locations.index')->with('success', 'Lokasi parkir berhasil ditambahkan.');
    }

    public function editLocation($id)
    {
        $location = Location::findOrFail($id);
        return view('locations.form', compact('location'));
    }

    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'location_name'  => 'required|string|max:100|unique:parkir_locations,location_name,' . $id,
            'max_motorcycle' => 'required|integer|min:0',
            'max_car'        => 'required|integer|min:0',
            'max_other'      => 'required|integer|min:0',
        ]);

        $location = Location::findOrFail($id);
        $location->update($request->only(['location_name', 'max_motorcycle', 'max_car', 'max_other']));

        return redirect()->route('locations.index')->with('success', 'Lokasi parkir berhasil diperbarui.');
    }

    public function destroyLocation($id)
    {
        $location = Location::findOrFail($id);

        if ($location->transactions()->whereNull('keluar')->exists()) {
            return redirect()->route('locations.index')
                ->with('error', 'Tidak dapat menghapus lokasi yang masih memiliki kendaraan parkir aktif.');
        }

        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Lokasi parkir berhasil dihapus.');
    }

    // =========================================================
    // JENIS KENDARAAN — CRUD
    // =========================================================

    public function indexVehicleType()
    {
        $vehicleTypes = VehicleType::all();
        return view('vehicle-types.index', compact('vehicleTypes'));
    }

    public function createVehicleType()
    {
        return view('vehicle-types.form');
    }

    public function storeVehicleType(Request $request)
    {
        $request->validate([
            'jenis'             => 'required|in:motorcycle,car,other|unique:parkir_vehicle_types,jenis',
            'perjam_pertama'    => 'required|integer|min:0',
            'perjam_berikutnya' => 'required|integer|min:0',
            'max_perhari'       => 'required|integer|min:0',
        ]);

        VehicleType::create($request->only(['jenis', 'perjam_pertama', 'perjam_berikutnya', 'max_perhari']));

        return redirect()->route('vehicle-types.index')->with('success', 'Jenis kendaraan berhasil ditambahkan.');
    }

    public function editVehicleType($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        return view('vehicle-types.form', compact('vehicleType'));
    }

    public function updateVehicleType(Request $request, $id)
    {
        $request->validate([
            'jenis'             => 'required|in:motorcycle,car,other|unique:parkir_vehicle_types,jenis,' . $id,
            'perjam_pertama'    => 'required|integer|min:0',
            'perjam_berikutnya' => 'required|integer|min:0',
            'max_perhari'       => 'required|integer|min:0',
        ]);

        $vt = VehicleType::findOrFail($id);
        $vt->update($request->only(['jenis', 'perjam_pertama', 'perjam_berikutnya', 'max_perhari']));

        return redirect()->route('vehicle-types.index')->with('success', 'Jenis kendaraan berhasil diperbarui.');
    }

    public function destroyVehicleType($id)
    {
        $vt = VehicleType::findOrFail($id);
        $vt->delete();

        return redirect()->route('vehicle-types.index')->with('success', 'Jenis kendaraan berhasil dihapus.');
    }

    // =========================================================
    // LAPORAN
    // =========================================================

    public function indexReport(Request $request)
    {
        $today      = Carbon::today();
        $startDate  = $request->filled('start_date') ? Carbon::parse($request->start_date) : $today->copy()->startOfMonth();
        $endDate    = $request->filled('end_date')   ? Carbon::parse($request->end_date)->endOfDay() : $today->copy()->endOfDay();

        $transactions = Transaction::with(['location', 'vehicleType'])
            ->whereNotNull('keluar')
            ->whereBetween('keluar', [$startDate, $endDate])
            ->latest('keluar')
            ->get();

        $totalPendapatan = $transactions->sum('total_bayar');
        $totalTransaksi  = $transactions->count();

        // Ringkasan per jenis kendaraan
        $byJenis = $transactions->groupBy(fn($t) => $t->vehicleType->jenis ?? 'unknown')
            ->map(fn($group) => [
                'count'     => $group->count(),
                'pendapatan' => $group->sum('total_bayar'),
            ]);

        // Ringkasan per lokasi
        $byLokasi = $transactions->groupBy(fn($t) => $t->location->location_name ?? 'unknown')
            ->map(fn($group) => [
                'count'     => $group->count(),
                'pendapatan' => $group->sum('total_bayar'),
            ]);

        return view('reports.index', compact(
            'transactions',
            'totalPendapatan',
            'totalTransaksi',
            'byJenis',
            'byLokasi',
            'startDate',
            'endDate'
        ));
    }

    // =========================================================
    // PRIVATE: Logika Perhitungan Biaya
    // =========================================================

    private function hitungBiayaParkir(int $totalJam, int $perjamPertama, int $perjamBerikutnya, int $maxPerhari): int
    {
        if ($totalJam <= 24) {
            $biaya = ($totalJam === 1)
                ? $perjamPertama
                : $perjamPertama + ($perjamBerikutnya * ($totalJam - 1));

            return min($biaya, $maxPerhari);
        }

        $biayaPerhari   = (int) round(0.60 * $maxPerhari);
        $hariPenuh      = (int) floor($totalJam / 24);
        $sisaJam        = $totalJam % 24;
        $biayaHariPenuh = $hariPenuh * $biayaPerhari;

        if ($sisaJam === 0) {
            $biayaSisaJam = 0;
        } elseif ($sisaJam === 1) {
            $biayaSisaJam = $perjamPertama;
        } else {
            $biayaSisaJam = $perjamPertama + ($perjamBerikutnya * ($sisaJam - 1));
        }

        return $biayaHariPenuh + min($biayaSisaJam, $biayaPerhari);
    }
}