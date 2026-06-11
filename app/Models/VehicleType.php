<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleType extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'parkir_vehicle_types';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'jenis',
        'perjam_pertama',
        'perjam_berikutnya',
        'max_perhari',
    ];

    /**
     * Cast tipe data kolom.
     */
    protected $casts = [
        'jenis'             => 'string',
        'perjam_pertama'    => 'integer',
        'perjam_berikutnya' => 'integer',
        'max_perhari'       => 'integer',
    ];

    /**
     * Nilai yang diperbolehkan untuk kolom enum 'jenis'.
     */
    const JENIS_OPTIONS = ['motorcycle', 'car', 'other'];

    // =========================================================
    // RELASI
    // =========================================================

    /**
     * Satu jenis kendaraan memiliki banyak transaksi parkir.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_jenis');
    }
}
