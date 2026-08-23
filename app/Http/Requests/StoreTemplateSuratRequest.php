<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemplateSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('template_surat') ?? $this->template_surat;
        return [
            'nama' => ['required', 'string', 'max:100'],
            'kode_surat' => ['required', 'string', 'max:50', 'unique:template_surat,kategori_surat,' . $id],
            'canvas_json' => ['required', 'string'],
            'margin_top' => ['nullable', 'integer', 'min:0', 'max:100'],
            'margin_bottom' => ['nullable', 'integer', 'min:0', 'max:100'],
            'margin_left' => ['nullable', 'integer', 'min:0', 'max:100'],
            'margin_right' => ['nullable', 'integer', 'min:0', 'max:100'],
            'dengan_kop' => ['boolean'],
            'status_aktif' => ['boolean'],
            'format_nomor_surat' => ['nullable', 'string', 'max:150'],
            'kop_line_1' => ['nullable', 'string', 'max:150'],
            'kop_line_2' => ['nullable', 'string', 'max:150'],
            'kop_line_3' => ['nullable', 'string', 'max:150'],
            'kop_alamat' => ['nullable', 'string', 'max:200'],
            'kop_kontak' => ['nullable', 'string', 'max:200'],
        ];
    }
}
