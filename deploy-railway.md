# 🚀 Déploiement sur Railway

## 📋 Étapes de Déploiement

### 1. Créer un Compte Railway
- Aller sur [railway.app](https://railway.app)
- Se connecter avec GitHub

### 2. Créer un Nouveau Projet
- Cliquer sur "New Project"
- Sélectionner "Deploy from GitHub repo"
- Choisir le repository `gestiondestock`

### 3. Ajouter une Base de Données
- Cliquer sur "+ New"
- Sélectionner "Database"
- Choisir "PostgreSQL"

### 4. Configurer les Variables d'Environnement
```
APP_NAME=Gestion de Stock
APP_ENV=production
APP_KEY=base64:UTdeL6ncmADXXdG05QnRtcisCoAkMcYraupjorZf
APP_DEBUG=false
APP_URL=https://votre-app.railway.app

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=auto-généré

CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

FORCE_HTTPS=true
```

### 5. Déployer
- Railway va automatiquement détecter le Dockerfile
- Le déploiement se fera automatiquement

## ✅ Avantages de Railway
- ✅ Base de données PostgreSQL incluse
- ✅ Déploiement automatique
- ✅ HTTPS automatique
- ✅ Plus stable que Render
- ✅ Support Laravel natif
