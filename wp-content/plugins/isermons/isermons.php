<?php
/* 
 * Plugin Name: iSermons
 * Plugin URI:  https://demo1.imithemes.com/isermons
 * Description: WordPress Sermons Manager Plugin
 * Author:      imithemes
 * Version:     2.2.1
 * Author URI:  http://www.imithemes.com
 * Licence:     GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Copyright:   (c) 2024 imithemes. All rights reserved
 * Text Domain: isermons
 * Domain Path: /languages
 */

// Do not allow direct access to this file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'ISERMONS__PLUGIN_PATH', plugin_dir_path(__FILE__ ) );
define( 'ISERMONS__PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/* PARTIALS ATTACHMENTS
================================================== */
require_once ISERMONS__PLUGIN_PATH . 'admin/admin_functions.php';
require_once ISERMONS__PLUGIN_PATH . 'admin/sermons-type.php';
require_once ISERMONS__PLUGIN_PATH . 'admin/settings_page.php';
require_once ISERMONS__PLUGIN_PATH . 'admin/meta_fields.php';
require_once ISERMONS__PLUGIN_PATH . 'admin/isermons_rest_endpoints.php';
require_once ISERMONS__PLUGIN_PATH . 'front/class-shortcodes.php';
require_once ISERMONS__PLUGIN_PATH . 'front/front_functions.php';
require_once ISERMONS__PLUGIN_PATH . 'front/actions.php';
require_once ISERMONS__PLUGIN_PATH . 'front/podcast-functions.php';
require_once ISERMONS__PLUGIN_PATH . 'front/REST_Endpoints.php';

/* SET LANGUAGE FILE FOLDER
=================================================== */
add_action('plugins_loaded', 'isermons_load_textdomain');
function isermons_load_textdomain() {
    load_plugin_textdomain('isermons', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
// Set default settings
function isermons_get_settings( $id ) 
{
	$options = get_option( 'isermons_options' );
    if(empty($options))
    {
        update_option('isermons_options', array ( 'isermons_details_meta' => array ( 'categories',  'series',  'books',  'topics',  'preachers' ), 'isermons_details_related' => 'related', 'isermons_sermons_related_taxonomy' => '[isermons-list layout="grid" search="" filters="" filters_operator="AND" watch="Watch sermon" hover="enable" per_page="3" meta_data="preacher,date,series" imi_isermons-categories="" imi_isermons-series="" imi_isermons-books="" imi_isermons-topics="" imi_isermons-preachers="" columns="3" relation="categories"]', 'isermons_details_recent' => 'recent', 'isermons_terms_related_taxonomy' => '[isermons-terms layout="style1" columns="4" taxonomy="imi_isermons-series" imi_isermons-series="" filters_order="id"]', 'isermons_taxonomy_categories' => array ( 'categories', 'hierarchical', 'filters', 'column' ), 'isermons_taxonomy_series' => array ('series', 'hierarchical', 'column' ), 'isermons_taxonomy_books' => array ( 'books', 'hierarchical', 'column' ), 'isermons_taxonomy_topics' => array ( 'topics', 'hierarchical', 'column' ), 'isermons_taxonomy_preachers' => array ( 'preachers', 'hierarchical', 'column' ), 'isermons_sermons_archive_switch' => 'on', 'isermons_sermons_taxonomy_template' => 'on', 'isermons_enable_audio_download' => 'on', 'isermons_enable_np_links' => 'on'));
        $options = get_option( 'isermons_options' );
    }
	if ( isset( $options[$id] ) ) 
	{
		return $options[$id];
	}
}
// Custom Elementor Widgets Registration
function register_isermons_elementor_widget( $widgets_manager ) {

	require_once(ISERMONS__PLUGIN_PATH . 'elementor/class-isermons.php');
	$widgets_manager->register( new \Elementor_Isermons_Widget() );

}
add_action( 'elementor/widgets/register', 'register_isermons_elementor_widget' );

// Registration functionality
add_action('admin_init', 'isermons_activation_redirect');

function isermons_activation_redirect() {
    if (get_option('redirect_isermons_activation', false)) {
        delete_option('redirect_isermons_activation');
        exit(wp_redirect(admin_url( 'edit.php?post_type=imi_isermons&page=isermons-license' )));
    }
}

add_action('admin_menu', 'isermons_register_license_page');
function isermons_register_license_page() {
    add_submenu_page(
        'edit.php?post_type=imi_isermons',
        __( 'License', 'isermons' ),
        __( 'License', 'isermons' ),
        'manage_options',
        'isermons-license',
        'isermons_license_callback'
    );
}
function disallowed_isermons_admin_pages()
  {
    global $pagenow;
    if ($pagenow == 'edit.php') {
      $domain = preg_replace( '|https?://([^/]+)|', '$1', home_url() );
      $localhost = false;
      if ( parse_url( home_url() , PHP_URL_PATH ) || 'localhost' === $domain || preg_match( '|^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$|', $domain ) ) {
        $localhost = true;
      }
      if (!$localhost && (!empty($_REQUEST['page']) && $_REQUEST['page'] == 'isermons_settings_options')) {
        if (empty(get_option('isermons_authenticate'))) {
          wp_safe_redirect(admin_url('edit.php?post_type=imi_isermons&page=isermons-license'));
          exit;
        }
      }
    }
  }
  add_action('admin_init', 'disallowed_isermons_admin_pages');

  function isermonsProcessAuthentication()
  {
    $status = $_REQUEST['status'];
    $authCode = $_REQUEST['authCode'];
    update_option('isermons_authenticate', $status);
    update_option('isermons_auth_code', $authCode);
    wp_die();
  }
  add_action('wp_ajax_isermonsProcessAuthentication', 'isermonsProcessAuthentication');

/**
 * Display callback for the submenu page.
 */
function isermons_license_callback() { 
    ?>
    <div class="wrap about-wrap">
		<h1><?php esc_html_e('License Activation','isermons'); ?></h1>
  <?php 

	$purchaseCode = $purchaseCodeRaw = $showRegisterForm = "";
	$showUnRegisterForm = ' style="display: none;"';
	if(!empty(get_option('isermons_auth_code'))) {
		$purchaseCode = "xxxxxx-xxxx-xxxxxxx-xxxxx".substr(get_option('isermons_auth_code'), -4);
		$purchaseCodeRaw = get_option('isermons_auth_code');
		$showRegisterForm = ' style="display: none;"';
		$showUnRegisterForm = "";
	}
	?>
	<div class="imi-box-content activation_welcome_box" <?php echo ''.$showRegisterForm; ?>>
		<p>
			<?php echo esc_html__('Thank you for choosing iSermons! Please register it to get access to all the features & settings. The instructions below must be followed exactly to successfully register your purchase.', 'isermons'); ?>
		</p>
		<div class="isermons-activate"></div>
	</div>
	<div class="isermons-validation-steps">
		<div class="imi-box-content imi-theme-reg-box" <?php echo ''.$showUnRegisterForm; ?>>
			<h3 style="color: green; text-align: left">Plugin is active</h3>
		</div>
		<div class="imi-box-content imi-theme-reg-box" <?php echo ''.$showRegisterForm; ?>>
				<h3 style="color: red; text-align: left">Please activate your iSermons purchase code</h3>
		</div>
		<div class="imi-box-content">
			<form class="imi_isermons_val" <?php echo ''.$showRegisterForm; ?>>
				<label for="imi_purchase_code"><?php esc_html_e('Purchase Code: ','framework') ?></label>
				<input type="text" value="" class="isermons-purchase-code">
				<input type="hidden" value="<?php echo urlencode(site_url()); ?>" class="isermons-verified-dm">
				<input type="hidden" value="<?php echo esc_url($_SERVER['REMOTE_ADDR']); ?>" class="isermons-server-type">
				<button type="submit" class="imi-submit-btn">Register</button>
				<div class="isermons-message"></div>
			</form>

			<form class="imi_isermons_vals" <?php echo ''.$showUnRegisterForm; ?>>
				<label for="imi_purchase_code"><?php esc_html_e('Purchase Code: ','framework') ?></label>
				<input type="text" class="isermons-hidden-code" value="<?php echo esc_attr($purchaseCode); ?>">
				<input type="hidden" value="<?php echo urlencode(site_url()) ?>" class="isermons-verified-dm">
				<input type="hidden" value="<?php echo esc_url($_SERVER['REMOTE_ADDR']); ?>" class="isermons-server-type">
				<input type="hidden" value="<?php echo esc_attr($purchaseCodeRaw); ?>" class="isermons-purchase-code">
				<button type="submit" class="imi-submit-btn">Unregister</button>
				<p><small>Unregister the purchase code to use it on any other domain/website.</small></p>
				<div class="isermons-message"></div>
			</form>

			<div <?php echo ''.$showRegisterForm; ?>>
				<h3><?php _e( 'Instructions for registering your purchase code', 'framework' ); ?></h3>
				<p>Whenever you purchase an item via the Codecanyon, they will provide you with a purchase code for each item purchased. The purchase code is used for purchase validation for use of the item and also so that you can access the support.</p>
				<ol>
					<li>Log in to Codecanyon with your Envato account.</li>
					<li>Navigate to the Downloads tab. Your all purchases and items appear in this page.</li>
					<li>Locate your item, and click the Download button.</li>
					<li>Choose between License Certificate &amp; Purchase Code (PDF) or License Certificate &amp; Purchase Code (Text).</li>
					<li>Open the file to find the purchase code.</li>
				</ol>
				<p><a href="https://www.youtube.com/watch?v=yTScONNFnZ8" target="_blank">See instructions video</a></p>
			</div>
		</div>
	</div>
    <?php
}