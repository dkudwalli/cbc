#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$repo_root"

if [ $# -eq 0 ]; then
  echo "Usage: ./scripts/wp.sh <wp-cli-args...>" >&2
  exit 1
fi

docker compose run --rm --no-deps wpcli "$@"
