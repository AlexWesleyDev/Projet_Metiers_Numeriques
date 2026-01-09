<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Service de gestion des données de rémunération
 * Récupère les salaires des métiers du numérique depuis data.gouv.fr
 */
class RemunerationService
{
    // URL de l'API rémunérations (à vérifier/ajuster selon l'API réelle)
    private const API_REMUNERATIONS = 'https://www.data.gouv.fr/fr/datasets/r/c2f6a9a7-b8ea-4f4e-9c8f-3b6d8e5f7c9a';
    
    /**
     * Récupère le référentiel complet des rémunérations
     * Avec cache de 7 jours (données peu volatiles)
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getRemunerations()
    {
        return Cache::remember('remunerations_metiers', 604800, function() {
            try {
                Log::info("🔍 Appel API rémunérations des métiers du numérique");
                
                $response = Http::timeout(30)
                    ->retry(2, 100)
                    ->get(self::API_REMUNERATIONS);
                
                if ($response->successful()) {
                    // Le format peut varier selon l'API
                    // À ajuster selon la structure réelle
                    $data = $response->json();
                    
                    Log::info("✅ Rémunérations récupérées");
                    
                    return collect($data);
                }
                
                Log::warning('⚠️ Erreur API rémunérations, utilisation des données par défaut');
                
                // Données par défaut si API indisponible
                return $this->getDefaultRemunerations();
                
            } catch (\Exception $e) {
                Log::error('💥 Exception API rémunérations', [
                    'error' => $e->getMessage()
                ]);
                
                return $this->getDefaultRemunerations();
            }
        });
    }
    
    /**
     * Données de rémunération par défaut (fallback)
     * Basées sur les moyennes du marché français 2024
     * 
     * @return \Illuminate\Support\Collection
     */
    private function getDefaultRemunerations()
    {
        return collect([
            [
                'metier' => 'Développeur Full Stack',
                'niveau' => 'Junior (0-2 ans)',
                'salaire_min' => 30000,
                'salaire_max' => 38000,
                'salaire_median' => 34000,
            ],
            [
                'metier' => 'Développeur Full Stack',
                'niveau' => 'Confirmé (2-5 ans)',
                'salaire_min' => 38000,
                'salaire_max' => 50000,
                'salaire_median' => 44000,
            ],
            [
                'metier' => 'Développeur Full Stack',
                'niveau' => 'Senior (5+ ans)',
                'salaire_min' => 50000,
                'salaire_max' => 70000,
                'salaire_median' => 60000,
            ],
            [
                'metier' => 'Administrateur Systèmes et Réseaux',
                'niveau' => 'Junior (0-2 ans)',
                'salaire_min' => 28000,
                'salaire_max' => 35000,
                'salaire_median' => 31500,
            ],
            [
                'metier' => 'Administrateur Systèmes et Réseaux',
                'niveau' => 'Confirmé (2-5 ans)',
                'salaire_min' => 35000,
                'salaire_max' => 48000,
                'salaire_median' => 41500,
            ],
            [
                'metier' => 'Administrateur Systèmes et Réseaux',
                'niveau' => 'Senior (5+ ans)',
                'salaire_min' => 48000,
                'salaire_max' => 65000,
                'salaire_median' => 56500,
            ],
            [
                'metier' => 'Technicien Support Informatique',
                'niveau' => 'Junior (0-2 ans)',
                'salaire_min' => 22000,
                'salaire_max' => 28000,
                'salaire_median' => 25000,
            ],
            [
                'metier' => 'Technicien Support Informatique',
                'niveau' => 'Confirmé (2-5 ans)',
                'salaire_min' => 28000,
                'salaire_max' => 35000,
                'salaire_median' => 31500,
            ],
            [
                'metier' => 'Data Analyst',
                'niveau' => 'Junior (0-2 ans)',
                'salaire_min' => 32000,
                'salaire_max' => 40000,
                'salaire_median' => 36000,
            ],
            [
                'metier' => 'Data Analyst',
                'niveau' => 'Confirmé (2-5 ans)',
                'salaire_min' => 40000,
                'salaire_max' => 55000,
                'salaire_median' => 47500,
            ],
            [
                'metier' => 'Data Analyst',
                'niveau' => 'Senior (5+ ans)',
                'salaire_min' => 55000,
                'salaire_max' => 75000,
                'salaire_median' => 65000,
            ],
            [
                'metier' => 'Développeur Web',
                'niveau' => 'Junior (0-2 ans)',
                'salaire_min' => 28000,
                'salaire_max' => 36000,
                'salaire_median' => 32000,
            ],
            [
                'metier' => 'Développeur Web',
                'niveau' => 'Confirmé (2-5 ans)',
                'salaire_min' => 36000,
                'salaire_max' => 48000,
                'salaire_median' => 42000,
            ],
            [
                'metier' => 'Développeur Web',
                'niveau' => 'Senior (5+ ans)',
                'salaire_min' => 48000,
                'salaire_max' => 65000,
                'salaire_median' => 56500,
            ],
            [
                'metier' => 'Chef de Projet Digital',
                'niveau' => 'Confirmé (2-5 ans)',
                'salaire_min' => 40000,
                'salaire_max' => 55000,
                'salaire_median' => 47500,
            ],
            [
                'metier' => 'Chef de Projet Digital',
                'niveau' => 'Senior (5+ ans)',
                'salaire_min' => 55000,
                'salaire_max' => 75000,
                'salaire_median' => 65000,
            ],
            [
                'metier' => 'Expert Cybersécurité',
                'niveau' => 'Junior (0-2 ans)',
                'salaire_min' => 35000,
                'salaire_max' => 45000,
                'salaire_median' => 40000,
            ],
            [
                'metier' => 'Expert Cybersécurité',
                'niveau' => 'Confirmé (2-5 ans)',
                'salaire_min' => 45000,
                'salaire_max' => 65000,
                'salaire_median' => 55000,
            ],
            [
                'metier' => 'Expert Cybersécurité',
                'niveau' => 'Senior (5+ ans)',
                'salaire_min' => 65000,
                'salaire_max' => 90000,
                'salaire_median' => 77500,
            ],
        ]);
    }
    
    /**
     * Recherche les métiers par fourchette de salaire
     * 
     * @param int $salaireMin Salaire minimum souhaité
     * @param int $salaireMax Salaire maximum souhaité
     * @return \Illuminate\Support\Collection
     */
    public function searchBySalaire(int $salaireMin, int $salaireMax = null)
    {
        $remunerations = $this->getRemunerations();
        
        return $remunerations->filter(function($metier) use ($salaireMin, $salaireMax) {
            $medianInRange = $metier['salaire_median'] >= $salaireMin;
            
            if ($salaireMax) {
                $medianInRange = $medianInRange && $metier['salaire_median'] <= $salaireMax;
            }
            
            return $medianInRange;
        });
    }
    
    /**
     * Obtient les métiers disponibles (liste unique)
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getMetiersDisponibles()
    {
        return $this->getRemunerations()
            ->pluck('metier')
            ->unique()
            ->sort()
            ->values();
    }
    
    /**
     * Obtient les détails d'un métier spécifique
     * 
     * @param string $nomMetier
     * @return \Illuminate\Support\Collection
     */
    public function getDetailsMetier(string $nomMetier)
    {
        return $this->getRemunerations()
            ->where('metier', $nomMetier)
            ->values();
    }
    
    /**
     * Nettoie le cache
     */
    public function clearCache(): void
    {
        Cache::forget('remunerations_metiers');
        Log::info('🧹 Cache rémunérations nettoyé');
    }
}