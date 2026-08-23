<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'no_surat' => ['nullable', 'string', 'max:50'],
            'template_surat_id' => ['required', 'exists:template_surat,id'],
            'penduduk_nik' => ['required', 'exists:penduduk,nik'],
            'keperluan' => ['required', 'string', 'max:255'],
            'form_data' => ['nullable', 'json'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $templateId = $this->input('template_surat_id');
            $nik = $this->input('penduduk_nik');

            if ($templateId && $nik) {
                $template = \App\Models\TemplateSurat::find($templateId);
                $jenisName = strtolower($template?->nama ?? '');
                $jenisKode = strtolower($template?->kategori_surat ?? $template?->kode_surat ?? '');

                if (str_contains($jenisName, 'tidak mampu') || str_contains($jenisName, 'sktm') || str_contains($jenisKode, 'sktm')) {
                    $dataSosial = \App\Models\DataSosial::where('penduduk_nik', $nik)->first();
                    if ($dataSosial && isset($dataSosial->layak_sktm) && !$dataSosial->layak_sktm) {
                        $validator->errors()->add(
                            'template_surat_id',
                            'Pengajuan ditolak: Warga ini terdaftar TIDAK LAYAK SKTM pada Master Data Sosial Desa.'
                        );
                    }
                }
            }
        });
    }
}
