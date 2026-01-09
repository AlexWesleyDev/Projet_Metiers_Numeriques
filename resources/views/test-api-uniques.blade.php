<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BTS Numérique - Catalogue</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- En-tête avec navigation -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-blue-600 mb-2">📚 Catalogue BTS Numérique</h1>
                <p class="text-gray-600">Formations distinctes sans doublons d'établissements</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('test.api') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    Tous les établissements
                </a>
            </div>
        </div>
        
        <!-- Statistiques Globales en cartes -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <span class="text-blue-600">📊</span> 
                Statistiques Année 2024
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- BTS Distincts -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-blue-700 bg-blue-200 px-2 py-1 rounded-full">
                            DISTINCTS
                        </span>
                    </div>
                    <div class="text-4xl font-bold text-blue-700 mb-1">
                        {{ $stats['nombre_bts_distincts'] }}
                    </div>
                    <div class="text-sm text-blue-600 font-medium">BTS Numérique différents</div>
                </div>

                <!-- Établissements Total -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-green-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-green-700 bg-green-200 px-2 py-1 rounded-full">
                            TOTAL
                        </span>
                    </div>
                    <div class="text-4xl font-bold text-green-700 mb-1">
                        {{ $stats['total_etablissements'] }}
                    </div>
                    <div class="text-sm text-green-600 font-medium">Établissements proposant ces BTS</div>
                </div>

                <!-- Élèves Total -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl border border-purple-200">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-purple-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-purple-700 bg-purple-200 px-2 py-1 rounded-full">
                            INSCRITS
                        </span>
                    </div>
                    <div class="text-4xl font-bold text-purple-700 mb-1">
                        {{ number_format($stats['total_eleves']) }}
                    </div>
                    <div class="text-sm text-purple-600 font-medium">Élèves en BTS numérique</div>
                </div>
            </div>
        </div>

        <!-- BTS le plus demandé (Highlight) -->
        @if($stats['bts_plus_demande'])
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-orange-500 rounded-lg p-6 mb-8 shadow-md">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-2xl">🏆</span>
                        <h3 class="text-lg font-bold text-orange-800">BTS le plus demandé</h3>
                    </div>
                    <div class="text-xl font-semibold text-gray-800 mb-2">
                        {{ $stats['bts_plus_demande']['nom_bts'] }}
                    </div>
                    <div class="flex gap-6 text-sm text-gray-600">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            <span class="font-semibold">{{ number_format($stats['bts_plus_demande']['total_eleves']) }}</span> élèves
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-semibold">{{ $stats['bts_plus_demande']['nombre_etablissements'] }}</span> établissements
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-semibold text-pink-700">{{ $stats['bts_plus_demande']['pourcentage_filles'] }}%</span> de filles
                        </div>
                    </div>
                </div>
                <a href="{{ route('test.api.detail', ['nom' => $stats['bts_plus_demande']['nom_bts'], 'year' => '2024']) }}" 
                   class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition font-semibold text-sm whitespace-nowrap">
                    Voir détails →
                </a>
            </div>
        </div>
        @endif

        <!-- Tableau des BTS -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    📋 Liste complète des BTS ({{ $btsUniques->count() }})
                </h2>
            </div>
            
            @if($btsUniques->isEmpty())
                <div class="p-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-600 text-lg">Aucune formation trouvée</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Formation
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Établissements
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Élèves
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Parité F/G
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Académies
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($btsUniques as $index => $bts)
                                <tr class="hover:bg-blue-50 transition {{ $index % 2 == 0 ? 'bg-gray-50' : '' }}">
                                    <!-- Nom Formation -->
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900 mb-1">
                                            {{ $bts['nom_bts'] }}
                                        </div>
                                        <div class="text-xs text-gray-500 font-mono">
                                            Code: {{ $bts['code_mef'] }}
                                        </div>
                                    </td>
                                    
                                    <!-- Nombre d'établissements -->
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-bold text-sm">
                                            {{ $bts['nombre_etablissements'] }}
                                        </span>
                                    </td>
                                    
                                    <!-- Total élèves -->
                                    <td class="px-6 py-4 text-center">
                                        <div class="font-bold text-gray-900">{{ $bts['total_eleves'] }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $bts['total_filles'] }}F / {{ $bts['total_garcons'] }}G
                                        </div>
                                    </td>
                                    
                                    <!-- Pourcentage filles -->
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @php
                                                $pct = $bts['pourcentage_filles'];
                                                $color = $pct < 20 ? 'red' : ($pct < 40 ? 'orange' : ($pct < 60 ? 'yellow' : 'green'));
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-sm font-bold
                                                {{ $color == 'red' ? 'bg-red-100 text-red-700' : '' }}
                                                {{ $color == 'orange' ? 'bg-orange-100 text-orange-700' : '' }}
                                                {{ $color == 'yellow' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                {{ $color == 'green' ? 'bg-green-100 text-green-700' : '' }}">
                                                {{ $pct }}%
                                            </span>
                                            <!-- Barre de progression -->
                                            <div class="hidden lg:block w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full transition-all
                                                    {{ $color == 'red' ? 'bg-red-500' : '' }}
                                                    {{ $color == 'orange' ? 'bg-orange-500' : '' }}
                                                    {{ $color == 'yellow' ? 'bg-yellow-500' : '' }}
                                                    {{ $color == 'green' ? 'bg-green-500' : '' }}"
                                                    style="width: {{ $pct }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Académies -->
                                    <td class="px-6 py-4 text-center">
                                        <div class="text-sm font-semibold text-gray-700">
                                            {{ $bts['academies']->count() }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $bts['nombre_villes'] }} villes
                                        </div>
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('test.api.detail', ['nom' => $bts['nom_bts'], 'year' => '2024']) }}" 
                                           class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-semibold text-sm transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Détails
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Footer info -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>💡 Données mises en cache pendant 24h • Source: data.gouv.fr</p>
        </div>
    </div>
</body>
</html>