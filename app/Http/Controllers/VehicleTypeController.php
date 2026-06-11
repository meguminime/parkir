<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use App\Http\Requests\StoreVehicleTypeRequest;
use App\Http\Requests\UpdateVehicleTypeRequest;
use Illuminate\Http\JsonResponse;

class VehicleTypeController extends Controller
{
    /**
     * Tampilkan semua jenis kendaraan beserta tarif parkir.
     * GET /vehicle-types
     */
    public function index(): JsonResponse
    {
        $vehicleTypes = VehicleType::all();

        return response()->json([
            'success' => true,
            'message' => 'Data jenis kendaraan berhasil diambil.',
            'data'    => $vehicleTypes,
        ]);
    }

    /**
     * Simpan jenis kendaraan baru beserta tarif parkir.
     * POST /vehicle-types
     */
    public function store(StoreVehicleTypeRequest $request): JsonResponse
    {
        $vehicleType = VehicleType::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Jenis kendaraan berhasil ditambahkan.',
            'data'    => $vehicleType,
        ], 201);
    }

    /**
     * Tampilkan detail satu jenis kendaraan.
     * GET /vehicle-types/{id}
     */
    public function show(VehicleType $vehicleType): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail jenis kendaraan berhasil diambil.',
            'data'    => $vehicleType,
        ]);
    }

    /**
     * Perbarui data jenis kendaraan dan tarifnya.
     * PUT /vehicle-types/{id}
     */
    public function update(UpdateVehicleTypeRequest $request, VehicleType $vehicleType): JsonResponse
    {
        $vehicleType->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Jenis kendaraan berhasil diperbarui.',
            'data'    => $vehicleType->fresh(),
        ]);
    }

    /**
     * Hapus jenis kendaraan.
     * DELETE /vehicle-types/{id}
     */
    public function destroy(VehicleType $vehicleType): JsonResponse
    {
        // Cek apakah jenis ini masih digunakan dalam transaksi aktif
        $aktif = $vehicleType->transactions()->whereNull('keluar')->count();

        if ($aktif > 0) {
            return response()->json([
                'success' => false,
                'message' => "Tidak bisa menghapus jenis kendaraan. Masih ada {$aktif} transaksi aktif.",
            ], 422);
        }

        $vehicleType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis kendaraan berhasil dihapus.',
        ]);
    }
}
