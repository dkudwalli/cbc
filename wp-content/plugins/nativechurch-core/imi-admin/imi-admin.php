<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class IMI_Admin {

	public function __construct() {
		
		add_action( 'admin_menu', array( $this, 'admin_menus' ) );
		add_action( 'admin_menu', array( $this, 'edit_admin_menus' ) );
		
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10 );
		add_action( 'wp_ajax_imi_install_plugin', array( $this, 'install_plugin' ) );
		add_action( 'wp_ajax_imi_activate_plugin', array( $this, 'activate_plugin' ) );
		add_action( 'wp_ajax_imi_deactivate_plugin', array( $this, 'deactivate_plugin' ) );
		add_action( 'wp_ajax_imi_update_plugin', array( $this, 'update_plugin' ) );
		
		// Redirect to welcome page
		add_action( 'after_switch_theme', array( $this, 'after_switch_theme' ) );
		
	}
	
	public function tgmpa_load( $load ) {
		return true;
	}

	public function install_plugin() {
		
		if ( current_user_can( 'manage_options' ) ) {
			
			check_admin_referer( 'tgmpa-install', 'tgmpa-nonce' );
			
			global $tgmpa;
			
			$tgmpa->install_plugins_page();
			
			$url = wp_nonce_url(
				add_query_arg(
					array(
						'plugin'			=> urlencode( $_GET['plugin'] ),
						'tgmpa-deactivate'	=> 'deactivate-plugin',
					),
					$tgmpa->get_tgmpa_url()
				),
				'tgmpa-deactivate',
				'tgmpa-nonce'
			);

			echo 'imi';
			echo wp_specialchars_decode( $url );
			
		}
		
		// this is required to terminate immediately and return a proper response
		wp_die();
		
	}

	public function activate_plugin() {
		
		if ( current_user_can( 'edit_theme_options' ) ) {
			
			check_admin_referer( 'tgmpa-activate', 'tgmpa-nonce' );
			
			global $tgmpa;
			
			$plugins = $tgmpa->plugins;
			
			foreach ( $plugins as $plugin ) {
				
				if ( isset( $_GET['plugin'] ) && $plugin['slug'] === $_GET['plugin'] ) {
					
					activate_plugin( $plugin['file_path'] );
					
					$url = wp_nonce_url(
						add_query_arg(
							array(
								'plugin'			=> urlencode( $_GET['plugin'] ),
								'tgmpa-deactivate'	=> 'deactivate-plugin',
							),
							$tgmpa->get_tgmpa_url()
						),
						'tgmpa-deactivate',
						'tgmpa-nonce'
					);
					
					echo wp_specialchars_decode( $url );
					
				}
				
			} // foreach
			
		}
		
		// this is required to terminate immediately and return a proper response
		wp_die();
		
	}
	
	public function deactivate_plugin() {
		
		if ( current_user_can( 'edit_theme_options' ) ) {
			
			check_admin_referer( 'tgmpa-deactivate', 'tgmpa-nonce' );
			
			global $tgmpa;
			
			$plugins = $tgmpa->plugins;
			
			foreach ( $plugins as $plugin ) {
				
				if ( isset( $_GET['plugin'] ) && $plugin['slug'] === $_GET['plugin'] ) {
					
					deactivate_plugins( $plugin['file_path'] );
					
					$url = wp_nonce_url(
						add_query_arg(
							array(
								'plugin'			=> urlencode( $_GET['plugin'] ),
								'tgmpa-activate'	=> 'activate-plugin',
							),
							$tgmpa->get_tgmpa_url()
						),
						'tgmpa-activate',
						'tgmpa-nonce'
					);
					
					echo wp_specialchars_decode( $url );
					
				}
				
			} // foreach
			
		}
		
		// this is required to terminate immediately and return a proper response
		wp_die();
		
	}

	public function update_plugin() {
		if ( current_user_can( 'manage_options' ) ) {
			check_admin_referer( 'tgmpa-update', 'tgmpa-nonce' );
			global $tgmpa;
			$tgmpa->install_plugins_page();

			$url = wp_nonce_url(
				add_query_arg(
					array(
						'plugin'			=> urlencode( $_GET['plugin'] ),
						'tgmpa-deactivate'	=> 'deactivate-plugin',
					),
					$tgmpa->get_tgmpa_url()
				),
				'tgmpa-deactivate',
				'tgmpa-nonce'
			);

			echo 'imi';
			echo wp_specialchars_decode( $url );
		}
		
		// this is required to terminate immediately and return a proper response
		wp_die();
	}

	public function after_switch_theme(){
		if ( is_admin() && current_user_can( 'manage_options' ) &&  !defined('ENVATO_HOSTED_SITE') ) {
			wp_redirect( admin_url( 'admin.php?page=imi-admin-welcome' ) );
		}
	}

	public function enqueue_scripts() {
		
		if ( isset( $_GET['page'] ) ) :
			
			if ( $_GET['page'] == 'imi-admin-system-status' || 'imi-admin-welcome' || 'imi-admin-support' || 'imi-admin-demo-importer' || $_GET['page'] == 'imi-admin-plugins' ) :

				// admin pages style
				wp_enqueue_style( 'imi-admin-styles', get_template_directory_uri() . '/assets/css/admin-pages.css' );

				// install plugins scripts
				if ( $_GET['page'] == 'imi-admin-demo-importer' || $_GET['page'] == 'imi-admin-plugins' ) :

					wp_enqueue_script(
						'imi-admin-plugins',
						NATIVECHURCH_CORE__PLUGIN_URL . 'imi-admin/js/imi-plugins.js',
						array( 'jquery' ),
						time(),
						true
					);

				endif;
				
			endif;
			
		endif; // isset
		
	}
	
	
	public function admin_menus() {
		
		// Welcome page
		call_user_func_array( 'add' . '_menu_' . 'page', array(
			imi_Admin::theme( 'name' ),
			imi_Admin::theme( 'name' ),
			'manage_options',
			'imi-admin-welcome',
			array( $this, 'screen_welcome' ),
			NATIVECHURCH_CORE__PLUGIN_URL . 'imi-admin/images/imi-menu-icon.png',
			'2',
		));
		
		// Demo Importer page
		/*call_user_func_array( 'add' . '_sub' . 'menu_' . 'page', array(
			'imi-admin-welcome',
			esc_html__( 'Demo Importer', 'imithemes' ),
			esc_html__( 'Demo Importer', 'imithemes' ),
			'manage_options',
			'imi-admin-demo-importer',
			array( $this, 'screen_demo_importer' )
		));*/
		
		// Plugins page
		call_user_func_array( 'add' . '_sub' . 'menu_' . 'page', array(
			'imi-admin-welcome',
			esc_html__( 'Plugins', 'imithemes' ),
			esc_html__( 'Plugins', 'imithemes' ),
			'manage_options',
			'imi-admin-plugins',
			array( $this, 'screen_plugins' )
		));

		// Support
		call_user_func_array( 'add' . '_sub' . 'menu_' . 'page', array(
			'imi-admin-welcome',
			esc_html__( 'Support', 'imithemes' ),
			esc_html__( 'Support', 'imithemes' ),
			'manage_options',
			'imi-admin-support',
			array( $this, 'screen_support' )
		));

		// System Status
		call_user_func_array( 'add' . '_sub' . 'menu_' . 'page', array(
			'imi-admin-welcome',
			esc_html__( 'System Status', 'imithemes' ),
			esc_html__( 'System Status', 'imithemes' ),
			'manage_options',
			'imi-admin-system-status',
			array( $this, 'screen_system_status' )
		));
		
	}
	
	function edit_admin_menus() {
		
		global $submenu;
		
		if ( current_user_can( 'manage_options' ) ) {
			$submenu['imi-admin-welcome'][0][0] = esc_html__( 'Welcome', 'imithemes' );
		}
		
	}
	
	public function screen_welcome() {
		
		// Stupid hack for Wordpress alerts and warnings
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		include_once( 'pages/welcome.php' );
		
	}
	
	public function screen_plugins() {
		
		// Stupid hack for Wordpress alerts and warnings
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		include_once( 'pages/plugins.php' );
		
	}
	
	
	public function screen_demo_importer() {
		
		// Stupid hack for Wordpress alerts and warnings
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		include_once( 'pages/demo-import.php' );
		
	}
	
	public function screen_support() {
		
		// Stupid hack for Wordpress alerts and warnings
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		include_once( 'pages/support.php' );
		
	}
	
	public function screen_system_status() {
		
		// Stupid hack for Wordpress alerts and warnings
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		include_once( 'pages/system-status.php' );
		
	}
	
	static function theme( $property = '' ) {
		
		// Gets a WP_Theme object for a theme
		$theme_data = wp_get_theme('NativeChurch');
		
		if( $theme_data->parent_theme ) {
			$theme_data = wp_get_theme( basename( NATIVECHURCH_CORE__PLUGIN_PATH ) );
		}
		
		switch ( $property ) :
			case 'name':
			$data = $theme_data->Name;
			break;
			
			case 'version':
			$data = $theme_data->Version;
			break;
			
			default:
			$data = '';
			break;
		endswitch;
		
		return $data;
		
	}
	
}

new IMI_Admin();