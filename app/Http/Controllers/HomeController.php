<?php

namespace App\Http\Controllers;

use App\Services\DataGouvService;
use Illuminate\Http\Request;

/**
 * Controller pour la page d'accueil publique
 * Accessible sans authentification avec carte globale
 */
class HomeController extends Controller
{
    protected $dataGouvService;
    
    public function __construct(DataGouvService $dataGouvService)
    {
        $this->dataGouvService = $dataGouvService;
    }
    
    /**
     * Page d'accueil avec carte publique
     * Affiche les statistiques globales et la carte des formations
     * Pas de filtres détaillés (nécessitent authentification)
     */
    public function index()
    {
        // Statistiques globales pour la page publique
        $stats = $this->dataGouvService->getStatistiques('2024');
        
        // Liste des BTS uniques pour affichage sur la carte
        $btsUniques = $this->dataGouvService->getBTSUniques('2024');
        
        // Formations avec leurs coordonnées pour la carte
        // On récupère un échantillon pour la carte publique
        $formationsPourCarte = $this->dataGouvService->getBTSNumerique('2024')
            ->take(50) // Limite à 50 pour ne pas surcharger
            ->map(function($formation) {
                return [
                    'nom' => $formation['mef_bcp_6_lib_l'] ?? 'N/A',
                    'etablissement' => $formation['patronyme'] ?? 'N/A',
                    'ville' => $formation['commune_d_implantation'] ?? 'N/A',
                    'academie' => $formation['academie_2020_lib_l'] ?? 'N/A',
                    'eleves' => $formation['nombre_d_eleves_total'] ?? 0,
                    'statut' => $formation['secteur_d_enseignement_lib_l'] ?? 'N/A',
                ];
            });
        
        // Académies pour la carte
        $academies = $this->dataGouvService->getAcademies('2024');
        
        return view('home', compact('stats', 'btsUniques', 'formationsPourCarte', 'academies'));
    }
    
    /**
     * API endpoint pour récupérer les formations pour la carte
     * Utilisé par JavaScript pour afficher les marqueurs
     */
    public function getFormationsForMap(Request $request)
    {
        $formations = $this->dataGouvService->getBTSNumerique('2024');
        
        // Grouper par ville pour éviter trop de marqueurs
        $formationsGroupees = $formations->groupBy('commune_d_implantation')
            ->map(function($group, $ville) {
                return [
                    'ville' => $ville,
                    'academie' => $group->first()['academie_2020_lib_l'] ?? 'N/A',
                    'nombre_formations' => $group->count(),
                    'nombre_eleves' => $group->sum('nombre_d_eleves_total'),
                    'formations' => $group->pluck('mef_bcp_6_lib_l')->unique()->values(),
                ];
            })
            ->values();
        
        return response()->json($formationsGroupees);
    }
}