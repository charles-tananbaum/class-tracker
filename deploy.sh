#!/bin/bash

# HBS Class Tracker Deployment Script

echo "🚀 Starting deployment..."

# Ensure database file exists
if [ ! -f database/database.sqlite ]; then
    echo "📁 Creating database file..."
    touch database/database.sqlite
    chmod 664 database/database.sqlite
fi

# Run migrations
echo "🔄 Running migrations..."
php artisan migrate --force

# Seed the database
echo "🌱 Seeding database..."
php artisan db:seed --class=ClassSeeder --force

# Clear and cache config
echo "🧹 Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Optimize
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment complete!"

