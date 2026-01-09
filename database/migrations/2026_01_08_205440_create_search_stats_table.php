<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table pour enregistrer les statistiques de recherche
     * Permet de tracker les formations les plus recherchées
     */
    public function up(): void
    {
        Schema::create('search_stats', function (Blueprint $table) {
            $table->id();
            
            // Utilisateur qui a fait la recherche (nullable pour stats anonymes)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Critères de recherche
            $table->string('formation')->nullable();
            $table->string('academie')->nullable();
            $table->string('ville')->nullable();
            $table->string('statut')->nullable(); // Public/Privé
            $table->string('year')->default('2024');
            
            // Informations démographiques du chercheur
            $table->enum('sexe_rechercheur', ['M', 'F', 'Autre', 'Non précisé'])->default('Non précisé');
            
            // Résultats de la recherche
            $table->integer('nombre_resultats')->default(0);
            
            // Métadonnées
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            // Index pour les requêtes fréquentes
            $table->index('formation');
            $table->index('academie');
            $table->index('created_at');
        });
    }

    /**
     * Supprime la table
     */
    public function down(): void
    {
        Schema::dropIfExists('search_stats');
    }
};