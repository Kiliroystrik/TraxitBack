# Utiliser l'image PHP officielle avec FPM
FROM php:8.3-fpm

# Installer les extensions PHP nécessaires et d'autres outils
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    curl \
    nginx \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    --no-install-recommends --fix-missing

# Installer APCu
RUN pecl install apcu \
    && docker-php-ext-enable apcu

# Installer les extensions PHP
RUN docker-php-ext-install pdo pdo_pgsql \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

# Configurer et installer GD
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd

# Installer Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug,coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer Symfony CLI
RUN curl -sS 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install -y symfony-cli

# Installer Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Installer Yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update \
    && apt-get install -y yarn

# Définir le répertoire de travail
WORKDIR /var/www/symfony

# Copier les fichiers de l'application
COPY . .

# Définir l'utilisateur non-root
RUN adduser --disabled-password --gecos '' appuser && chown -R appuser:appuser /var/www/symfony
USER appuser

# Installer les dépendances PHP
RUN composer install

# Revenir à l'utilisateur root pour exécuter PHP-FPM et Nginx
USER root

# Copier la configuration de Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Exposer le port 80 pour Nginx
EXPOSE 80

# Démarrer PHP-FPM et Nginx
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
