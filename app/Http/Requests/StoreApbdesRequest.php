<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApbdesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'kategori' => ['required', 'in:Pendapatan,Belanja,Pembiayaan'],
            'sub_kategori' => ['nullable', 'string', 'max:150'],
            'nama_item' => ['required', 'string', 'max:150'],
            'anggaran' => ['required', 'numeric', 'min:0'],
            'realisasi' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ];
    }
}
