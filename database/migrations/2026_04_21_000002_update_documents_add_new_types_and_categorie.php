<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extend the ENUM to include new document types
        DB::statement("
            ALTER TABLE documents
            MODIFY COLUMN type ENUM(
                'ID',
                'KBIS',
                'CERTIFICAT_CE',
                'PPE2',
                'TVA',
                'GARANTIE_CONSTRUCTEUR',
                'RC_PRO',
                'STATUTS_SOCIETE',
                'PIECE_IDENTITE_DIRIGEANT',
                'RIB_BANCAIRE'
            ) NOT NULL
        ");

        // Add categorie column for certification categories (JSON array as string)
        Schema::table('documents', function (Blueprint $table) {
            $table->string('categorie', 500)->nullable()->after('commentaire_admin');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('categorie');
        });

        DB::statement("
            ALTER TABLE documents
            MODIFY COLUMN type ENUM(
                'ID',
                'KBIS',
                'CERTIFICAT_CE',
                'PPE2',
                'TVA',
                'GARANTIE_CONSTRUCTEUR',
                'RC_PRO'
            ) NOT NULL
        ");
    }
};
