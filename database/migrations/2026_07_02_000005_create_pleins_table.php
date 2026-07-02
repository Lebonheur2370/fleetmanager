<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pleins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chauffeur_id')->nullable()->constrained('chauffeurs')->nullOnDelete();

            $table->date('date_plein');
            $table->decimal('litres', 8, 2);
            $table->decimal('montant', 10, 2);
            $table->unsignedInteger('kilometrage'); // kilométrage du véhicule au moment du plein

            $table->timestamps();

            $table->index(['vehicule_id', 'date_plein']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pleins');
    }
};
