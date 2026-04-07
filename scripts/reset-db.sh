#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$repo_root"

if [ ! -f .env ]; then
  echo ".env is missing. Run ./scripts/docker-up.sh first." >&2
  exit 1
fi

if [ ! -f cbc.sql ]; then
  echo "cbc.sql is missing from the repo root." >&2
  exit 1
fi

set -a
. ./.env
set +a

docker compose up -d db app

if [ -d wp-content/uploads ]; then
  chmod -R a+rwX wp-content/uploads
fi

docker compose exec -T db mariadb -uroot "-p${MYSQL_ROOT_PASSWORD}" -e \
  "DROP DATABASE IF EXISTS \`${MYSQL_DATABASE}\`; CREATE DATABASE \`${MYSQL_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;"

docker compose exec -T db mariadb -uroot "-p${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}" < cbc.sql

./scripts/wp.sh option update siteurl "${WP_SITEURL}"
./scripts/wp.sh option update home "${WP_HOME}"
./scripts/wp.sh search-replace "${LIVE_SITE_URL}" "${WP_HOME}" --all-tables-with-prefix --skip-columns=guid --quiet
./scripts/wp.sh search-replace "http://${LIVE_SITE_URL#https://}" "${WP_HOME}" --all-tables-with-prefix --skip-columns=guid --quiet || true
./scripts/wp.sh rewrite structure '/%postname%/' --hard >/dev/null 2>&1 || true

if ./scripts/wp.sh user get "${LOCAL_ADMIN_USER}" >/dev/null 2>&1; then
  ./scripts/wp.sh user update "${LOCAL_ADMIN_USER}" \
    --user_email="${LOCAL_ADMIN_EMAIL}" \
    --user_pass="${LOCAL_ADMIN_PASSWORD}" >/dev/null
else
  ./scripts/wp.sh user create "${LOCAL_ADMIN_USER}" "${LOCAL_ADMIN_EMAIL}" \
    --role=administrator \
    --user_pass="${LOCAL_ADMIN_PASSWORD}" >/dev/null
fi

echo "Database imported and local URLs rewritten."
echo "Local admin: ${LOCAL_ADMIN_USER}"
