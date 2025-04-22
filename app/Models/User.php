<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; 
use Spatie\Permission\Models\Role; 
use Spatie\Permission\Models\Permission; 

use App\Models\Client;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens; //

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
  
    
    protected $fillable = [
        'name',
        'username',
        'last_name',
        'email',
        'phone',
        'password',
        'country_id', 
        'state_id', 
        'city_id',
        'client_status',
        'user_status',
        'client_id',
        'password_change',
        'confirmed'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }


    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }


    /**
     * Scope for filtering users
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('username', 'like', '%'.$search.'%');
            });
        })->when($filters['client_id'] ?? null, function ($query, $clientId) {
            $query->where('client_id', $clientId);
        })->when($filters['role'] ?? null, function ($query, $role) {
            $query->role($role);
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('user_status', $status);
        });
    }


    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    public function markEmailAsVerified()
    {
        $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'confirmed' => 1 // Actualiza tu campo personalizado
        ])->save();

        return true;
    }


}
