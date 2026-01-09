<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Service de gestion des données data.gouv.fr
 * Gère la récupération et le filtrage des formations BTS du numérique
 */
class DataGouvService
{
    // URL de l'API data.gouv.fr pour les effectifs BTS
    private const API_EFFECTIFS = 'https://data.education.gouv.fr/api/explore/v2.1/catalog/datasets/fr-en-lycee_pro-effectifs-niveau-sexe-mef/records';
    
    /**
     * Récupère les BTS du numérique avec filtrage API direct sur "BTS"
     * Par défaut, l'API filtre déjà sur les formations BTS avant traitement PHP
     * 
     * @param string|null $year Format: "2024" pour année scolaire 2024-2025
     * @return \Illuminate\Support\Collection Collection des formations BTS numérique
     */
    public function getBTSNumerique(?string $year = null)
    {
        // Année par défaut = la plus récente
        $annee = $year ?? '2024';
        $cacheKey = "bts_numerique_{$annee}";
        
        // Cache de 24h pour éviter trop d'appels API
        return Cache::remember($cacheKey, 86400, function() use ($annee) {
            try {
                Log::info("🔍 Appel API data.gouv.fr pour BTS numérique année {$annee}");
                
                // Construction du filtre WHERE pour l'API
                // On filtre directement côté API pour réduire le volume de données
                $whereConditions = [
                    "rentree_scolaire=\"{$annee}\"",
                    // Filtre sur BTS uniquement (optimisation API)
                    "(mef_bcp_11_lib_l LIKE \"%BTS%\" OR mef_bcp_6_lib_l LIKE \"%BTS%\")"
                ];
                
                // Récupération par pagination pour gérer le grand volume
                $allRecords = collect([]);
                $limit = 100; // Nombre d'enregistrements par page
                $offset = 0;
                $hasMore = true;
                
                // Boucle de pagination pour récupérer tous les BTS
                while ($hasMore) {
                    $response = Http::timeout(60)
                        ->retry(3, 100) // 3 tentatives en cas d'échec
                        ->get(self::API_EFFECTIFS, [
                            'limit' => $limit,
                            'offset' => $offset,
                            'where' => implode(' AND ', $whereConditions),
                            'timezone' => 'Europe/Paris'
                        ]);
                    
                    // Vérification de la réponse API
                    if (!$response->successful()) {
                        Log::error('❌ Erreur API effectifs', [
                            'status' => $response->status(),
                            'body' => $response->body()
                        ]);
                        break;
                    }
                    
                    $data = $response->json();
                    $records = collect($data['results'] ?? []);
                    
                    // Si aucun résultat, on arrête la pagination
                    if ($records->isEmpty()) {
                        $hasMore = false;
                    } else {
                        // Ajout des résultats au total
                        $allRecords = $allRecords->merge($records);
                        $offset += $limit;
                        
                        Log::info("📦 Page récupérée: {$records->count()} enregistrements (total: {$allRecords->count()})");
                        
                        // Limite de sécurité : max 1000 enregistrements
                        // Évite les timeouts et surcharge mémoire
                        if ($allRecords->count() >= 1000) {
                            Log::warning('⚠️ Limite de 1000 enregistrements atteinte');
                            $hasMore = false;
                        }
                    }
                }
                
                Log::info("✅ Total BTS récupérés depuis API: {$allRecords->count()}");
                
                // Filtrage sur "numérique" après récupération
                // On applique nos critères métier pour identifier les BTS du numérique
                $filtered = $allRecords->filter(function($record) {
                    return $this->isNumerique($record);
                });
                
                Log::info("🎯 Après filtrage numérique: {$filtered->count()} formations");
                
                return $filtered;
                
            } catch (\Exception $e) {
                // Gestion des erreurs avec logs détaillés
                Log::error('💥 Exception lors de l\'appel API effectifs', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return collect([]);
            }
        });
    }
    
    /**
     * Vérifie si une formation est considérée comme "numérique"
     * Basé sur l'analyse du libellé de formation (mef_bcp_11_lib_l)
     * Inclut à la fois les BTS strictement numérique et ceux "adjacent" au numérique
     * 
     * @param array $record Enregistrement de formation depuis l'API
     * @return bool True si la formation est numérique, False sinon
     */
    private function isNumerique(array $record): bool
    {
        // Extraction et normalisation du libellé de formation
        $libelle = strtolower($record['mef_bcp_11_lib_l'] ?? '');
        
        if (empty($libelle)) {
            return false;
        }
        
        // Liste des mots-clés identifiant une formation numérique
        // Divisé en deux catégories : strictement numérique et adjacent
        $keywords = [
            // === Strictement numérique ===
            'informatique',
            'informatiq', // Pour capturer "informatiq..." sans accent
            'numérique',
            'numerique',
            'digital',
            'web',
            'développement',
            'systèmes numériques',
            'système numérique',
            'réseaux',
            'réseau',
            'cybersécurité',
            'cybersecurite',
            'data',
            'cloud',
            
            // === Codes BTS spécifiques ===
            'sio',  // Services Informatiques aux Organisations
            'snir', // Systèmes Numériques option Informatique et Réseaux
            'snec', // Systèmes Numériques option Électronique et Communication
            'ciel', // Cybersécurité, Informatique et réseaux, ÉLectronique
            
            // === Adjacent au numérique ===
            // Formations avec forte dimension numérique/digitale
            'audiovisuel',
            'communication digitale',
            'communication numérique',
            'multimédia',
            'automatique',
            'électronique',
            'electronique',
            'informatique de gestion',
            'traitement de l\'image',
            'photographie',
            'design graphique',
            'animation 3d',
        ];
        
        // Recherche de correspondance avec les mots-clés
        foreach ($keywords as $keyword) {
            if (str_contains($libelle, $keyword)) {
                // Log en mode debug pour traçabilité (désactivable en prod)
                Log::debug("✓ Formation numérique détectée", [
                    'libelle' => $libelle,
                    'keyword_match' => $keyword
                ]);
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Récupère la liste UNIQUE des BTS (DISTINCT sur nom de formation)
     * Élimine les doublons d'établissements pour ne garder que les types de BTS
     * Agrège les statistiques par type de BTS
     * 
     * @param string|null $year Année scolaire
     * @return \Illuminate\Support\Collection Collection des BTS uniques avec stats agrégées
     */
    public function getBTSUniques(?string $year = null)
    {
        // Récupération de toutes les formations (avec doublons établissements)
        $formations = $this->getBTSNumerique($year);
        
        // Groupement par nom de BTS (mef_bcp_6_lib_l = nom générique)
        // Chaque groupe contient tous les établissements proposant ce BTS
        return $formations
            ->groupBy('mef_bcp_6_lib_l')
            ->map(function($group, $nomBTS) {
                $premier = $group->first();
                
                // Calcul du pourcentage de filles
                $totalEleves = $group->sum('nombre_d_eleves_total');
                $totalFilles = $group->sum('nombre_d_eleves_filles');
                $pourcentageFilles = $totalEleves > 0 
                    ? round(($totalFilles / $totalEleves) * 100, 1)
                    : 0;
                
                // Agrégation des données du groupe
                return [
                    'nom_bts' => $nomBTS,
                    'code_mef' => $premier['mef_bcp_6'] ?? null,
                    'nombre_etablissements' => $group->count(),
                    'total_eleves' => $totalEleves,
                    'total_filles' => $totalFilles,
                    'total_garcons' => $group->sum('nombre_d_eleves_garcons'),
                    'pourcentage_filles' => $pourcentageFilles,
                    // Liste des académies proposant ce BTS
                    'academies' => $group->pluck('academie_2020_lib_l')->unique()->values(),
                    // Nombre de villes différentes
                    'nombre_villes' => $group->pluck('commune_d_implantation')->unique()->count(),
                    // Statuts des établissements (Public/Privé)
                    'statuts' => $group->pluck('secteur_d_enseignement_lib_l')->unique()->values(),
                ];
            })
            ->values()
            ->sortBy('nom_bts'); // Tri alphabétique
    }
    
    /**
     * Récupère tous les établissements proposant un BTS spécifique
     * Utilisé pour voir le détail d'un BTS avec tous ses lieux de formation
     * 
     * @param string $nomBTS Nom exact du BTS (mef_bcp_6_lib_l)
     * @param string|null $year Année scolaire
     * @return \Illuminate\Support\Collection Liste des établissements
     */
    public function getEtablissementsPourBTS(string $nomBTS, ?string $year = null)
    {
        $formations = $this->getBTSNumerique($year);
        
        return $formations->filter(function($record) use ($nomBTS) {
            return ($record['mef_bcp_6_lib_l'] ?? '') === $nomBTS;
        })->values();
    }
    
    /**
     * Récupère la liste des académies disponibles
     * Utilisé pour les filtres de recherche
     * 
     * @param string|null $year Année scolaire
     * @return \Illuminate\Support\Collection Liste triée des académies
     */
    public function getAcademies(?string $year = null): \Illuminate\Support\Collection
    {
        $formations = $this->getBTSNumerique($year);
        
        return $formations
            ->pluck('academie_2020_lib_l')
            ->filter() // Supprime les valeurs null
            ->unique()
            ->sort()
            ->values();
    }
    
    /**
     * Récupère la liste des villes disponibles
     * Utilisé pour les filtres de recherche géographique
     * 
     * @param string|null $year Année scolaire
     * @return \Illuminate\Support\Collection Liste triée des villes
     */
    public function getVilles(?string $year = null): \Illuminate\Support\Collection
    {
        $formations = $this->getBTSNumerique($year);
        
        return $formations
            ->pluck('commune_d_implantation')
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }
    
    /**
     * Retourne les années scolaires disponibles dans l'API
     * Liste hardcodée basée sur les données disponibles
     * 
     * @return array Liste des années (format string)
     */
    public function getAnneesDisponibles(): array
    {
        return ['2024', '2023', '2022', '2021', '2020', '2019'];
    }
    
    /**
     * Calcule les statistiques globales pour une année
     * Dashboard avec KPIs principaux
     * 
     * @param string|null $year Année scolaire
     * @return array Tableau associatif avec toutes les stats
     */
    public function getStatistiques(?string $year = null): array
    {
        $formations = $this->getBTSNumerique($year);
        
        // Calcul du total d'élèves pour pourcentage
        $totalEleves = $formations->sum('nombre_d_eleves_total');
        $totalFilles = $formations->sum('nombre_d_eleves_filles');
        
        return [
            'total_formations' => $formations->count(),
            'total_eleves' => $totalEleves,
            'total_filles' => $totalFilles,
            'total_garcons' => $formations->sum('nombre_d_eleves_garcons'),
            'pourcentage_filles' => $totalEleves > 0
                ? round(($totalFilles / $totalEleves) * 100, 1)
                : 0,
            'nombre_academies' => $this->getAcademies($year)->count(),
            'nombre_villes' => $this->getVilles($year)->count(),
        ];
    }
    
    /**
     * Statistiques spécifiques aux BTS uniques (sans doublons établissements)
     * Inclut les BTS les plus/moins demandés et les plus/moins féminisés
     * 
     * @param string|null $year Année scolaire
     * @return array Stats agrégées + tops/flops
     */
    public function getStatistiquesBTSUniques(?string $year = null): array
    {
        $btsUniques = $this->getBTSUniques($year);
        
        return [
            'nombre_bts_distincts' => $btsUniques->count(),
            'total_etablissements' => $btsUniques->sum('nombre_etablissements'),
            'total_eleves' => $btsUniques->sum('total_eleves'),
            // BTS avec le plus d'élèves
            'bts_plus_demande' => $btsUniques->sortByDesc('total_eleves')->first(),
            // BTS avec le plus fort % de filles
            'bts_plus_feminise' => $btsUniques->sortByDesc('pourcentage_filles')->first(),
            // BTS avec le plus faible % de filles
            'bts_moins_feminise' => $btsUniques->sortBy('pourcentage_filles')->first(),
        ];
    }
    
    /**
     * Recherche avancée avec filtres multiples
     * Permet de combiner plusieurs critères de recherche
     * 
     * @param array $filters Tableau des filtres ['year', 'academie', 'ville', 'statut', 'formation']
     * @return \Illuminate\Support\Collection Résultats filtrés
     */
    public function searchFormations(array $filters): \Illuminate\Support\Collection
    {
        // Récupération de base avec année
        $formations = $this->getBTSNumerique($filters['year'] ?? null);
        
        // Filtre par académie (recherche partielle, insensible à la casse)
        if (!empty($filters['academie'])) {
            $formations = $formations->filter(function($record) use ($filters) {
                return str_contains(
                    strtolower($record['academie_2020_lib_l'] ?? ''),
                    strtolower($filters['academie'])
                );
            });
        }
        
        // Filtre par ville (recherche partielle)
        if (!empty($filters['ville'])) {
            $formations = $formations->filter(function($record) use ($filters) {
                return str_contains(
                    strtolower($record['commune_d_implantation'] ?? ''),
                    strtolower($filters['ville'])
                );
            });
        }
        
        // Filtre par statut établissement (Public/Privé)
        if (!empty($filters['statut'])) {
            $formations = $formations->filter(function($record) use ($filters) {
                return str_contains(
                    strtolower($record['secteur_d_enseignement_lib_l'] ?? ''),
                    strtolower($filters['statut'])
                );
            });
        }
        
        // Filtre par nom de formation (recherche texte libre)
        if (!empty($filters['formation'])) {
            $formations = $formations->filter(function($record) use ($filters) {
                return str_contains(
                    strtolower($record['mef_bcp_11_lib_l'] ?? ''),
                    strtolower($filters['formation'])
                );
            });
        }
        
        // Réindexation de la collection pour éviter les trous d'index
        return $formations->values();
    }
    
    /**
     * Vide le cache pour forcer le rechargement des données
     * À utiliser via commande artisan ou cron nocturne
     * Permet de rafraîchir les données sans redémarrer l'app
     * 
     * @return void
     */
    public function clearCache(): void
    {
        $annees = $this->getAnneesDisponibles();
        
        // Suppression du cache pour toutes les années
        foreach ($annees as $annee) {
            Cache::forget("bts_numerique_{$annee}");
        }
        
        Log::info('🧹 Cache API nettoyé pour toutes les années');
    }
}