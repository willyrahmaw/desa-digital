<?php

namespace App\Http\Requests;

use App\Models\DataSosial;
use App\Models\Penduduk;
use App\Models\TemplateSurat;
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
            'nomor_surat' => ['nullable', 'string', 'max:50'],
            'template_surat_id' => ['nullable', 'exists:template_surat,id'],
            'template_id' => ['nullable', 'exists:template_surat,id'],
            'penduduk_nik' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Penduduk::where('nik', $value)->exists()) {
                        $fail('Warga / NIK yang dipilih tidak terdaftar di basis data kependudukan.');
                    }
                }
            ],
            'keperluan' => ['required', 'string', 'max:255'],
            'form_data' => ['nullable'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $templateId = $this->input('template_surat_id') ?? $this->input('template_id');
            $nik = $this->input('penduduk_nik');

            if (!$templateId) {
                $validator->errors()->add('template_surat_id', 'Template surat wajib dipilih.');
                return;
            }

            if ($templateId && $nik) {
                $template = TemplateSurat::find($templateId);
                $jenisName = strtolower($template?->nama ?? '');
                $jenisKode = strtolower($template?->kategori_surat ?? $template?->kode_surat ?? '');

                if (str_contains($jenisName, 'tidak mampu') || str_contains($jenisName, 'sktm') || str_contains($jenisKode, 'sktm')) {
                    $dataSosial = DataSosial::where('penduduk_nik', $nik)->first();
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
