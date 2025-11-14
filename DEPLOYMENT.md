# Deployment Instructions

## Quick Deploy

After pulling the latest code, run:

```bash
./deploy.sh
```

Or manually:

```bash
# 1. Create database file (if it doesn't exist)
touch database/database.sqlite
chmod 664 database/database.sqlite

# 2. Run migrations
php artisan migrate --force

# 3. Seed the classes
php artisan db:seed --class=ClassSeeder --force

# 4. Clear and optimize
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

## First Time Setup

1. **Copy environment file:**
   ```bash
   cp .env.example .env
   ```

2. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

3. **Set database permissions:**
   ```bash
   chmod -R 775 database
   chmod -R 775 storage
   ```

4. **Run the deployment script:**
   ```bash
   ./deploy.sh
   ```

## Troubleshooting

### Database file doesn't exist
The `AppServiceProvider` will automatically create the database file, but you can also create it manually:
```bash
touch database/database.sqlite
chmod 664 database/database.sqlite
```

### Tables don't exist
Run migrations:
```bash
php artisan migrate --force
```

### Classes not showing
Seed the database:
```bash
php artisan db:seed --class=ClassSeeder --force
```

