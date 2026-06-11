<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnterVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk kendaraan masuk parkir.
     */
    public function rules(): array
    {
        return [
            'no_polisi' => 'required|string|max:15',
            'id_lokasi' => 'required|integer|exists:parkir_locations,id',
            'id_jenis'  => 'required|integer|exists:parkir_vehicle_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'no_polisi.required' => 'Nomor polisi kendaraan wajib diisi.',
            'no_polisi.max'      => 'Nomor polisi maksimal 15 karakter.',
            'id_lokasi.required' => 'Lokasi parkir wajib dipilih.',
            'id_lokasi.exists'   => 'Lokasi parkir tidak ditemukan.',
            'id_jenis.required'  => 'Jenis kendaraan wajib dipilih.',
            'id_jenis.exists'    => 'Jenis kendaraan tidak ditemukan.',
        ];
    }
}
