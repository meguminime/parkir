<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk memperbarui data lokasi.
     * Semua field bersifat opsional (sometimes) agar bisa partial update.
     */
    public function rules(): array
    {
        return [
            'location_name'  => 'sometimes|required|string|max:100',
            'max_motorcycle' => 'sometimes|required|integer|min:0',
            'max_car'        => 'sometimes|required|integer|min:0',
            'max_other'      => 'sometimes|required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'location_name.max'       => 'Nama lokasi maksimal 100 karakter.',
            'max_motorcycle.integer'  => 'Kapasitas motor harus berupa angka.',
            'max_motorcycle.min'      => 'Kapasitas motor tidak boleh negatif.',
            'max_car.integer'         => 'Kapasitas mobil harus berupa angka.',
            'max_car.min'             => 'Kapasitas mobil tidak boleh negatif.',
            'max_other.integer'       => 'Kapasitas kendaraan lain harus berupa angka.',
            'max_other.min'           => 'Kapasitas kendaraan lain tidak boleh negatif.',
        ];
    }
}
