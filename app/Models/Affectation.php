<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Affectation extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'chauffeur_id',
        'date_debut',
        'date_fin',
        'statut',
        'motif',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'datetime',
            'date_fin' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // Règle métier US7 : un véhicule (ou un chauffeur) déjà affecté sur une
        // affectation "active" ne peut pas en recevoir une seconde.
        static::creating(function (Affectation $affectation) {
            $conflitVehicule = self::where('vehicule_id', $affectation->vehicule_id)
                ->where('statut', 'active')
                ->exists();

            $conflitChauffeur = self::where('chauffeur_id', $affectation->chauffeur_id)
                ->where('statut', 'active')
                ->exists();

            if ($conflitVehicule || $conflitChauffeur) {
                throw ValidationException::withMessages([
                    'affectation' => "Ce véhicule ou ce chauffeur est déjà affecté sur une période en cours.",
                ]);
            }
        });
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function chauffeur()
    {
        return $this->belongsTo(Chauffeur::class);
    }

    public function cloturer(): void
    {
        $this->update([
            'statut' => 'terminee',
            'date_fin' => now(),
        ]);
    }
}
