# Christ Bible Church

This repository contains the site-owned code and local workflow for the Christ Bible Church WordPress site hosted on Hostinger. It is intentionally not a full WordPress mirror: WordPress core, Elementor, other vendor plugins, uploads, and hosting-managed files stay on disk for runtime but are not tracked in Git.

## What Is Tracked
- `wp-content/themes/NativeChurch`
- `wp-content/plugins/nativechurch-core`
- `wp-content/plugins/isermons`
- `wp-content/plugins/ipray`
- `wp-content/plugins/eventer`
- local tooling in `docker/`, `scripts/`, `deploy/`, and `docs/`

## Local Development
The local environment uses Docker with WordPress, MariaDB, and WP-CLI.

```bash
cp .env.example .env
./scripts/docker-up.sh
./scripts/reset-db.sh
```

Then open `http://localhost:8080`.

Notes:
- `cbc.sql` is a local-only database dump input and is not committed.
- `docker/wp-config.local.php` is mounted into the container, so the production `wp-config.php` remains untouched.
- The local admin credentials come from `.env`.

## Daily Workflow
1. Pull the latest changes on `main`.
2. Start the local stack with `./scripts/docker-up.sh`.
3. Make code changes in the tracked theme/plugin paths.
4. Verify locally.
5. Build a deploy zip with `./scripts/build-deploy-zip.sh`.

The deploy zip is written to `deploy/build/` and contains only the paths listed in `deploy/custom-code.paths`.

## Hostinger Deployment
For code-only changes, upload the generated zip to Hostinger File Manager or via FTP, extract it at the site root, and overwrite the matching `wp-content/` paths.

For database-backed changes such as Elementor edits, menus, widgets, or settings, use Hostinger Staging first. Do not use Staging Publish for routine code-only releases, because it publishes both files and database.

## Repository Notes
- `legacy-origin` points to the pre-cleanup GitHub repo for archive/reference.
- Contributor guidance lives in `AGENTS.md`.
- Deployment and environment details live in `docs/hostinger-workflow.md`.
