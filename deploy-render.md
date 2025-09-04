# Guide de Déploiement Render

## Étapes pour déployer sur Render :

### 1. Créer un compte Render
- Aller sur https://render.com
- Cliquer sur "Get Started for Free"
- Choisir "Sign up with GitHub"
- Autoriser Render à accéder à votre GitHub

### 2. Créer un nouveau service
- Cliquer sur "New +" → "Web Service"
- Connecter votre repository GitHub
- Sélectionner "fatimaknt/gestiondestock"

### 3. Configurer le service web
- **Name**: gestiondestock
- **Environment**: PHP
- **Build Command**: composer install
- **Start Command**: php artisan serve --host=0.0.0.0 --port=$PORT
- **Health Check Path**: /

### 4. Ajouter la base de données
- Cliquer sur "New +" → "PostgreSQL"
- **Name**: gestiondestock-db
- **Plan**: Free
- Noter les informations de connexion

### 5. Configurer les variables d'environnement
Dans le service web, ajouter :
```
APP_NAME=Gestion de Stock
APP_ENV=production
APP_DEBUG=false
APP_URL=https://gestiondestock.onrender.com
APP_KEY=base64:VOTRE_CLE_ICI

DB_CONNECTION=pgsql
DB_HOST=your-postgres-host
DB_PORT=5432
DB_DATABASE=your-database-name
DB_USERNAME=your-username
DB_PASSWORD=your-password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME=Gestion de Stock
```

### 6. Déployer
- Cliquer sur "Create Web Service"
- Attendre le déploiement (5-10 minutes)
- Générer la clé d'application dans les logs

### 7. Exécuter les migrations
Dans les logs du service, exécuter :
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## URL finale :
https://gestiondestock.onrender.com
