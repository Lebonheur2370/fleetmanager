<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'immatriculation',
        'marque',
        'modele',
        'annee',
        'kilometrage',
        'statut',
    ];

    public function affectations()
    {
        return $this->hasMany(Affectation::class);
    }

    public function affectationActive()
    {
        return $this->hasOne(Affectation::class)->where('statut', 'active');
    }

    public function entretiens()
    {
        return $this->hasMany(Entretien::class);
    }

    public function pleins()
    {
        return $this->hasMany(Plein::class);
    }

    /**
     * Consommation moyenne en L/100km sur l'ensemble des pleins enregistrés (US12).
     */
    public function consommationMoyenne(): ?float
    {
        $pleins = $this->pleins()->orderBy('kilometrage')->get();

        if ($pleins->count() < 2) {
            return null;
        }

        $distanceTotale = $pleins->last()->kilometrage - $pleins->first()->kilometrage;
        $litresTotal = $pleins->sum('litres');

        if ($distanceTotale <= 0) {
            return null;
        }

        return round(($litresTotal / $distanceTotale) * 100, 2);
    }

    /**
     * Dépenses cumulées (entretiens + carburant) pour US13.
     */
    public function depensesTotales(): float
    {
        return $this->entretiens()->sum('cout') + $this->pleins()->sum('montant');
    }
}
