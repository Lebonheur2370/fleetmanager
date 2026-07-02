<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entretiens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained()->cascadeOnDelete();

            $table->string('type'); // vidange, révision, pneus, freins, etc.
            $table->date('date_entretien');
            $table->unsignedInteger('kilometrage');
            $table->decimal('cout', 10, 2)->default(0);
            $table->text('description')->nullable();

            // Pour US10 (alerte préventive)
            $table->unsignedInteger('prochain_kilometrage_seuil')->nullable();
            $table->date('prochaine_date_prevue')->nullable();

            $table->timestamps();

            $table->index(['vehicule_id', 'date_entretien']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entretiens');
    }
};
