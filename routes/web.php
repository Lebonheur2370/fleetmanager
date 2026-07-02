<?php

use App\Http\Controllers\AffectationController;
use App\Http\Controllers\ChauffeurController;
use App\Http\Controllers\ChauffeurEspaceController;
use App\Http\Controllers\EntretienController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\PleinController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VehiculeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Epic 0 — Authentification (US0.1, US0.2, US0.3)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [UsersController::class, 'create'])->name('register');
    Route::post('/register', [UsersController::class, 'store']);
    Route::get('/login', [UsersController::class, 'login'])->name('login');
    Route::post('/login', [UsersController::class, 'authenticate']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [UsersController::class, 'logout'])->name('logout');

    Route::get('/mot-de-passe/changer', [PasswordChangeController::class, 'edit'])->name('password.change');
    Route::put('/mot-de-passe/changer', [PasswordChangeController::class, 'update'])->name('password.update');

    Route::get('/dashboard', function () {
        return auth()->user()->isAdmin()
            ? view('dashboard.admin')
            : view('dashboard.chauffeur');
    })->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Routes réservées à l'admin (Epics 1 à 6)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UsersController::class)->except(['create', 'store']);

        // Epic 1 — Véhicules
        Route::resource('vehicules', VehiculeController::class);

        // Epic 2 — Chauffeurs (créés par l'admin, identifiants auto-générés)
        Route::resource('chauffeurs', ChauffeurController::class);

        // Epic 3 — Affectations
        Route::resource('affectations', AffectationController::class)
            ->except(['edit', 'update']);
        Route::patch('affectations/{affectation}/cloturer', [AffectationController::class, 'cloturer'])
            ->name('affectations.cloturer');

        // Epic 4 — Entretiens
        Route::resource('entretiens', EntretienController::class);

        // Epic 5 — Carburant
        Route::resource('pleins', PleinController::class);

        // Epic 6 — Reporting
        Route::get('/rapports/depenses', [RapportController::class, 'depenses'])
            ->name('rapports.depenses');
        Route::get('/rapports/kilometrage', [RapportController::class, 'kilometrage'])
            ->name('rapports.kilometrage');
    });

    /*
    |----------------------------------------------------------------------
    | Routes réservées au chauffeur (espace personnel restreint)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:chauffeur')->prefix('mon-espace')->name('chauffeur.')->group(function () {
        Route::get('/affectation', [ChauffeurEspaceController::class, 'affectation'])
            ->name('affectation');
        Route::get('/pleins', [ChauffeurEspaceController::class, 'pleins'])
            ->name('pleins');
    });
});
