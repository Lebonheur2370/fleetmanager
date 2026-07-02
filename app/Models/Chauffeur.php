<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chauffeur extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'matricule',
        'nom',
        'prenom',
        'numero_permis',
        'date_expiration_permis',
        'telephone',
        'disponibilite',
    ];

    protected function casts(): array
    {
        return [
            'date_expiration_permis' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function affectations()
    {
        return $this->hasMany(Affectation::class);
    }

    public function affectationActive()
    {
        return $this->hasOne(Affectation::class)->where('statut', 'active');
    }

    public function pleins()
    {
        return $this->hasMany(Plein::class);
    }

    public function nomComplet(): string
    {
        return "{$this->prenom} {$this->nom}";
    }
}
