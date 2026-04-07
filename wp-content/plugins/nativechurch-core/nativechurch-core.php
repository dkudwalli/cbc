<?php
/*
Plugin Name: NativeChurch Core
Plugin URI: https://demo.imithemes.com/native-church-wp/
Description: NativeChurch core plugin includes all features of NativeChurch theme in a plugin for a better user experience.
Version: 4.1
Author: Imithemes
Author URI: https://imithemes.com/
Licence:     GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Copyright:   (c) 2024 imithemes. All rights reserved
Domain Path: /languages
Text Domain: imithemes
*/

// Do not allow direct access to this file.
defined('ABSPATH') or die('No script kiddies please!');
define('NATIVECHURCH_CORE__PLUGIN_PATH', WP_PLUGIN_DIR.'/nativechurch-core/');
define( 'NATIVECHURCH_CORE__PLUGIN_URL', WP_PLUGIN_URL . '/nativechurch-core/' );
/* PARTIALS ATTACHMENTS
================================================== */
require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'includes.php';
require_once NATIVECHURCH_CORE__PLUGIN_PATH . 'demo-import/config.php';
/* SET LANGUAGE FILE FOLDER
=================================================== */
add_action('plugins_loaded', 'nativechurch_core_load_textdomain');
function nativechurch_core_load_textdomain()
{
    load_plugin_textdomain('imithemes', false, basename(dirname(__FILE__)) . '/languages');
}