<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->string('immatriculation')->unique();
            $table->string('marque');
            $table->string('modele');
            $table->unsignedSmallInteger('annee')->nullable();
            $table->unsignedInteger('kilometrage')->default(0);
            $table->enum('statut', ['disponible', 'affecte', 'en_entretien', 'hors_service'])
                ->default('disponible');

            $table->timestamps();
            $table->softDeletes();

            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
