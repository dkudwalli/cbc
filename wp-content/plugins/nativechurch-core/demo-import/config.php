<?php
// Once Click Demo Importer
function OCDI_Plugin_init()
{
  	if( !class_exists( 'OCDI_Plugin' ) ):
    	require_once plugin_dir_path(__FILE__) . '/importer/one-click-demo-import.php';
  	endif;
}
add_action( 'plugins_loaded', 'OCDI_Plugin_init' );

function ocdi_plugin_page_setup( $default_settings ) {
    $default_settings['parent_slug'] = 'imi-admin-welcome';
    $default_settings['page_title']  = esc_html__( 'One Click Demo Import' , 'one-click-demo-import' );
    $default_settings['menu_title']  = esc_html__( 'Demo Importer' , 'one-click-demo-import' );
    $default_settings['capability']  = 'import';
    $default_settings['menu_slug']   = 'imi-admin-demo-importer';
 
    return $default_settings;
}
add_filter( 'ocdi/plugin_page_setup', 'ocdi_plugin_page_setup' );


function ocdi_import_files() {
  return [
    [
      'import_file_name'             => 'Classic - Page Templates',
      'categories'                   => [ 'Church', 'Page Templates', 'SiteOrigin' ],
      'local_import_file'            => plugin_dir_path(__FILE__) . '/data/demo1/content.xml',
      'local_import_widget_file'     => plugin_dir_path(__FILE__) . '/data/demo1/widgets.json',
      'local_import_customizer_file' => '',
      'local_import_redux'           => [
        [
          'file_path'   => plugin_dir_path(__FILE__) . '/data/demo1/theme-options.json',
          'option_name' => 'imic_options',
        ],
      ],
      'import_preview_image_url'     => plugin_dir_url(__FILE__) . 'data/demo1/screen-image.jpg',
      'preview_url'                  => 'https://native-church.imithemes.com/classic/',
    ],
    [
      'import_file_name'             => 'Classic - SiteOrigin Page Builder',
      'categories'                   => [ 'Church', 'SiteOrigin' ],
      'local_import_file'            => plugin_dir_path(__FILE__) . '/data/demo2/content.xml',
      'local_import_widget_file'     => plugin_dir_path(__FILE__) . '/data/demo2/widgets.json',
      'local_import_customizer_file' => '',
      'local_import_redux'           => [
        [
          'file_path'   => plugin_dir_path(__FILE__) . '/data/demo2/theme-options.json',
          'option_name' => 'imic_options',
        ],
      ],
      'import_preview_image_url'     => plugin_dir_url(__FILE__) . 'data/demo2/screen-image.jpg',
      'preview_url'                  => 'https://native-church.imithemes.com/classic/',
    ],
    [
      'import_file_name'             => 'Parish - Elementor',
      'categories'                   => [ 'Church', 'Elementor' ],
      'local_import_file'            => plugin_dir_path(__FILE__) . '/data/demo3/content.xml',
      'local_import_widget_file'     => plugin_dir_path(__FILE__) . '/data/demo3/widgets.json',
      'local_import_customizer_file' => '',
      'local_import_redux'           => [
        [
          'file_path'   => plugin_dir_path(__FILE__) . '/data/demo3/theme-options.json',
          'option_name' => 'imic_options',
        ],
      ],
      'import_preview_image_url'     => plugin_dir_url(__FILE__) . 'data/demo3/screen-image.jpg',
      'preview_url'                  => 'https://native-church.imithemes.com/parish/',
    ],
    [
      'import_file_name'             => 'Chapel - Elementor',
      'categories'                   => [ 'Church', 'Elementor' ],
      'local_import_file'            => plugin_dir_path(__FILE__) . '/data/demo4/content.xml',
      'local_import_widget_file'     => plugin_dir_path(__FILE__) . '/data/demo4/widgets.json',
      'local_import_customizer_file' => '',
      'local_import_redux'           => [
        [
          'file_path'   => plugin_dir_path(__FILE__) . '/data/demo4/theme-options.json',
          'option_name' => 'imic_options',
        ],
      ],
      'import_preview_image_url'     => plugin_dir_url(__FILE__) . 'data/demo4/screen-image.jpg',
      'preview_url'                  => 'https://native-church.imithemes.com/chapel/',
    ],
    [
      'import_file_name'             => 'Classic - Elementor',
      'categories'                   => [ 'Church', 'Elementor' ],
      'local_import_file'            => plugin_dir_path(__FILE__) . '/data/demo5/content.xml',
      'local_import_widget_file'     => plugin_dir_path(__FILE__) . '/data/demo5/widgets.json',
      'local_import_customizer_file' => '',
      'local_import_redux'           => [
        [
          'file_path'   => plugin_dir_path(__FILE__) . '/data/demo5/theme-options.json',
          'option_name' => 'imic_options',
        ],
      ],
      'import_preview_image_url'     => plugin_dir_url(__FILE__) . 'data/demo5/screen-image.jpg',
      'preview_url'                  => 'https://native-church.imithemes.com/classic-elementor',
    ],
    [
      'import_file_name'             => 'Buddhism - Elementor',
      'categories'                   => [ 'Buddhism', 'Buddha', 'Elementor' ],
      'local_import_file'            => plugin_dir_path(__FILE__) . '/data/demo6/content.xml',
      'local_import_widget_file'     => plugin_dir_path(__FILE__) . '/data/demo6/widgets.json',
      'local_import_customizer_file' => '',
      'local_import_redux'           => [
        [
          'file_path'   => plugin_dir_path(__FILE__) . '/data/demo6/theme-options.json',
          'option_name' => 'imic_options',
        ],
      ],
      'import_preview_image_url'     => plugin_dir_url(__FILE__) . 'data/demo6/screen-image.jpg',
      'preview_url'                  => 'https://nativetheme.com/buddhism',
    ],
  ];
}
add_filter( 'ocdi/import_files', 'ocdi_import_files' );

function ocdi_register_plugins( $plugins ) {
 
  // List of plugins used by all theme demos.
  $theme_plugins = [
    [ 
      'name' 	=> esc_html__('Redux Framework', 'imithemes'),
      'slug' 	=> 'redux-framework',
    ],
    [ 
      'name' 	=> esc_html__('Pojo Sidebars', 'imithemes'),
      'slug' 	=> 'pojo-sidebars',
    ],
    [ 
      'name' 	=> esc_html__('Give - WordPress Donation Plugin', 'imithemes'),
      'slug' 	=> 'give',
    ],
    [ 
      'name' 	=> esc_html__('WooCommerce - excelling eCommerce', 'imithemes'),
      'slug' 	=> 'woocommerce',
    ],
    [ 
      'name' 	=> esc_html__('Contact Form 7', 'imithemes'),
      'slug' 	=> 'contact-form-7',
    ],
    [ 
      'name'	=> esc_html__('iPray', 'framework'),
      'slug'	=> 'ipray',
      'source'	=> get_template_directory_uri() . '/framework/tgm/plugins/ipray.zip',
    ],
    [
      'name'     => 'Breadcrumb NavXT',
      'slug'     => 'breadcrumb-navxt',
    ],
    [
      'name'     => 'Revolution Slider',
      'slug'     => 'revslider',
      'source'   => get_template_directory_uri() . '/framework/tgm/plugins/revslider.zip',
    ],
 
    [
      'name'     => 'imithemes causes',
      'slug'     => 'imi-causes',
      'source'   => get_template_directory_uri() . '/framework/tgm/plugins/imi-causes.zip',
    ],
    [
      'name'     => 'iSermons',
      'slug'     => 'isermons',
      'source'   => get_template_directory_uri() . '/framework/tgm/plugins/isermons.zip',
    ],
    [
        'name'     => 'Elementor',
        'slug'     => 'elementor',
	],
    [
      'name'     => 'Eventer',
      'slug'     => 'eventer',
      'source'   => get_template_directory_uri() . '/framework/tgm/plugins/eventer.zip',
    ],
	[
      'name'     => 'Pro Elements',
      'slug'     => 'pro-elements',
      'source'   => get_template_directory_uri() . '/framework/tgm/plugins/pro-elements.zip',
    ],
 	[
		'name'     => 'Page Builder by SiteOrigin',
        'slug'     => 'siteorigin-panels',
	],
    [
        'name'     => 'SiteOrigin Widgets Bundle',
        'slug'     => 'so-widgets-bundle',
	],
    [
        'name'     => 'Black Studio TinyMCE Widget',
        'slug'     => 'black-studio-tinymce-widget',
	],
    [ 
      'name'	=> esc_html__('GiveWP Donation Widgets for Elementor', 'imithemes'),
      'slug' 	=> 'givewp-donation-widgets-for-elementor',
    ],
    [ 
      'name' 	=> esc_html__('Make Column Clickable Elementor', 'imithemes'),
      'slug'  => 'make-column-clickable-elementor',
    ],
  ];
 
  // Check if user is on the theme recommeneded plugins step and a demo was selected.
  if (
    isset( $_GET['step'] ) &&
    $_GET['step'] === 'import' &&
    isset( $_GET['import'] )
  ) {
	// Adding one additional plugin for the first demo import ('import' number = 0).
    if ( $_GET['import'] === '0' ) {
 
      $theme_plugins = [
          [ 
            'name' 	=> esc_html__('Redux Framework', 'imithemes'),
            'slug' 	=> 'redux-framework',
        	'required' => true,
            'preselected' => true,
          ],
		  [
			'name'     => 'Breadcrumb NavXT',
			'slug'     => 'breadcrumb-navxt',
			'required' => true,
		  ],
		  [
			'name'     => 'Smart Slider 3',
			'slug'     => 'smart-slider-3',
			'required' => true,
		  ],
          [ 
            'name' 	=> esc_html__('Pojo Sidebars', 'imithemes'),
            'slug' 	=> 'pojo-sidebars',
            'required'=> true,
            'preselected' => true,
          ],
          [ 
            'name' 	=> esc_html__('WooCommerce - excelling eCommerce', 'imithemes'),
            'slug' 	=> 'woocommerce',
            'required'=> true,
          ],
          [ 
            'name' 	=> esc_html__('Contact Form 7', 'imithemes'),
            'slug' 	=> 'contact-form-7',
            'required'=> true,
          ],
          [ 
            'name'	=> esc_html__('iPray', 'framework'),
            'slug'	=> 'ipray',
            'required'=> true,
          ],
		  [
            'name'     => 'Revolution Slider',
            'slug'     => 'revslider',
			'required' => true,
          ],
          [
            'name'     => 'imithemes causes',
            'slug'     => 'imi-causes',
            'required' => true,
          ],
	  ];
    }
 
    // List of all plugins only used by second demo import [overwrite the list] ('import' number = 1).
    if ( $_GET['import'] === '1' ) {
 
      $theme_plugins = [
          [ 
            'name' 	=> esc_html__('Redux Framework', 'imithemes'),
            'slug' 	=> 'redux-framework',
        	'required' => true,
            'preselected' => true,
          ],
		  [
			'name'     => 'Breadcrumb NavXT',
			'slug'     => 'breadcrumb-navxt',
			'required' => true,
		  ],
		  [
			'name'     => 'Smart Slider 3',
			'slug'     => 'smart-slider-3',
			'required' => true,
		  ],
          [ 
            'name' 	=> esc_html__('Pojo Sidebars', 'imithemes'),
            'slug' 	=> 'pojo-sidebars',
            'required'=> true,
          ],
          [ 
            'name' 	=> esc_html__('WooCommerce - excelling eCommerce', 'imithemes'),
            'slug' 	=> 'woocommerce',
            'required'=> true,
          ],
          [ 
            'name' 	=> esc_html__('Contact Form 7', 'imithemes'),
            'slug' 	=> 'contact-form-7',
            'required'=> true,
          ],
          [ 
            'name'	=> esc_html__('iPray', 'framework'),
            'slug'	=> 'ipray',
            'required'=> true,
          ],
		  [
            'name'     => 'Revolution Slider',
            'slug'     => 'revslider',
			'required' => true,
          ],
          [
            'name'     => 'imithemes causes',
            'slug'     => 'imi-causes',
            'required' => true,
          ],
          [
              'name'     => 'Page Builder by SiteOrigin',
              'slug'     => 'siteorigin-panels',
              'required' => true,
          ],
          [
              'name'     => 'SiteOrigin Widgets Bundle',
              'slug'     => 'so-widgets-bundle',
              'required' => true,
          ],
          [
              'name'     => 'Black Studio TinyMCE Widget',
              'slug'     => 'black-studio-tinymce-widget',
              'required' => true,
          ],
	  ];
    }
 
    // List of all plugins only used by second demo import [overwrite the list] ('import' number = 2).
    if ( $_GET['import'] === '2' ) {
 
      $theme_plugins = [
          [ 
            'name' 	=> esc_html__('Redux Framework', 'imithemes'),
            'slug' 	=> 'redux-framework',
            'required'=> false,
            'preselected' => true,
          ],
          [ 
            'name' 	=> esc_html__('Contact Form 7', 'imithemes'),
            'slug' 	=> 'contact-form-7',
            'required'=> true,
          ],
          [ 
            'name' 	=> esc_html__('Pojo Sidebars', 'imithemes'),
            'slug' 	=> 'pojo-sidebars',
            'required'=> true,
          ],
          [ 
            'name' 	=> esc_html__('WooCommerce - excelling eCommerce', 'imithemes'),
            'slug' 	=> 'woocommerce',
            'required'=> true,
          ],
          [ 
            'name'	=> esc_html__('iPray', 'framework'),
            'slug'	=> 'ipray',
            'required'=> true,
          ],
          [
            'name'     => 'iSermons',
            'slug'     => 'isermons',
            'required' => false,
            'preselected' => true,
          ],
          [
              'name'     => 'Elementor',
              'slug'     => 'elementor',
              'required' => true,
          ],
          [
            'name'     => 'Eventer',
            'slug'     => 'eventer',
            'required' => false,
            'preselected' => true,
          ],
          [
            'name'     => 'Pro Elements',
            'slug'     => 'pro-elements',
            'required' => false,
            'preselected' => true,
          ],
	  ];
    }
 
    // List of all plugins only used by second demo import [overwrite the list] ('import' number = 2).
    if ( $_GET['import'] === '3' ) {
 
      $theme_plugins = [
          [ 
            'name' 	=> esc_html__('Redux Framework', 'imithemes'),
            'slug' 	=> 'redux-framework',
            'required'=> false,
            'preselected' => true,
          ],
          [ 
            'name' 	=> esc_html__('Contact Form 7', 'imithemes'),
            'slug' 	=> 'contact-form-7',
            'required'=> true,
          ],
          [ 
            'name' 	=> esc_html__('Pojo Sidebars', 'imithemes'),
            'slug' 	=> 'pojo-sidebars',
            'required'=> true,
          ],
          [ 
            'name' 	=> esc_html__('WooCommerce - excelling eCommerce', 'imithemes'),
            'slug' 	=> 'woocommerce',
            'required'=> true,
          ],
          [ 
            'name'	=> esc_html__('iPray', 'framework'),
            'slug'	=> 'ipray',
            'required'=> true,
          ],
          [
            'name'     => 'iSermons',
            'slug'     => 'isermons',
            'required' => false,
            'preselected' => true,
          ],
          [
              'name'     => 'Elementor',
              'slug'     => 'elementor',
              'required' => true,
          ],
          [
            'name'     => 'Eventer',
            'slug'     => 'eventer',
        	'required' => true,
            'preselected' => true,
          ],
          [
            'name'     => 'Pro Elements',
            'slug'     => 'pro-elements',
        	'required' => true,
            'preselected' => true,
          ],
          [ 
            'name' 	=> esc_html__('Give - WordPress Donation Plugin', 'imithemes'),
            'slug' 	=> 'give',
        	'required' => true,
            'preselected' => true,
          ],
	  ];
    }
 
    // List of all plugins only used by Classic Elementor demo import
    if ( $_GET['import'] === '4' ) {
 
      $theme_plugins = [
          [ 
            'name' 	=> esc_html__('Redux Framework', 'imithemes'),
            'slug' 	=> 'redux-framework',
            'required'=> false,
            'preselected' => true,
          ],
          [ 
            'name' 	=> esc_html__('Contact Form 7', 'imithemes'),
            'slug' 	=> 'contact-form-7',
            'required'=> true,
          ],
          [ 
            'name' 	=> esc_html__('Pojo Sidebars', 'imithemes'),
            'slug' 	=> 'pojo-sidebars',
            'required'=> true,
          ],
          [ 
            'name' 	=> esc_html__('WooCommerce - excelling eCommerce', 'imithemes'),
            'slug' 	=> 'woocommerce',
            'required'=> true,
          ],
          [ 
            'name'	=> esc_html__('iPray', 'framework'),
            'slug'	=> 'ipray',
            'required'=> true,
          ],
          [
            'name'     => 'iSermons',
            'slug'     => 'isermons',
            'required' => false,
            'preselected' => true,
          ],
          [
              'name'     => 'Elementor',
              'slug'     => 'elementor',
              'required' => true,
          ],
          [
            'name'     => 'Eventer',
            'slug'     => 'eventer',
        	'required' => true,
            'preselected' => true,
          ],
          [
            'name'     => 'Pro Elements',
            'slug'     => 'pro-elements',
        	'required' => true,
            'preselected' => true,
          ],
          [ 
            'name' 	=> esc_html__('Give - WordPress Donation Plugin', 'imithemes'),
            'slug' 	=> 'give',
        	'required' => false,
            'preselected' => true,
          ],
          [ 
            'name'	=> esc_html__('GiveWP Donation Widgets for Elementor', 'imithemes'),
			'slug' 	=> 'givewp-donation-widgets-for-elementor',
        	'required' => false,
            'preselected' => true,
          ],
          [ 
            'name' 	=> esc_html__('Make Column Clickable Elementor', 'imithemes'),
			'slug'  => 'make-column-clickable-elementor',
        	'required' => false,
            'preselected' => true,
          ],
	  ];
    }
	  
    // List of all plugins only used by Buddhism demo import
    if ( $_GET['import'] === '5' ) {
 
      $theme_plugins = [
          [ 
            'name' 	=> esc_html__('Redux Framework', 'imithemes'),
            'slug' 	=> 'redux-framework',
            'required'=> false,
            'preselected' => true,
          ],
          [ 
            'name' 	=> esc_html__('WooCommerce - excelling eCommerce', 'imithemes'),
            'slug' 	=> 'woocommerce',
            'required'=> true,
          ],
          [
            'name'     => 'iSermons',
            'slug'     => 'isermons',
            'required' => false,
            'preselected' => true,
          ],
          [
              'name'     => 'Elementor',
              'slug'     => 'elementor',
              'required' => true,
          ],
          [
            'name'     => 'Eventer',
            'slug'     => 'eventer',
        	'required' => true,
            'preselected' => true,
          ],
          [
            'name'     => 'Pro Elements',
            'slug'     => 'pro-elements',
        	'required' => true,
            'preselected' => true,
          ],
          [ 
            'name' 	=> esc_html__('Give - WordPress Donation Plugin', 'imithemes'),
            'slug' 	=> 'give',
        	'required' => false,
            'preselected' => true,
          ],
          [ 
            'name'	=> esc_html__('GiveWP Donation Widgets for Elementor', 'imithemes'),
			'slug' 	=> 'givewp-donation-widgets-for-elementor',
        	'required' => false,
            'preselected' => true,
          ],
          [ 
            'name' 	=> esc_html__('Make Column Clickable Elementor', 'imithemes'),
			'slug'  => 'make-column-clickable-elementor',
        	'required' => false,
            'preselected' => true,
          ],
	  ];
    }
  }
 
  return array_merge( $plugins, $theme_plugins );
}
add_filter( 'ocdi/register_plugins', 'ocdi_register_plugins' );

function ocdi_after_import( $selected_import ) {
 	if ( 'Classic - Page Templates' === $selected_import['import_file_name'] ) {
        $top_menu = get_term_by( 'name', 'Top Menu', 'nav_menu' );
        $main_menu = get_term_by( 'name', 'Header Menu', 'nav_menu' );
        $footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
        if ( isset( $main_menu->term_id ) ) {
          	set_theme_mod( 'nav_menu_locations', array(
              'top-menu' => $top_menu->term_id,
              'primary-menu' => $main_menu->term_id,
              'footer-menu' => $footer_menu->term_id
          ));
		}
		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'Blog' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );
		
		if ( class_exists( 'RevSlider' ) ) {
            $slider_path = plugin_dir_path(__FILE__) . 'data/demo1/newslider2014.zip';

            if ( file_exists( $slider_path ) ) {
                $slider = new RevSlider();
                $slider->importSliderFromPost( true, true, $slider_path );
            }
      	}
    }
    elseif ( 'Classic - SiteOrigin Page Builder' === $selected_import['import_file_name'] ) {
        $top_menu = get_term_by( 'name', 'Top Menu', 'nav_menu' );
        $main_menu = get_term_by( 'name', 'Header Menu', 'nav_menu' );
        $footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
        if ( isset( $main_menu->term_id ) ) {
            set_theme_mod( 'nav_menu_locations', array(
                'top-menu' => $top_menu->term_id,
                'primary-menu' => $main_menu->term_id,
                'footer-menu' => $footer_menu->term_id
            ));
		}
		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'Blog' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );
			
		//Update Widgets Switch to On
		$all_widgets_on = 'a:40:{s:6:"button";b:1;s:10:"google-map";b:1;s:5:"image";b:1;s:6:"slider";b:1;s:13:"post-carousel";b:1;s:6:"editor";b:1;s:12:"alert-widget";b:1;s:14:"counter-widget";b:1;s:21:"featured-block-widget";b:1;s:19:"gallery-grid-widget";b:1;s:4:"icon";b:1;s:15:"carousel-widget";b:1;s:17:"posts-list-widget";b:1;s:18:"progressbar-widget";b:1;s:19:"sermons-list-widget";b:1;s:21:"sermons-albums-widget";b:1;s:17:"staff-grid-widget";b:1;s:13:"spacer-widget";b:1;s:11:"tabs-widget";b:1;s:8:"taxonomy";b:1;s:13:"toggle-widget";b:1;s:11:"testimonial";b:1;s:30:"upcoming-events-listing-widget";b:1;s:5:"video";b:1;s:14:"simple-masonry";b:1;s:20:"social-media-buttons";b:1;s:11:"price-table";b:1;s:13:"layout-slider";b:1;s:10:"image-grid";b:1;s:4:"hero";b:1;s:8:"headline";b:1;s:8:"features";b:1;s:7:"contact";b:1;s:3:"cta";b:1;s:20:"blog-timeline-widget";b:1;s:12:"cause-widget";b:1;s:31:"event-grid-minimal-style-widget";b:1;s:32:"event-listing-with-filter-widget";b:1;s:28:"posts-full-width-list-widget";b:1;s:26:"event-grid-timeline-widget";b:1;}';
		$all_widgets_on = unserialize($all_widgets_on);
		update_option('siteorigin_widgets_active', $all_widgets_on);
		
		if ( class_exists( 'RevSlider' ) ) {
            $slider_path = plugin_dir_path(__FILE__) . 'data/demo2/newslider2014.zip';

            if ( file_exists( $slider_path ) ) {
                $slider = new RevSlider();
                $slider->importSliderFromPost( true, true, $slider_path );
            }
      	}
    }
    elseif ( 'Parish - Elementor' === $selected_import['import_file_name'] ) {
		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( 'Home' );
		$blog_page_id  = get_page_by_title( 'Blog' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );
		
		$from = 'https://native-church.imithemes.com/parish/wp-content/uploads/sites/4/';
        // Elementor Kit
        $demo3_kit = '{"system_colors":[{"_id":"primary","title":"Primary","color":"#AC1E30"},{"_id":"secondary","title":"Secondary","color":"#F2CA65"},{"_id":"text","title":"Text","color":"#7A7A7A"},{"_id":"accent","title":"Accent","color":"#AC1E30"}],"custom_colors":[{"title":"Dark Text","_id":"e5f59cf","color":"#282828"}],"system_typography":[{"_id":"primary","title":"Primary","typography_typography":"custom","typography_font_family":"Lora","typography_font_weight":"400","typography_font_size":{"unit":"px","size":16,"sizes":[]},"typography_line_height":{"unit":"em","size":1.5,"sizes":[]}},{"_id":"secondary","title":"Secondary","typography_typography":"custom","typography_font_family":"Roboto","typography_font_weight":"600","typography_letter_spacing":{"unit":"px","size":0,"sizes":[]},"typography_line_height":{"unit":"em","size":"","sizes":[]}},{"_id":"text","title":"Text","typography_typography":"custom","typography_font_family":"Roboto","typography_font_weight":"400"},{"_id":"accent","title":"Accent","typography_typography":"custom","typography_font_family":"Roboto","typography_font_weight":"500"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","page_title_selector":"h1.entry-title","activeItemIndex":1,"viewport_md":768,"viewport_lg":1025,"__globals__":{"body_color":"globals\/colors?id=text","body_typography_typography":"globals\/typography?id=primary","button_typography_typography":"","button_background_color":"globals\/colors?id=primary","button_hover_background_color":"globals\/colors?id=secondary","button_hover_text_color":"globals\/colors?id=e5f59cf","h1_typography_typography":"","h2_typography_typography":"","h3_typography_typography":"globals\/typography?id=secondary","h4_typography_typography":"globals\/typography?id=secondary","link_normal_color":"globals\/colors?id=primary","link_hover_color":"","h1_color":"globals\/colors?id=e5f59cf","h2_color":"globals\/colors?id=e5f59cf","h4_color":"globals\/colors?id=e5f59cf","h3_color":"globals\/colors?id=e5f59cf","h5_color":"globals\/colors?id=e5f59cf","h6_color":"globals\/colors?id=e5f59cf"},"body_color":"#7A7A7A","body_typography_typography":"custom","body_typography_font_family":"Lora","body_typography_font_size":{"unit":"px","size":16,"sizes":[]},"body_typography_font_weight":"400","body_typography_line_height":{"unit":"em","size":1.5,"sizes":[]},"button_typography_typography":"custom","button_typography_font_family":"Spartan","button_typography_font_size":{"unit":"px","size":13,"sizes":[]},"button_typography_font_weight":"700","button_typography_text_transform":"uppercase","button_text_color":"#FFFFFF","button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_typography_line_height":{"unit":"px","size":18,"sizes":[]},"h1_typography_typography":"custom","h1_typography_font_family":"Roboto","h1_typography_font_weight":"600","h1_typography_letter_spacing":{"unit":"px","size":0,"sizes":[]},"h3_typography_typography":"custom","h3_typography_font_family":"Roboto","h3_typography_font_weight":"600","h3_typography_letter_spacing":{"unit":"px","size":0,"sizes":[]},"h4_typography_typography":"custom","h4_typography_font_family":"Roboto","h4_typography_font_weight":"600","h4_typography_letter_spacing":{"unit":"px","size":0,"sizes":[]},"button_background_color":"#AC1E30","button_hover_text_color":"#282828","button_hover_background_color":"#F2CA65","h3_typography_font_size":{"unit":"px","size":28,"sizes":[]},"h3_typography_line_height":{"unit":"px","size":1.3,"sizes":[]},"link_normal_color":"#AC1E30","link_hover_color":"#9F1426","link_normal_typography_typography":"custom","h1_color":"#282828","h2_color":"#282828","h3_color":"#282828","h4_color":"#282828","h5_color":"#282828","h6_color":"#282828","h2_typography_typography":"custom","h2_typography_font_family":"Roboto","h2_typography_font_size":{"unit":"px","size":43,"sizes":[]},"h2_typography_font_weight":"600","h2_typography_letter_spacing":{"unit":"px","size":0,"sizes":[]},"h4_typography_line_height":{"unit":"px","size":1.3,"sizes":[]}}';
        $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
        $newColors     = json_decode($demo3_kit, true);
        update_post_meta($active_kit_id, '_elementor_page_settings', $newColors);
		
		$conditions_mani = 'a:4:{s:6:"single";a:3:{i:2588;a:1:{i:0;s:24:"include/singular/eventer";}i:2577;a:1:{i:0;s:21:"include/singular/post";}i:2570;a:1:{i:0;s:29:"include/singular/imi_isermons";}}s:7:"archive";a:2:{i:2584;a:1:{i:0;s:23:"include/product_archive";}i:2574;a:1:{i:0;s:28:"include/archive/post_archive";}}s:6:"footer";a:1:{i:2413;a:1:{i:0;s:15:"include/general";}}s:6:"header";a:1:{i:2381;a:1:{i:0;s:15:"include/general";}}}';
        $conditions     = unserialize($conditions_mani);
        update_option('elementor_pro_theme_builder_conditions', $conditions);
        // Update Elementor settings
        update_option('elementor_disable_color_schemes', 'yes');
        update_option('elementor_disable_typography_schemes', 'yes');
		// Update Elementor builder(native theme settings)
		$is_active_elementor_builder = 'a:1:{s:23:"elementor_demo_active_0";s:23:"elementor_demo_active_0";}';
		$activate_elementor_builder_option = unserialize($is_active_elementor_builder);
		update_option('native_theme_settings_option_name', $activate_elementor_builder_option);
		// Update Eventer plugin default color
		$eventer_data = get_option('eventer_options');
		$eventer_data['event_default_color'] = '#ac1e30';
		update_option('eventer_options', $eventer_data);
		// Update Eventer plugin default color
		$isermons_data = get_option('isermons_options');
		$isermons_data['isermons_default_color'] = '#ac1e30';
		update_option('isermons_options', $isermons_data);
		// Change on page media URLs 
		$to = get_site_url(null, '/wp-content/uploads/');
		global $wpdb;
		$wpdb->query(
			"UPDATE {$wpdb->postmeta} " .
			"SET `meta_value` = REPLACE(`meta_value`, '" . str_replace( '/', '\\\/', $from ) . "', '" . str_replace( '/', '\\\/', $to ) . "') " .
			"WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE '[%' ;"
		);
    }
    elseif ( 'Chapel - Elementor' === $selected_import['import_file_name'] ) {
		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( 'Home' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		
		$from = 'https://native-church.imithemes.com/chapel/wp-content/uploads/sites/5/';
        // Elementor Kit
        $demo4_kit = '{"system_colors":[{"_id":"primary","title":"Primary","color":"#F07241"},{"_id":"secondary","title":"Secondary","color":"#6F7173"},{"_id":"text","title":"Text","color":"#1B1F1F"},{"_id":"accent","title":"Accent","color":"#F6D86B"}],"custom_colors":[],"system_typography":[{"_id":"primary","title":"Primary","typography_typography":"custom","typography_font_family":"Tinos","typography_font_weight":"400","typography_font_size":{"unit":"px","size":20,"sizes":[]},"typography_line_height":{"unit":"em","size":1.4,"sizes":[]}},{"_id":"secondary","title":"Secondary","typography_typography":"custom","typography_font_family":"Rubik","typography_font_weight":"400","typography_font_size":{"unit":"px","size":16,"sizes":[]},"typography_line_height":{"unit":"em","size":1.5,"sizes":[]}},{"_id":"text","title":"Text","typography_typography":"custom","typography_font_family":"Tinos","typography_font_weight":"400","typography_font_size":{"unit":"px","size":18,"sizes":[]},"typography_line_height":{"unit":"em","size":1.4,"sizes":[]}},{"_id":"accent","title":"Accent","typography_typography":"custom","typography_font_family":"Homemade Apple","typography_font_weight":"500"}],"custom_typography":[],"default_generic_fonts":"Sans-serif","container_width":{"unit":"px","size":1170,"sizes":[]},"page_title_selector":"h1.entry-title","viewport_md":768,"viewport_lg":1025,"activeItemIndex":1,"button_text_color":"#FFFFFF","__globals__":{"button_background_color":"globals\/colors?id=primary","button_typography_typography":"globals\/typography?id=secondary","body_color":"globals\/colors?id=secondary","body_typography_typography":"","link_normal_color":"globals\/colors?id=primary","link_hover_color":"globals\/colors?id=secondary","h2_color":"globals\/colors?id=text","h2_typography_typography":"","h3_color":"globals\/colors?id=text","h3_typography_typography":"","h4_color":"globals\/colors?id=text","h4_typography_typography":"","h1_typography_typography":"","button_hover_text_color":"globals\/colors?id=primary","button_hover_background_color":"globals\/colors?id=accent","button_text_color":"","h5_typography_typography":"","form_field_border_color":"","form_label_typography_typography":"","form_field_typography_typography":"globals\/typography?id=secondary"},"body_typography_typography":"custom","body_typography_font_family":"Tinos","body_typography_font_weight":"400","link_normal_color":"#F07241","link_hover_color":"#6F7173","h2_color":"#1B1F1F","h2_typography_typography":"custom","h2_typography_font_family":"Tinos","h2_typography_font_size":{"unit":"px","size":48,"sizes":[]},"h2_typography_font_weight":"700","h3_color":"#1B1F1F","h3_typography_typography":"custom","h3_typography_font_family":"Tinos","h3_typography_font_weight":"700","h4_color":"#1B1F1F","h4_typography_typography":"custom","h4_typography_font_family":"Rubik","h4_typography_font_weight":"500","button_typography_typography":"custom","button_typography_font_family":"Rubik","button_typography_font_weight":"400","button_background_color":"#F07241","link_normal_typography_typography":"custom","h1_typography_typography":"custom","h1_typography_font_family":"Tinos","h1_typography_font_weight":"600","h4_typography_font_size":{"unit":"px","size":14,"sizes":[]},"button_typography_font_size":{"unit":"px","size":16,"sizes":[]},"body_color":"#6F7173","button_box_shadow_box_shadow":{"horizontal":0,"vertical":0,"blur":33,"spread":0,"color":"rgba(0, 0, 0, 0.25)"},"button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_text_color":"#F07241","button_hover_background_color":"#F6D86B","button_hover_box_shadow_box_shadow_type":"yes","button_hover_box_shadow_box_shadow":{"horizontal":0,"vertical":0,"blur":30,"spread":0,"color":"rgba(0, 0, 0, 0.19)"},"body_typography_line_height":{"unit":"em","size":1.5,"sizes":[]},"h1_typography_font_size":{"unit":"px","size":60,"sizes":[]},"h1_typography_line_height":{"unit":"em","size":1.1,"sizes":[]},"h2_typography_line_height":{"unit":"em","size":1.1,"sizes":[]},"h5_typography_typography":"custom","h5_typography_font_family":"Rubik","h5_typography_font_size":{"unit":"px","size":16,"sizes":[]},"h5_typography_font_weight":"400","h3_typography_font_size":{"unit":"px","size":32,"sizes":[]},"h4_typography_letter_spacing":{"unit":"px","size":1.5,"sizes":[]},"button_typography_line_height":{"unit":"em","size":1.5,"sizes":[]},"form_label_typography_typography":"custom","form_label_typography_font_family":"Rubik","form_label_typography_font_size":{"unit":"px","size":13,"sizes":[]},"form_label_typography_font_weight":"500","form_label_typography_text_transform":"uppercase","form_label_typography_line_height":{"unit":"em","size":1.5,"sizes":[]},"form_field_border_border":"solid","form_field_border_width":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"form_field_border_color":"#C9C9C9","form_field_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"form_field_focus_box_shadow_box_shadow_type":"yes","form_field_focus_box_shadow_box_shadow":{"horizontal":0,"vertical":0,"blur":10,"spread":0,"color":"rgba(0, 0, 0, 0.1)"},"body_typography_font_size":{"unit":"px","size":18,"sizes":[]},"button_padding":{"unit":"px","top":"15","right":"20","bottom":"15","left":"20","isLinked":false},"form_field_background_color":"#F0F0F0","form_field_padding":{"unit":"px","top":"12","right":"12","bottom":"12","left":"12","isLinked":true},"h1_typography_font_size_tablet":{"unit":"px","size":50,"sizes":[]},"h1_typography_font_size_mobile":{"unit":"px","size":40,"sizes":[]},"h2_typography_font_size_tablet":{"unit":"px","size":40,"sizes":[]},"h2_typography_font_size_mobile":{"unit":"px","size":30,"sizes":[]},"h3_typography_font_size_tablet":{"unit":"px","size":28,"sizes":[]},"h3_typography_font_size_mobile":{"unit":"px","size":22,"sizes":[]}}';
        $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
        $newColors     = json_decode($demo4_kit, true);
        update_post_meta($active_kit_id, '_elementor_page_settings', $newColors);
		
		$conditions_mani = 'a:4:{s:6:"single";a:3:{i:2606;a:1:{i:0;s:24:"include/singular/eventer";}i:2597;a:1:{i:0;s:29:"include/singular/imi_isermons";}i:2594;a:1:{i:0;s:21:"include/singular/post";}}s:6:"header";a:2:{i:2605;a:1:{i:0;s:15:"include/archive";}i:7;a:2:{i:0;s:15:"include/general";i:1;s:15:"exclude/archive";}}s:14:"elementor_head";a:1:{i:2516;a:1:{i:0;s:15:"include/general";}}s:6:"footer";a:1:{i:2494;a:1:{i:0;s:15:"include/general";}}}';
        $conditions     = unserialize($conditions_mani);
        update_option('elementor_pro_theme_builder_conditions', $conditions);
        // Update Elementor settings
        update_option('elementor_disable_color_schemes', 'yes');
        update_option('elementor_disable_typography_schemes', 'yes');
		// Update Elementor builder(native theme settings)
		$is_active_elementor_builder = 'a:1:{s:23:"elementor_demo_active_0";s:23:"elementor_demo_active_0";}';
		$activate_elementor_builder_option = unserialize($is_active_elementor_builder);
		update_option('native_theme_settings_option_name', $activate_elementor_builder_option);
		// Update Eventer plugin default color
		$eventer_data = get_option('eventer_options');
		$eventer_data['event_default_color'] = '#f07241';
		update_option('eventer_options', $eventer_data);
		// Update Eventer plugin default color
		$isermons_data = get_option('isermons_options');
		$isermons_data['isermons_default_color'] = '#f07241';
		update_option('isermons_options', $isermons_data);
		// Change on page media URLs 
		$to = get_site_url(null, '/wp-content/uploads/');
		global $wpdb;
		$wpdb->query(
			"UPDATE {$wpdb->postmeta} " .
			"SET `meta_value` = REPLACE(`meta_value`, '" . str_replace( '/', '\\\/', $from ) . "', '" . str_replace( '/', '\\\/', $to ) . "') " .
			"WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE '[%' ;"
		);
    }
    elseif ( 'Classic - Elementor' === $selected_import['import_file_name'] ) {
		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( 'Home' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		
		$from = 'https://native-church.imithemes.com/classic-elementor/wp-content/uploads/sites/8/';
        // Elementor Kit
        $demo5_kit = '{"system_colors":[{"_id":"primary","title":"Primary","color":"#007F7B"},{"_id":"secondary","title":"Secondary","color":"#333333"},{"_id":"text","title":"Text","color":"#666666"},{"_id":"accent","title":"Accent","color":"#007F7B"}],"custom_colors":[{"_id":"da45b6e","title":"Light BG","color":"#F8F7F3"},{"_id":"0323a0e","title":"Light BG 2","color":"#ECEAE4"},{"_id":"910f7cf","title":"Meta","color":"#999999"},{"_id":"809e63a","title":"Primary Hover","color":"#058984"}],"system_typography":[{"_id":"primary","title":"Primary","typography_typography":"custom","typography_font_family":"Roboto","typography_font_weight":"400","typography_line_height":{"unit":"em","size":1.5,"sizes":[]}},{"_id":"secondary","title":"Secondary","typography_typography":"custom","typography_font_family":"Roboto Condensed","typography_font_weight":"500","typography_line_height":{"unit":"em","size":1.4,"sizes":[]}},{"_id":"text","title":"Text","typography_typography":"custom","typography_font_family":"Roboto","typography_font_weight":"400","typography_line_height":{"unit":"em","size":1.5,"sizes":[]}},{"_id":"accent","title":"Accent","typography_typography":"custom","typography_font_family":"Volkhov","typography_font_weight":"400","typography_font_style":"italic","typography_line_height":{"unit":"em","size":1.4,"sizes":[]}}],"custom_typography":[],"default_generic_fonts":"Sans-serif","page_title_selector":"h1.entry-title","activeItemIndex":4,"__globals__":{"body_color":"globals\/colors?id=text","body_typography_typography":"globals\/typography?id=primary","button_typography_typography":"","button_background_color":"globals\/colors?id=primary","button_hover_background_color":"","h4_color":"globals\/colors?id=secondary","h4_typography_typography":"","form_field_border_color":""},"viewport_md":768,"viewport_lg":1025,"body_color":"#666666","body_typography_typography":"custom","body_typography_font_family":"Roboto","body_typography_font_weight":"400","body_typography_line_height":{"unit":"em","size":1.5,"sizes":[]},"button_typography_typography":"custom","button_typography_font_family":"Roboto Condensed","button_typography_font_weight":"500","button_typography_text_transform":"uppercase","button_typography_line_height":{"unit":"em","size":1.4,"sizes":[]},"button_typography_letter_spacing":{"unit":"px","size":1,"sizes":[]},"button_text_color":"#FFFFFF","button_background_color":"#007F7B","button_hover_text_color":"#FFFFFF","button_hover_background_color":"#058984","paragraph_spacing":{"unit":"px","size":20,"sizes":[]},"h4_color":"#333333","h4_typography_typography":"custom","h4_typography_font_family":"Roboto Condensed","h4_typography_font_size":{"unit":"px","size":16,"sizes":[]},"h4_typography_font_weight":"500","h4_typography_text_transform":"uppercase","h4_typography_line_height":{"unit":"em","size":1.4,"sizes":[]},"container_width":{"unit":"px","size":1040,"sizes":[]},"form_field_border_border":"solid","form_field_border_width":{"unit":"px","top":"1","right":"1","bottom":"1","left":"1","isLinked":true},"form_field_border_color":"#D7D7D7","form_field_border_radius":{"unit":"px","top":"4","right":"4","bottom":"4","left":"4","isLinked":true},"form_field_padding":{"unit":"px","top":"15","right":"15","bottom":"15","left":"15","isLinked":true}}';
        $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
        $newColors     = json_decode($demo5_kit, true);
        update_post_meta($active_kit_id, '_elementor_page_settings', $newColors);
		
		$conditions_mani = 'a:4:{s:6:"single";a:3:{i:2626;a:1:{i:0;s:21:"include/singular/post";}i:2571;a:1:{i:0;s:29:"include/singular/imi_isermons";}i:2537;a:2:{i:0;s:16:"include/singular";i:1;s:27:"exclude/singular/front_page";}}s:14:"elementor_head";a:2:{i:2530;a:1:{i:0;s:15:"include/general";}i:2446;a:1:{i:0;s:15:"include/general";}}s:6:"footer";a:1:{i:2528;a:1:{i:0;s:15:"include/general";}}s:6:"header";a:1:{i:2504;a:1:{i:0;s:15:"include/general";}}}';
        $conditions     = unserialize($conditions_mani);
        update_option('elementor_pro_theme_builder_conditions', $conditions);
        // Update Elementor settings
        update_option('elementor_disable_color_schemes', 'yes');
        update_option('elementor_disable_typography_schemes', 'yes');
		//Update Elementor builder(native theme settings)
		$is_active_elementor_builder = 'a:1:{s:23:"elementor_demo_active_0";s:23:"elementor_demo_active_0";}';
		$activate_elementor_builder_option = unserialize($is_active_elementor_builder);
		update_option('native_theme_settings_option_name', $activate_elementor_builder_option);
		// Update Eventer plugin default color
		$eventer_data = get_option('eventer_options');
		$eventer_data['event_default_color'] = '#007f7b';
		update_option('eventer_options', $eventer_data);
		// Update Eventer plugin default color
		$isermons_data = get_option('isermons_options');
		$isermons_data['isermons_default_color'] = '#007f7b';
		update_option('isermons_options', $isermons_data);
		// Change on page media URLs 
		$to = get_site_url(null, '/wp-content/uploads/');
		global $wpdb;
		$wpdb->query(
			"UPDATE {$wpdb->postmeta} " .
			"SET `meta_value` = REPLACE(`meta_value`, '" . str_replace( '/', '\\\/', $from ) . "', '" . str_replace( '/', '\\\/', $to ) . "') " .
			"WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE '[%' ;"
		);
    }
    elseif ( 'Buddhism - Elementor' === $selected_import['import_file_name'] ) {
		// Assign front page and posts page (blog page).
		$front_page_id = get_page_by_title( 'Home' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		
		$from = 'https://nativetheme.com/buddhism/wp-content/uploads/sites/3/';
        // Elementor Kit
        $demo6_kit = '{"system_colors":[{"_id":"primary","title":"Primary","color":"#267592"},{"_id":"secondary","title":"Secondary","color":"#131516"},{"_id":"text","title":"Text","color":"#373D3F"},{"_id":"accent","title":"Accent","color":"#FCE043"}],"custom_colors":[{"_id":"a999e1b","title":"Primary Light","color":"#4192B1"},{"_id":"1ac7978","title":"Primary Dark","color":"#1E6681"},{"_id":"e1d0867","title":"Light BG","color":"#EDF2F3"},{"_id":"1be77dc","title":"Functional Text","color":"#999999"}],"system_typography":[{"_id":"primary","title":"Primary","typography_typography":"custom","typography_font_family":"Lato","typography_font_weight":"600"},{"_id":"secondary","title":"Secondary","typography_typography":"custom","typography_font_family":"Bitter","typography_font_weight":"400","typography_line_height":{"unit":"em","size":1.5,"sizes":[]}},{"_id":"text","title":"Text","typography_typography":"custom","typography_font_family":"Bitter","typography_font_weight":"400","typography_line_height":{"unit":"em","size":1.5,"sizes":[]}},{"_id":"accent","title":"Accent","typography_typography":"custom","typography_font_family":"Lato","typography_font_weight":"500"}],"custom_typography":[{"_id":"ee2aeb1","title":"Functional Text","typography_typography":"custom","typography_font_family":"Lato","typography_font_weight":"500","typography_text_transform":"uppercase","typography_line_height":{"unit":"px","size":1,"sizes":[]}}],"default_generic_fonts":"Sans-serif","body_color":"#373D3F","body_typography_typography":"custom","body_typography_font_family":"Bitter","body_typography_font_weight":"400","body_typography_line_height":{"unit":"em","size":1.5,"sizes":[]},"paragraph_spacing":{"unit":"px","size":25,"sizes":[]},"link_normal_color":"#267592","link_hover_color":"#5EA7BF","h1_color":"#131516","h1_typography_typography":"custom","h1_typography_font_family":"Lato","h1_typography_font_size":{"unit":"rem","size":4,"sizes":[]},"h1_typography_font_weight":"400","h1_typography_line_height":{"unit":"em","size":1.1000000000000001,"sizes":[]},"h2_color":"#131516","h2_typography_typography":"custom","h2_typography_font_family":"Lato","h2_typography_font_size":{"unit":"rem","size":3,"sizes":[]},"h2_typography_font_weight":"600","h2_typography_line_height":{"unit":"em","size":1.1000000000000001,"sizes":[]},"h3_color":"#131516","h3_typography_typography":"custom","h3_typography_font_family":"Lato","h3_typography_font_size":{"unit":"rem","size":2,"sizes":[]},"h3_typography_font_weight":"600","h3_typography_line_height":{"unit":"em","size":1.3,"sizes":[]},"h4_color":"#131516","h4_typography_typography":"custom","h4_typography_font_family":"Lato","h4_typography_font_size":{"unit":"rem","size":1.5,"sizes":[]},"h4_typography_font_weight":"600","h4_typography_line_height":{"unit":"em","size":1.3999999999999999,"sizes":[]},"button_typography_typography":"custom","button_typography_font_family":"Lato","button_typography_font_weight":"600","button_text_color":"#FFFFFF","page_title_selector":"h1.entry-title","activeItemIndex":1,"__globals__":{"body_color":"globals\/colors?id=text","body_typography_typography":"","link_normal_color":"globals\/colors?id=1ac7978","link_hover_color":"globals\/colors?id=primary","h1_color":"globals\/colors?id=secondary","h1_typography_typography":"","h2_color":"globals\/colors?id=secondary","h2_typography_typography":"","h3_color":"globals\/colors?id=secondary","h3_typography_typography":"","h4_color":"globals\/colors?id=secondary","h4_typography_typography":"","button_typography_typography":"","button_background_color":"globals\/colors?id=primary","button_hover_background_color":"globals\/colors?id=1ac7978","h5_color":"globals\/colors?id=secondary","h5_typography_typography":"","h6_color":"globals\/colors?id=secondary","h6_typography_typography":"","settings_page_transitions_background_color":"globals\/colors?id=primary"},"viewport_md":768,"viewport_lg":1025,"button_typography_text_transform":"uppercase","button_hover_border_radius":{"unit":"px","top":"6","right":"6","bottom":"6","left":"6","isLinked":true},"button_border_radius":{"unit":"px","top":"0","right":"0","bottom":"0","left":"0","isLinked":true},"button_hover_text_color":"#FFFFFF","button_padding":{"unit":"px","top":"15","right":"25","bottom":"15","left":"25","isLinked":false},"button_background_color":"#267592","button_typography_font_size":{"unit":"px","size":14,"sizes":[]},"body_typography_font_size":{"unit":"px","size":18,"sizes":[]},"button_typography_letter_spacing":{"unit":"px","size":0.5,"sizes":[]},"h5_color":"#131516","h5_typography_typography":"custom","h5_typography_font_family":"Lato","h5_typography_font_size":{"unit":"px","size":17,"sizes":[]},"h5_typography_font_weight":"600","h5_typography_line_height":{"unit":"em","size":0.90000000000000002,"sizes":[]},"h6_color":"#131516","h6_typography_typography":"custom","h6_typography_font_family":"Lato","h6_typography_font_size":{"unit":"px","size":16,"sizes":[]},"h6_typography_font_weight":"400","h6_typography_line_height":{"unit":"em","size":1.5,"sizes":[]},"h4_typography_text_transform":"capitalize","h4_typography_letter_spacing":{"unit":"px","size":0,"sizes":[]},"container_width":{"unit":"px","size":1240,"sizes":[]},"settings_page_transitions_preloader_image":{"url":"https:\/\/nativetheme.com\/buddhism\/wp-content\/uploads\/sites\/3\/2023\/03\/romeo-a-iMi16fg2E88-unsplash.jpg","id":592,"size":"","alt":"","source":"library"},"settings_page_transitions_preloader_width":{"unit":"%","size":100,"sizes":[]},"settings_page_transitions_preloader_max_width":{"unit":"%","size":100,"sizes":[]},"settings_page_transitions_preloader_opacity":{"unit":"px","size":1,"sizes":[]},"body_typography_font_size_tablet":{"unit":"px","size":16,"sizes":[]},"body_typography_font_size_mobile":{"unit":"px","size":16,"sizes":[]},"h1_typography_font_size_mobile":{"unit":"rem","size":2.5,"sizes":[]},"h2_typography_font_size_mobile":{"unit":"rem","size":2,"sizes":[]},"h3_typography_font_size_mobile":{"unit":"rem","size":1.5,"sizes":[]},"h4_typography_font_size_mobile":{"unit":"rem","size":1.2,"sizes":[]},"link_normal_typography_typography":"custom","link_normal_typography_text_decoration":"none","link_hover_typography_typography":"custom","link_hover_typography_text_decoration":"none"}';
        $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
        $newColors     = json_decode($demo6_kit, true);
        update_post_meta($active_kit_id, '_elementor_page_settings', $newColors);
		
		$conditions_mani = 'a:5:{s:6:"single";a:3:{i:1219;a:1:{i:0;s:29:"include/singular/imi_isermons";}i:1202;a:1:{i:0;s:24:"include/singular/eventer";}i:1154;a:1:{i:0;s:21:"include/singular/post";}}s:5:"popup";a:1:{i:1002;a:1:{i:0;s:15:"include/general";}}s:6:"footer";a:1:{i:524;a:1:{i:0;s:15:"include/general";}}s:14:"elementor_head";a:1:{i:362;a:1:{i:0;s:15:"include/general";}}s:6:"header";a:1:{i:899;a:1:{i:0;s:15:"include/general";}}}';
        $conditions     = unserialize($conditions_mani);
        update_option('elementor_pro_theme_builder_conditions', $conditions);
        // Update Elementor settings
        update_option('elementor_disable_color_schemes', 'yes');
        update_option('elementor_disable_typography_schemes', 'yes');
		//Update Elementor builder(native theme settings)
		$is_active_elementor_builder = 'a:1:{s:23:"elementor_demo_active_0";s:23:"elementor_demo_active_0";}';
		$activate_elementor_builder_option = unserialize($is_active_elementor_builder);
		update_option('native_theme_settings_option_name', $activate_elementor_builder_option);
		// Update Eventer plugin default color
		$eventer_data = get_option('eventer_options');
		$eventer_data['event_default_color'] = '#267592';
		update_option('eventer_options', $eventer_data);
		// Update Eventer plugin default color
		$isermons_data = get_option('isermons_options');
		$isermons_data['isermons_default_color'] = '#267592';
		update_option('isermons_options', $isermons_data);
	
		$to = get_site_url(null, '/wp-content/uploads/');
		global $wpdb;
		$wpdb->query(
			"UPDATE {$wpdb->postmeta} " .
			"SET `meta_value` = REPLACE(`meta_value`, '" . str_replace( '/', '\\\/', $from ) . "', '" . str_replace( '/', '\\\/', $to ) . "') " .
			"WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE '[%' ;"
		);
    }
}
add_action( 'ocdi/after_import', 'ocdi_after_import' );