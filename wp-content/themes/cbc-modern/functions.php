<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('cbc_modern_get_asset_version')) {
    function cbc_modern_get_asset_version($relative_path)
    {
        $absolute_path = get_stylesheet_directory() . $relative_path;

        if (file_exists($absolute_path)) {
            return (string) filemtime($absolute_path);
        }

        return (string) wp_get_theme()->get('Version');
    }
}

if (!function_exists('cbc_modern_is_homepage_refresh')) {
    function cbc_modern_is_homepage_refresh()
    {
        $front_page_id = (int) get_option('page_on_front');
        $queried_id = (int) get_queried_object_id();

        if ($front_page_id > 0 && $queried_id === $front_page_id) {
            return true;
        }

        if (is_front_page()) {
            return true;
        }

        return is_page(2726);
    }
}

add_action('wp_enqueue_scripts', 'cbc_modern_enqueue_assets', 10001);
function cbc_modern_enqueue_assets()
{
    wp_dequeue_style('default-google-fonts');
    wp_deregister_style('default-google-fonts');
    wp_dequeue_style('nativechurch-fonts');
    wp_deregister_style('nativechurch-fonts');

    wp_enqueue_style(
        'cbc-modern-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'cbc-modern-style',
        get_stylesheet_directory_uri() . '/assets/css/modern.css',
        array('imic_main', 'cbc-modern-fonts'),
        cbc_modern_get_asset_version('/assets/css/modern.css')
    );

    wp_enqueue_script(
        'cbc-modern-script',
        get_stylesheet_directory_uri() . '/assets/js/modern.js',
        array(),
        cbc_modern_get_asset_version('/assets/js/modern.js'),
        true
    );

    if (function_exists('wp_script_add_data')) {
        wp_script_add_data('cbc-modern-script', 'defer', true);
    }
}

add_filter('body_class', 'cbc_modern_body_classes');
function cbc_modern_body_classes($classes)
{
    $classes[] = 'cbc-modern';

    if (cbc_modern_is_homepage_refresh()) {
        $classes[] = 'cbc-home-modern';
    }

    if (is_front_page()) {
        $classes[] = 'cbc-modern-front-page';
    }

    return array_values(array_unique($classes));
}
