<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->boolean('actif')->default(true)->after('stock');
            $table->string('reference')->nullable()->after('nom');
            $table->float('poids_kg')->nullable()->after('prix');
            $table->string('dimensions')->nullable()->after('poids_kg');   // e.g. "1200x800x1600mm"
            $table->string('categorie')->nullable()->after('dimensions');  // panneau solaire, onduleur, batterie…
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['actif', 'reference', 'poids_kg', 'dimensions', 'categorie']);
        });
    }
};
