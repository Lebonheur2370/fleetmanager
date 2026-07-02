<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| US10 — Recevoir une alerte avant entretien
|--------------------------------------------------------------------------
| Vérification quotidienne des véhicules approchant du seuil kilométrique
| ou de la date d'entretien prévue. La commande "entretiens:verifier-alertes"
| est à créer (app/Console/Commands) par toi côté back — elle doit :
|   1. Parcourir les entretiens ayant prochain_kilometrage_seuil ou
|      prochaine_date_prevue renseignés
|   2. Comparer au kilométrage actuel du véhicule / à la date du jour
|   3. Déclencher une notification (email/DB) au gestionnaire si le seuil
|      est atteint ou dépassé
*/
Schedule::command('entretiens:verifier-alertes')->dailyAt('06:00');
