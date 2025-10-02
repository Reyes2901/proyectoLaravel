FROM php:8.2-cli

# Instalar dependencias de Laravel
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

EXPOSE 3000

CMD php artisan serve --host=0.0.0.0 --port=3000
