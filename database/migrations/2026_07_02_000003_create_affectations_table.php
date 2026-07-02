<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affectations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chauffeur_id')->constrained('chauffeurs')->cascadeOnDelete();

            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->enum('statut', ['active', 'terminee'])->default('active');
            $table->text('motif')->nullable();

            $table->timestamps();

            // Un véhicule ne doit avoir qu'une seule affectation "active" à la fois
            // (règle US7). MySQL ne supporte pas les index uniques partiels, donc
            // cette règle est appliquée au niveau du modèle (cf. Affectation::boot).
            $table->index(['vehicule_id', 'statut']);
            $table->index(['chauffeur_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};
