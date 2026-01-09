<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }} - {{ Auth::user()->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Accès rapide -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('formations.search') }}" 
                   class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-3">
                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-1">Recherche Avancée</h3>
                    <p class="text-blue-100 text-sm">Filtres multi-critères et rémunérations</p>
                </a>

                <a href="{{ route('home') }}" 
                   class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-3">
                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-1">Carte Interactive</h3>
                    <p class="text-green-100 text-sm">Visualisation géographique des formations</p>
                </a>

                <a href="{{ route('test.api.uniques') }}" 
                   class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-3">
                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-1">Catalogue BTS</h3>
                    <p class="text-purple-100 text-sm">Liste complète sans doublons</p>
                </a>
            </div>

            <!-- Stats globales -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                    Statistiques Globales 2024
                </h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $statsGlobales['total_formations'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Formations BTS</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ number_format($statsGlobales['total_eleves'] ?? 0) }}</div>
                        <div class="text-sm text-gray-600">Élèves</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ $statsGlobales['nombre_academies'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Académies</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-pink-600">{{ $statsGlobales['pourcentage_filles'] ?? 0 }}%</div>
                        <div class="text-sm text-gray-600">Filles</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Mes recherches récentes -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Mes Recherches Récentes
                    </h3>
                    
                    @if($mesRecherches->isEmpty())
                        <p class="text-gray-500 text-sm">Aucune recherche effectuée</p>
                    @else
                        <div class="space-y-3">
                            @foreach($mesRecherches->take(5) as $recherche)
                                <div class="border-l-4 border-blue-500 bg-blue-50 p-3 rounded-r-lg">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            @if($recherche->formation)
                                                <div class="font-semibold text-gray-900 text-sm">{{ $recherche->formation }}</div>
                                            @endif
                                            <div class="text-xs text-gray-600 mt-1 space-x-2">
                                                @if($recherche->academie)
                                                    <span>📍 {{ $recherche->academie }}</span>
                                                @endif
                                                @if($recherche->ville)
                                                    <span>🏙️ {{ $recherche->ville }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right ml-3">
                                            <div class="font-bold text-blue-700">{{ $recherche->nombre_resultats }}</div>
                                            <div class="text-xs text-gray-500">{{ $recherche->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Formations les plus recherchées -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Formations Populaires
                    </h3>
                    
                    @if($formationsPopulaires->isEmpty())
                        <p class="text-gray-500 text-sm">Aucune donnée disponible</p>
                    @else
                        <div class="space-y-2">
                            @foreach($formationsPopulaires as $index => $formation)
                                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded">
                                    <div class="flex-shrink-0 w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-bold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 text-sm">{{ $formation->formation }}</div>
                                    </div>
                                    <div class="text-orange-600 font-bold">{{ $formation->count }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Académies populaires -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        Académies les Plus Recherchées
                    </h3>
                    
                    @if($academiesPopulaires->isEmpty())
                        <p class="text-gray-500 text-sm">Aucune donnée disponible</p>
                    @else
                        <div class="space-y-2">
                            @foreach($academiesPopulaires as $academie)
                                <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded">
                                    <span class="text-sm font-medium text-gray-900">{{ $academie->academie }}</span>
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $academie->count }} recherches
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Top rémunérations -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        Métiers Mieux Rémunérés
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach($metiersTopSalaires as $metier)
                            <div class="border-l-4 border-green-500 bg-green-50 p-3 rounded-r-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-semibold text-gray-900 text-sm">{{ $metier['metier'] }}</div>
                                        <div class="text-xs text-gray-600">{{ $metier['niveau'] }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-green-700">{{ number_format($metier['salaire_median']) }}€</div>
                                        <div class="text-xs text-gray-500">médian</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>