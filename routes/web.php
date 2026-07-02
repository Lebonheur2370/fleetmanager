<?php

use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\UsersController;
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
        Route::resource('vehicules', \App\Http\Controllers\VehiculeController::class);

        // Epic 2 — Chauffeurs (créés par l'admin, identifiants auto-générés)
        Route::resource('chauffeurs', \App\Http\Controllers\ChauffeurController::class);

        // Epic 3 — Affectations
        Route::resource('affectations', \App\Http\Controllers\AffectationController::class)
            ->except(['edit', 'update']);
        Route::patch('affectations/{affectation}/cloturer', [\App\Http\Controllers\AffectationController::class, 'cloturer'])
            ->name('affectations.cloturer');

        // Epic 4 — Entretiens
        Route::resource('entretiens', \App\Http\Controllers\EntretienController::class);

        // Epic 5 — Carburant
        Route::resource('pleins', \App\Http\Controllers\PleinController::class);

        // Epic 6 — Reporting
        Route::get('/rapports/depenses', [\App\Http\Controllers\RapportController::class, 'depenses'])
            ->name('rapports.depenses');
        Route::get('/rapports/kilometrage', [\App\Http\Controllers\RapportController::class, 'kilometrage'])
            ->name('rapports.kilometrage');
    });

    /*
    |----------------------------------------------------------------------
    | Routes réservées au chauffeur (espace personnel restreint)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:chauffeur')->prefix('mon-espace')->name('chauffeur.')->group(function () {
        Route::get('/affectation', [\App\Http\Controllers\ChauffeurEspaceController::class, 'affectation'])
            ->name('affectation');
        Route::get('/pleins', [\App\Http\Controllers\ChauffeurEspaceController::class, 'pleins'])
            ->name('pleins');
    });
});
