<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entretien extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'type',
        'date_entretien',
        'kilometrage',
        'cout',
        'description',
        'prochain_kilometrage_seuil',
        'prochaine_date_prevue',
    ];

    protected function casts(): array
    {
        return [
            'date_entretien' => 'date',
            'prochaine_date_prevue' => 'date',
            'cout' => 'decimal:2',
        ];
    }

    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }
}
