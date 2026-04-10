<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendeurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('profil_complet')->default(false);
            $table->string('coordonnees')->nullable();
            $table->string('rib')->nullable();
            $table->text('informations_legales')->nullable();
            $table->enum('statut_onboarding', ['EN_ATTENTE', 'VALIDE', 'REJETE'])->default('EN_ATTENTE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendeurs');
    }
};
