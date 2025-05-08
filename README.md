# Projet Histoire Guidée

Une application web interactive permettant aux utilisateurs de vivre des histoires guidées avec des choix multiples, des énigmes et un système d'inventaire.

## 🎮 Fonctionnalités

- **Histoires Interactives** : Parcourez différentes histoires avec des choix multiples
- **Système d'Énigmes** : Résolvez des énigmes pour progresser dans l'histoire
- **Inventaire** : Collectez et gérez des objets tout au long de votre aventure
- **Système de Progression** : Sauvegardez votre progression et reprenez vos histoires plus tard
- **Audio** : Ambiance sonore pour une immersion totale
- **Interface Responsive** : Compatible avec tous les appareils

## 🛠️ Technologies Utilisées

- **Backend** : Laravel (PHP)
- **Frontend** : Vue.js
- **Base de données** : MySQL/PostgreSQL
- **Authentication** : Laravel Sanctum

## 📋 Prérequis

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL ou PostgreSQL
- Serveur web (Apache/Nginx)

## 🚀 Installation

1. **Cloner le repository**
   ```bash
   git clone [URL_DU_REPO]
   cd [NOM_DU_PROJET]
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances JavaScript**
   ```bash
   npm install
   ```

4. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurer la base de données**
   - Modifier le fichier `.env` avec vos informations de base de données
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=histoire_guidee
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Migrer la base de données**
   ```bash
   php artisan migrate
   ```

7. **Compiler les assets**
   ```bash
   npm run dev
   ```

8. **Lancer le serveur**
   ```bash
   php artisan serve
   ```

## 🎯 Structure du Projet

- `app/Http/Controllers/Story/` : Contrôleurs pour la gestion des histoires
- `resources/js/components/` : Composants Vue.js
- `resources/js/pages/` : Pages principales de l'application
- `resources/views/` : Templates Blade
- `routes/` : Définition des routes

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
- Gestion des images et sons

### Inventaire
- Collecte d'objets
- Sauvegarde persistante
- Affichage dans l'interface

## 🔒 Sécurité

- Authentification utilisateur
- Protection CSRF
- Validation des entrées
- Gestion sécurisée des sessions

## 🤝 Contribution

1. Fork le projet
2. Créer une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Commit vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📝 Licence

Ce projet est sous licence [MIT](LICENSE).

## 👥 Auteurs

- [Votre Nom] - *Développement initial*

## 🙏 Remerciements

- Tous les contributeurs qui ont participé au projet
- La communauté Laravel et Vue.js
