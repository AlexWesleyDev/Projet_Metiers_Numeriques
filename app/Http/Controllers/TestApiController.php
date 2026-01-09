<?php

namespace App\Http\Controllers;

use App\Services\DataGouvService;
use Illuminate\Http\Request;

/**
 * Controller de test pour l'API data.gouv.fr
 * Permet de visualiser et tester les données BTS numérique
 */
class TestApiController extends Controller
{
    protected $dataGouvService;
    
    /**
     * Injection du service DataGouv
     */
    public function __construct(DataGouvService $dataGouvService)
    {
        $this->dataGouvService = $dataGouvService;
    }
    
    /**
     * Vue avec toutes les formations (lignes établissements)
     * Route : /test-api
     */
    public function index()
    {
        $formations = $this->dataGouvService->getBTSNumerique('2024');
        $stats = $this->dataGouvService->getStatistiques('2024');
        $academies = $this->dataGouvService->getAcademies('2024');
        $villes = $this->dataGouvService->getVilles('2024');
        
        return view('test-api', compact('formations', 'stats', 'academies', 'villes'));
    }
    
    /**
     * Vue avec BTS UNIQUES (sans doublons établissements)
     * Route : /test-api/uniques
     */
    public function uniques()
    {
        $btsUniques = $this->dataGouvService->getBTSUniques('2024');
        $stats = $this->dataGouvService->getStatistiquesBTSUniques('2024');
        
        return view('test-api-uniques', compact('btsUniques', 'stats'));
    }
    
    /**
     * Détail d'un BTS spécifique avec tous ses établissements
     * Route : /test-api/detail?nom=XXX&year=2024
     */
    public function detailBTS(Request $request)
    {
        $nomBTS = $request->query('nom');
        $year = $request->query('year', '2024');
        
        if (!$nomBTS) {
            return redirect()->route('test.api.uniques')
                ->with('error', 'Nom du BTS manquant');
        }
        
        $etablissements = $this->dataGouvService->getEtablissementsPourBTS($nomBTS, $year);
        
        return view('test-api-detail-bts', compact('nomBTS', 'etablissements', 'year'));
    }
}