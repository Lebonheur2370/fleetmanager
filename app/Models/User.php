<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'doit_changer_mot_de_passe',
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
            'doit_changer_mot_de_passe' => 'boolean',
        ];
    }

    public function chauffeur()
    {
        return $this->hasOne(Chauffeur::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isChauffeur(): bool
    {
        return $this->role === 'chauffeur';
    }
}
