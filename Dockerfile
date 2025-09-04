# Utiliser l'image PHP officielle
FROM php:8.3-cli

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY . .

# Installer les dépendances
RUN composer update --no-dev --optimize-autoloader

# Configurer les permissions
RUN chmod -R 755 storage bootstrap/cache

# Exposer le port
EXPOSE $PORT

# Commande de démarrage
CMD php artisan serve --host=0.0.0.0 --port=$PORT
