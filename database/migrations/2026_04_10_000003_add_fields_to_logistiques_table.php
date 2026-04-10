<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
     * Enrichit la table logistiques pour supporter :
     *  - palettes lourdes (poids > 800 kg, 1.2m x 2.2m)
     *  - import de matrice CSV (zone/tarif/poids/délai)
     *  - granularité min/max poids par ligne tarifaire
     */
    public function up(): void
    {
        Schema::table('logistiques', function (Blueprint $table) {
            // Fourchette de poids (la colonne `poids` existante devient poids_max)
            $table->float('poids_min')->default(0)->after('zone');
            // poids et prix restent ; on ajoute :
            $table->float('tarif_par_kg')->nullable()->after('prix');
            $table->unsignedInteger('delai_jours')->nullable()->after('tarif_par_kg');
            $table->string('description')->nullable()->after('delai_jours');
            // incoterm existe déjà, on le rend nullable
            $table->string('incoterm')->nullable()->change();
            // Indicateur de source (manual | import)
            $table->enum('source', ['manual', 'import'])->default('manual')->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('logistiques', function (Blueprint $table) {
            $table->dropColumn(['poids_min', 'tarif_par_kg', 'delai_jours', 'description', 'source']);
            $table->string('incoterm')->nullable(false)->change();
        });
    }
};
