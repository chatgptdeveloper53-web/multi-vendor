<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logistiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendeur_id')->constrained('vendeurs')->onDelete('cascade');
            $table->foreignId('catalogue_id')->nullable()->constrained('catalogues')->onDelete('set null');
            $table->string('zone');
            $table->float('poids');
            $table->float('prix');
            $table->string('incoterm');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logistiques');
    }
};
