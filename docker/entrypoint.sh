#!/usr/bin/env bash
set -euo pipefail

# Colors
GREEN="\033[0;32m"; RED="\033[0;31m"; YELLOW="\033[1;33m"; NC="\033[0m"

echo -e "${YELLOW}Bootstrapping Laravel container...${NC}"

# Ensure correct permissions for storage and bootstrap/cache
mkdir -p storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Ensure uploads directory exists with correct permissions
mkdir -p public/uploads/edukasi/covers
chown -R www-data:www-data public/uploads || true
chmod -R 775 public/uploads || true

# If .env not exists, copy from example
if [ ! -f .env ]; then
  echo -e "${YELLOW}.env not found, copying from .env.example${NC}"
  cp .env.example .env
fi

# Override DB host for containerized MySQL
if grep -q "^DB_HOST=" .env; then
  sed -i "s/^DB_HOST=.*/DB_HOST=mysql/" .env
else
  echo "DB_HOST=mysql" >> .env
fi

# Install PHP dependencies
if [ ! -d vendor ]; then
  echo -e "${YELLOW}Installing Composer dependencies...${NC}"
  composer install --no-interaction --prefer-dist --optimize-autoloader
else
  echo -e "${YELLOW}Composer dependencies already installed, running update check...${NC}"
  composer dump-autoload -o
fi

# Generate app key if missing
php -r "file_exists('.env') && strpos(file_get_contents('.env'), 'APP_KEY=base64:') === false ? (require 'vendor/autoload.php') && passthru('php artisan key:generate') : null;"

# Install Node dependencies and build assets
if [ ! -d node_modules ]; then
  echo -e "${YELLOW}Installing Node dependencies...${NC}"
  npm install --no-fund --no-audit
fi

echo -e "${YELLOW}Running npm build...${NC}"
npm run build

# Wait for MySQL to be ready
echo -e "${YELLOW}Waiting for MySQL at mysql:3306...${NC}"
until nc -z -w 3 mysql 3306; do
  echo -e "${YELLOW}MySQL is unavailable - sleeping${NC}"
  sleep 2
done

# Run migrations fresh and seed
echo -e "${YELLOW}Running php artisan migrate:fresh --force${NC}"
php artisan migrate:fresh --force

echo -e "${YELLOW}Running php artisan db:seed --force${NC}"
php artisan db:seed --force

echo -e "${GREEN}Bootstrap complete. Starting PHP-FPM...${NC}"
php artisan storage:link
exec "$@"