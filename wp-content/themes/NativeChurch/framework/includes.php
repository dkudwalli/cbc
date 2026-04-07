<?php
if (!defined('ABSPATH')) {
  exit;
}
// Exit if accessed directly
define('ImicFrameworkPath', dirname(__FILE__));
/*
 * Here you include files which is required by theme
 */
require_once ImicFrameworkPath . '/theme-functions.php';
/* CUSTOM POST TYPES
================================================== */
/* META BOX & MEGA MENU FRAMEWORK
================================================== */
$native_theme_settings_options = get_option('native_theme_settings_option_name');
if ($native_theme_settings_options) {
  $elementor_demo_active_0 = $native_theme_settings_options['elementor_demo_active_0'];
  if ($elementor_demo_active_0 == '') {
    require_once ImicFrameworkPath . '/meta-boxes.php';
  } else {
    function hoge_remove_page_templates($templates)
    {
      //    use unset() if you want to remove specific template
      unset($templates['template-staff.php']);
      unset($templates['template-home.php']);
      unset($templates['template-home-pb.php']);
      unset($templates['template-h-second.php']);
      unset($templates['template-h-third.php']);
      unset($templates['template-sermons.php']);
      unset($templates['template-sermons-albums.php']);
      unset($templates['template-ministry.php']);
      unset($templates['template-gallery-pagination.php']);
      unset($templates['template-gallery-masonry.php']);
      unset($templates['template-gallery-filter.php']);
      unset($templates['template-fullwidth.php']);
      unset($templates['template-events.php']);
      unset($templates['template-events-timeline.php']);
      unset($templates['template-events-classic.php']);
      unset($templates['template-events_grid.php']);
      unset($templates['template-event-category.php']);
      unset($templates['template-content-with-sidebar.php']);
      unset($templates['template-contact.php']);
      unset($templates['template-blog-timeline.php']);
      unset($templates['template-blog-medium-thumbnails.php']);
      unset($templates['template-blog-masonry.php']);
      unset($templates['template-blog-full-width.php']);
      unset($templates['plugin_template/template-causes-grid.php']);
      unset($templates['plugin_template/template-causes-list.php']);
      return $templates;

      //    or just return empty array to remove the entire page templates input 
      //	return array();
    }
    add_filter('theme_page_templates', 'hoge_remove_page_templates');
  }
} else {
  require_once ImicFrameworkPath . '/meta-boxes.php';
}
// IMI CUSTOM MEGAMENU WALKER
require_once ImicFrameworkPath . '/megamenu/megamenu.php';


/* SHORTCODES
================================================== */

/* WELCOME PAGE
================================================== */
require_once IMIC_FILEPATH . '/welcome.php';
/* PLUGIN INCLUDES
================================================== */
require_once ImicFrameworkPath . '/tgm/plugin-includes.php';
require_once ImicFrameworkPath . '/theme_options_css.php';

/* Woocommerce INCLUDES
================================================== */
require_once ImicFrameworkPath . '/woocommerce.php';
/* LOAD STYLESHEETS
================================================== */
if (!function_exists('imic_enqueue_styles')) {
  function imic_enqueue_styles()
  {
    $imic_options = get_option('imic_options');
    $event_feature = (isset($imic_options['enable_event_feature'])) ? $imic_options['enable_event_feature'] : '1';
    $theme_info = wp_get_theme();
    wp_register_style('imic_base_style', IMIC_THEME_PATH . '/assets/css/base.css', array(), $theme_info->get('Version'), 'all');
    wp_register_style('imic_bootstrap', IMIC_THEME_PATH . '/assets/css/bootstrap.css', array(), $theme_info->get('Version'), 'all');
    wp_register_style('imic_fontawesome', IMIC_THEME_PATH . '/assets/css/font-awesome.css', array(), $theme_info->get('Version'), 'all');
    wp_register_style('imic_animations', IMIC_THEME_PATH . '/assets/css/animations.css', array(), $theme_info->get('Version'), 'all');
    wp_register_style('imic_mediaelementplayer', IMIC_THEME_PATH . '/assets/vendor/mediaelement/mediaelementplayer.css', array(), $theme_info->get('Version'), 'all');
    wp_register_style('imic_main', get_stylesheet_uri(), array(), $theme_info->get('Version'), 'all');
    wp_register_style('imic_prettyPhoto', IMIC_THEME_PATH . '/assets/vendor/prettyphoto/css/prettyPhoto.css', array(), $theme_info->get('Version'), 'all');
    wp_register_style('imic_magnific', IMIC_THEME_PATH . '/assets/vendor/magnific/magnific-popup.css', array(), $theme_info->get('Version'), 'all');
    wp_register_style('imic_owl1', IMIC_THEME_PATH . '/assets/vendor/owl-carousel/css/owl.carousel.css', array(), $theme_info->get('Version'), 'all');
    wp_register_style('imic_owl2', IMIC_THEME_PATH . '/assets/vendor/owl-carousel/css/owl.theme.css', array(), $theme_info->get('Version'), 'all');
    $theme_color_sceheme = (isset($imic_options['theme_color_scheme'])) ? $imic_options['theme_color_scheme'] : '';
    wp_register_style('theme-colors', IMIC_THEME_PATH . '/assets/colors/' . $theme_color_sceheme, array(), $theme_info->get('Version'), 'all');
    ($event_feature == '1') ? wp_register_style('imic_fullcalendar_css', IMIC_THEME_PATH . '/assets/vendor/fullcalendar/fullcalendar.min.css', array(), $theme_info->get('Version'), 'all') : '';
    ($event_feature == '1') ? wp_register_style('imic_fullcalendar_print', IMIC_THEME_PATH . '/assets/vendor/fullcalendar/fullcalendar.print.css', array(), $theme_info->get('Version'), 'print') : '';
    if (!isset($imic_options['body_font_typography']) && !isset($imic_options['heading_font_typography'])) {
      wp_enqueue_style('default-google-fonts', 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&family=Roboto+Condensed:ital,wght@0,400;0,700;1,400;1,700&display=swap', '', '', 'all');
    }
    //**Enqueue STYLESHEETPATH**//
    if (!isset($imic_options['theme_bootstrap_css']) || ($imic_options['theme_bootstrap_css'] == 1)) {
      wp_enqueue_style('imic_bootstrap');
    }
    if (!isset($imic_options['theme_fontawesome_css']) || ($imic_options['theme_fontawesome_css'] == 1)) {
      wp_enqueue_style('imic_fontawesome');
    }
    if (!isset($imic_options['theme_animations_css']) || ($imic_options['theme_animations_css'] == 1)) {
      wp_enqueue_style('imic_animations');
    }
    if (!isset($imic_options['theme_mediaelement_css']) || ($imic_options['theme_mediaelement_css'] == 1)) {
      wp_enqueue_style('imic_mediaelementplayer');
    }
    wp_enqueue_style('imic_main');
    if (!isset($imic_options['theme_base_css']) || ($imic_options['theme_base_css'] == 1)) {
      wp_enqueue_style('imic_base_style');
    }
    if (!isset($imic_options['theme_lightbox_css']) || ($imic_options['theme_lightbox_css'] == 1)) {
      if (isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox'] == 0) {
        wp_enqueue_style('imic_prettyPhoto');
      } elseif (isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox'] == 1) {
        wp_enqueue_style('imic_magnific');
      }
    }
    if ($event_feature == '1') {
      wp_enqueue_style('imic_fullcalendar_css');
      wp_enqueue_style('imic_fullcalendar_print');
    }
    if (!isset($imic_options['theme_color_css']) || ($imic_options['theme_color_css'] == 1)) {
      if (isset($imic_options['theme_color_type'])) {
        if ($imic_options['theme_color_type'][0] == 0) {
          wp_enqueue_style('theme-colors');
        }
      } elseif (!isset($imic_options['theme_color_type'])) {
        wp_enqueue_style('theme-colors-default', IMIC_THEME_PATH . '/assets/colors/color1.css', array(), $theme_info->get('Version'), 'all');
      }
    }
    //**End Enqueue STYLESHEETPATH**//
  }
  add_action('wp_enqueue_scripts', 'imic_enqueue_styles', 99);
}
if (!function_exists('imic_enqueue_scripts')) {
  function imic_enqueue_scripts()
  {
    $imic_options = get_option('imic_options');
    $theme_info = wp_get_theme();
    $event_feature = (isset($imic_options['enable_event_feature'])) ? $imic_options['enable_event_feature'] : '1';
    $google_api_key = (isset($imic_options['google_feed_key'])) ? $imic_options['google_feed_key'] : '';
    $google_calendar_id = (isset($imic_options['google_feed_id'])) ? $imic_options['google_feed_id'] : '';
    $monthNamesValue = (isset($imic_options['calendar_month_name'])) ? $imic_options['calendar_month_name'] : '';
    $monthNames = (empty($monthNamesValue)) ? array() : explode(',', trim($monthNamesValue));
    $monthNamesShortValue = (isset($imic_options['calendar_month_name_short'])) ? $imic_options['calendar_month_name_short'] : '';
    $monthNamesShort = (empty($monthNamesShortValue)) ? array() : explode(',', trim($monthNamesShortValue));
    $dayNamesValue = (isset($imic_options['calendar_day_name'])) ? $imic_options['calendar_day_name'] : '';
    $dayNames = (empty($dayNamesValue)) ? array() : explode(',', trim($dayNamesValue));
    $dayNamesShortValue = (isset($imic_options['calendar_day_name_short'])) ? $imic_options['calendar_day_name_short'] : '';
    $dayNamesShort = (empty($dayNamesShortValue)) ? array() : explode(',', trim($dayNamesShortValue));
    //**register script**//
    wp_register_script('imic_jquery_modernizr', IMIC_THEME_PATH . '/assets/js/modernizr.js', array(), $theme_info->get('Version'), true);
    wp_register_script('imic_jquery_prettyphoto', IMIC_THEME_PATH . '/assets/vendor/prettyphoto/js/prettyphoto.js', array('jquery'), $theme_info->get('Version'), true);
    wp_register_script('imic_jquery_magnific', IMIC_THEME_PATH . '/assets/vendor/magnific/jquery.magnific-popup.min.js', array('jquery'), $theme_info->get('Version'), true);
    wp_register_script('imic_jquery_helper_plugins', IMIC_THEME_PATH . '/assets/js/helper-plugins.js', array('jquery'), $theme_info->get('Version'), true);
    wp_register_script('imic_jquery_bootstrap', IMIC_THEME_PATH . '/assets/js/bootstrap.js', array(), $theme_info->get('Version'), true);
    wp_register_script('imic_jquery_waypoints', IMIC_THEME_PATH . '/assets/js/waypoints.js', array('jquery'), $theme_info->get('Version'), true);
    wp_register_script('imic_jquery_mediaelement_and_player', IMIC_THEME_PATH . '/assets/vendor/mediaelement/mediaelement-and-player.min.js', array('jquery'), $theme_info->get('Version'), true);
    wp_register_script('imic_jquery_init', IMIC_THEME_PATH . '/assets/js/init.js', array('jquery'), $theme_info->get('Version'), true);
    wp_register_script('imic_jquery_flexslider', IMIC_THEME_PATH . '/assets/vendor/flexslider/js/jquery.flexslider.js', array('jquery'), $theme_info->get('Version'), true);
    wp_register_script('imic_owl_carousel', IMIC_THEME_PATH . '/assets/vendor/owl-carousel/js/owl.carousel.min.js', array('jquery'), $theme_info->get('Version'), true);
    wp_register_script('imic_owl_carousel_init', IMIC_THEME_PATH . '/assets/vendor/owl-carousel/js/owl.carousel.init.js', array('jquery'), $theme_info->get('Version'), true);
    if ($event_feature == '1') {
      wp_register_script('imic_jquery_countdown', IMIC_THEME_PATH . '/assets/vendor/countdown/js/jquery.countdown.min.js', array('jquery'), $theme_info->get('Version'), true);
      wp_register_script('imic_jquery_countdown_init', IMIC_THEME_PATH . '/assets/vendor/countdown/js/countdown.init.js', array('jquery'), $theme_info->get('Version'), true);
      wp_register_script('imic_fullcalendar', IMIC_THEME_PATH . '/assets/vendor/fullcalendar/fullcalendar.min.js', array('jquery'), $theme_info->get('Version'), true);
      wp_register_script('imic_gcal', IMIC_THEME_PATH . '/assets/vendor/fullcalendar/gcal.js', array('jquery'), $theme_info->get('Version'), true);
      wp_register_script('imic_calender_events', IMIC_THEME_PATH . '/assets/js/calender_events.js', array('jquery'), $theme_info->get('Version'), true);
      wp_register_script('imic_calender_updated', IMIC_THEME_PATH . '/assets/vendor/fullcalendar/lib/moment.min.js', array('jquery'), $theme_info->get('Version'), true);
      wp_register_script('fullcalendar-locale', IMIC_THEME_PATH . '/assets/vendor/fullcalendar/locale-all.js', array('jquery'), $theme_info->get('Version'), true);
      wp_register_script('imic_print_ticket', IMIC_THEME_PATH . '/assets/js/print-ticket.js', array('jquery'), $theme_info->get('Version'), true);
      wp_register_script('imic_event_pay', IMIC_THEME_PATH . '/assets/js/event_pay.js', array('jquery'), $theme_info->get('Version'), true);
    }
    wp_register_script('imic_sticky', IMIC_THEME_PATH . '/assets/js/sticky.js', array('jquery'), $theme_info->get('Version'), true);
    //**End register script**//
    //**Enqueue script**//
    if (!isset($imic_options['theme_modernizr_js']) || ($imic_options['theme_modernizr_js'] == 1)) {
      wp_enqueue_script('imic_jquery_modernizr');
      wp_enqueue_script('jquery');
    }
    ($event_feature == '1') ? wp_enqueue_script('imic_calender_updated') : '';
    if (!isset($imic_options['theme_lightbox_js']) || ($imic_options['theme_lightbox_js'] == 1)) {
      if (isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox'] == 0) {
        wp_enqueue_script('imic_jquery_prettyphoto');
        $pp_opacity = (isset($imic_options['prettyphoto_opacity']) && $imic_options['prettyphoto_opacity'] != '') ? esc_attr($imic_options['prettyphoto_opacity']) : '0.80';
        $pp_resize = (isset($imic_options['prettyphoto_opt_resize']) && $imic_options['prettyphoto_opt_resize'] != '') ? esc_attr($imic_options['prettyphoto_opt_resize']) : 'true';
        $pp_title = (isset($imic_options['prettyphoto_title']) && $imic_options['prettyphoto_title'] == 0) ? 'true' : 'false';
        $pp_theme = (isset($imic_options['prettyphoto_theme']) && $imic_options['prettyphoto_theme'] != '') ? esc_attr($imic_options['prettyphoto_theme']) : '';
        wp_add_inline_script('imic_jquery_prettyphoto', 'jQuery(document).ready(function(){jQuery("a[data-rel^=\'prettyPhoto\']").prettyPhoto({opacity:' . $pp_opacity . ',social_tools:"",deeplinking:false,allow_resize:' . $pp_resize . ',show_title:' . $pp_title . ',theme:\'' . $pp_theme . '\'});});');
      } elseif (isset($imic_options['switch_lightbox']) && $imic_options['switch_lightbox'] == 1) {
        wp_enqueue_script('imic_jquery_magnific');
        wp_add_inline_script('imic_jquery_magnific', 'jQuery(document).ready(function(){jQuery(".format-gallery").each(function(){jQuery(this).magnificPopup({delegate:"a.magnific-gallery-image",type:"image",gallery:{enabled:true}});});jQuery(".magnific-image").magnificPopup({type:"image"});jQuery(".magnific-video").magnificPopup({type:"iframe"});jQuery(".title-subtitle-holder-inner").magnificPopup({delegate:"a.magnific-video",type:"iframe",gallery:{enabled:true}});});');
      }
    }
    ($event_feature == '1') ? wp_enqueue_script('imic_event_scripts', IMIC_THEME_PATH . '/assets/js/event_script.js', array('jquery'), $theme_info->get('Version'), true) : '';
    ($event_feature == '1') ? wp_localize_script('imic_event_scripts', 'events', array('ajaxurl' => admin_url('admin-ajax.php'))) : '';
    if (!isset($imic_options['theme_bootstrap_js']) || ($imic_options['theme_bootstrap_js'] == 1)) {
      wp_enqueue_script('imic_jquery_bootstrap');
    }
    if (!isset($imic_options['theme_general_js']) || ($imic_options['theme_general_js'] == 1)) {
      wp_enqueue_script('imic_jquery_helper_plugins');
      wp_enqueue_script('imic_jquery_waypoints');
      wp_enqueue_script('imic_jquery_mediaelement_and_player');
      wp_enqueue_script('imic_jquery_flexslider');
      wp_enqueue_script('imic_jquery_init');
      wp_localize_script('imic_jquery_init', 'initval', array('tmp' => get_template_directory_uri(), 'ajaxurl' => admin_url('admin-ajax.php')));
    }
    ($event_feature == '1') ? wp_enqueue_script('imic_jquery_countdown') : '';
    ($event_feature == '1') ? wp_enqueue_script('imic_jquery_countdown_init') : '';
    if (isset($imic_options['enable-header-stick']) && $imic_options['enable-header-stick'] == 1) {
      wp_enqueue_script('imic_sticky');
    }
    if (is_singular() && comments_open() && get_option('thread_comments')) {
      wp_enqueue_script('comment-reply');
    }
    // Google calendar link target
    $event_google_open_link = isset($imic_options['event_google_open_link']) ? $imic_options['event_google_open_link'] : 0;
    if ($event_google_open_link == 1) {
      wp_add_inline_script('imic_jquery_init', 'jQuery(document).ready(function(){jQuery(\'a[href^="https://www.google.com/calendar/"]\').attr("target","_blank");});');
    }
    // Only load registration script for non-logged-in users (form is only on registration pages)
    if (!is_user_logged_in()) {
      wp_enqueue_script('agent-register', IMIC_THEME_PATH . '/assets/js/agent-register.js', array('jquery'), $theme_info->get('Version'), true);
      wp_localize_script('agent-register', 'agent_register', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
    // Only load event AJAX script on event-related pages
    if (is_singular('event') || is_post_type_archive('event') || is_page_template(array('template-events.php', 'template-events-classic.php', 'template-events-timeline.php', 'template-events_grid.php', 'template-event-category.php'))) {
      wp_enqueue_script('event_ajax', IMIC_THEME_PATH . '/assets/js/event_ajax.js', array('jquery'), $theme_info->get('Version'), true);
      wp_localize_script('event_ajax', 'urlajax', array('homeurl' => get_template_directory_uri(), 'ajaxurl' => admin_url('admin-ajax.php')));
    }
    ($event_feature == '1') ? wp_localize_script('imic_jquery_countdown', 'upcoming_data', array('c_time' => date_i18n('U'))) : '';
    //**End Enqueue script**//
  }
  add_action('wp_enqueue_scripts', 'imic_enqueue_scripts');
}
/* LOAD BACKEND SCRIPTS
================================================== */
function nativechurch_load_backend_scripts($hook)
{
  $theme_info = wp_get_theme();
  if ($hook == 'widgets.php') {
    wp_enqueue_script('imic-selected-post', IMIC_THEME_PATH . '/assets/js/selected_post.js', 'jquery', $theme_info->get('Version'), true);
    wp_localize_script('imic-selected-post', 'cats', array('ajaxurl' => admin_url('admin-ajax.php')));
  }
  wp_enqueue_script('imic-admin-functions', IMIC_THEME_PATH . '/assets/js/imic_admin.js', 'jquery', $theme_info->get('Version'), true);
  $allSermonsPlayed = get_option('sermons_played');
  $allSermonsPlayed = $allSermonsPlayed ? $allSermonsPlayed : 0;
  wp_localize_script('imic-admin-functions', 'adminVals', ['plays' => $allSermonsPlayed, 'ajaxurl' => admin_url('admin-ajax.php')]);
  if (isset($_REQUEST['taxonomy'])) {
    wp_enqueue_script('imic-upload', IMIC_THEME_PATH . '/assets/js/upload.js', 'jquery', $theme_info->get('Version'), true);
    wp_enqueue_media();
  }
  wp_enqueue_script('imic-admin-scripts-new', IMIC_THEME_PATH . '/assets/js/imi-plugins.js', 'jquery', $theme_info->get('Version'), true);
  wp_localize_script('imic-admin-scripts-new', 'vals', array('siteurl' => esc_url(site_url('wp-admin/admin.php?page=imi-admin-welcome'))));
  wp_enqueue_style('adorechurch-admin-style', IMIC_THEME_PATH . '/assets/css/admin-pages.css', array(), $theme_info->get('Version'), 'all');
}
add_action('admin_enqueue_scripts', 'nativechurch_load_backend_scripts');
/* LOAD Page Builder Prebuilt Pages
================================================== */
require_once ImicFrameworkPath . '/page-builder/page-builder.php';
