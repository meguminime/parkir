<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    /**
     * Tampilkan semua data lokasi parkir.
     * GET /locations
     */
    public function index(): JsonResponse
    {
        $locations = Location::withCount([
            // Hitung transaksi aktif (kendaraan yang masih di dalam)
            'transactions as aktif_count' => function ($query) {
                $query->whereNull('keluar');
            },
        ])->get();

        return response()->json([
            'success' => true,
            'message' => 'Data lokasi berhasil diambil.',
            'data'    => $locations,
        ]);
    }

    /**
     * Simpan lokasi parkir baru ke database.
     * POST /locations
     */
    public function store(StoreLocationRequest $request): JsonResponse
    {
        // Data sudah tervalidasi oleh StoreLocationRequest
        $location = Location::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Lokasi parkir berhasil ditambahkan.',
            'data'    => $location,
        ], 201);
    }

    /**
     * Tampilkan detail satu lokasi parkir beserta kendaraan yang sedang parkir.
     * GET /locations/{id}
     */
    public function show(Location $location): JsonResponse
    {
        // Load relasi transaksi yang masih aktif
        $location->load(['transactions' => function ($query) {
            $query->whereNull('keluar')->with('vehicleType');
        }]);

        return response()->json([
            'success' => true,
            'message' => 'Detail lokasi berhasil diambil.',
            'data'    => $location,
        ]);
    }

    /**
     * Perbarui data lokasi parkir.
     * PUT /locations/{id}
     */
    public function update(UpdateLocationRequest $request, Location $location): JsonResponse
    {
        $location->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Lokasi parkir berhasil diperbarui.',
            'data'    => $location->fresh(),
        ]);
    }

    /**
     * Hapus lokasi parkir.
     * DELETE /locations/{id}
     */
    public function destroy(Location $location): JsonResponse
    {
        // Cek apakah masih ada kendaraan yang parkir di lokasi ini
        $aktif = $location->transactions()->whereNull('keluar')->count();

        if ($aktif > 0) {
            return response()->json([
                'success' => false,
                'message' => "Tidak bisa menghapus lokasi. Masih ada {$aktif} kendaraan yang sedang parkir.",
            ], 422);
        }

        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lokasi parkir berhasil dihapus.',
        ]);
    }
}
