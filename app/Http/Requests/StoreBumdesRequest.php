<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBumdesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bumdes_unit_id' => ['required', 'exists:bumdes_unit,id'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'jenis_laporan' => ['required', 'string', 'max:100'],
            'file_path_file' => ['nullable', 'file', 'mimes:pdf,docx,xlsx', 'max:5120'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ];
    }
}
