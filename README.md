# 🎓 Métiers du Numérique - Plateforme d'Orientation BTS

Plateforme web permettant aux lycéens et étudiants de rechercher des formations BTS dans le domaine du numérique en France, avec visualisation cartographique, filtres avancés et informations sur les rémunérations des métiers.

## 📋 Table des matières

- [Fonctionnalités](#fonctionnalités)
- [Technologies utilisées](#technologies-utilisées)
- [Installation](#installation)
- [Utilisation](#utilisation)
- [Architecture](#architecture)
- [Choix techniques](#choix-techniques)
- [API et sources de données](#api-et-sources-de-données)
- [Sécurité](#sécurité)

---

## ✨ Fonctionnalités

### Accès Public (sans authentification)
- 🗺️ **Carte interactive** : Visualisation géographique des formations BTS numérique avec Leaflet/OpenStreetMap
- 📊 **Statistiques globales** : Nombre de formations, élèves, académies, parité F/H
- 🎨 **Interface moderne** : Design responsive avec Tailwind CSS

### Accès Authentifié (compte requis)
- 🔍 **Recherche avancée multi-critères** :
  - Nom de formation (recherche partielle)
  - Académie (36 académies disponibles)
  - Ville (1293 communes)
  - Statut établissement (Public/Privé)
  - Année scolaire (2019-2024)
  - Fourchette de rémunération souhaitée

- 💰 **Référentiel de rémunérations** :
  - Salaires par métier et niveau d'expérience
  - Recherche inverse : métiers accessibles par fourchette salariale
  - Données actualisées 2024

- 📈 **Statistiques personnalisées** :
  - Historique des recherches utilisateur
  - Formations et académies les plus recherchées
  - Métiers mieux rémunérés

- 📊 **Dashboard personnalisé** :
  - Vue d'ensemble des statistiques
  - Accès rapides aux fonctionnalités
  - Visualisation des tendances

### Enregistrement des statistiques
- 🔢 Toutes les recherches authentifiées sont enregistrées (anonymisation possible)
- 📊 Permet d'identifier les formations les plus demandées
- 🎯 Base pour futures suggestions personnalisées

---

## 🛠️ Technologies utilisées

### Backend
- **Laravel 10.x** (PHP 8.1+)
- **SQLite/MySQL** pour la base de données
- **Laravel Breeze** pour l'authentification

### Frontend
- **Blade Templates** (moteur de templates Laravel)
- **Tailwind CSS 3.x** pour le styling
- **Leaflet.js 1.9.4** pour la cartographie
- **Alpine.js** (inclus avec Breeze)

### APIs externes
- **data.gouv.fr** : Effectifs BTS par établissement
- **data.gouv.fr** : Référentiel rémunérations métiers du numérique

---

## 📦 Installation

### Prérequis
- PHP >= 8.1
- Composer
- Node.js et npm
- SQLite (ou MySQL)

### Étapes d'installation

1. **Cloner le repository**
```bash