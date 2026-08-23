<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDusunRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('dusun') ?? $this->dusun;
        return [
            'nama' => ['required', 'string', 'max:100', 'unique:dusun,nama,' . $id],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama dusun wajib diisi.',
            'nama.unique' => 'Nama dusun sudah terdaftar.',
        ];
    }
}
