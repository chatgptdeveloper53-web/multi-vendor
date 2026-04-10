<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /*
     * Étend l'enum `type` de la table `documents` pour ajouter :
     *  - GARANTIE_CONSTRUCTEUR : garanties fabricant uploadées en masse
     *  - RC_PRO                : attestation d'assurance RC Professionnelle
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE documents
            MODIFY COLUMN type
            ENUM('ID','KBIS','CERTIFICAT_CE','PPE2','TVA','GARANTIE_CONSTRUCTEUR','RC_PRO')
            NOT NULL
        ");

        // Ajout d'un champ nom_fichier_original pour l'affichage UX + un champ statut plus lisible
        DB::statement("
            ALTER TABLE documents
            ADD COLUMN nom_original VARCHAR(255) NULL AFTER fichier,
            ADD COLUMN commentaire_admin TEXT NULL AFTER valide
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE documents
            MODIFY COLUMN type
            ENUM('ID','KBIS','CERTIFICAT_CE','PPE2','TVA')
            NOT NULL
        ");

        DB::statement("
            ALTER TABLE documents
            DROP COLUMN nom_original,
            DROP COLUMN commentaire_admin
        ");
    }
};
