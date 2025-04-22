<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Divition
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Divition extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description'];

    // Relación con Client (una división tiene muchos clientes)
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    // Relación con Department (una división tiene muchos departamentos)
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

}
