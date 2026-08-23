<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBeritaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'kategori_berita_id' => ['required', 'exists:kategori_berita,id'],
            'konten' => ['required', 'string'],
            'status' => ['required', 'in:Draft,Publikasi'],
            'tanggal_publikasi' => ['nullable', 'date'],
            'gambar_file' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
