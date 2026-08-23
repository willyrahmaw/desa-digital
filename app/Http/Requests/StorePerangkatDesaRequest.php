<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePerangkatDesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('perangkat_desa') ?? $this->perangkat_desa;
        return [
            'nama' => ['required', 'string', 'max:100'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:perangkat_desa,nip,' . $id],
            'jabatan_id' => ['required', 'exists:jabatan,id'],
            'user_id' => ['nullable', 'exists:user,id'],
            'foto_file' => ['nullable', 'image', 'max:2048'],
            'status_aktif' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama perangkat desa wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'jabatan_id.required' => 'Jabatan wajib diisi.',
            'jabatan_id.exists' => 'Jabatan tidak valid.',
            'foto_file.image' => 'File harus berupa gambar.',
            'foto_file.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
