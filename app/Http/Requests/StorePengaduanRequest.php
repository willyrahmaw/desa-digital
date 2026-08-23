<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengaduanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'nik' => ['required', 'string', 'size:16'],
            'kategori' => ['required', 'string', 'max:50'],
            'judul' => ['required', 'string', 'max:150'],
            'isi' => ['required', 'string'],
            'foto_file' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
