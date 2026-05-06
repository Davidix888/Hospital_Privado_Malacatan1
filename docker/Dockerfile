FROM php:8.2-apache

WORKDIR /var/www/html

# Dependencias del sistema y extensiones PHP comunes para Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libpq-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip gd \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configuracion Apache para Laravel
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copiar aplicacion
COPY . /var/www/html

# Instalar dependencias PHP (sin dev para imagen mas liviana)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
