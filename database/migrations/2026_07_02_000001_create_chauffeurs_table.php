<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chauffeurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('matricule')->unique(); // ex: CHF-0007
            $table->string('nom');
            $table->string('prenom');
            $table->string('numero_permis')->unique();
            $table->date('date_expiration_permis')->nullable();
            $table->string('telephone');
            $table->enum('disponibilite', ['disponible', 'indisponible', 'en_conge'])
                ->default('disponible');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['nom', 'prenom']);
            $table->index('disponibilite');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chauffeurs');
    }
};
