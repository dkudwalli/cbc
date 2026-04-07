<?php
require_once NATIVECHURCH_INC_PATH . '/tgm/class-tgm-plugin-activation.php';
add_action('tgmpa_register', 'nativechurch_register_required_plugins');

function nativechurch_register_required_plugins()
{
	$plugins_path = get_template_directory() . '/framework/tgm/plugins/';
	$plugins = array(
		array(
			'name'        		=> esc_html__('A Core Plugin', 'framework'),
			'slug'         		=> 'nativechurch-core',
			'source'       		=> IMITHEMES_NATIVECHURCH_PLGNSURI . 'nativechurch-core.zip',
			'required'       	=> false,
			'version'     		=> '4.1',
			'force_activation'	=> false,
			'force_deactivation' => false,
			'external_url'      => '',
			'type'				=> 'Required',
			'image_src'			=> get_template_directory_uri() . '/framework/tgm/images/plugin-screen-core.png',
		),
		array(
			'name'               => esc_html__('Revolution Slider', 'framework'),
			'slug'               => 'revslider',
			'source'             => IMITHEMES_NATIVECHURCH_PLGNSURI . 'revslider.zip',
			'required'           => true,
			'version' 			 => '6.7.15',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => '',
			'image_src'	=> get_template_directory_uri() . '/framework/tgm/images/plugin-revslider.png',
		),
		array(
			'name'               => esc_html__('imi causes', 'framework'),
			'slug'               => 'imi-causes',
			'source'             => IMITHEMES_NATIVECHURCH_PLGNSURI . 'imi-causes.zip',
			'required'           => false,
			'version'            => '1.8',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => '',
			'image_src'	=> get_template_directory_uri() . '/framework/tgm/images/plugin-imithemes.png',
		),
		array(
			'name'               => esc_html__('iPray', 'framework'),
			'slug'               => 'ipray',
			'source'             => IMITHEMES_NATIVECHURCH_PLGNSURI . 'ipray.zip',
			'version' 			 => '1.9',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
			'image_src'	=> get_template_directory_uri() . '/framework/tgm/images/plugin-ipray.png',
		),
		array(
			'name'               => esc_html__('iSermons', 'framework'),
			'slug'               => 'isermons',
			'source'             => IMITHEMES_NATIVECHURCH_PLGNSURI . 'isermons.zip',
			'version' 			 => '2.2.1',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
			'image_src'	=> get_template_directory_uri() . '/framework/tgm/images/plugin-isermons.png',
		),
		array(
			'name'               => esc_html__('Eventer', 'framework'),
			'slug'               => 'eventer',
			'source'             => IMITHEMES_NATIVECHURCH_PLGNSURI . 'eventer.zip',
			'version' 			 => '3.8.5',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
			'image_src'	=> get_template_directory_uri() . '/framework/tgm/images/plugin-eventer.png',
		),
		array(
			'name'               => esc_html__('Pro Elements', 'framework'),
			'slug'               => 'pro-elements',
			'source'             => IMITHEMES_NATIVECHURCH_PLGNSURI . 'pro-elements.zip',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
			'image_src'	=> get_template_directory_uri() . '/framework/tgm/images/plugin-proelements.png',
		),
		array(
			'name'               => esc_html__('Redux Framework', 'framework'),
			'slug'               => 'redux-framework',
			'required' 	         => true,
			'type'               => 'Required',
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-redux.png',
		),
		array(
			'name'               => esc_html__('Custom Twitter Feeds', 'framework'),
			'slug'               => 'custom-twitter-feeds',
			'required' 	         => false,
			'type'               => 'Optional',
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-twitter.png',
		),
		array(
			'name'               => esc_html__('Elementor', 'framework'),
			'slug'               => 'elementor',
			'required' 	         => false,
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-elementor.png',
		),
		array(
			'name'               => esc_html__('Smart Slider 3', 'framework'),
			'slug'               => 'smart-slider-3',
			'required' 	         => false,
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-smart.png',
		),
		array(
			'name'               => esc_html__('Breadcrumb NavXT', 'framework'),
			'slug'               => 'breadcrumb-navxt',
			'required' 	         => false,
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-navxt.png',
		),
		array(
			'name'               => esc_html__('Pojo Sidebars', 'framework'),
			'slug'               => 'pojo-sidebars',
			'required' 	         => false,
			'type'               => 'Required',
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-pojo.png',
		),
		array(
			'name'               => esc_html__('Loco Translate', 'framework'),
			'slug'               => 'loco-translate',
			'required' 	         => false,
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-loco.png',
		),
		array(
			'name'               => esc_html__('WooCommerce', 'framework'),
			'slug'               => 'woocommerce',
			'required' 	         => false,
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-woo.png',
		),
		array(
			'name'               => esc_html__('Contact Form 7', 'framework'),
			'slug'               => 'contact-form-7',
			'required' 	         => false,
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-cf7.png',
		),
		array(
			'name'               => esc_html__('Give - WordPress Donation Plugin', 'framework'),
			'slug'               => 'give',
			'required'           => false,
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-give.png',
		),
		array(
			'name'               => esc_html__('Page Builder by SiteOrigin', 'framework'),
			'slug'               => 'siteorigin-panels',
			'required'           => true,
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-siteorigin.png',
		),
		array(
			'name'               => esc_html__('SiteOrigin Widgets Bundle', 'framework'),
			'slug'               => 'so-widgets-bundle',
			'required'           => true,
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-widgetbundle.png',
		),
		array(
			'name'               => esc_html__('SiteOrigin CSS', 'framework'),
			'slug'               => 'so-css',
			'required'           => true,
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-siteorigin-css.png',
		),
		array(
			'name'               => esc_html__('Black Studio TinyMCE Widget', 'framework'),
			'slug'               => 'black-studio-tinymce-widget',
			'required'           => true,
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-blackstudio.png',
		),
		array(
			'name'               => esc_html__('Regenerate Thumbnails', 'framework'),
			'slug'               => 'regenerate-thumbnails',
			'required'           => false,
			'image_src'	         => get_template_directory_uri() . '/framework/tgm/images/plugin-regen.png',
		),
		array(
			'name'               => esc_html__('Best Contact Forms', 'framework'),
			'slug'               => 'wpforms-lite',
			'required'           => false,
			'image_src'          => get_template_directory_uri() . '/framework/tgm/images/plugin-wpforms.png',
		),
		array(
			'name'               => esc_html__('Make Column Clickable Elementor', 'framework'),
			'slug'               => 'make-column-clickable-elementor',
			'required'           => false,
			'image_src'          => get_template_directory_uri() . '/framework/tgm/images/plugin-mcce.png',
		),
		array(
			'name'               => esc_html__('GiveWP Donation Widgets for Elementor', 'framework'),
			'slug'               => 'givewp-donation-widgets-for-elementor',
			'required'           => false,
			'image_src'          => get_template_directory_uri() . '/framework/tgm/images/plugin-giveele.png',
		),

	);

	$config = array(
		'id'			=> 'tgmpa',
		'default_path'	=> '',
		'menu'			=> 'tgmpa-install-plugins',
		'parent_slug'	=> 'themes.php',
		'capability'	=> 'edit_theme_options',
		'has_notices'	=> false,
		'dismissable'	=> true,
		'dismiss_msg'	=> '',
		'is_automatic'	=> true,
		'message'		=> '',
	);

	tgmpa($plugins, $config);
}
if (function_exists('vc_set_as_theme')) vc_set_as_theme($disable_updater = true);
