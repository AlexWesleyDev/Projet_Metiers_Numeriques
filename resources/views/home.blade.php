<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Métiers du Numérique - Orientation BTS</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Leaflet pour la carte -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        #map {
            height: 600px;
            width: 100%;
            border-radius: 0.5rem;
        }
        
        /* Animation pour les cartes */
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                    <div>
                        <h1 class="text-xl font-bold">Métiers du Numérique</h1>
                        <p class="text-xs text-blue-100">Orientation BTS & Carrières</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition font-semibold">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-blue-100 transition font-semibold">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition font-semibold">
                            Inscription
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Trouvez votre formation BTS en Numérique
                </h2>
                <p class="text-xl text-blue-100 mb-8">
                    Explorez plus de {{ $stats['total_formations'] }} formations dans {{ $stats['nombre_academies'] }} académies
                </p>
                
                @guest
                    <div class="bg-yellow-400 text-yellow-900 inline-block px-6 py-3 rounded-lg font-semibold">
                        🔒 Connectez-vous pour accéder aux filtres avancés et statistiques détaillées
                    </div>
                @endguest
            </div>

            <!-- Statistiques Globales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- BTS Distincts -->
                <div class="stat-card bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                    <div class="flex items-center justify-between mb-3">
                        <svg class="w-10 h-10 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                        </svg>
                        <span class="text-xs font-bold bg-blue-500 px-2 py-1 rounded-full">FORMATIONS</span>
                    </div>
                    <div class="text-4xl font-bold mb-1">{{ $btsUniques->count() }}</div>
                    <div class="text-blue-100 text-sm">BTS Numérique différents</div>
                </div>

                <!-- Établissements -->
                <div class="stat-card bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                    <div class="flex items-center justify-between mb-3">
                        <svg class="w-10 h-10 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-bold bg-green-500 px-2 py-1 rounded-full">LIEUX</span>
                    </div>
                    <div class="text-4xl font-bold mb-1">{{ $stats['total_formations'] }}</div>
                    <div class="text-blue-100 text-sm">Établissements proposant ces BTS</div>
                </div>

                <!-- Élèves -->
                <div class="stat-card bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                    <div class="flex items-center justify-between mb-3">
                        <svg class="w-10 h-10 text-purple-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        <span class="text-xs font-bold bg-purple-500 px-2 py-1 rounded-full">INSCRITS</span>
                    </div>
                    <div class="text-4xl font-bold mb-1">{{ number_format($stats['total_eleves']) }}</div>
                    <div class="text-blue-100 text-sm">Élèves en BTS numérique</div>
                </div>

                <!-- Parité -->
                <div class="stat-card bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                    <div class="flex items-center justify-between mb-3">
                        <svg class="w-10 h-10 text-pink-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-bold bg-pink-500 px-2 py-1 rounded-full">PARITÉ</span>
                    </div>
                    <div class="text-4xl font-bold mb-1">{{ $stats['pourcentage_filles'] }}%</div>
                    <div class="text-blue-100 text-sm">de filles ({{ number_format($stats['total_filles']) }})</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Carte -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Titre Section -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                Carte des formations BTS Numérique
            </h2>
            <p class="text-gray-600">
                Vue d'ensemble géographique des formations • 
                <span class="font-semibold text-blue-600">{{ $formationsPourCarte->count() }}</span> formations affichées
            </p>
        </div>

        <!-- Carte Interactive -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div id="map" class="rounded-lg"></div>
            
            <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                        <span>Formations disponibles</span>
                    </div>
                </div>
                <div>
                    💡 Cliquez sur les marqueurs pour plus de détails
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        @guest
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-8 text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-3">
                Accédez à plus de fonctionnalités
            </h3>
            <p class="text-gray-600 mb-6">
                Créez un compte pour utiliser les filtres avancés, voir les statistiques détaillées par académie,
                consulter les rémunérations par métier et sauvegarder vos recherches.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('register') }}" 
                   class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold text-lg">
                    Créer un compte gratuit
                </a>
                <a href="{{ route('login') }}" 
                   class="bg-white text-blue-600 border-2 border-blue-600 px-8 py-3 rounded-lg hover:bg-blue-50 transition font-semibold text-lg">
                    Se connecter
                </a>
            </div>
        </div>
        @endguest
    </div>

    <!-- Script pour la carte Leaflet -->
    <script>
        // Initialisation de la carte centrée sur la France
        const map = L.map('map').setView([46.603354, 1.888334], 6);
        
        // Ajout du fond de carte OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Données des formations depuis Laravel
        const formations = @json($formationsPourCarte);
        
        // Coordonnées approximatives des académies françaises (pour démo)
        const academieCoords = {
            'CRETEIL': [48.790367, 2.455572],
            'ORLEANS-TOURS': [47.902964, 1.909251],
            'PARIS': [48.856614, 2.352222],
            'VERSAILLES': [48.801408, 2.130122],
            'LILLE': [50.629250, 3.057256],
            'AMIENS': [49.894067, 2.295753],
            'NANCY-METZ': [48.692054, 6.184417],
            'STRASBOURG': [48.573405, 7.752111],
            'REIMS': [49.258329, 4.031696],
            'BESANCON': [47.237829, 6.024054],
            'DIJON': [47.322047, 5.041480],
            'LYON': [45.764043, 4.835659],
            'GRENOBLE': [45.188529, 5.724524],
            'CLERMONT-FERRAND': [45.777222, 3.087025],
            'TOULOUSE': [43.604652, 1.444209],
            'MONTPELLIER': [43.610769, 3.876716],
            'AIX-MARSEILLE': [43.296482, 5.369780],
            'NICE': [43.710173, 7.261953],
            'BORDEAUX': [44.837789, -0.579180],
            'LIMOGES': [45.833619, 1.261105],
            'POITIERS': [46.580224, 0.340375],
            'RENNES': [48.117266, -1.677793],
            'NANTES': [47.218371, -1.553621],
            'ROUEN': [49.443232, 1.099971],
            'CAEN': [49.182863, -0.370679],
            'CORSE': [42.039604, 9.012893],
        };
        
        // Ajout des marqueurs sur la carte
        formations.forEach(formation => {
            const academie = formation.academie.toUpperCase();
            const coords = academieCoords[academie];
            
            if (coords) {
                // Ajout d'un petit décalage aléatoire pour éviter les superpositions
                const lat = coords[0] + (Math.random() - 0.5) * 0.5;
                const lng = coords[1] + (Math.random() - 0.5) * 0.5;
                
                // Couleur selon le statut
                const color = formation.statut.includes('PUBLIC') ? '#3b82f6' : '#8b5cf6';
                
                // Création du marqueur
                const marker = L.circleMarker([lat, lng], {
                    radius: 8,
                    fillColor: color,
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(map);
                
                // Popup avec informations
                marker.bindPopup(`
                    <div class="p-2">
                        <h4 class="font-bold text-blue-600 mb-1">${formation.nom}</h4>
                        <div class="text-sm text-gray-700 space-y-1">
                            <div><strong>Établissement:</strong> ${formation.etablissement}</div>
                            <div><strong>Ville:</strong> ${formation.ville}</div>
                            <div><strong>Académie:</strong> ${formation.academie}</div>
                            <div><strong>Élèves:</strong> ${formation.eleves}</div>
                            <div>
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold ${
                                    formation.statut.includes('PUBLIC') 
                                        ? 'bg-blue-100 text-blue-700' 
                                        : 'bg-purple-100 text-purple-700'
                                }">
                                    ${formation.statut}
                                </span>
                            </div>
                        </div>
                    </div>
                `);
            }
        });
        
        console.log(`✅ Carte chargée avec ${formations.length} formations`);
    </script>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <p class="text-gray-400">
                    💡 Données issues de data.gouv.fr • Mises à jour quotidiennes
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    © 2024 Métiers du Numérique - Projet d'orientation
                </p>
            </div>
        </div>
    </footer>

</body>
</html>