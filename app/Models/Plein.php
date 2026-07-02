<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plein extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'chauffeur_id',
        'date_plein',
        'litres',
        'montant',
        'kilometrage',
    ];

    protected function casts(): array
    {
        return [
            'date_plein' => 'date',
            'litres' => 'decimal:2',
            'montant' => 'decimal:2',
        ];
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function chauffeur()
    {
        return $this->belongsTo(Chauffeur::class);
    }
}
