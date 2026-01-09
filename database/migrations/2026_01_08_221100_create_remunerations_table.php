<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remunerations', function (Blueprint $table) {
            $table->id();
            $table->string('metier');
            $table->string('niveau');
            $table->integer('salaire_min');
            $table->integer('salaire_max');
            $table->integer('salaire_median');
            $table->timestamps();
            
            // Index pour recherche rapide
            $table->index('metier');
            $table->index('salaire_median');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remunerations');
    }
};