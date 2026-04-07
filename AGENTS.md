# Repository Guidelines

## Project Structure & Module Organization
This repository is a deployed WordPress tree. Keep changes inside `wp-content/` unless you are intentionally updating WordPress core. The main theme lives in `wp-content/themes/NativeChurch/`, with shared PHP in `framework/`, templates at the theme root, assets under `assets/`, and translations in `language/`. Site-specific plugin logic lives in `wp-content/plugins/nativechurch-core/`, `wp-content/plugins/isermons/`, `wp-content/plugins/ipray/`, and `wp-content/plugins/eventer/`. Treat `wp-admin/`, `wp-includes/`, and bundled `vendor/` code as upstream. Media uploads, maintenance files, and local config are not part of normal code changes.

## Build, Test, and Development Commands
There is no root build tool such as Composer or npm in this repo. Common local checks are:

```bash
php -l wp-content/themes/NativeChurch/functions.php
find wp-content/themes/NativeChurch wp-content/plugins/nativechurch-core wp-content/plugins/isermons -name '*.php' -print0 | xargs -0 -n1 php -l
mysql -u <user> -p <database> < cbc.sql
```

Use the first command for a quick syntax check, the second for a broader lint pass, and the third to load the provided database dump into a local WordPress environment. Serve the repo with your usual Apache, Nginx, or local WordPress stack.

## Coding Style & Naming Conventions
Match the surrounding legacy WordPress style instead of reformatting whole files. Use PHP with 4-space indentation where that is already established, `snake_case` for functions, and WordPress hooks such as `add_action()` and `add_filter()` near related logic. Keep template filenames descriptive and WordPress-native, for example `single-sermons.php` or `taxonomy-event-category.php`. Preserve existing CSS section comments in theme and plugin stylesheets.

## Testing Guidelines
There is no committed PHPUnit or JavaScript test suite. For each change, run PHP syntax checks and perform manual QA in the affected area: home page, sermons, events, forms, Elementor widgets, and admin settings when relevant. Document the pages, shortcodes, or post types you verified in the PR.

## Commit & Pull Request Guidelines
Recent history uses short imperative subjects, often with prefixes such as `fix:` and `refactor:`. Follow that pattern, for example `fix: guard empty sermon audio metadata`. Keep commits focused and avoid mixing theme, plugin, and content changes. PRs should include a short summary, any database or config steps, linked issues, and screenshots for front-end or wp-admin updates.

## Security & Configuration Tips
Never commit secrets or machine-specific settings from `wp-config.php`. Respect `.gitignore`: `wp-content/uploads/`, `error_log`, upgrade directories, and maintenance artifacts should stay untracked.
