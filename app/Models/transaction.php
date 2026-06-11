<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'parkir_transactions';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'id_lokasi',
        'no_tiket',
        'no_polisi',
        'id_jenis',
        'masuk',
        'keluar',
        'perjam_pertama',
        'perjam_berikutnya',
        'max_perhari',
        'total_jam',
        'total_bayar',
    ];

    /**
     * Cast tipe data kolom agar mudah diproses.
     */
    protected $casts = [
        'masuk'             => 'datetime',
        'keluar'            => 'datetime',
        'perjam_pertama'    => 'integer',
        'perjam_berikutnya' => 'integer',
        'max_perhari'       => 'integer',
        'total_jam'         => 'integer',
        'total_bayar'       => 'integer',
    ];

    // =========================================================
    // RELASI
    // =========================================================

    /**
     * Transaksi ini milik satu lokasi parkir.
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'id_lokasi');
    }

    /**
     * Transaksi ini milik satu jenis kendaraan.
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'id_jenis');
    }

    // =========================================================
    // SCOPE (Query Filter)
    // =========================================================

    /**
     * Filter transaksi yang masih aktif (kendaraan belum keluar).
     */
    public function scopeAktif($query)
    {
        return $query->whereNull('keluar');
    }

    /**
     * Filter transaksi yang sudah selesai (kendaraan sudah keluar).
     */
    public function scopeSelesai($query)
    {
        return $query->whereNotNull('keluar');
    }
}