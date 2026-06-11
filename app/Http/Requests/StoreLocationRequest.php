<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     * Ubah ke 'true' atau tambahkan logika otorisasi sesuai kebutuhan.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk menyimpan data lokasi baru.
     */
    public function rules(): array
    {
        return [
            'location_name'  => 'required|string|max:100',
            'max_motorcycle' => 'required|integer|min:0',
            'max_car'        => 'required|integer|min:0',
            'max_other'      => 'required|integer|min:0',
        ];
    }

    /**
     * Pesan error kustom dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'location_name.required'  => 'Nama lokasi wajib diisi.',
            'location_name.max'       => 'Nama lokasi maksimal 100 karakter.',
            'max_motorcycle.required' => 'Kapasitas motor wajib diisi.',
            'max_motorcycle.integer'  => 'Kapasitas motor harus berupa angka.',
            'max_motorcycle.min'      => 'Kapasitas motor tidak boleh negatif.',
            'max_car.required'        => 'Kapasitas mobil wajib diisi.',
            'max_car.integer'         => 'Kapasitas mobil harus berupa angka.',
            'max_car.min'             => 'Kapasitas mobil tidak boleh negatif.',
            'max_other.required'      => 'Kapasitas kendaraan lain wajib diisi.',
            'max_other.integer'       => 'Kapasitas kendaraan lain harus berupa angka.',
            'max_other.min'           => 'Kapasitas kendaraan lain tidak boleh negatif.',
        ];
    }
}
