<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait HasAuditLog
{
    public static function bootHasAuditLog()
    {
        static::created(function ($model) {
            $model->logActivity('created', "Created record in " . $model->getTable() . " (ID: " . $model->getKey() . ")");
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            unset($dirty['password'], $dirty['updated_at'], $dirty['remember_token']);
            if (empty($dirty)) {
                return;
            }
            $payload = [
                'old' => array_intersect_key($model->getOriginal(), $dirty),
                'new' => $dirty
            ];
            $model->logActivity('updated', "Updated record in " . $model->getTable() . " (ID: " . $model->getKey() . ")", $payload);
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', "Deleted record from " . $model->getTable() . " (ID: " . $model->getKey() . ")");
        });
    }

    public function logActivity(string $action, string $description, ?array $payload = null)
    {
        if ($this->getTable() === 'activity_log') {
            return;
        }

        try {
            DB::table('activity_log')->insert([
                'user_id' => Auth::id(),
                'action' => $action,
                'description' => $description,
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent() ?? 'CLI',
                'payload' => $payload ? json_encode($payload) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silently fail if table does not exist yet during migration
        }
    }
}
