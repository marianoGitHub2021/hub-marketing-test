#!/usr/bin/env bash
# Prepara l'Hub in GitHub Codespaces: dipendenze, asset, .env di sviluppo e database SQLite.
set -euo pipefail

composer install --no-interaction --prefer-dist --no-progress
npm install --no-audit --no-fund
npm run build

if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate --no-interaction
    sed -i 's/^HUB_DEV_LOGIN=.*/HUB_DEV_LOGIN=true/' .env
fi

# In Codespaces l'app è servita in https tramite il proxy di GitHub.
if [ -n "${CODESPACE_NAME:-}" ]; then
    url="https://${CODESPACE_NAME}-8000.${GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN}"
    sed -i "s|^APP_URL=.*|APP_URL=${url}|" .env
    sed -i 's/^TRUSTED_PROXIES=.*/TRUSTED_PROXIES=*/' .env
    grep -q '^TRUSTED_PROXIES=' .env || echo 'TRUSTED_PROXIES=*' >> .env
fi

touch database/database.sqlite
php artisan migrate --force --no-interaction

echo
echo "Hub Marketing pronto. Si apre sulla porta 8000 (scheda Ports)."
echo "Accedi con il riquadro 'Accesso di sviluppo' e un'email @network-service.it."
echo "Per diventare admin: php artisan hub:make-admin tua.email@network-service.it"
