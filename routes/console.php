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
| Vérification quotidienne (app/Console/Commands/VerifierAlertesEntretiens.php)
| des véhicules approchant du seuil kilométrique ou de la date d'entretien
| prévue. Notifie chaque administrateur par email + notification en base
| (App\Notifications\AlerteEntretienNotification) via $admin->notify(...).
*/
Schedule::command('entretiens:verifier-alertes')->dailyAt('06:00');
