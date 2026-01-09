<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test API - BTS Numérique</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-blue-600 mb-8">🧪 Test API Data.gouv.fr</h1>
        
        <!-- Statistiques Globales -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">📊 Statistiques Année 2024</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $stats['total_formations'] }}</div>
                    <div class="text-sm text-gray-600">Formations BTS Numérique</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg text-center">
                    <div class="text-3xl font-bold text-green-600">{{ number_format($stats['total_eleves']) }}</div>
                    <div class="text-sm text-gray-600">Élèves Total</div>
                </div>
                <div class="bg-pink-50 p-4 rounded-lg text-center">
                    <div class="text-3xl font-bold text-pink-600">{{ $stats['pourcentage_filles'] }}%</div>
                    <div class="text-sm text-gray-600">Filles ({{ number_format($stats['total_filles']) }})</div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $stats['nombre_academies'] }}</div>
                    <div class="text-sm text-gray-600">Académies</div>
                </div>
            </div>
        </div>

        <!-- Liste des Académies -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">🗺️ Académies disponibles ({{ $academies->count() }})</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                @foreach($academies as $academie)
                    <div class="bg-gray-50 px-3 py-2 rounded text-sm">{{ $academie }}</div>
                @endforeach
            </div>
        </div>

        <!-- Exemple de Formations -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">📚 Formations trouvées ({{ $formations->count() }}) - Échantillon</h2>
            
            @if($formations->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-800">
                    ⚠️ Aucune formation trouvée. Vérifiez les logs avec <code>tail -f storage/logs/laravel.log</code>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Formation</th>
                                <th class="px-4 py-2 text-left">Établissement</th>
                                <th class="px-4 py-2 text-left">Ville</th>
                                <th class="px-4 py-2 text-left">Académie</th>
                                <th class="px-4 py-2 text-center">Effectifs</th>
                                <th class="px-4 py-2 text-center">% Filles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($formations->take(20) as $formation)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-blue-600">
                                            {{ $formation['mef_bcp_11_lib_l'] ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">{{ $formation['patronyme'] ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">{{ $formation['commune_d_implantation'] ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">{{ $formation['academie_2020_lib_l'] ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="font-bold">{{ $formation['nombre_d_eleves_total'] ?? 0 }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $total = $formation['nombre_d_eleves_total'] ?? 0;
                                            $filles = $formation['nombre_d_eleves_filles'] ?? 0;
                                            $pct = $total > 0 ? round(($filles / $total) * 100, 1) : 0;
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            {{ $pct < 30 ? 'bg-red-100 text-red-700' : ($pct > 50 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ $pct }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($formations->count() > 20)
                    <div class="mt-4 text-center text-gray-500">
                        ... et {{ $formations->count() - 20 }} autres formations
                    </div>
                @endif
            @endif
        </div>

        <!-- Debug Info -->
        <div class="bg-gray-800 text-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">🔧 Informations Debug</h2>
            <div class="space-y-2 text-sm font-mono">
                <div><span class="text-green-400">✓</span> Service DataGouvService chargé</div>
                <div><span class="text-green-400">✓</span> Cache actif (driver: {{ config('cache.default') }})</div>
                <div><span class="text-green-400">✓</span> API URL: {{ $formations->isEmpty() ? '❌ Pas de données' : '✅ Connectée' }}</div>
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <div class="text-gray-400">Commande pour voir les logs en temps réel :</div>
                    <div class="bg-black p-2 rounded mt-2">tail -f storage/logs/laravel.log</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>