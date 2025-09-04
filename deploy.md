# Guide de Déploiement Railway

## Étapes pour déployer sur Railway :

### 1. Créer un compte Railway
- Aller sur https://railway.app
- Cliquer sur "Login" puis "GitHub"
- Autoriser Railway à accéder à votre GitHub

### 2. Créer un nouveau projet
- Cliquer sur "New Project"
- Sélectionner "Deploy from GitHub repo"
- Choisir votre repository "fatimaknt/gestiondestock"

### 3. Configurer la base de données
- Cliquer sur "Add Service" → "Database" → "MySQL"
- Railway créera automatiquement les variables d'environnement

### 4. Configurer les variables d'environnement
Dans les settings du service web, ajouter :
```
APP_NAME=Gestion de Stock
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-app.railway.app
APP_KEY=base64:VOTRE_CLE_ICI

DB_CONNECTION=mysql
DB_HOST=containers-us-west-xxx.railway.app
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=VOTRE_MOT_DE_PASSE

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME=Gestion de Stock
```

### 5. Générer la clé d'application
```bash
php artisan key:generate
```

### 6. Exécuter les migrations
```bash
php artisan migrate
```

### 7. Créer l'utilisateur admin
```bash
php artisan db:seed
```

## URL finale :
https://votre-app.railway.app
