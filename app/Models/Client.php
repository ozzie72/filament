<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Client
 *
 * @property $id
 * @property $company
 * @property $name
 * @property $last_name
 * @property $ip
 * @property $port
 * @property $server_user
 * @property $server_pass
 * @property $image
 * @property $status
 * 
 * @property $divition_id
 * @property $department_id
 * 
 * @property $country_id
 * @property $state_id
 * @property $city_id
 * 
 * @property $created_at
 * @property $updated_at
 *
 * @property Department $department
 * @property Divition $divition
 * 
 * @property Country $country
 * @property State $state
 * @property City $city
 * 
 * @property Campaign[] $campaigns
 * @property PhoneList[] $phoneLists
 * @property User[] $users
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
use App\Traits\Auditable;

class Client extends Model
{
    use Auditable;

    protected $perPage = 20;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company', 
        'name', 
        'last_name', 
        'ip', 
        'port', 
        'server_user', 
        'server_pass', 
        'image', 
        'status', 
        'divition_id', 
        'department_id', 
        'country_id', 
        'state_id', 
        'city_id'
    ];

    /**
     * Relación con División
     */
    public function divition()
    {
        return $this->belongsTo(Divition::class, 'divition_id');
    }

    /**
     * Relación con Departamento
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Relación con País
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * Relación con Estado/Provincia
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    /**
     * Relación con Ciudad
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Relación con Campañas
     * Corregido: La clave foránea correcta es 'client_id' en la tabla campaigns
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'client_id');
    }

    /**
     * Relación con Listas de Teléfonos
     * Corregido: La clave foránea correcta es 'client_id' en la tabla phone_lists
     */
    public function phoneLists()
    {
        return $this->hasMany(PhoneList::class, 'client_id');
    }

    /**
     * Relación con Usuarios
     * Corregido: La clave foránea correcta es 'client_id' en la tabla users
     */
    public function users()
    {
        return $this->hasMany(User::class, 'client_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($client) {
            // Eliminar usuarios relacionados
            $client->users()->delete();
            // Opcional: eliminar otras relaciones si es necesario
            // $client->campaigns()->delete();
            // $client->phoneLists()->delete();
        });
    }

    /**
     * Scope for filtering clients
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('company', 'like', '%'.$search.'%')
                    ->orWhere('name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%');
            });
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        })->when($filters['divition_id'] ?? null, function ($query, $divitionId) {
            $query->where('divition_id', $divitionId);
        })->when($filters['department_id'] ?? null, function ($query, $departmentId) {
            $query->where('department_id', $departmentId);
        });
    }    


}
