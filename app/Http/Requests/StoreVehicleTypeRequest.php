<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\VehicleType;

class StoreVehicleTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk menyimpan jenis kendaraan baru.
     */
    public function rules(): array
    {
        return [
            'jenis'             => 'required|string|in:motorcycle,car,other|unique:parkir_vehicle_types,jenis',
            'perjam_pertama'    => 'required|integer|min:0',
            'perjam_berikutnya' => 'required|integer|min:0',
            'max_perhari'       => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'jenis.required'             => 'Jenis kendaraan wajib dipilih.',
            'jenis.in'                   => 'Jenis kendaraan harus salah satu dari: motorcycle, car, other.',
            'jenis.unique'               => 'Jenis kendaraan ini sudah terdaftar.',
            'perjam_pertama.required'    => 'Tarif jam pertama wajib diisi.',
            'perjam_pertama.integer'     => 'Tarif jam pertama harus berupa angka.',
            'perjam_pertama.min'         => 'Tarif jam pertama tidak boleh negatif.',
            'perjam_berikutnya.required' => 'Tarif jam berikutnya wajib diisi.',
            'perjam_berikutnya.integer'  => 'Tarif jam berikutnya harus berupa angka.',
            'perjam_berikutnya.min'      => 'Tarif jam berikutnya tidak boleh negatif.',
            'max_perhari.required'       => 'Tarif maksimum per hari wajib diisi.',
            'max_perhari.integer'        => 'Tarif maksimum per hari harus berupa angka.',
            'max_perhari.min'            => 'Tarif maksimum per hari tidak boleh negatif.',
        ];
    }
}
