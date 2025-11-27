<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            AuditLog::log('created', $model, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            if ($model->wasChanged()) {
                AuditLog::log(
                    'updated',
                    $model,
                    $model->getOriginal(),
                    $model->getChanges()
                );
            }
        });

        static::deleted(function ($model) {
            AuditLog::log('deleted', $model, $model->getAttributes(), null);
        });
    }
}
