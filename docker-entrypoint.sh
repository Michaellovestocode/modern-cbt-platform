#!/bin/sh
set -e

echo "🚀 Starting Laravel application..."

# Wait a moment for database to be ready
sleep 2

echo "📦 Optimizing configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🗄️  Running database migrations..."
php artisan migrate --force || echo "⚠️  Migrations failed or already run"

echo "🌱 Seeding database..."
php artisan db:seed --force || echo "⚠️  Seeding skipped (data may already exist)"

echo "🔗 Creating storage link..."
php artisan storage:link || echo "⚠️  Storage link already exists"

echo "✅ Starting Apache..."
exec apache2-foreground