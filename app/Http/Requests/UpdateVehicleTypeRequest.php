<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID dari route untuk validasi unique (mengabaikan diri sendiri)
        $id = $this->route('vehicle_type') ?? $this->route('id');

        return [
            'jenis'             => "sometimes|required|string|in:motorcycle,car,other|unique:parkir_vehicle_types,jenis,{$id}",
            'perjam_pertama'    => 'sometimes|required|integer|min:0',
            'perjam_berikutnya' => 'sometimes|required|integer|min:0',
            'max_perhari'       => 'sometimes|required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'jenis.in'                   => 'Jenis kendaraan harus salah satu dari: motorcycle, car, other.',
            'jenis.unique'               => 'Jenis kendaraan ini sudah terdaftar.',
            'perjam_pertama.integer'     => 'Tarif jam pertama harus berupa angka.',
            'perjam_pertama.min'         => 'Tarif jam pertama tidak boleh negatif.',
            'perjam_berikutnya.integer'  => 'Tarif jam berikutnya harus berupa angka.',
            'perjam_berikutnya.min'      => 'Tarif jam berikutnya tidak boleh negatif.',
            'max_perhari.integer'        => 'Tarif maksimum per hari harus berupa angka.',
            'max_perhari.min'            => 'Tarif maksimum per hari tidak boleh negatif.',
        ];
    }
}
