# 🚀 Gestion de Stock - Application Laravel

Une application complète de gestion de stock développée avec Laravel, MySQL et TailwindCSS, conçue pour les boutiques et commerces.

## ✨ Fonctionnalités Principales

### 👤 Authentification & Sécurité
- **Inscription/Connexion sécurisée** avec Laravel Sanctum
- **Gestion des rôles** : Admin, Vendeur, Caissier, Gestionnaire
- **Protection CSRF** et validation des entrées
- **Hashage des mots de passe** avec bcrypt

### 🏪 Gestion de la Boutique
- **Profil personnalisable** : nom, logo, couleurs, description
- **Multi-boutiques** : un vendeur peut gérer plusieurs boutiques
- **Gestion des employés** avec rôles et permissions

### 📦 Gestion des Produits
- **CRUD complet** : Ajouter, Modifier, Supprimer des produits
- **Catégorisation** : Parfums, Vêtements, Accessoires, Cosmétiques, Électronique
- **Gestion des fournisseurs** et informations détaillées
- **Upload d'images** pour les produits
- **Codes SKU et codes-barres** uniques

### 📊 Gestion du Stock
- **Suivi en temps réel** des entrées et sorties
- **Alertes automatiques** pour stock bas et rupture
- **Historique complet** des mouvements de stock
- **Gestion des dates d'expiration**

### 💰 Gestion des Ventes & Achats
- **Enregistrement des ventes** avec clients et détails
- **Gestion des achats** auprès des fournisseurs
- **Génération de factures** en PDF
- **Suivi du chiffre d'affaires** et des bénéfices

### 📈 Tableau de Bord
- **Statistiques graphiques** des ventes
- **Indicateurs clés** : stock, ventes, alertes
- **Graphiques** d'évolution des ventes
- **Vue d'ensemble** de la boutique

### 🔒 Sécurité de la Base de Données
- **Accès restreint** par rôle et boutique
- **Validation stricte** des données
- **Sauvegardes** et intégrité des données

### 🎨 Interface Utilisateur
- **Design moderne** avec TailwindCSS
- **Responsive** (mobile, tablette, desktop)
- **Thème clair/sombre** 🌙☀️
- **Interface intuitive** et accessible

## 🛠️ Technologies Utilisées

- **Backend** : Laravel 12.x
- **Base de données** : MySQL 8.0+
- **Frontend** : Blade + TailwindCSS + Alpine.js
- **Authentification** : Laravel Sanctum
- **Permissions** : Spatie Laravel Permission
- **PDF** : DomPDF
- **Excel** : Maatwebsite Excel

## 📋 Prérequis

- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 18+ et npm
- MAMP/XAMPP (pour le développement local)

## 🚀 Installation

### 1. Cloner le projet
```bash
git clone <repository-url>
cd gestion_de_stock
```

### 2. Installer les dépendances PHP
```bash
composer install
```

### 3. Installer les dépendances Node.js
```bash
npm install
```

### 4. Configuration de l'environnement
```bash
cp .env.example .env
```

Modifier le fichier `.env` avec vos informations de base de données :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=gestion_stock
DB_USERNAME=root
DB_PASSWORD=root
```

### 5. Générer la clé d'application
```bash
php artisan key:generate
```

### 6. Créer la base de données
```sql
CREATE DATABASE gestion_stock CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Exécuter les migrations et seeders
```bash
php artisan migrate:fresh --seed
```

### 8. Compiler les assets
```bash
npm run build
```

### 9. Démarrer le serveur
```bash
php artisan serve
```

## 🔐 Comptes de Test

Après l'installation, vous pouvez vous connecter avec :

- **Email** : admin@aurya.com
- **Mot de passe** : password
- **Rôle** : Administrateur

## 📱 Utilisation

### Connexion
1. Accédez à `/login`
2. Entrez vos identifiants
3. Vous serez redirigé vers le tableau de bord

### Création d'un compte
1. Accédez à `/register`
2. Remplissez le formulaire avec vos informations
3. Une boutique sera automatiquement créée
4. Connectez-vous avec vos nouveaux identifiants

### Tableau de Bord
- **Vue d'ensemble** des statistiques
- **Alertes de stock** en temps réel
- **Ventes récentes** et graphiques
- **Navigation** vers toutes les fonctionnalités

## 🗄️ Structure de la Base de Données

### Tables Principales
- `users` - Utilisateurs et employés
- `shops` - Boutiques et informations
- `categories` - Catégories de produits
- `products` - Produits et stock
- `suppliers` - Fournisseurs
- `sales` - Ventes et factures
- `purchases` - Achats et commandes
- `stock_movements` - Mouvements de stock
- `stock_alerts` - Alertes de stock

### Relations
- Un utilisateur peut appartenir à plusieurs boutiques
- Chaque produit appartient à une catégorie et un fournisseur
- Les ventes et achats sont liés aux utilisateurs et boutiques
- Le stock est suivi via les mouvements de stock

## 🔧 Configuration Avancée

### Permissions et Rôles
Les permissions sont gérées via Spatie Laravel Permission :

- **Admin** : Accès complet à toutes les fonctionnalités
- **Vendeur** : Gestion des produits, stock, ventes et achats
- **Caissier** : Enregistrement des ventes uniquement
- **Gestionnaire** : Gestion des produits et du stock

### Personnalisation des Boutiques
Chaque boutique peut personnaliser :
- Nom et description
- Logo et couleurs
- Informations de contact
- Paramètres spécifiques

## 📊 Fonctionnalités Avancées

### Export de Données
- **Export Excel** des ventes, achats et stock
- **Génération de factures** en PDF
- **Rapports détaillés** et statistiques

### Alertes et Notifications
- **Stock bas** : notification automatique
- **Rupture de stock** : alerte immédiate
- **Expiration** : avertissement pour les produits périssables

### Gestion Multi-boutiques
- **Séparation** complète des données entre boutiques
- **Gestion centralisée** des utilisateurs
- **Rôles spécifiques** par boutique

## 🚀 Déploiement

### Production
1. Configurer la base de données de production
2. Optimiser les performances Laravel
3. Configurer le serveur web (Apache/Nginx)
4. Mettre en place les sauvegardes automatiques

### Environnement de Développement
1. Utiliser Laravel Sail pour Docker
2. Configurer Xdebug pour le débogage
3. Utiliser les outils de développement Laravel

## 🤝 Contribution

1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 🆘 Support

Pour toute question ou problème :
- Créer une issue sur GitHub
- Consulter la documentation Laravel
- Vérifier les logs d'erreur

## 🔮 Roadmap

### Fonctionnalités Futures
- [ ] Application mobile React Native
- [ ] Intégration avec des services de paiement
- [ ] Système de caisse enregistreuse
- [ ] Gestion des clients et fidélité
- [ ] Intégration avec des marketplaces
- [ ] Système de notifications push
- [ ] API REST complète
- [ ] Webhooks pour intégrations tierces

---

**Développé avec ❤️ en utilisant Laravel et les meilleures pratiques de développement web.**
