<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rig',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'activo'   => 'boolean',
        ];
    }

    public function reportes()
    {
        return $this->hasMany(MorningReport::class, 'creado_por');
    }

    public function esAdmin(): bool
    {
        return $this->hasRole('ADMIN');
    }

    public function esRigManager(): bool
    {
        return $this->hasRole('RIG_MANAGER');
    }
}
