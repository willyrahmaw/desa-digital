<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePendudukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $nik = $this->route('penduduk') ?? $this->penduduk;
        return [
            'nik' => ['required', 'string', 'size:16', 'unique:penduduk,nik,' . $nik . ',nik'],
            'no_kk' => ['nullable', 'string', 'size:16', 'exists:kartu_keluarga,no_kk'],
            'nama' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'agama_id' => ['required', 'exists:agama,id'],
            'status_kawin_id' => ['required', 'exists:status_kawin,id'],
            'pendidikan_id' => ['required', 'exists:pendidikan,id'],
            'pekerjaan_id' => ['required', 'exists:pekerjaan,id'],
            'alamat' => ['required', 'string'],
            'dusun_id' => ['required', 'exists:dusun,id'],
            'rw_id' => ['required', 'exists:rw,id'],
            'rt_id' => ['required', 'exists:rt,id'],
            'nomor_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'status_tinggal_id' => ['required', 'exists:status_tinggal,id'],
            'kewarganegaraan_id' => ['required', 'exists:kewarganegaraan,id'],
            'golongan_darah_id' => ['required', 'exists:golongan_darah,id'],
            'foto_file' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus tepat 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'no_kk.exists' => 'Nomor KK tidak terdaftar.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'agama_id.required' => 'Agama wajib dipilih.',
            'status_kawin_id.required' => 'Status perkawinan wajib dipilih.',
            'pendidikan_id.required' => 'Pendidikan wajib dipilih.',
            'pekerjaan_id.required' => 'Pekerjaan wajib dipilih.',
            'alamat.required' => 'Alamat wajib diisi.',
            'dusun_id.required' => 'Dusun wajib dipilih.',
            'rw_id.required' => 'RW wajib dipilih.',
            'rt_id.required' => 'RT wajib dipilih.',
            'status_tinggal_id.required' => 'Status tinggal wajib dipilih.',
            'kewarganegaraan_id.required' => 'Kewarganegaraan wajib dipilih.',
            'golongan_darah_id.required' => 'Golongan darah wajib dipilih.',
            'foto_file.image' => 'File harus berupa foto gambar.',
            'foto_file.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
