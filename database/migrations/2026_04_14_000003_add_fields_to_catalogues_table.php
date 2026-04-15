<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catalogues', function (Blueprint $table) {
            $table->string('nom')->nullable()->after('vendeur_id');
            $table->text('description')->nullable()->after('nom');
            $table->boolean('actif')->default(true)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('catalogues', function (Blueprint $table) {
            $table->dropColumn(['nom', 'description', 'actif']);
        });
    }
};
