#!/bin/sh
set -e

echo "🚀 Starting Hulahup App initialization..."

# Step 1: Generate .env from .env.example with environment variables
echo "📝 Generating .env from .env.example with runtime environment variables..."

# Export environment variables for envsubst (set defaults if not present)
export APP_NAME="${APP_NAME:-Hulahup App}"
export APP_ENV="${APP_ENV:-production}"
export APP_KEY="${APP_KEY:-}"
export APP_DEBUG="${APP_DEBUG:-false}"
export APP_URL="${APP_URL:-https://hulahup-production.up.railway.app}"
export APP_LOCALE="${APP_LOCALE:-id}"
export APP_FALLBACK_LOCALE="${APP_FALLBACK_LOCALE:-en}"
export APP_FAKER_LOCALE="${APP_FAKER_LOCALE:-id_ID}"
export LOG_CHANNEL="${LOG_CHANNEL:-stack}"
export LOG_LEVEL="${LOG_LEVEL:-error}"
export DB_CONNECTION="${DB_CONNECTION:-mysql}"
export DB_HOST="${DB_HOST:-127.0.0.1}"
export DB_PORT="${DB_PORT:-3306}"
export DB_DATABASE="${DB_DATABASE:-hulahup_db}"
export DB_USERNAME="${DB_USERNAME:-root}"
export DB_PASSWORD="${DB_PASSWORD:-}"
export SESSION_DRIVER="${SESSION_DRIVER:-database}"
export CACHE_STORE="${CACHE_STORE:-database}"
export QUEUE_CONNECTION="${QUEUE_CONNECTION:-database}"

# Use envsubst to expand all environment variables in .env.example
envsubst '${APP_NAME} ${APP_ENV} ${APP_KEY} ${APP_DEBUG} ${APP_URL} ${APP_LOCALE} ${APP_FALLBACK_LOCALE} ${APP_FAKER_LOCALE} ${LOG_CHANNEL} ${LOG_LEVEL} ${DB_CONNECTION} ${DB_HOST} ${DB_PORT} ${DB_DATABASE} ${DB_USERNAME} ${DB_PASSWORD} ${SESSION_DRIVER} ${CACHE_STORE} ${QUEUE_CONNECTION}' < .env.example > .env

echo "✅ .env generated with runtime values"
echo "📋 Generated .env content (first 10 lines):"
head -10 .env

# Verify important .env values
echo ""
echo "🔍 Verification:"
grep "^APP_KEY=" .env | head -1
grep "^DB_HOST=" .env | head -1
grep "^DB_USERNAME=" .env | head -1

# Step 2: Generate APP_KEY if not set
echo "🔑 Checking APP_KEY..."
APP_KEY=$(grep "^APP_KEY=" .env | cut -d'=' -f2)
if [ -z "$APP_KEY" ]; then
    echo "📝 APP_KEY is empty, generating..."
    php artisan key:generate --no-interaction --force 2>&1 | head -5
    if [ $? -eq 0 ]; then
        echo "✅ APP_KEY generated successfully"
    else
        echo "⚠️  APP_KEY generation might have issues but continuing..."
    fi
else
    echo "✅ APP_KEY found: ${APP_KEY:0:20}..."
fi

# Step 3: Wait for database to be ready (if DB_HOST is set)
echo "🔍 Checking database connection..."
DB_HOST=$(grep "^DB_HOST=" .env | cut -d'=' -f2)
DB_PORT=$(grep "^DB_PORT=" .env | cut -d'=' -f2 || echo "3306")
DB_USERNAME=$(grep "^DB_USERNAME=" .env | cut -d'=' -f2)
echo "   Database: $DB_HOST:$DB_PORT as $DB_USERNAME"
if [ -n "$DB_HOST" ] && [ "$DB_HOST" != "127.0.0.1" ]; then
    echo "⏳ Waiting for database at $DB_HOST:$DB_PORT..."
    max_attempts=30
    attempt=0
    while [ $attempt -lt $max_attempts ]; do
        if nc -z "$DB_HOST" "$DB_PORT" 2>/dev/null; then
            echo "✅ Database is reachable"
            break
        fi
        attempt=$((attempt + 1))
        echo "⏳ Attempt $attempt/$max_attempts: Waiting for database..."
        sleep 2
    done
    if [ $attempt -eq $max_attempts ]; then
        echo "⚠️  Warning: Database not reachable after $max_attempts attempts, will attempt migration anyway..."
    fi
fi

# Step 4: Clear all caches first (important for config changes)
echo "🧹 Clearing application caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Step 4B: Show database configuration for debugging
echo "🔍 Database Configuration:"
grep "^DB_" .env | head -5


# Step 5: Run database migrations (non-blocking)
echo "🗄️  Running database migrations..."
if php artisan migrate --force --no-interaction 2>&1; then
    echo "✅ Database migrations completed successfully"
else
    MIGRATION_EXIT=$?
    if [ $MIGRATION_EXIT -eq 0 ]; then
        echo "✅ Migrations completed"
    else
        echo "⚠️  Migrations had issues (exit code: $MIGRATION_EXIT)"
        echo "    This may be normal on first deploy or if schema is already synced"
        echo "    Continuing anyway - app will still start"
    fi
fi

# Step 6: Rebuild caches for production
echo "⚙️  Building application caches..."
php artisan config:cache 2>/dev/null || echo "⚠️  Config cache failed (non-critical)"
php artisan route:cache 2>/dev/null || echo "⚠️  Route cache failed (non-critical)"  
php artisan view:cache 2>/dev/null || echo "⚠️  View cache failed (non-critical)"

# Step 7: Start PHP server with minimal router
PORT=${PORT:-8000}
echo "✅ Application initialization complete!"
echo "🌐 Starting server on 0.0.0.0:$PORT"

# Create ultra-simple router with error output
cat > /tmp/router.php << 'ROUTER'
<?php
// Simplest possible router for Laravel
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$public = '/app/public';

// If it's a static file that exists, serve it
$file = $public . $uri;
if ($uri !== '/' && is_file($file)) {
    return false;
}

// Everything else goes to Laravel
// Set required server variables
$_SERVER['SCRIPT_FILENAME'] = $public . '/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Capture any errors from Laravel
error_reporting(E_ALL);
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo "ERROR [$errno]: $errstr in $errfile:$errline\n";
    return true;
}, E_ALL);

set_exception_handler(function($e) {
    http_response_code(500);
    echo "EXCEPTION: " . $e->getMessage() . "\n" . $e->getTraceAsString();
});

try {
    require $public . '/index.php';
} catch (Exception $e) {
    http_response_code(500);
    echo "FATAL: " . $e->getMessage();
}
ROUTER

# Start server
cd /app && php -S 0.0.0.0:$PORT -r /tmp/router.php 2>&1
