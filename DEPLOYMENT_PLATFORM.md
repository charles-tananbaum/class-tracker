# Deployment Platform Commands

## Build Commands
These run during the build phase (before deployment):

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Deployment Commands
These run after deployment (when the app is live):

```bash
php artisan migrate --force
php artisan db:seed --class=ClassSeeder --force
```

## Alternative: Single Build Command
If your platform only has one "build" or "deploy" command, use this:

```bash
composer install --no-dev --optimize-autoloader && \
php artisan migrate --force && \
php artisan db:seed --class=ClassSeeder --force && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache
```

## Platform-Specific Notes

### Railway / Render / Fly.io
- **Build Command:** `composer install --no-dev --optimize-autoloader`
- **Deploy/Start Command:** 
  ```bash
  php artisan migrate --force && \
  php artisan db:seed --class=ClassSeeder --force && \
  php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
  ```

### Heroku
- **Buildpack:** `heroku/php`
- **Procfile:**
  ```
  release: php artisan migrate --force && php artisan db:seed --class=ClassSeeder --force
  web: vendor/bin/heroku-php-apache2 public/
  ```

### Vercel / Netlify
- These platforms are typically for static sites, not Laravel
- Consider Railway, Render, or Fly.io instead

### Shared Hosting (cPanel, etc.)
- Run migrations manually via SSH:
  ```bash
  php artisan migrate --force
  php artisan db:seed --class=ClassSeeder --force
  ```

## Important Notes

1. **Always run migrations BEFORE the app starts serving requests**
2. **Use `--force` flag** to skip confirmation prompts in non-interactive environments
3. **Database file permissions:** Ensure the `database/` directory is writable (chmod 775)
4. **Storage permissions:** Ensure the `storage/` directory is writable (chmod 775)

## Troubleshooting

### "Failed connecting to unix:///tmp/cloud-init.sock" Error

This error is typically from the deployment platform's system services, not your application. To fix:

1. **Check your deployment logs** - Look for the actual error after this message
2. **Verify migrations are running** - The real issue might be migration failures
3. **Try running migrations manually** in your deployment platform's console/SSH:
   ```bash
   php artisan migrate --force
   ```

### Migration Errors

If migrations fail, you can reset and start fresh:
```bash
php artisan migrate:fresh --force
php artisan db:seed --class=ClassSeeder --force
```

**Note:** This will delete all existing data. Only use on fresh deployments.

### Database Connection Issues

If you see database connection errors:
1. Verify `.env` file has correct database settings
2. Check that `database/database.sqlite` file exists and is writable
3. Ensure `database/` directory has proper permissions (775)

