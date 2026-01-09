<?php

namespace App\Http\Controllers;

use App\Services\DataGouvService;
use App\Services\RemunerationService;
use App\Models\SearchStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller pour la recherche avancée de formations
 * AUTHENTIFICATION REQUISE pour accès aux filtres détaillés
 */
class FormationSearchController extends Controller
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
     * Page de recherche avancée avec tous les filtres
     * Route : /formations/recherche
     */
    public function index()
    {
        // Données pour les filtres
        $academies = $this->dataGouvService->getAcademies('2024');
        $villes = $this->dataGouvService->getVilles('2024');
        $annees = $this->dataGouvService->getAnneesDisponibles();
        $btsUniques = $this->dataGouvService->getBTSUniques('2024');
        $metiers = $this->remunerationService->getMetiersDisponibles();
        
        // Stats pour l'utilisateur connecté
        $mesRecherches = SearchStat::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('formations.search', compact(
            'academies',
            'villes',
            'annees',
            'btsUniques',
            'metiers',
            'mesRecherches'
        ));
    }
    
    /**
     * Traite la recherche avec filtres et enregistre les stats
     * Route : POST /formations/recherche
     */
    public function search(Request $request)
    {
        // Validation des filtres
        $validated = $request->validate([
            'formation' => 'nullable|string|max:255',
            'academie' => 'nullable|string|max:100',
            'ville' => 'nullable|string|max:100',
            'statut' => 'nullable|in:public,privé,prive',
            'year' => 'nullable|string|in:2024,2023,2022,2021,2020,2019',
            'salaire_min' => 'nullable|integer|min:0',
            'salaire_max' => 'nullable|integer|min:0',
        ]);
        
        // Recherche des formations
        $formations = $this->dataGouvService->searchFormations([
            'formation' => $request->formation,
            'academie' => $request->academie,
            'ville' => $request->ville,
            'statut' => $request->statut,
            'year' => $request->year ?? '2024',
        ]);
        
        // Si recherche par salaire, récupérer les métiers correspondants
        $metiers = null;
        if ($request->salaire_min) {
            $metiers = $this->remunerationService->searchBySalaire(
                $request->salaire_min,
                $request->salaire_max
            );
        }
        
        // Enregistrement des statistiques de recherche
        SearchStat::create([
            'user_id' => Auth::id(),
            'formation' => $request->formation,
            'academie' => $request->academie,
            'ville' => $request->ville,
            'statut' => $request->statut,
            'year' => $request->year ?? '2024',
            'sexe_rechercheur' => 'Non précisé', // À implémenter dans profil utilisateur
            'nombre_resultats' => $formations->count(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        // Retour JSON pour AJAX ou redirection vers résultats
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'formations' => $formations,
                'metiers' => $metiers,
                'total' => $formations->count(),
            ]);
        }
        
        // Redirection avec résultats en session
        return redirect()->route('formations.results')
            ->with('formations', $formations)
            ->with('metiers', $metiers)
            ->with('filters', $validated);
    }
    
    /**
     * Page de résultats de recherche
     * Route : /formations/resultats
     */
    public function results()
    {
        $formations = session('formations', collect([]));
        $metiers = session('metiers', collect([]));
        $filters = session('filters', []);
        
        return view('formations.results', compact('formations', 'metiers', 'filters'));
    }
}