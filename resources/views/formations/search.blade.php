<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🔍 Recherche Avancée de Formations
            </h2>
            <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:text-blue-800">
                ← Retour à la carte publique
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Message d'info -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Accès réservé aux membres :</strong> Utilisez les filtres ci-dessous pour affiner votre recherche.
                            Vos recherches sont enregistrées pour vous proposer des suggestions personnalisées.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Colonne Filtres (Sidebar) -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                                </svg>
                                Filtres de recherche
                            </h3>

                            <form method="POST" action="{{ route('formations.search.post') }}" id="searchForm">
                                @csrf

                                <!-- Recherche par nom de formation -->
                                <div class="mb-4">
                                    <label for="formation" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nom de la formation
                                    </label>
                                    <input type="text" 
                                           name="formation" 
                                           id="formation"
                                           placeholder="Ex: BTS SIO, Informatique..."
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('formation') }}">
                                    <p class="text-xs text-gray-500 mt-1">Recherche partielle acceptée</p>
                                </div>

                                <!-- Sélection Académie -->
                                <div class="mb-4">
                                    <label for="academie" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Académie
                                    </label>
                                    <select name="academie" 
                                            id="academie"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Toutes les académies</option>
                                        @foreach($academies as $academie)
                                            <option value="{{ $academie }}" {{ old('academie') == $academie ? 'selected' : '' }}>
                                                {{ $academie }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Recherche par ville -->
                                <div class="mb-4">
                                    <label for="ville" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Ville
                                    </label>
                                    <input type="text" 
                                           name="ville" 
                                           id="ville"
                                           placeholder="Ex: Paris, Lyon..."
                                           list="villes-list"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('ville') }}">
                                    <datalist id="villes-list">
                                        @foreach($villes->take(100) as $ville)
                                            <option value="{{ $ville }}">
                                        @endforeach
                                    </datalist>
                                </div>

                                <!-- Statut établissement -->
                                <div class="mb-4">
                                    <label for="statut" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Statut de l'établissement
                                    </label>
                                    <select name="statut" 
                                            id="statut"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Public et Privé</option>
                                        <option value="public" {{ old('statut') == 'public' ? 'selected' : '' }}>Public uniquement</option>
                                        <option value="privé" {{ old('statut') == 'privé' ? 'selected' : '' }}>Privé uniquement</option>
                                    </select>
                                </div>

                                <!-- Année scolaire -->
                                <div class="mb-4">
                                    <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Année scolaire
                                    </label>
                                    <select name="year" 
                                            id="year"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @foreach($annees as $annee)
                                            <option value="{{ $annee }}" {{ $annee == '2024' ? 'selected' : '' }}>
                                                {{ $annee }}-{{ $annee + 1 }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <hr class="my-6">

                                <!-- Filtres Rémunération -->
                                <h4 class="text-md font-bold text-gray-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                    </svg>
                                    Rémunération souhaitée
                                </h4>

                                <div class="mb-4">
                                    <label for="salaire_min" class="block text-sm font-medium text-gray-700 mb-2">
                                        Salaire minimum (€/an)
                                    </label>
                                    <input type="number" 
                                           name="salaire_min" 
                                           id="salaire_min"
                                           placeholder="Ex: 30000"
                                           min="0"
                                           step="1000"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('salaire_min') }}">
                                </div>

                                <div class="mb-6">
                                    <label for="salaire_max" class="block text-sm font-medium text-gray-700 mb-2">
                                        Salaire maximum (€/an)
                                    </label>
                                    <input type="number" 
                                           name="salaire_max" 
                                           id="salaire_max"
                                           placeholder="Ex: 50000"
                                           min="0"
                                           step="1000"
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('salaire_max') }}">
                                    <p class="text-xs text-gray-500 mt-1">Optionnel</p>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="space-y-2">
                                    <button type="submit" 
                                            class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition font-semibold flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        Rechercher
                                    </button>
                                    
                                    <button type="reset" 
                                            onclick="document.getElementById('searchForm').reset();"
                                            class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition font-semibold">
                                        Réinitialiser
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Colonne Principale (Résultats et infos) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- BTS Disponibles -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                                BTS Numérique Disponibles ({{ $btsUniques->count() }})
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-3 max-h-96 overflow-y-auto">
                                @foreach($btsUniques->take(10) as $bts)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 transition cursor-pointer"
                                         onclick="document.getElementById('formation').value = '{{ addslashes($bts['nom_bts']) }}';">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900 text-sm">{{ $bts['nom_bts'] }}</h4>
                                                <div class="flex gap-3 mt-2 text-xs text-gray-600">
                                                    <span>🏫 {{ $bts['nombre_etablissements'] }} établissement(s)</span>
                                                    <span>👥 {{ $bts['total_eleves'] }} élèves</span>
                                                    <span class="font-semibold {{ $bts['pourcentage_filles'] < 30 ? 'text-red-600' : 'text-green-600' }}">
                                                        👧 {{ $bts['pourcentage_filles'] }}%
                                                    </span>
                                                </div>
                                            </div>
                                            <button type="button" 
                                                    class="text-blue-600 hover:text-blue-800 text-xs font-semibold">
                                                Sélectionner →
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($btsUniques->count() > 10)
                                <div class="mt-3 text-center">
                                    <a href="{{ route('test.api.uniques') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                                        Voir les {{ $btsUniques->count() - 10 }} autres BTS →
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Métiers et Rémunérations -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                Aperçu des Rémunérations
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-4">
                                Salaires moyens des métiers du numérique accessibles après un BTS
                            </p>

                            <div class="space-y-3">
                                @foreach($metiers->take(5) as $metier)
                                    @php
                                        $details = \App\Services\RemunerationService::class;
                                        $detailsMetier = app($details)->getDetailsMetier($metier)->first();
                                    @endphp
                                    @if($detailsMetier)
                                        <div class="border-l-4 border-green-500 bg-green-50 p-3 rounded-r-lg">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 text-sm">{{ $metier }}</h4>
                                                    <p class="text-xs text-gray-600">{{ $detailsMetier['niveau'] }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-lg font-bold text-green-700">
                                                        {{ number_format($detailsMetier['salaire_median'], 0, ',', ' ') }}€
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ number_format($detailsMetier['salaire_min'], 0, ',', ' ') }}€ - 
                                                        {{ number_format($detailsMetier['salaire_max'], 0, ',', ' ') }}€
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Mes Recherches Récentes -->
                    @if($mesRecherches->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Mes Recherches Récentes
                            </h3>
                            
                            <div class="space-y-2">
                                @foreach($mesRecherches as $recherche)
                                    <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition text-sm">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                @if($recherche->formation)
                                                    <span class="font-semibold text-gray-900">{{ $recherche->formation }}</span>
                                                @endif
                                                @if($recherche->academie)
                                                    <span class="text-gray-600"> • {{ $recherche->academie }}</span>
                                                @endif
                                                @if($recherche->ville)
                                                    <span class="text-gray-600"> • {{ $recherche->ville }}</span>
                                                @endif
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $recherche->nombre_resultats }} résultat(s) • 
                                                    {{ $recherche->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>