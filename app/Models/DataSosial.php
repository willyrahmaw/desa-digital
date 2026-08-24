<?php

namespace App\Models;

use App\Traits\HasAesEncryption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataSosial extends Model
{
    use HasAesEncryption;

    protected $table = 'data_sosial';

    protected array $encrypted = [
        'penduduk_nik',
    ];

    protected $fillable = [
        'penduduk_nik',
        'dtks',
        'pkh',
        'bpnt',
        'pbi',
        'rtlh',
        'disabilitas',
        'lansia',
        'yatim_piatu',
        'status_ekonomi',
        'desil',
        'layak_sktm',
        'tanggal_verifikasi',
        'verifikator_id',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'dtks' => 'boolean',
            'pkh' => 'boolean',
            'bpnt' => 'boolean',
            'pbi' => 'boolean',
            'rtlh' => 'boolean',
            'lansia' => 'boolean',
            'yatim_piatu' => 'boolean',
            'layak_sktm' => 'boolean',
            'desil' => 'integer',
            'tanggal_verifikasi' => 'date',
        ];
    }

    public static array $desilKemensosMap = [
        1  => ['label' => 'Desil 1 - Sangat Miskin', 'desc' => '10% Rumah tangga dengan tingkat kesejahteraan terendah secara nasional (Prioritas Bansos PKH/BPNT).'],
        2  => ['label' => 'Desil 2 - Miskin', 'desc' => '10% Rumah tangga tingkat kesejahteraan 11% - 20% (Prioritas Penerima Bansos & PBI JK).'],
        3  => ['label' => 'Desil 3 - Hampir Miskin', 'desc' => '10% Rumah tangga tingkat kesejahteraan 21% - 30% (Berhak menerima BPNT & Layak SKTM).'],
        4  => ['label' => 'Desil 4 - Rentan Miskin', 'desc' => '10% Rumah tangga tingkat kesejahteraan 31% - 40% (Sasaran Perlindungan Sosial & JKN PBI).'],
        5  => ['label' => 'Desil 5 - Menengah Bawah', 'desc' => '10% Rumah tangga tingkat kesejahteraan 41% - 50% (Batas kecukupan dasar).'],
        6  => ['label' => 'Desil 6 - Menengah', 'desc' => '10% Rumah tangga tingkat kesejahteraan 51% - 60% (Kategori ekonomi menengah).'],
        7  => ['label' => 'Desil 7 - Menengah', 'desc' => '10% Rumah tangga tingkat kesejahteraan 61% - 70% (Kategori ekonomi menengah mandiri).'],
        8  => ['label' => 'Desil 8 - Menengah Atas', 'desc' => '10% Rumah tangga tingkat kesejahteraan 71% - 80% (Kategori mampu/berkecukupan).'],
        9  => ['label' => 'Desil 9 - Mampu', 'desc' => '10% Rumah tangga tingkat kesejahteraan 81% - 90% (Kategori ekonomi atas/kaya).'],
        10 => ['label' => 'Desil 10 - Paling Mampu', 'desc' => '10% Rumah tangga tingkat kesejahteraan tertinggi 91% - 100% (Sangat mampu/sangat kaya).'],
    ];

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_nik', 'nik');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }
}
