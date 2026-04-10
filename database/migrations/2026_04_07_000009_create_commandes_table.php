<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('date_commande')->useCurrent();
            $table->enum('statut', ['EN_COURS', 'LIVREE', 'ANNULEE'])->default('EN_COURS');
            $table->timestamps();
        });

        Schema::create('commande_produit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite')->default(1);
            $table->float('prix_unitaire');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande_produit');
        Schema::dropIfExists('commandes');
    }
};
