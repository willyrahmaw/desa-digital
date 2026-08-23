<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKartuKeluargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $no_kk = $this->route('kartu_keluarga') ?? $this->kartu_keluarga;
        return [
            'no_kk' => ['required', 'string', 'size:16', 'unique:kartu_keluarga,no_kk,' . $no_kk . ',no_kk'],
            'alamat' => ['required', 'string'],
            'dusun_id' => ['required', 'exists:dusun,id'],
            'rw_id' => ['required', 'exists:rw,id'],
            'rt_id' => ['required', 'exists:rt,id'],
            'kepala_keluarga_nik' => ['nullable', 'string', 'size:16'],
        ];
    }

    public function messages(): array
    {
        return [
            'no_kk.required' => 'Nomor KK wajib diisi.',
            'no_kk.size' => 'Nomor KK harus tepat 16 digit.',
            'no_kk.unique' => 'Nomor KK sudah terdaftar.',
            'alamat.required' => 'Alamat wajib diisi.',
            'dusun_id.required' => 'Dusun wajib dipilih.',
            'rw_id.required' => 'RW wajib dipilih.',
            'rt_id.required' => 'RT wajib dipilih.',
        ];
    }
}
