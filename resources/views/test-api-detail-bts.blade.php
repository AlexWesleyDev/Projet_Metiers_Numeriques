<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail BTS - {{ $nomBTS }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('test.api.uniques') }}" class="text-blue-600 hover:text-blue-800">
                        📚 Catalogue BTS
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-600 font-semibold">{{ $nomBTS }}</li>
            </ol>
        </nav>

        <!-- En-tête -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-8 mb-8 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $nomBTS }}</h1>
                    <p class="text-blue-100">Tous les établissements proposant cette formation</p>
                </div>
                <div class="bg-white text-blue-600 px-4 py-2 rounded-lg font-bold text-xl">
                    {{ $etablissements->count() }}
                    <div class="text-xs font-normal">établissement(s)</div>
                </div>
            </div>
        </div>

        @if($etablissements->isEmpty())
            <!-- Message si aucun établissement -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-gray-400 mb-4">
                    <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-xl text-gray-600 mb-2">Aucun établissement trouvé</p>
                <p class="text-gray-500">pour cette formation en {{ $year }}</p>
            </div>
        @else
            <!-- Statistiques agrégées -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-blue-600 mb-2">
                        <svg class="w-8 h-8 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">{{ $etablissements->sum('nombre_d_eleves_total') }}</div>
                    <div class="text-sm text-gray-500">Élèves Total</div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-pink-600 mb-2">
                        <svg class="w-8 h-8 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-pink-600">
                        {{ $etablissements->sum('nombre_d_eleves_total') > 0 ? round(($etablissements->sum('nombre_d_eleves_filles') / $etablissements->sum('nombre_d_eleves_total')) * 100, 1) : 0 }}%
                    </div>
                    <div class="text-sm text-gray-500">
                        Filles ({{ $etablissements->sum('nombre_d_eleves_filles') }})
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-blue-600 mb-2">
                        <svg class="w-8 h-8 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-blue-600">
                        {{ $etablissements->sum('nombre_d_eleves_total') > 0 ? round(($etablissements->sum('nombre_d_eleves_garcons') / $etablissements->sum('nombre_d_eleves_total')) * 100, 1) : 0 }}%
                    </div>
                    <div class="text-sm text-gray-500">
                        Garçons ({{ $etablissements->sum('nombre_d_eleves_garcons') }})
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-green-600 mb-2">
                        <svg class="w-8 h-8 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">{{ $etablissements->pluck('academie_2020_lib_l')->unique()->count() }}</div>
                    <div class="text-sm text-gray-500">Académies</div>
                </div>
            </div>

            <!-- Liste des établissements -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h2 class="text-xl font-bold text-gray-900">Liste des établissements</h2>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @foreach($etablissements as $etab)
                        <div class="p-6 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <!-- Nom établissement -->
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                                        {{ $etab['patronyme'] ?? 'N/A' }}
                                    </h3>
                                    
                                    <!-- Localisation -->
                                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>{{ $etab['commune_d_implantation'] ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 5a1 1 0 10-2 0v1H8a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>{{ $etab['academie_2020_lib_l'] ?? 'N/A' }}</span>
                                        </div>
                                        <div>
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                {{ str_contains(strtolower($etab['secteur_d_enseignement_lib_l'] ?? ''), 'public') ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                                {{ $etab['secteur_d_enseignement_lib_l'] ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Statistiques élèves -->
                                    <div class="flex gap-6">
                                        <div>
                                            <span class="text-2xl font-bold text-gray-900">{{ $etab['nombre_d_eleves_total'] ?? 0 }}</span>
                                            <span class="text-sm text-gray-500">élèves</span>
                                    </div>
                                    <div>
                                        <span class="text-lg font-semibold text-pink-600">{{ $etab['nombre_d_eleves_filles'] ?? 0 }}</span>
                                        <span class="text-sm text-gray-500">filles</span>
                                    </div>
                                    <div>
                                        <span class="text-lg font-semibold text-blue-600">{{ $etab['nombre_d_eleves_garcons'] ?? 0 }}</span>
                                        <span class="text-sm text-gray-500">garçons</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Badge parité -->
                            <div class="ml-4">
                                @php
                                    $total = $etab['nombre_d_eleves_total'] ?? 0;
                                    $filles = $etab['nombre_d_eleves_filles'] ?? 0;
                                    $pct = $total > 0 ? round(($filles / $total) * 100, 1) : 0;
                                @endphp
                                <div class="text-center">
                                    <div class="text-3xl font-bold
                                        {{ $pct < 30 ? 'text-red-600' : ($pct > 50 ? 'text-green-600' : 'text-yellow-600') }}">
                                        {{ $pct }}%
                                    </div>
                                    <div class="text-xs text-gray-500">filles</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Bouton retour -->
    <div class="mt-8 text-center">
        <a href="{{ route('test.api.uniques') }}" 
           class="inline-flex items-center gap-2 bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour au catalogue
        </a>
    </div>

</body>
</html>