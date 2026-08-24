<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDataSosialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'penduduk_nik' => [
                'required', 
                'string', 
                'max:20', 
                function ($attribute, $value, $fail) {
                    if (!\App\Models\Penduduk::where('nik', $value)->exists()) {
                        $fail('NIK Penduduk tidak terdaftar.');
                    }
                }
            ],
            'dtks' => ['boolean'],
            'pkh' => ['boolean'],
            'bpnt' => ['boolean'],
            'pbi' => ['boolean'],
            'kpr' => ['boolean'],
            'desil' => ['nullable', 'integer', 'between:1,10'],
            'layak_sktm' => ['boolean'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'penduduk_nik.required' => 'NIK Penduduk wajib diisi.',
            'penduduk_nik.exists' => 'NIK Penduduk tidak terdaftar.',
            'penduduk_nik.size' => 'NIK Penduduk harus 16 digit.',
        ];
    }
}
