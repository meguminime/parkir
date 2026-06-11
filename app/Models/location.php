<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'parkir_locations';

    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     */
    protected $fillable = [
        'location_name',
        'max_motorcycle',
        'max_car',
        'max_other',
    ];

    /**
     * Cast tipe data kolom agar lebih mudah diproses.
     */
    protected $casts = [
        'max_motorcycle' => 'integer',
        'max_car'        => 'integer',
        'max_other'      => 'integer',
    ];

    // =========================================================
    // RELASI
    // =========================================================

    /**
     * Satu lokasi memiliki banyak transaksi parkir.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_lokasi');
    }

    /**
     * Cek apakah masih ada slot tersedia untuk jenis kendaraan tertentu.
     *
     * @param  string $vehicleType  'motorcycle' | 'car' | 'other'
     * @return bool
     */
    public function hasAvailableSlot(string $vehicleType): bool
    {
        $column    = "max_{$vehicleType}";
        $available = $this->$column;

        return $available > 0;
    }

    /**
     * Kurangi kapasitas parkir ketika kendaraan masuk.
     *
     * @param  string $vehicleType  'motorcycle' | 'car' | 'other'
     * @return void
     */
    public function decrementSlot(string $vehicleType): void
    {
        $column = "max_{$vehicleType}";
        $this->decrement($column);
    }

    /**
     * Kembalikan kapasitas parkir ketika kendaraan keluar.
     *
     * @param  string $vehicleType  'motorcycle' | 'car' | 'other'
     * @return void
     */
    public function incrementSlot(string $vehicleType): void
    {
        $column = "max_{$vehicleType}";
        $this->increment($column);
    }
}