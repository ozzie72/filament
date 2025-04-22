<?php

namespace App\Traits;

use App\Models\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function (Model $model) {
            self::registerLog($model, 'CREATED');
        });

        static::updated(function (Model $model) {
            self::registerLog($model, 'UPDATED');
        });

        static::deleted(function (Model $model) {
            self::registerLog($model, 'DELETED');
        });

        // Para SoftDeletes
        if (method_exists(static::class, 'restored')) {
            static::restored(function (Model $model) {
                self::registerLog($model, 'RESTORED');
            });
        }
    }

    protected static function registerLog(Model $model, string $operation)
    {
        if (Auth::check()) {
            $details = [
                'model' => get_class($model),
                'id' => $model->id,
                'attributes' => $model->getAttributes(),
                'old' => $model->getOriginal() ?? [],
            ];

            Log::create([
                'user_id' => Auth::id(),
                'operation' => $operation,
                'ip' => Request::ip(),
                'method' => Request::method(),
                'url' => Request::fullUrl(),
                'type' => class_basename($model),
                'details' => json_encode($details),
            ]);
        }
    }
}