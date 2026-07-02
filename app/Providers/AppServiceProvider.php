<?php

namespace App\Providers;

use App\Support\EntretienAlerteHelper;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pastille d'alertes entretien (US10) affichée dans le header, sur
        // toutes les pages admin — calcul léger, ignoré pour les invités/chauffeurs.
        View::composer('layouts.app', function ($view) {
            $alertes = collect();

            if (auth()->check() && auth()->user()->isAdmin()) {
                $alertes = EntretienAlerteHelper::vehiculesConcernes();
            }

            $view->with('alertesEntretienHeader', $alertes);
        });
    }
}
