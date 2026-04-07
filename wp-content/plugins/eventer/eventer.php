<?php
/*
 * Plugin Name: Eventer
 * Plugin URI:  https://eventer.imithemes.com
 * Description: WordPress Event Manager Plugin
 * Author:      imithemes
 * Version:     3.8.5
 * Author URI:  http://www.imithemes.com
 * Licence:     GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Copyright:   (c) 2024 imithemes. All rights reserved
 * Text Domain: eventer
 * Domain Path: /languages
 */

// Prevent direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

// Define plugin paths
define('EVENTER__PLUGIN_PATH', plugin_dir_path(__FILE__));
define('EVENTER__PLUGIN_URL', plugin_dir_url(__FILE__));

/* PARTIALS ATTACHMENTS
================================================== */

// Include necessary files
require_once EVENTER__PLUGIN_PATH . '/admin/admin_functions.php';
require_once EVENTER__PLUGIN_PATH . '/admin/eventer-type.php';
require_once EVENTER__PLUGIN_PATH . '/admin/settings_page.php';
require_once EVENTER__PLUGIN_PATH . '/admin/meta_fields.php';
if (!is_admin()) {
    require_once EVENTER__PLUGIN_PATH . '/front/eventer-shortcodes.php';
}
require_once EVENTER__PLUGIN_PATH . '/front/front_functions.php';
require_once EVENTER__PLUGIN_PATH . '/front/eventer_rest_endpoints.php';
require_once EVENTER__PLUGIN_PATH . '/front/eventer_rest_endpoints_v2.php';
require_once EVENTER__PLUGIN_PATH . '/front/eventer_actions.php';
require_once EVENTER__PLUGIN_PATH . '/front/shortcodes.php';
require_once EVENTER__PLUGIN_PATH . '/front/schema.php';
require_once EVENTER__PLUGIN_PATH . '/front/ipn.php';
require_once EVENTER__PLUGIN_PATH . 'WC/WC.php';
require_once EVENTER__PLUGIN_PATH . '/VC/VC.php';

/* SET LANGUAGE FILE FOLDER
=================================================== */
add_action('plugins_loaded', 'eventer_load_textdomain');
function eventer_load_textdomain() {
    load_plugin_textdomain('eventer', false, basename(dirname(__FILE__)) . '/languages');
    
    $site_lang = substr(get_locale(), 0, 2);
    if (function_exists('icl_object_id') && class_exists('SitePress')) {
        $site_lang = ICL_LANGUAGE_CODE;
    }
    define('EVENTER__LANGUAGE_CODE', $site_lang);

    $woocommerce_switch = eventer_get_settings('eventer_enable_woocommerce_ticketing');
    $woocommerce_layout = eventer_get_settings('eventer_woo_layout');
    $eventer_slug = eventer_get_settings('eventer_event_permalink');
    $event_slug = (empty($eventer_slug)) ? 'eventer' : $eventer_slug;
    $event_link = $_SERVER['REQUEST_URI'];
    
    if ($woocommerce_switch == 'on' && $woocommerce_layout == 'on' && !defined('WOOCOMMERCE_CHECKOUT') && strpos($event_link, $event_slug)) {
        define('WOOCOMMERCE_CHECKOUT', true);
    }
}

/* GETTING EVENTER SETTING PAGE ID
=================================================== */
function eventer_get_settings($id) {
    $options = get_option('eventer_options');
    if (isset($options[$id])) {
        return $options[$id];
    }
    return null;
}

// Redirect after plugin activation
register_activation_hook(__FILE__, 'imi_redirect_after_activation');
function imi_redirect_after_activation() {
    add_option('redirect_eventer_activation', true);
}

add_action('admin_init', 'imi_activation_redirect');
function imi_activation_redirect() {
    if (get_option('redirect_eventer_activation', false)) {
        delete_option('redirect_eventer_activation');
        exit(wp_redirect(admin_url('edit.php?post_type=eventer&page=eventer-license')));
    }
}

// Register license page in admin menu
add_action('admin_menu', 'eventer_register_license_page');
function eventer_register_license_page() {
    add_submenu_page(
        'edit.php?post_type=eventer',
        __('License', 'eventer'),
        __('License', 'eventer'),
        'manage_options',
        'eventer-license',
        'eventer_license_callback'
    );
}

// Disallow access to certain admin pages if not authenticated
add_action('admin_init', 'disallowed_eventer_admin_pages');
function disallowed_eventer_admin_pages() {
    global $pagenow;
    if ($pagenow == 'edit.php') {
        $domain = preg_replace('|https?://([^/]+)|', '$1', home_url());
        $localhost = false;
        if (parse_url(home_url(), PHP_URL_PATH) || 'localhost' === $domain || preg_match('|^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$|', $domain)) {
            $localhost = true;
        }
        if (!$localhost && (!empty($_REQUEST['page']) && $_REQUEST['page'] == 'eventer_settings_options')) {
            if (empty(get_option('eventer_authenticate'))) {
                wp_safe_redirect(admin_url('edit.php?post_type=eventer&page=eventer-license'));
                exit;
            }
        }
    }
}

// Process authentication via AJAX
add_action('wp_ajax_eventerProcessAuthentication', 'eventerProcessAuthentication');
function eventerProcessAuthentication() {
    $status = $_REQUEST['status'];
    $authCode = $_REQUEST['authCode'];
    update_option('eventer_authenticate', $status);
    update_option('eventer_auth_code', $authCode);
    wp_die();
}

// Display callback for the license activation page
function eventer_license_callback() { 
    ?>
    <div class="wrap about-wrap">
        <h1><?php esc_html_e('License Activation', 'eventer'); ?></h1>
        <?php
        $purchaseCode = $purchaseCodeRaw = $showRegisterForm = "";
        $showUnRegisterForm = ' style="display: none;"';
        if (!empty(get_option('eventer_auth_code'))) {
            $purchaseCode = "xxxxxx-xxxx-xxxxxxx-xxxxx" . substr(get_option('eventer_auth_code'), -4);
            $purchaseCodeRaw = get_option('eventer_auth_code');
            $showRegisterForm = ' style="display: none;"';
            $showUnRegisterForm = "";
        }
        ?>
        <div class="imi-box-content activation_welcome_box" <?php echo $showRegisterForm; ?>>
            <p>
                <?php echo esc_html__('Thank you for choosing Eventer! Please register it to get access to all the features & settings. The instructions below must be followed exactly to successfully register your purchase.', 'eventer'); ?>
            </p>
            <div class="eventer-activate"></div>
        </div>
        <div class="eventer-validation-steps">
            <div class="imi-box-content imi-theme-reg-box" <?php echo $showUnRegisterForm; ?>>
                <h3 style="color: green; text-align: left"><?php esc_html_e('Plugin is active', 'eventer'); ?></h3>
            </div>
            <div class="imi-box-content imi-theme-reg-box" <?php echo $showRegisterForm; ?>>
                <h3 style="color: red; text-align: left"><?php esc_html_e('Please activate your Eventer purchase code', 'eventer'); ?></h3>
            </div>
            <div class="imi-box-content">
                <form class="imi_eventer_val" <?php echo $showRegisterForm; ?>>
                    <label for="imi_purchase_code"><?php esc_html_e('Purchase Code:', 'framework') ?></label>
                    <input type="text" value="" class="eventer-purchase-code">
                    <input type="hidden" value="<?php echo urlencode(site_url()); ?>" class="eventer-verified-dm">
                    <input type="hidden" value="<?php echo esc_url($_SERVER['REMOTE_ADDR']); ?>" class="eventer-server-type">
                    <button type="submit" class="imi-submit-btn">Register</button>
                    <div class="eventer-message"></div>
                </form>
                <form class="imi_eventer_vals" <?php echo $showUnRegisterForm; ?>>
                    <label for="imi_purchase_code"><?php esc_html_e('Purchase Code:', 'framework') ?></label>
                    <input type="text" class="eventer-hidden-code" value="<?php echo esc_attr($purchaseCode); ?>">
                    <input type="hidden" value="<?php echo urlencode(site_url()) ?>" class="eventer-verified-dm">
                    <input type="hidden" value="<?php echo esc_url($_SERVER['REMOTE_ADDR']); ?>" class="eventer-server-type">
                    <input type="hidden" value="<?php echo esc_attr($purchaseCodeRaw); ?>" class="eventer-purchase-code">
                    <button type="submit" class="imi-submit-btn">Unregister</button>
                    <p><small><?php esc_html_e('Unregister the purchase code to use it on any other domain/website.', 'eventer'); ?></small></p>
                    <div class="eventer-message"></div>
                </form>
                <div <?php echo $showRegisterForm; ?>>
                    <h3><?php esc_html_e('Instructions for registering your purchase code', 'framework'); ?></h3>
                    <p><?php esc_html_e('Whenever you purchase an item via Codecanyon, they will provide you with a purchase code for each item purchased. The purchase code is used for purchase validation for use of the item and also so that you can access the support.', 'eventer'); ?></p>
                    <ol>
                        <li><?php esc_html_e('Log in to Codecanyon with your Envato account.', 'eventer'); ?></li>
                        <li><?php esc_html_e('Navigate to the Downloads tab. Your all purchases and items appear in this page.', 'eventer'); ?></li>
                        <li><?php esc_html_e('Locate your item, and click the Download button.', 'eventer'); ?></li>
                        <li><?php esc_html_e('Choose between License Certificate & Purchase Code (PDF) or License Certificate & Purchase Code (Text).', 'eventer'); ?></li>
                        <li><?php esc_html_e('Open the file to find the purchase code.', 'eventer'); ?></li>
                    </ol>
                    <p><a href="https://www.youtube.com/watch?v=yTScONNFnZ8" target="_blank"><?php esc_html_e('See instructions video', 'eventer'); ?></a></p>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/* EVENTER DATE DIFFERENCE FUNCTION
=================================================== */
if (!function_exists('eventer_dateDiff')) {
    function eventer_dateDiff($start, $end) {
        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        $diff = $end_ts - $start_ts;
        return floor($diff / 86400);
    }
}

/* ADD CUSTOM USER ROLE FOR EVENT MANAGERS
=================================================== */
function eventer_add_eventer_manager_role() {
    add_role('eventer_manager', esc_html__('Event manager', 'eventer'), array(
        'edit_post' => true,
        'edit_published_posts' => true,
        'edit_posts' => true,
        'publish_posts' => true,
        'read_post' => true,
        'read' => true,
        'delete_post' => true,
        'edit_published_posts' => true,
        'upload_files' => true,
        'edit_product' => true,
        'edit_published_products' => true,
        'edit_products' => true,
        'publish_products' => true,
        'read_product' => true,
        'delete_product' => true
    ));
}

/* REGISTER CUSTOM ELEMENTOR WIDGET
=================================================== */
add_action('elementor/widgets/register', 'register_eventer_elementor_widget');
function register_eventer_elementor_widget($widgets_manager) {
    require_once(EVENTER__PLUGIN_PATH . '/elementor/class-eventer.php');
    $widgets_manager->register(new \Elementor_Eventer_Widget());
}

/* PLUGIN ACTIVATION HOOK
=================================================== */
register_activation_hook(__FILE__, 'eventer_add_eventer_manager_role');
add_action('admin_init', array('Eventer_Settings_Options', 'eventer_create_ticket_details_table'));
register_activation_hook(__FILE__, array('Eventer_Settings_Options', 'eventer_flush_rewrite_activate'));
register_activation_hook(__FILE__, array('Eventer_Settings_Options', 'eventer_flush_rewrite_deactivate'));
register_activation_hook(__FILE__, array('Eventer_Settings_Options', 'eventer_store_default_settings'));

/* PDF TICKETS DOWNLOAD
=================================================== */

// Register custom endpoint for PDF download
add_action('init', 'register_pdf_download_endpoint');
function register_pdf_download_endpoint() {
    add_rewrite_rule('download-pdf-tickets/([0-9]+)/?', 'index.php?download_pdf_tickets=$matches[1]', 'top');
    flush_rewrite_rules(); // Flush rewrite rules
}

// Add custom query variable for PDF download
add_filter('query_vars', 'add_pdf_download_query_var');
function add_pdf_download_query_var($vars) {
    $vars[] = 'download_pdf_tickets';
    return $vars;
}

// Handle PDF download request
add_action('template_redirect', 'handle_pdf_download_request');
function handle_pdf_download_request() {
    $booking_id = get_query_var('download_pdf_tickets');
    if ($booking_id) {
        // Generate the zip file and initiate the download
        download_pdf_tickets_as_zip($booking_id);
        exit;
    }
}

// Function to generate and download PDF tickets as a zip file
function download_pdf_tickets_as_zip($booking_id) {
    // Start output buffering to handle errors
    ob_start();

    // Define the directory where PDF tickets are stored
    $upload_dir = wp_upload_dir();
    $pdf_dir = $upload_dir['basedir'] . '/eventer';

    // Fetch all PDFs in the directory
    $pattern = "$pdf_dir/{$booking_id}-*.pdf";
    $all_files = glob($pattern);

    // Log all files found for the booking ID
    error_log("All files found for booking ID $booking_id: " . print_r($all_files, true));

    if (empty($all_files)) {
        $error_message = __('No PDF tickets found for this booking. Click on the "Create Tickets" button first to create/re-create tickets PDF file.', 'eventer');
        set_transient('eventer_pdf_error', $error_message, 30);
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    // Create a new zip file
    $zip = new ZipArchive();
    $zip_filename = "$pdf_dir/tickets_$booking_id.zip";

    if ($zip->open($zip_filename, ZipArchive::CREATE) !== TRUE) {
        $error_message = __('Failed to create zip file.', 'eventer');
        set_transient('eventer_pdf_error', $error_message, 30);
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    // Add files to the zip archive
    foreach ($all_files as $file) {
        $zip->addFile($file, basename($file));
    }

    $zip->close();

    // Download the zip file
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename=' . basename($zip_filename));
    header('Content-Length: ' . filesize($zip_filename));
    readfile($zip_filename);

    // Delete the zip file after download
    unlink($zip_filename);
    exit;
}

// Display admin notice for PDF errors
add_action('admin_notices', 'eventer_pdf_error_notice');
function eventer_pdf_error_notice() {
    if ($error_message = get_transient('eventer_pdf_error')) {
        delete_transient('eventer_pdf_error');
        ?>
        <div class="notice notice-error">
            <p><?php echo $error_message; ?></p>
        </div>
        <?php
    }
}

// Function to get the PDF download URL
function get_pdf_download_url($booking_id) {
    return site_url("/download-pdf-tickets/$booking_id");
}

/* WOOCOMMERCE CHECK & NOTICES
=================================================== */
add_action('admin_notices', 'check_woocommerce_installation');
function check_woocommerce_installation() {
    // Check if the setting is 'on'
    $woocommerce_ticketing_enabled = eventer_get_settings('eventer_enable_woocommerce_ticketing');

    if ($woocommerce_ticketing_enabled === 'on' && !is_plugin_active('woocommerce/woocommerce.php')) {
        // Check if WooCommerce is installed
        if (file_exists(WP_PLUGIN_DIR . '/woocommerce/woocommerce.php')) {
            // WooCommerce is installed but not active
            $activate_url = wp_nonce_url('plugins.php?action=activate&plugin=woocommerce/woocommerce.php', 'activate-plugin_woocommerce/woocommerce.php');
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p><span class="dashicons dashicons-warning" style="color: #f56e28"></span> ' . __('WooCommerce is required for Eventer ticket bookings. Click <a href="' . $activate_url . '">here</a> to activate it.', 'eventer') . '</p>';
            echo '</div>';
        } else {
            // WooCommerce is not installed
            $install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=woocommerce'), 'install-plugin_woocommerce');
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p><span class="dashicons dashicons-warning" style="color: #f56e28"></span> ' . __('WooCommerce is required for Eventer ticket bookings. Click <a href="' . $install_url . '">here</a> to install and activate it.', 'eventer') . '</p>';
            echo '</div>';
        }
    }
}

?>