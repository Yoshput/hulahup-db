# ============================================================
# Stage 1: Node.js — build frontend assets
# ============================================================
FROM node:20-alpine AS frontend

WORKDIR /app

# Copy package files and install dependencies
COPY package.json package-lock.json ./
RUN npm ci

# Copy source files needed for the Vite build
COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

# Build production assets
RUN npm run build

# ============================================================
# Stage 2: PHP — Laravel application
# ============================================================
FROM php:8.2-cli-alpine AS app

# Install system dependencies and PHP extensions required by Laravel
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

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files and install PHP dependencies (no dev)
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-interaction \
        --no-progress \
        --optimize-autoloader \
        --no-scripts

# Copy the full application source
COPY . .

# Copy compiled frontend assets from the frontend stage
COPY --from=frontend /app/public/build ./public/build

# Run composer scripts that require the full app to be present
RUN composer run-script post-autoload-dump --no-interaction

# Step 1: Copy .env from .env.example at build time to ensure it exists
RUN cp .env.example .env || true

# Step 2: Generate APP_KEY during build if not present
RUN php artisan key:generate --no-interaction --force || true

# Step 3: Create required storage directories
RUN mkdir -p storage/framework/{sessions,views,cache/data} \
             storage/logs \
             bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Step 4: Copy entrypoint script and make it executable
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose the port PHP's built-in server will listen on
EXPOSE 8000

# Health check to ensure app is responding
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost:${PORT:-8000}/health.html || exit 1

# Use entrypoint script for robust initialization
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
