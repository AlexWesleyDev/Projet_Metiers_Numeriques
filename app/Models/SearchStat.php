<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour les statistiques de recherche
 * Enregistre chaque recherche effectuée sur la plateforme
 */
class SearchStat extends Model
{
    use HasFactory;

    /**
     * Champs remplissables en masse
     */
    protected $fillable = [
        'user_id',
        'formation',
        'academie',
        'ville',
        'statut',
        'year',
        'sexe_rechercheur',
        'nombre_resultats',
        'ip_address',
        'user_agent',
    ];

    /**
     * Relation : Une stat appartient à un utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope : Recherches récentes (7 derniers jours)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(7));
    }

    /**
     * Scope : Recherches d'une année spécifique
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Obtient les formations les plus recherchées
     */
    public static function topFormations($limit = 10)
    {
        return self::selectRaw('formation, COUNT(*) as count')
            ->whereNotNull('formation')
            ->groupBy('formation')
            ->orderByDesc('count')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtient les académies les plus recherchées
     */
    public static function topAcademies($limit = 10)
    {
        return self::selectRaw('academie, COUNT(*) as count')
            ->whereNotNull('academie')
            ->groupBy('academie')
            ->orderByDesc('count')
            ->limit($limit)
            ->get();
    }
}