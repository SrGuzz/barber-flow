######################################################################
#  ETAPA 1 · Composer – PHP deps + Mary UI                           #
######################################################################
FROM composer:2 AS vendor
WORKDIR /app

# Copia TODO o projeto logo de início — o artisan já estará presente
COPY . .

# Instala dependências de produção
RUN composer install --no-dev --prefer-dist --optimize-autoloader \
 && composer require --no-interaction --no-dev robsontenorio/mary || true \
 && php artisan mary:install --no-interaction || true

######################################################################
#  ETAPA 2 · Node – build dos assets                                 #
######################################################################
FROM node:22 AS assets
WORKDIR /app
COPY --from=vendor /app .

RUN npm ci && npm run build        # gera public/build/.vite + manifest

######################################################################
#  ETAPA 3 · Runtime Apache + PHP                                    #
######################################################################
FROM php:8.3-apache

# Extensões PHP indispensáveis
RUN apt-get update && apt-get install -y \
        git zip unzip curl libpng-dev libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql bcmath

# .htaccess + docroot
RUN a2enmod rewrite \
 && sed -ri '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride .*/AllowOverride All/' /etc/apache2/apache2.conf

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g'  /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g'   /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copia código, vendor e assets compilados
COPY --from=vendor /app            /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build

WORKDIR /var/www/html

# Permissões
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Manifest do Vite
ENV LARAVEL_VITE_BUILD_DIRECTORY=build/.vite

EXPOSE 8080

CMD ["sh","-c","\
    [ -f .env ] || cp .env.example .env; \
    php artisan key:generate --no-interaction --ansi; \
    php artisan migrate:fresh --seed --force; \
    php artisan config:cache && php artisan route:cache && php artisan view:cache; \
    apache2-foreground"]
