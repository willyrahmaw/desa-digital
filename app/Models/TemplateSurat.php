<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateSurat extends Model
{
    protected $table = 'template_surat';

    protected $fillable = [
        'nama',
        'kategori_surat',
        'kode_surat',
        'content',
        'canvas_json',
        'margin_top',
        'margin_bottom',
        'margin_left',
        'margin_right',
        'dengan_kop',
        'is_active',
        'status_aktif',
        'format_nomor_surat',
        'kop_line_1',
        'kop_line_2',
        'kop_line_3',
        'kop_alamat',
        'kop_kontak',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'dengan_kop' => 'boolean',
        'margin_top' => 'integer',
        'margin_bottom' => 'integer',
        'margin_left' => 'integer',
        'margin_right' => 'integer',
    ];

    public function getCanvasJsonAttribute()
    {
        return $this->attributes['content'] ?? '';
    }

    public function setCanvasJsonAttribute($value)
    {
        $this->attributes['content'] = $value;
    }

    public function getStatusAktifAttribute()
    {
        return $this->attributes['is_active'] ?? true;
    }

    public function setStatusAktifAttribute($value)
    {
        $this->attributes['is_active'] = (bool)$value;
    }

    public function getKodeSuratAttribute()
    {
        return $this->attributes['kategori_surat'] ?? '';
    }

    public function setKodeSuratAttribute($value)
    {
        $this->attributes['kategori_surat'] = $value;
    }

    public function getNextNomorSurat(): string
    {
        $format = $this->format_nomor_surat ?? '[NOMOR]/[KODE]/[BULAN]/[TAHUN]';
        
        $count = \App\Models\Surat::where('template_id', $this->id)
            ->whereYear('created_at', date('Y'))
            ->count();
            
        $nextNum = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        
        $replacements = [
            '[NOMOR]' => $nextNum,
            '[KODE]' => $this->kode_surat,
            '[BULAN]' => date('m'),
            '[TAHUN]' => date('Y'),
        ];
        
        return str_replace(array_keys($replacements), array_values($replacements), $format);
    }

    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class, 'template_id');
    }
}
