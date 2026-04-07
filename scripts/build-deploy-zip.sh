#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$repo_root"

manifest="deploy/custom-code.paths"
build_dir="deploy/build"

if [ ! -f "$manifest" ]; then
  echo "Missing $manifest" >&2
  exit 1
fi

mkdir -p "$build_dir"

mapfile -t paths < <(grep -v '^[[:space:]]*$' "$manifest" | grep -v '^[[:space:]]*#')

if [ "${#paths[@]}" -eq 0 ]; then
  echo "No paths configured in $manifest" >&2
  exit 1
fi

for path in "${paths[@]}"; do
  if [ ! -e "$path" ]; then
    echo "Configured path does not exist: $path" >&2
    exit 1
  fi
done

timestamp="$(date +%Y%m%d-%H%M%S)"
artifact="$build_dir/cbc-custom-code-$timestamp.zip"

zip -qr "$artifact" "${paths[@]}" \
  -x "*/.git/*" "*/.git" "*/error_log" "*.log" "*.swp" "deploy/build/*"

echo "$artifact"
