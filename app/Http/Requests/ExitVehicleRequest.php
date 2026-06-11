<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExitVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk kendaraan keluar parkir.
     */
    public function rules(): array
    {
        return [
            'no_tiket' => 'required|string|exists:parkir_transactions,no_tiket',
        ];
    }

    public function messages(): array
    {
        return [
            'no_tiket.required' => 'Nomor tiket wajib diisi.',
            'no_tiket.exists'   => 'Nomor tiket tidak ditemukan di sistem.',
        ];
    }
}
