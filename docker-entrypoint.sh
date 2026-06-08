#!/bin/sh
set -e

cd /var/www/html

echo "🚀 Starting Hulahup App initialization..."
echo "📂 Working directory: $(pwd)"

# Step 1: Generate .env
echo "📝 Generating .env..."
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

envsubst '${APP_NAME} ${APP_ENV} ${APP_KEY} ${APP_DEBUG} ${APP_URL} ${APP_LOCALE} ${APP_FALLBACK_LOCALE} ${APP_FAKER_LOCALE} ${LOG_CHANNEL} ${LOG_LEVEL} ${DB_CONNECTION} ${DB_HOST} ${DB_PORT} ${DB_DATABASE} ${DB_USERNAME} ${DB_PASSWORD} ${SESSION_DRIVER} ${CACHE_STORE} ${QUEUE_CONNECTION}' < .env.example > .env

echo "✅ .env generated"

# Step 2: Generate APP_KEY if not set
echo "🔑 Checking APP_KEY..."
APP_KEY=$(grep "^APP_KEY=" .env | cut -d'=' -f2)
if [ -z "$APP_KEY" ]; then
    echo "📝 Generating APP_KEY..."
    php artisan key:generate --no-interaction --force 2>&1 || true
else
    echo "✅ APP_KEY found"
fi

# Step 3: Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Step 4: Run migrations (non-blocking)
echo "🗄️  Running migrations..."
php artisan migrate --force --no-interaction 2>&1 || echo "⚠️  Migrations skipped (database may not be available)"

# Step 5: Build caches
echo "⚙️  Building caches..."
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# Step 6: Start Laravel with Artisan serve
PORT=${PORT:-8000}
echo "✅ Initialization complete!"
echo "🌐 Starting Laravel on 0.0.0.0:$PORT"

php artisan serve --host=0.0.0.0 --port=$PORT

