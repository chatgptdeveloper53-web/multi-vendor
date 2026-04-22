<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendeurs', function (Blueprint $table) {
            // Adresse siège social (étape 1 – Société)
            $table->string('adresse_siege', 500)->nullable()->after('telephone');

            // Logistique (étape 4)
            $table->string('adresse_expedition', 500)->nullable()->after('matrice_transport_fichier');
            $table->float('poids_max_palette')->nullable()->after('adresse_expedition'); // kg
        });
    }

    public function down(): void
    {
        Schema::table('vendeurs', function (Blueprint $table) {
            $table->dropColumn(['adresse_siege', 'adresse_expedition', 'poids_max_palette']);
        });
    }
};
