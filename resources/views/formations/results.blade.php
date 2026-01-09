<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📊 Résultats de Recherche
            </h2>
            <a href="{{ route('formations.search') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                ← Nouvelle recherche
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Résumé de la recherche -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-blue-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-bold text-blue-800">Critères de recherche</h3>
                        <div class="mt-2 text-sm text-blue-700 space-y-1">
                            @if(!empty($filters['formation']))
                                <div>• Formation : <strong>{{ $filters['formation'] }}</strong></div>
                            @endif
                            @if(!empty($filters['academie']))
                                <div>• Académie : <strong>{{ $filters['academie'] }}</strong></div>
                            @endif
                            @if(!empty($filters['ville']))
                                <div>• Ville : <strong>{{ $filters['ville'] }}</strong></div>
                            @endif
                            @if(!empty($filters['statut']))
                                <div>• Statut : <strong>{{ ucfirst($filters['statut']) }}</strong></div>
                            @endif
                            @if(empty($filters))
                                <div>Aucun filtre appliqué (toutes les formations)</div>
                            @endif
                        </div>
                        <div class="mt-2 pt-2 border-t border-blue-200">
                            <strong class="text-blue-900">{{ $formations->count() }} formation(s) trouvée(s)</strong>
                        </div>
                    </div>
                </div>
            </div>

            @if($formations->isEmpty())
                <!-- Aucun résultat -->
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Aucune formation trouvée</h3>
                    <p class="text-gray-600 mb-6">Essayez d'élargir vos critères de recherche</p>
                    <a href="{{ route('formations.search') }}" 
                       class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Nouvelle recherche
                    </a>
                </div>
            @else
                <!-- Liste des résultats -->
                <div class="space-y-4">
                    @foreach($formations as $formation)
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-6">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                                        {{ $formation['mef_bcp_6_lib_l'] ?? 'Formation non spécifiée' }}
                                    </h3>
                                    
                                    <div class="text-gray-700 mb-3">
                                        <strong>{{ $formation['patronyme'] ?? 'N/A' }}</strong>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-3">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $formation['commune_d_implantation'] ?? 'N/A' }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 5a1 1 0 10-2 0v1H8a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $formation['academie_2020_lib_l'] ?? 'N/A' }}
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ str_contains(strtolower($formation['secteur_d_enseignement_lib_l'] ?? ''), 'public') ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $formation['secteur_d_enseignement_lib_l'] ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="ml-6 text-right">
                                    <div class="bg-blue-50 rounded-lg p-4 min-w-[120px]">
                                        <div class="text-3xl font-bold text-blue-600">
                                            {{ $formation['nombre_d_eleves_total'] ?? 0 }}
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">élèves</div>
                                        
                                        <div class="mt-3 pt-3 border-t border-blue-200">
                                            @php
                                                $total = $formation['nombre_d_eleves_total'] ?? 0;
                                                $filles = $formation['nombre_d_eleves_filles'] ?? 0;
                                                $pct = $total > 0 ? round(($filles / $total) * 100, 1) : 0;
                                            @endphp
                                            <div class="text-sm font-semibold {{ $pct < 30 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $pct }}% filles
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $filles }}F / {{ $formation['nombre_d_eleves_garcons'] ?? 0 }}G
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        <!-- Métiers associés (si recherche par salaire) -->
        @if($metiers && $metiers->count() > 0)
            <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    Métiers correspondant à votre fourchette de salaire
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($metiers as $metier)
                        <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-1">{{ $metier['metier'] }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ $metier['niveau'] }}</p>
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-700">
                                    {{ number_format($metier['salaire_min'], 0, ',', ' ') }}€ - 
                                    {{ number_format($metier['salaire_max'], 0, ',', ' ') }}€
                                </div>
                                <div class="text-lg font-bold text-green-700">
                                    {{ number_format($metier['salaire_median'], 0, ',', ' ') }}€
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
</x-app-layout>
````