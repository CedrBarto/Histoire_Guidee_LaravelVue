# Projet Histoire Guidée

Projet de cours Laravel/Vue. 
Développement d'une application web interactive permettant aux utilisateurs de vivre des histoires guidées avec des choix, des énigmes et un système d'inventaire.

## 🎮 Fonctionnalités

- **Histoires Interactives** : Parcourez différentes histoires avec des choix multiples
- **Système d'Énigmes** : Résolvez des énigmes pour progresser dans l'histoire
- **Inventaire** : Collectez et gérez des objets tout au long de votre aventure
- **Système de Progression** : Sauvegardez votre progression et reprenez vos histoires plus tard
- **Interface Responsive** : Compatible avec tous les appareils

## 🛠️ Technologies Utilisées

- **Backend** : Laravel (PHP)
- **Frontend** : Vue.js
- **Base de données** : SQLite
- **Authentication** : Laravel Breeze

## 🎯 Structure du Projet

- `app/Http/Controllers/Story/` : Contrôleurs pour la gestion des histoires
- `resources/js/components/` : Composants Vue.js
- `resources/js/pages/` : Pages principales de l'application
- `resources/views/` : Templates Blade
- `routes/web.php` : Définition des routes

## 🎨 Fonctionnalités Principales

### Histoires
- Liste des histoires disponibles
- Système de progression par histoire
- Possibilité de reprendre une histoire en cours
- Affichage des détails de progression

### Scènes
- Affichage dynamique des scènes
- Système de choix multiples
- Énigmes interactives
- Gestion des images (concept du son mis en place mais non utilisé)

### Inventaire
- Collecte d'objets
- Sauvegarde persistante
- Affichage dans l'interface

## 🔒 Sécurité

- Authentification utilisateur
- Protection CSRF
- Validation des entrées
- Gestion sécurisée des sessions

## 👥 Auteurs

- Cédric Bartolacelli

## 🙏 Remerciements

- Retours et conseils des enseignants
