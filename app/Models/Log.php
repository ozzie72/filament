<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Log
 *
 * @property $id
 * @property $user_id
 * @property $operation
 * @property $ip
 * @property $method
 * @property $url
 * @property $type
 * @property $details
 * @property $created_at
 * @property $updated_at
 *
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Log extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 
        'operation', 
        'ip', 
        'method', 
        'url', 
        'type', 
        'details',
        'model_id',
        'model_type'
    ];
    
    protected $casts = [
        'details' => 'array',
    ];
    
    // Relación polimórfica para vincular con cualquier modelo
    public function loggable()
    {
        return $this->morphTo('model');
    }
    
    // Método para obtener el nombre legible del tipo de registro
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'country' => 'País',
            'user' => 'Usuario',
            default => ucfirst($this->type),
        };
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
    
}
