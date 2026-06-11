<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Transaction;
use App\Models\VehicleType;
use App\Http\Requests\EnterVehicleRequest;
use App\Http\Requests\ExitVehicleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // =========================================================
    // ENTER VEHICLE — Kendaraan Masuk
    // =========================================================

    /**
     * Proses kendaraan masuk ke area parkir.
     * POST /transactions/enter
     *
     * Alur:
     * 1. Validasi input (no_polisi, id_lokasi, id_jenis)
     * 2. Cek ketersediaan slot parkir
     * 3. Generate nomor tiket unik
     * 4. Simpan transaksi + kurangi kapasitas lokasi
     */
    public function enterVehicle(EnterVehicleRequest $request): JsonResponse
    {
        // Gunakan DB transaction agar data konsisten
        // (jika satu langkah gagal, semua dibatalkan)
        return DB::transaction(function () use ($request) {

            // --- Step 1: Ambil data lokasi dan jenis kendaraan ---
            $location    = Location::findOrFail($request->id_lokasi);
            $vehicleType = VehicleType::findOrFail($request->id_jenis);

            // --- Step 2: Cek ketersediaan slot berdasarkan jenis kendaraan ---
            // Kolom kapasitas: max_motorcycle, max_car, max_other
            if (!$location->hasAvailableSlot($vehicleType->jenis)) {
                return response()->json([
                    'success' => false,
                    'message' => "Maaf, slot parkir untuk {$vehicleType->jenis} di lokasi ini sudah penuh.",
                ], 422);
            }

            // --- Step 3: Generate nomor tiket unik menggunakan timestamp ---
            // Format: PKR-{timestamp_microtime} contoh: PKR-1749354000123456
            $noTiket = 'PKR-' . now()->format('YmdHis') . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

            // --- Step 4: Simpan transaksi baru ---
            // Salin tarif dari VehicleType ke transaksi agar tidak berubah
            // jika tarif diubah di kemudian hari (snapshot tarif saat masuk)
            $transaction = Transaction::create([
                'id_lokasi'         => $location->id,
                'no_tiket'          => $noTiket,
                'no_polisi'         => strtoupper($request->no_polisi),
                'id_jenis'          => $vehicleType->id,
                'masuk'             => now(),
                'keluar'            => null,
                // Snapshot tarif saat kendaraan masuk
                'perjam_pertama'    => $vehicleType->perjam_pertama,
                'perjam_berikutnya' => $vehicleType->perjam_berikutnya,
                'max_perhari'       => $vehicleType->max_perhari,
                'total_jam'         => null,
                'total_bayar'       => null,
            ]);

            // --- Step 5: Kurangi kapasitas slot di lokasi ---
            $location->decrementSlot($vehicleType->jenis);

            return response()->json([
                'success' => true,
                'message' => 'Kendaraan berhasil masuk. Simpan nomor tiket Anda!',
                'data'    => [
                    'no_tiket'    => $transaction->no_tiket,
                    'no_polisi'   => $transaction->no_polisi,
                    'lokasi'      => $location->location_name,
                    'jenis'       => $vehicleType->jenis,
                    'waktu_masuk' => $transaction->masuk->format('d/m/Y H:i:s'),
                ],
            ], 201);
        });
    }

    // =========================================================
    // EXIT VEHICLE — Kendaraan Keluar
    // =========================================================

    /**
     * Proses kendaraan keluar dari area parkir dan hitung biaya.
     * POST /transactions/exit
     *
     * Alur:
     * 1. Cari transaksi berdasarkan no_tiket
     * 2. Hitung durasi parkir (masuk → keluar)
     * 3. Hitung biaya sesuai aturan tarif
     * 4. Simpan waktu keluar & total bayar
     * 5. Kembalikan kapasitas slot ke lokasi
     */
    public function exitVehicle(ExitVehicleRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {

            // --- Step 1: Cari transaksi berdasarkan nomor tiket ---
            $transaction = Transaction::with(['location', 'vehicleType'])
                ->where('no_tiket', $request->no_tiket)
                ->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor tiket tidak ditemukan.',
                ], 404);
            }

            // Pastikan kendaraan belum keluar sebelumnya
            if ($transaction->keluar !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kendaraan dengan tiket ini sudah keluar pada ' .
                                 $transaction->keluar->format('d/m/Y H:i:s'),
                ], 422);
            }

            // --- Step 2: Hitung durasi parkir ---
            $waktuKeluar = now();
            $waktuMasuk  = $transaction->masuk;

            // Hitung total menit, lalu konversi ke jam (dibulatkan ke atas)
            // Misal: 1 jam 5 menit → dihitung 2 jam
            $totalMenit = $waktuMasuk->diffInMinutes($waktuKeluar);
            $totalJam   = (int) ceil($totalMenit / 60);

            // Pastikan minimal 1 jam agar tidak 0
            if ($totalJam < 1) {
                $totalJam = 1;
            }

            // --- Step 3: Hitung biaya parkir ---
            // Ambil tarif dari snapshot transaksi (bukan dari tabel VehicleType)
            $perjamPertama    = $transaction->perjam_pertama;
            $perjamBerikutnya = $transaction->perjam_berikutnya;
            $maxPerhari       = $transaction->max_perhari;

            $totalBayar = $this->hitungBiayaParkir(
                totalJam:         $totalJam,
                perjamPertama:    $perjamPertama,
                perjamBerikutnya: $perjamBerikutnya,
                maxPerhari:       $maxPerhari
            );

            // --- Step 4: Update transaksi dengan waktu keluar dan biaya ---
            $transaction->update([
                'keluar'     => $waktuKeluar,
                'total_jam'  => $totalJam,
                'total_bayar' => $totalBayar,
            ]);

            // --- Step 5: Kembalikan kapasitas slot ke lokasi ---
            $location    = $transaction->location;
            $vehicleType = $transaction->vehicleType;
            $location->incrementSlot($vehicleType->jenis);

            return response()->json([
                'success' => true,
                'message' => 'Kendaraan berhasil keluar. Terima kasih!',
                'data'    => [
                    'no_tiket'     => $transaction->no_tiket,
                    'no_polisi'    => $transaction->no_polisi,
                    'lokasi'       => $location->location_name,
                    'jenis'        => $vehicleType->jenis,
                    'waktu_masuk'  => $transaction->masuk->format('d/m/Y H:i:s'),
                    'waktu_keluar' => $waktuKeluar->format('d/m/Y H:i:s'),
                    'durasi'       => "{$totalJam} jam",
                    'total_bayar'  => 'Rp ' . number_format($totalBayar, 0, ',', '.'),
                ],
            ]);
        });
    }

    // =========================================================
    // RIWAYAT TRANSAKSI
    // =========================================================

    /**
     * Tampilkan semua riwayat transaksi parkir.
     * GET /transactions
     */
    public function index(): JsonResponse
    {
        $transactions = Transaction::with(['location', 'vehicleType'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat transaksi berhasil diambil.',
            'data'    => $transactions,
        ]);
    }

    /**
     * Tampilkan detail satu transaksi.
     * GET /transactions/{id}
     */
    public function show(Transaction $transaction): JsonResponse
    {
        $transaction->load(['location', 'vehicleType']);

        return response()->json([
            'success' => true,
            'message' => 'Detail transaksi berhasil diambil.',
            'data'    => $transaction,
        ]);
    }

    // =========================================================
    // PRIVATE HELPER — Logika Perhitungan Biaya
    // =========================================================

    /**
     * Hitung total biaya parkir berdasarkan durasi dan tarif.
     *
     * ─────────────────────────────────────────────────────────
     * ATURAN PERHITUNGAN:
     *
     * [SKENARIO A] Durasi 0–24 jam (≤ 24 jam):
     *   Biaya = perjam_pertama + (perjam_berikutnya × (total_jam - 1))
     *   → Jika biaya > max_perhari, gunakan nilai max_perhari sebagai batas atas.
     *
     *   Contoh: Motor masuk 3 jam, perjam_pertama=2000, perjam_berikutnya=1000, max_perhari=10000
     *   Biaya = 2000 + (1000 × (3-1)) = 2000 + 2000 = 4000
     *
     * [SKENARIO B] Durasi > 24 jam (lebih dari 1 hari):
     *   - Biaya per hari = 60% × max_perhari
     *   - Sisa jam (yang tidak genap 1 hari) dihitung dengan rumus Skenario A,
     *     lalu dibatasi oleh biaya_perhari (bukan max_perhari penuh).
     *   - Total = (jumlah_hari_penuh × biaya_perhari) + biaya_sisa_jam
     *
     *   Contoh: Mobil parkir 30 jam, max_perhari=50000
     *   biaya_perhari = 60% × 50000 = 30000
     *   Hari penuh = 1 hari (24 jam), sisa = 6 jam
     *   Total = 30000 + biaya(6 jam, dibatasi 30000)
     * ─────────────────────────────────────────────────────────
     *
     * @param  int $totalJam          Total jam parkir (sudah dibulatkan ke atas)
     * @param  int $perjamPertama     Tarif jam pertama (Rp)
     * @param  int $perjamBerikutnya  Tarif jam berikutnya (Rp)
     * @param  int $maxPerhari        Batas maksimum biaya per hari (Rp)
     * @return int                    Total biaya yang harus dibayar (Rp)
     */
    private function hitungBiayaParkir(
        int $totalJam,
        int $perjamPertama,
        int $perjamBerikutnya,
        int $maxPerhari
    ): int {

        // ── SKENARIO A: Durasi tidak melebihi 24 jam ──────────────
        if ($totalJam <= 24) {

            // Hitung biaya normal:
            // Jam pertama + (tarif berikutnya × sisa jam)
            if ($totalJam === 1) {
                // Hanya 1 jam: hanya kena biaya jam pertama
                $biaya = $perjamPertama;
            } else {
                // Lebih dari 1 jam: jam pertama + berikutnya
                $biaya = $perjamPertama + ($perjamBerikutnya * ($totalJam - 1));
            }

            // Terapkan batas atas max_perhari
            // Jika biaya normal sudah melebihi max, cukup bayar max_perhari
            return min($biaya, $maxPerhari);
        }

        // ── SKENARIO B: Durasi lebih dari 24 jam ──────────────────
        // Biaya per hari dihitung 60% dari max_perhari
        $biayaPerhari = (int) round(0.60 * $maxPerhari);

        // Hitung berapa hari penuh dan jam sisa
        // Contoh: 30 jam → 1 hari penuh + 6 jam sisa
        $hariPenuh = (int) floor($totalJam / 24);
        $sisaJam   = $totalJam % 24;

        // Biaya untuk hari-hari penuh
        $biayaHariPenuh = $hariPenuh * $biayaPerhari;

        // Biaya untuk sisa jam (dihitung seperti Skenario A,
        // namun batas atasnya adalah biayaPerhari, bukan max_perhari penuh)
        if ($sisaJam === 0) {
            // Jika pas habis per hari, tidak ada sisa
            $biayaSisaJam = 0;
        } elseif ($sisaJam === 1) {
            $biayaSisaJam = $perjamPertama;
        } else {
            $biayaSisaJam = $perjamPertama + ($perjamBerikutnya * ($sisaJam - 1));
        }

        // Batasi biaya sisa jam dengan biaya_perhari (bukan max_perhari penuh)
        $biayaSisaJam = min($biayaSisaJam, $biayaPerhari);

        // Total = biaya hari penuh + biaya sisa jam
        return $biayaHariPenuh + $biayaSisaJam;
    }
}
