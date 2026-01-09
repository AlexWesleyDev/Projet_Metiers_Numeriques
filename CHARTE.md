# 🤖 Charte d'utilisation des outils d'aide à l'écriture du code

## Projet : Métiers du Numérique - Plateforme d'Orientation BTS

### Outils utilisés

**Claude 3.5 Sonnet (Anthropic)** - Assistant IA conversationnel

### Utilisation dans le projet

#### Ce qui a été généré avec l'aide de l'IA :

1. **Services Backend** 
   - `DataGouvService.php` : Appels API data.gouv.fr
   - Logique de filtrage BTS numérique

3. **Controllers** 
   - Logique métier et validation

4. **Vues Blade** 
   - Page d'accueil avec carte Leaflet
   - Formulaire de recherche avancée
   - Dashboard utilisateur
   - Pages de résultats

6. **Documentation** (100% IA)
   - README.md
   - Commentaires dans le code
   - Cette charte

#### Ce qui a été fait manuellement :

1. **Architecture globale** 
   - Choix Laravel + Breeze
   - Structure MVC avec Services
   - Stratégie de cache API


2. **Configuration initiale**
   - Installation de Laravel
   - Configuration .env
   - Structure de dossiers

3. **Tests et débuggage**
   - Exécution des commandes artisan
   - Tests de l'application
   - Corrections d'erreurs

5. **Migrations**
   - Table `search_stats`
   - Structure complète avec index

4. **Décisions**
   - Choix des fonctionnalités
   - Priorisation des features
   - Validation de l'architecture

5. **Adaptation et personnalisation**
   - Ajustement des données de fallback
   - Personnalisation du design
   - Configuration spécifique de l'environnement

### Méthodologie de travail

1. **Analyse du besoin** : Lecture du cahier des charges
2. **Questions ciblées** à Claude pour chaque fonctionnalité
3. **Revue et test** manuel du code généré
4. **Itérations** en cas d'erreur ou d'amélioration

### Bénéfices de l'utilisation de l'IA

✅ **Code de qualité** : Patterns Laravel respectés, commentaires  
✅ **Pédagogie** : Explications détaillées à chaque étape  
✅ **Best practices** : Architecture propre et maintenable  
✅ **Documentation** : README complet généré  

### Limites rencontrées

❌ Quelques erreurs de syntaxe (middleware)  
❌ Nécessité de tester manuellement chaque feature  
❌ Adaptation nécessaire aux URLs réelles des API  
❌ Personnalisation des données et des vues 

---

**Date de rédaction** : Janvier 2026  
**Développeur** : Wesley  