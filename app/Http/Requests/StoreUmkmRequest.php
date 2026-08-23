<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'harga' => ['required', 'numeric', 'min:0'],
            'umkm_pelaku_id' => ['required', 'exists:umkm_pelaku,id'],
            'umkm_kategori_id' => ['required', 'exists:umkm_kategori,id'],
            'foto_file' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
