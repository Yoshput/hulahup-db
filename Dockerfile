# ============================================================
# Stage 1: Node.js — build frontend assets
# ============================================================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm run build

# ============================================================
# Stage 2: PHP — Laravel application
# ============================================================
FROM php:8.2-cli-alpine AS app

RUN apk add --no-cache \
        bash \
        curl \
        git \
        unzip \
        netcat-openbsd \
        gettext \
        libpng-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        freetype-dev \
        oniguruma-dev \
        libxml2-dev \
        mysql-client \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mbstring \
        xml \
        ctype \
        bcmath \
        gd \
        opcache \
        pcntl \
        fileinfo

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-interaction \
        --no-progress \
        --optimize-autoloader \
        --no-scripts

COPY . .

COPY --from=frontend /app/public/build ./public/build

RUN composer run-script post-autoload-dump --no-interaction

RUN cp .env.example .env || true

RUN php artisan key:generate --no-interaction --force || true

RUN mkdir -p storage/framework/{sessions,views,cache/data} \
             storage/logs \
             bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

