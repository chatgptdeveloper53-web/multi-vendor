<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendeurs', function (Blueprint $table) {

            /*── Identité entreprise (étape 1) ─────────────────────────*/
            $table->string('raison_sociale')->nullable()->after('user_id');
            $table->string('forme_juridique')->nullable()->after('raison_sociale');
            $table->string('siret', 14)->nullable()->after('forme_juridique');
            $table->string('numero_tva', 20)->nullable()->after('siret');
            $table->boolean('tva_verifiee')->default(false)->after('numero_tva');
            $table->string('pays', 2)->default('FR')->after('tva_verifiee');
            $table->string('site_web')->nullable()->after('pays');
            $table->string('telephone', 20)->nullable()->after('site_web');

            /*── Représentant légal (étape 2) ───────────────────────────*/
            $table->string('nom_dirigeant')->nullable()->after('telephone');
            $table->string('fonction_dirigeant')->nullable()->after('nom_dirigeant');
            $table->string('email_commercial')->nullable()->after('fonction_dirigeant');

            /*── Logistique (étape 4) ───────────────────────────────────*/
            $table->string('incoterm_preference')->nullable()->after('informations_legales');
            $table->text('incoterm_notes')->nullable()->after('incoterm_preference');
            $table->unsignedInteger('moq')->nullable()->after('incoterm_notes');
            $table->unsignedInteger('delai_traitement_jours')->nullable()->after('moq');
            $table->text('politique_retour')->nullable()->after('delai_traitement_jours');
            $table->string('matrice_transport_fichier')->nullable()->after('politique_retour');

            /*── Progression wizard ─────────────────────────────────────*/
            $table->unsignedTinyInteger('etape_onboarding')->default(1)->after('statut_onboarding');
        });
    }

    public function down(): void
    {
        Schema::table('vendeurs', function (Blueprint $table) {
            $table->dropColumn([
                'raison_sociale', 'forme_juridique', 'siret', 'numero_tva', 'tva_verifiee',
                'pays', 'site_web', 'telephone',
                'nom_dirigeant', 'fonction_dirigeant', 'email_commercial',
                'incoterm_preference', 'incoterm_notes', 'moq',
                'delai_traitement_jours', 'politique_retour', 'matrice_transport_fichier',
                'etape_onboarding',
            ]);
        });
    }
};
