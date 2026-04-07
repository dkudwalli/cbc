<?php
/**
 * Local WordPress config for the Docker stack.
 * This file is mounted into the container and leaves the ignored production
 * wp-config.php in the repo root untouched.
 */

if (!function_exists('cbc_env')) {
    function cbc_env($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false || $value === '') {
            return $default;
        }

        return $value;
    }
}

if (PHP_SAPI === 'cli' && !isset($_SERVER['QUERY_STRING'])) {
    $_SERVER['QUERY_STRING'] = '';
}

define('DB_NAME', cbc_env('WP_DB_NAME', 'cbc'));
define('DB_USER', cbc_env('WP_DB_USER', 'cbc'));
define('DB_PASSWORD', cbc_env('WP_DB_PASSWORD', 'cbc'));
define('DB_HOST', cbc_env('WP_DB_HOST', 'db:3306'));
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

define('AUTH_KEY', 'cbc-local-auth-key');
define('SECURE_AUTH_KEY', 'cbc-local-secure-auth-key');
define('LOGGED_IN_KEY', 'cbc-local-logged-in-key');
define('NONCE_KEY', 'cbc-local-nonce-key');
define('AUTH_SALT', 'cbc-local-auth-salt');
define('SECURE_AUTH_SALT', 'cbc-local-secure-auth-salt');
define('LOGGED_IN_SALT', 'cbc-local-logged-in-salt');
define('NONCE_SALT', 'cbc-local-nonce-salt');

$table_prefix = cbc_env('WORDPRESS_TABLE_PREFIX', 'cbc_');

define('WP_HOME', cbc_env('WP_HOME', 'http://localhost:8080'));
define('WP_SITEURL', cbc_env('WP_SITEURL', 'http://localhost:8080'));
define('WP_ENVIRONMENT_TYPE', cbc_env('WP_ENV', 'local'));

define('WP_DEBUG', filter_var(cbc_env('WP_DEBUG', '1'), FILTER_VALIDATE_BOOLEAN));
define('WP_DEBUG_LOG', filter_var(cbc_env('WP_DEBUG_LOG', '1'), FILTER_VALIDATE_BOOLEAN));
define('WP_DEBUG_DISPLAY', filter_var(cbc_env('WP_DEBUG_DISPLAY', '0'), FILTER_VALIDATE_BOOLEAN));

define('DISALLOW_FILE_EDIT', true);
define('AUTOMATIC_UPDATER_DISABLED', true);
define('FS_METHOD', 'direct');

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
