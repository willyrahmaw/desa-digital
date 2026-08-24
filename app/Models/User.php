<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use App\Traits\HasAesEncryption;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasAuditLog, HasAesEncryption;

    protected $table = 'user';

    protected array $encrypted = [
        'penduduk_nik',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'penduduk_nik',
        'avatar',
        'status_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status_aktif' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_nik', 'nik');
    }

    public function perangkatDesa(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PerangkatDesa::class, 'user_id');
    }

    public function hasPermission(string $permissionName): bool
    {
        if (!$this->role) {
            return false;
        }
        
        // Super Admin gets all permissions
        if ($this->role->name === 'super-admin') {
            return true;
        }

        return $this->role->permissions()->where('name', $permissionName)->exists();
    }
}
