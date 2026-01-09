<?php

namespace App\Http\Controllers;

use App\Services\DataGouvService;
use App\Services\RemunerationService;
use App\Models\SearchStat;
use Illuminate\Support\Facades\Auth;

/**
 * Controller pour le dashboard utilisateur
 * Affiche les statistiques personnalisées et les recherches populaires
 */
class DashboardController extends Controller
{
    protected $dataGouvService;
    protected $remunerationService;
    
    public function __construct(
        DataGouvService $dataGouvService,
        RemunerationService $remunerationService
    ) {
        $this->dataGouvService = $dataGouvService;
        $this->remunerationService = $remunerationService;
    }
    
    /**
     * Affiche le dashboard utilisateur
     */
    public function index()
    {
        // Stats globales
        $statsGlobales = $this->dataGouvService->getStatistiques('2024');
        
        // Mes recherches récentes
        $mesRecherches = SearchStat::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Formations les plus recherchées (toutes les recherches)
        $formationsPopulaires = SearchStat::topFormations(5);
        
        // Académies les plus recherchées
        $academiesPopulaires = SearchStat::topAcademies(5);
        
        // Métiers avec meilleurs salaires
        $metiersTopSalaires = $this->remunerationService->getRemunerations()
            ->sortByDesc('salaire_median')
            ->take(5);
        
        return view('dashboard', compact(
            'statsGlobales',
            'mesRecherches',
            'formationsPopulaires',
            'academiesPopulaires',
            'metiersTopSalaires'
        ));
    }
}