# Local + Hostinger Workflow

## One-time local setup
1. Run `./scripts/docker-up.sh` to create `.env` and start MariaDB + Apache.
2. Run `./scripts/reset-db.sh` to import `cbc.sql`, rewrite URLs from `https://christbiblechurch.in` to your local URL, and create the local admin from `.env`.
3. Open `http://localhost:8080`.

## Daily code workflow
1. Pull `master`.
2. Start Docker with `./scripts/docker-up.sh`.
3. Make theme/plugin code changes locally with Codex.
4. Verify the page or admin screen locally.
5. Build a deploy artifact with `./scripts/build-deploy-zip.sh`.

## Hostinger deployment
Use zip upload for code changes. Upload the zip from `deploy/build/` to Hostinger File Manager, extract it at the site root, and overwrite matching files under `wp-content/`.

Do not use Hostinger Staging Publish for routine code-only releases. That feature publishes both files and database.

## Content workflow
Use Hostinger Staging for Elementor edits, menus, widgets, settings, and other database-backed changes. Verify there, take a fresh backup, then use Hostinger Publish when you are ready to move those staging-originated content changes live.

## Notes
- `deploy/custom-code.paths` defines which directories are packaged.
- The Docker stack mounts `docker/wp-config.local.php` into the container, so your ignored production `wp-config.php` stays untouched.
- Hostinger-specific `mu-plugins` are not part of the deploy package.
