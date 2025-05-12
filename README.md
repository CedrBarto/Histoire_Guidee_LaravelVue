# Projet Histoire Guidée

Développement d'un projet fullstack Laravel et Vue permettant aux utilisateurs de jouer à des histoires guidées par des choix, des énigmes et un système d'inventaire.
Actuellement qu'une seule histoire est a été développée.

## 🎮 Fonctionnalités

- **Histoire Guidée** : Parcourez une histoire originale avec des choix multiples
- **Système d'Énigmes** : Résolvez des énigmes pour progresser dans l'histoire
- **Inventaire** : Collectez des objets durant votre histoire
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

### Histoires
- Liste des histoires disponibles
- Système de progression par histoire
- Possibilité de reprendre une histoire en cours
- Affichage des détails de progression

### Scènes
- Affichage dynamique des scènes
- Système de choix multiples
- Énigmes à résoudre
- Gestion des images (concept du son imaginé mais non utilisé pour des raisons techniques) 

### Inventaire
- Collecte d'objets
- Sauvegarde des objets
- Affichage dans l'interface

## 🔒 Sécurité

- Authentification utilisateur nécessaire
- Protection CSRF
- Validation des entrées

## 👥 Auteurs

- Cédric Bartolacelli

## 🙏 Remerciements

- Retours et conseils des enseignants
