<?php
if (!defined('ABSPATH'))
  exit; // Exit if accessed directly
/*
 *
 * 	imic Framework Theme Functions
 * 	------------------------------------------------
 * 	imic Framework v2.0
 * 	Copyright imic  2014 - http://www.imicreation.com/
 *  ------------------------------------------------
 *  ocdi setup
 * 	imic_theme_activation()
 * 	imic_maintenance_mode()
 * 	imic_custom_login_logo()
 * 	imic_add_nofollow_cat()
 * 	imic_admin_bar_menu()
 * 	imic_admin_css()
 * 	imic_analytics()
 * 	imic_custom_styles()
 * 	imic_custom_script()
 *  imic_content_filter()
 *  imic_video_embed()
 *  imic_video_youtube()
 *  imic_video_vimeo()
 *  imic_audio_soundcloud()
 * 	imic_register_sidebars()
 *  imic_custom_taxonomies_terms_links()    
 * 	event_time_columns()
 * 	event_time_column_content()
 * 	sortable_event_column()
 *  event_time_orderby()
 *  imic_register_meta_box()
 *  class IMIC_Custom_Nav
 *  imic_get_all_types()
 *  imic_day_diff()
 *  imic_get_recursive_event_data()
 *  imic_get_cat_list()
 *  imic_widget_titles()
 *  widget_text filter
 *  imic_month_translate()
 *  imic_short_month_translate()
 *  imic_day_translate()
 *  imic_global_month_name()
 *  RevSliderShortCode()
 *  imic_gallery_flexslider()
 *  imic_sermon_attach_full_audio()
 *  imic_sermon_attach_full_pdf()
 *  imic_query_arg()
 *  imicAddQueryVarsFilter()
 *  imicConvertDate()
 *  imic_cat_count_flag()
 *	imic_page_design()
 *  imic_get_home_recursive_event_data()
 *  imicBlogTemplateRedirect()
 *  imicGetThumbAndLargeSize()
 * 	imic_sidebar_position_module()
 *	imic_share_buttons()
 *	imic save events module()
 */
/* One Click Demo Import Setup */

/* -------------------------------------------------------------------------------------
  Theme Activation
  @since NativeChurch 1.0
------------------------------------------------------------------------------------- */
if (!function_exists('imic_theme_activation')) {
  function imic_theme_activation()
  {
    global $pagenow;
    if (is_admin() && 'themes.php' == $pagenow && isset($_GET['activated'])) {
      #provide hook so themes can execute theme specific functions on activation
      do_action('imic_theme_activation');
    }
  }
  add_action('admin_init', 'imic_theme_activation');
}
/* -------------------------------------------------------------------------------------
  Maintenance Mode
  @since NativeChurch 1.0
------------------------------------------------------------------------------------- */
if (!function_exists('imic_maintenance_mode')) {
  function imic_maintenance_mode()
  {
    $options = get_option('imic_options');
    $custom_logo = $custom_logo_output = $maintenance_mode = "";
    if (isset($options['custom_admin_login_logo']['url'])) {
      $custom_logo = $options['custom_admin_login_logo'];
      $custom_logo_output = '<img src="' . $custom_logo['url'] . '" alt="maintenance" style="height: 62px!important;margin: 0 auto; display: block;" />';
    }
    if (isset($options['enable_maintenance'])) {
      $maintenance_mode = $options['enable_maintenance'];
    } else {
      $maintenance_mode = false;
    }
    if ($maintenance_mode) {
      if (!current_user_can('edit_themes') || !is_user_logged_in()) {
        wp_die($custom_logo_output . '<p style="text-align:center">' . esc_html__('We are currently in maintenance mode, please check back shortly.', 'framework') . '</p>', esc_html__('Maintenance Mode', 'framework'));
      }
    }
  }
  add_action('get_header', 'imic_maintenance_mode');
}
/* -------------------------------------------------------------------------------------
  Custom Admin Logo
  @since NativeChurch 1.0
------------------------------------------------------------------------------------- */
if (!function_exists('imic_custom_login_logo')) {
  function imic_custom_login_logo()
  {
    $options = get_option('imic_options');
    $custom_logo = "";
    if (isset($options['custom_admin_login_logo'])) {
      $custom_logo = $options['custom_admin_login_logo']['url'];
    }
    echo '<style type="text/css">
			    .login h1 a { background-image:url(' . $custom_logo . ') !important; background-size: auto !important; width: auto !important; height: 95px !important; }
			</style>';
  }
  add_action('login_head', 'imic_custom_login_logo');
}
/* -------------------------------------------------------------------------------------
  Category REL Fix
  @since NativeChurch 1.0
------------------------------------------------------------------------------------- */
if (!function_exists('imic_add_nofollow_cat')) {
  function imic_add_nofollow_cat($text)
  {
    $text = str_replace('rel="category tag"', "", $text);
    return $text;
  }
  add_filter('the_category', 'imic_add_nofollow_cat');
}

/* -------------------------------------------------------------------------------------
  Show analytics code in footer
  @since NativeChurch 1.1
------------------------------------------------------------------------------------- */
if (!function_exists('imic_analytics')) {
  function imic_analytics()
  {
    $options = get_option('imic_options');
    if (isset($options['tracking-code']) && $options['tracking-code'] != "") {
      echo '<script>';
      echo '' . $options['tracking-code'];
      echo '</script>';
    }
  }
  add_action('wp_head', 'imic_analytics');
}
/* -------------------------------------------------------------------------------------
  Custom JS Output
  @since NativeChurch 1.1
------------------------------------------------------------------------------------- */
if (!function_exists('imic_custom_script')) {
  function imic_custom_script()
  {
    $options = get_option('imic_options');
    $custom_js = (isset($options['custom_js'])) ? $options['custom_js'] : '';
    if ($custom_js) {
      echo '<script type ="text/javascript">';
      echo '' . $custom_js;
      echo '</script>';
    }
  }
  add_action('wp_footer', 'imic_custom_script');
}
/* -------------------------------------------------------------------------------------
  Shortcode Fixes
  @since NativeChurch 1.1
------------------------------------------------------------------------------------- */
if (!function_exists('imic_content_filter')) {
  function imic_content_filter($content)
  {
    // array of custom shortcodes requiring the fix 
    $block = join("|", array("imic_button", "icon", "iconbox", "imic_image", "anchor", "paragraph", "divider", "heading", "alert", "blockquote", "dropcap", "code", "label", "container", "spacer", "span", "one_full", "one_half", "one_third", "one_fourth", "one_sixth", "two_third", "progress_bar", "imic_count", "imic_tooltip", "imic_video", "htable", "thead", "tbody", "trow", "thcol", "tcol", "pricing_table", "pt_column", "pt_package", "pt_button", "pt_details", "pt_price", "list", "list_item", "list_item_dt", "list_item_dd", "accordions", "accgroup", "acchead", "accbody", "toggles", "togglegroup", "togglehead", "togglebody", "tabs", "tabh", "tab", "tabc", "tabrow", "section", "page_first", "page_last", "page", "modal_box", "imic_form", "fullcalendar", "staff", "fullscreenvideo", "event_calender"));
    // opening tag
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content);
    // closing tag
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep);
    return $rep;
  }
  add_filter("the_content", "imic_content_filter");
}
/* -------------------------------------------------------------------------------------
  Video Embed Functions
  @since NativeChurch 1.1
------------------------------------------------------------------------------------- */
if (!function_exists('imic_video_embed')) {
  function imic_video_embed($url, $width = 200, $height = 150, $autopaly = 0)
  {
    if (strpos($url, 'youtube') || strpos($url, 'youtu.be')) {
      return imic_video_youtube($url, $width, $height, $autopaly);
    } else {
      return imic_video_vimeo($url, $width, $height, $autopaly);
    }
  }
}
/* -------------------------------------------------------------------------------------
  Youtube Video
  @since NativeChurch 1.2
------------------------------------------------------------------------------------- */
if (!function_exists('imic_video_youtube')) {
  function imic_video_youtube($url, $width = 200, $height = 150, $autopaly = "")
  {
    preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $video_parts);
    return '<iframe itemprop="video" src="https://youtube.com/embed/' . $video_parts[1] . '?autoplay=' . $autopaly . '&rel=0" width="' . $width . '" height="' . $height . '" allowfullscreen="allowfullscreen"></iframe>';
  }
}
/* -------------------------------------------------------------------------------------
  Vimeo Video
  @since NativeChurch 1.2
------------------------------------------------------------------------------------- */
if (!function_exists('imic_video_vimeo')) {
  function imic_video_vimeo($url, $width = 200, $height = 150, $autopaly = 0)
  {
    preg_match('/https?:\/\/vimeo.com\/(\d+)$/', $url, $video_id);
    return '<iframe src="https://player.vimeo.com/video/' . $video_id[1] . '" width="' . $width . '" height="' . $height . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
  }
}
/* -------------------------------------------------------------------------------------
  Soundcloud Audio
  @since NativeChurch 1.2
------------------------------------------------------------------------------------- */
if (!function_exists('imic_audio_soundcloud') && !class_exists('Native_Church_Core_Features')) {
  function imic_audio_soundcloud($url, $width = "100%", $height = 250)
  {
    return '';
  }
}
/* -------------------------------------------------------------------------------------
  Register Sidebars
  @since NativeChurch 1.0
------------------------------------------------------------------------------------- */
if (!function_exists('imic_register_sidebars')) {
  function imic_register_sidebars()
  {
    $options = get_option('imic_options');
    $footer_class = (isset($options["footer_layout"])) ? $options["footer_layout"] : '';
    register_sidebar(array(
      'name' => esc_html__('Home Page Sidebar', 'framework'),
      'id' => 'main-sidebar',
      'description' => '',
      'class' => '',
      'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="widgettitle">',
      'after_title' => '</h3>'
    ));
    register_sidebar(array(
      'name' => esc_html__('Contact Sidebar', 'framework'),
      'id' => 'contact-sidebar',
      'description' => '',
      'class' => '',
      'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
      'after_title' => '</h3></div>'
    ));
    register_sidebar(array(
      'name' => esc_html__('Inner Page Sidebar', 'framework'),
      'id' => 'inner-sidebar',
      'description' => '',
      'class' => '',
      'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
      'after_title' => '</h3></div>'
    ));
    register_sidebar(array(
      'name' => esc_html__('Sermons Sidebar', 'framework'),
      'id' => 'sermons-sidebar',
      'description' => '',
      'class' => '',
      'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
      'after_title' => '</h3></div>'
    ));
    register_sidebar(array(
      'name' => esc_html__('Event Page Sidebar', 'framework'),
      'id' => 'event-sidebar',
      'description' => '',
      'class' => '',
      'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
      'after_title' => '</h3></div>'
    ));
    register_sidebar(array(
      'name' => esc_html__('Single Event Page Sidebar', 'framework'),
      'id' => 'single-event-sidebar',
      'description' => '',
      'class' => '',
      'before_widget' => '<div id="%1$s" class="widget sidebar-widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<div class="sidebar-widget-title"><h3 class="widgettitle">',
      'after_title' => '</h3></div>'
    ));
    register_sidebar(array(
      'name' => esc_html__('Post Sidebar', 'framework'),
      'id' => 'post-sidebar',
      'description' => '',
      'class' => '',
      'before_widget' => '<div class="widget sidebar-widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<div class="sidebar-widget-title"><h3>',
      'after_title' => '</h3></div>'
    ));
    register_sidebar(array(
      'name' => esc_html__('Footer Sidebar', 'framework'),
      'id' => 'footer-sidebar',
      'description' => '',
      'class' => '',
      'before_widget' => '<div class="col-md-' . $footer_class . ' col-sm-' . $footer_class . ' widget footer-widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<h4 class="footer-widget-title">',
      'after_title' => '</h4>'
    ));
  }
  add_action('widgets_init', 'imic_register_sidebars', 35);
}
/* -------------------------------------------------------------------------------------
  Get date differences
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_dateDiff')) {
  function imic_dateDiff($start, $end)
  {
    $start_ts = strtotime($start);
    $end_ts = strtotime($end);
    $diff = intval($end_ts) - intval($start_ts);
    return round($diff / 86400);
  }
}
/* -------------------------------------------------------------------------------------
  Get taxonomies terms links
  @since NativeChurch 1.2
------------------------------------------------------------------------------------- */
if (!function_exists('imic_custom_taxonomies_terms_links')) {
  function imic_custom_taxonomies_terms_links()
  {
    global $post;
    // get post by post id
    $post = get_post($post->ID);
    // get post type by post
    $post_type = $post->post_type;
    // get post type taxonomies
    $taxonomies = get_object_taxonomies($post_type, 'objects');
    $out = array();
    foreach ($taxonomies as $taxonomy_slug => $taxonomy) {
      // get the terms related to post
      $terms = get_the_terms($post->ID, $taxonomy_slug);
      if (!empty($terms)) {
        $i = 1;
        foreach ($terms as $term) {
          if ($i == 1) {
            $out[] =
              ' <a href="'
              . get_term_link($term->slug, $taxonomy_slug) . '">'
              . $term->name
              . "</a>";
          }
          $i++;
        }
      }
    }
    return implode('', $out);
  }
}
/* -------------------------------------------------------------------------------------
  Sidebar Meta Box
  @since NativeChurch 1.2
------------------------------------------------------------------------------------- */
if (!function_exists('imic_get_all_sidebars')) {
  function imic_get_all_sidebars()
  {
    $all_sidebars = array();
    global $wp_registered_sidebars;
    $all_sidebars = array('' => '');
    foreach ($wp_registered_sidebars as $sidebar) {
      $all_sidebars[$sidebar['id']] = $sidebar['name'];
    }
    return $all_sidebars;
  }
}
/* -------------------------------------------------------------------------------------
  Manage Staff Post Type Menu Order Column
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
function add_new_staff_column($header_text_columns)
{
  $header_text_columns['menu_order'] = "Order";
  return $header_text_columns;
}
add_action('manage_edit-staff_columns', 'add_new_staff_column');
function show_order_column($name)
{
  global $post;
  switch ($name) {
    case 'menu_order':
      $order = $post->menu_order;
      echo esc_attr($order);
      break;
    default:
      break;
  }
}
add_action('manage_staff_posts_custom_column', 'show_order_column');
function order_column_register_sortable($columns)
{
  $columns['menu_order'] = 'menu_order';
  return $columns;
}
add_filter('manage_edit-staff_sortable_columns', 'order_column_register_sortable');
function afterSavePost()
{
  if (isset($_GET['post'])) {
    $postId = $_GET['post'];
    $post_type = get_post_type($postId);
    if ($post_type == 'event') {
      /////////////////////////////////////////////////////////////////
      $sdate = get_post_meta($postId, 'imic_event_start_dt', true);
      $start_time = get_post_meta($postId, 'imic_event_start_tm', true);
      $end_time = get_post_meta($postId, 'imic_event_end_tm', true);
      $all_day = get_post_meta($postId, 'imic_event_all_day', true);
      $all_day = ($all_day == null || $all_day == 0) ? 0 : $all_day;
      ////////////////////////////////////////////////////////////////
      $sdate_unix = strtotime($sdate);
      $sdate_ymd = date_i18n('Y-m-d', $sdate_unix);
      $end_event_date = get_post_meta($postId, 'imic_event_end_dt', true);
      $edate_unix = strtotime($end_event_date);
      $edate_ymd = date_i18n('Y-m-d', $edate_unix);
      if ($end_event_date == '') {
        update_post_meta($postId, 'imic_event_end_dt', $sdate);
      }
      $frequency = get_post_meta($postId, 'imic_event_frequency', true);
      $frequency_count = get_post_meta($postId, 'imic_event_frequency_count', true);
      $value = strtotime($sdate);
      if ($frequency == 32) {
        $frequency_count = 20;
      }
      if ($frequency == 30) {
        $svalue = strtotime("+" . $frequency_count . " month", $value);
        $suvalue = date_i18n('Y-m-d', $svalue);
      } else {
        $svalue = intval($frequency) * intval($frequency_count) * 86400;
        $suvalue = intval($svalue) + intval($value);
        $suvalue = date_i18n('Y-m-d', $suvalue);
      }
      $count_days = imic_dateDiff($sdate_ymd, $edate_ymd);
      if ($count_days > 0) {
        $suvalue = $edate_ymd;
      }
      update_post_meta($postId, 'imic_event_frequency_end', $suvalue);
      #if user not check all day checkbox as well as empty start,end time than update time here.
      if ($all_day == 0 && empty($start_time)) {
        update_post_meta($postId, 'imic_event_start_tm', '23:59');
      }
      if ($all_day == 0 && empty($end_time)) {
        update_post_meta($postId, 'imic_event_end_tm', '23:59');
      }
    }
  }
}
afterSavePost();

/* -------------------------------------------------------------------------------------
  Get All Post Types
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_get_all_types')) {
  add_action('wp_loaded', 'imic_get_all_types');
  function imic_get_all_types()
  {
    $args = array(
      'public'   => true,
    );
    $output = 'names'; // names or objects, note names is the default
    return $post_types = get_post_types($args, $output);
  }
}
/* -------------------------------------------------------------------------------------
  Get Days Diff
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_day_diff')) {
  function imic_day_diff($value)
  {
    $endEventTemp = get_post_meta($value, 'imic_event_end_dt', true);
    $startEventTemp = get_post_meta($value, 'imic_event_start_dt', true);
    $timeTemp = get_post_meta($value, 'imic_event_start_tm', true);
    $timeTemp = strtotime($timeTemp);
    $timeTemp = date_i18n(get_option('time_format'), $timeTemp);
    $endEventTemp = $endEventTemp . ' ' . $timeTemp;
    $startEventTemp = $startEventTemp . ' ' . $timeTemp;
    $endEventTemp = strtotime($endEventTemp);
    $startEventTemp = strtotime($startEventTemp);
    $daysTemp = $endEventTemp - $startEventTemp;
    $daysTemp = intval($daysTemp) / 86400;
    return $daysTemp = floor($daysTemp);
  }
}
/* -------------------------------------------------------------------------------------
  Attachment Meta Box
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_attachment_url')) {
  function imic_attachment_url($fields, $post)
  {
    $meta = get_post_meta($post->ID, 'meta_link', true);
    $fields['meta_link'] = array(
      'label' => esc_html__('Image URL', 'framework'),
      'input' => 'text',
      'value' => $meta,
      'show_in_edit' => true,
    );
    return $fields;
  }
  add_filter('attachment_fields_to_edit', 'imic_attachment_url', 10, 2);
}
/* -------------------------------------------------------------------------------------
  Update custom field on save
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_update_attachment_url')) {
  function imic_update_attachment_url($attachment)
  {
    global $post;
    update_post_meta($post->ID, 'meta_link', $attachment['attachments'][$post->ID]['meta_link']);
    return $attachment;
  }
  add_filter('attachment_fields_to_save', 'imic_update_attachment_url', 4);
}
/* -------------------------------------------------------------------------------------
  Update custom field via ajax
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_save_attachment_url')) {
  function imic_save_attachment_url()
  {
    $post_id = $_POST['id'];
    $meta = $_POST['attachments'][$post_id]['meta_link'];
    update_post_meta($post_id, 'meta_link', $meta);
    clean_post_cache($post_id);
  }
  add_action('wp_ajax_save-attachment-compat', 'imic_save_attachment_url', 0, 1);
}
/* -------------------------------------------------------------------------------------
  Get Attachment Fields
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_wp_get_attachment')) {
  function imic_wp_get_attachment($attachment_id)
  {
    $attachment = get_post($attachment_id);
    if (!$attachment || !is_numeric($attachment_id)) return;
    return array(
      'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
      'caption' => $attachment->post_excerpt,
      'description' => $attachment->post_content,
      'href' => get_permalink($attachment->ID),
      'src' => $attachment->guid,
      'title' => $attachment->post_title,
      'url' => $attachment->meta_link
    );
  }
}
/* -------------------------------------------------------------------------------------
  Get Recursive Event Data.
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_get_recursive_event_data')) {
  function imic_get_recursive_event_data($menuposttype, $menupost, $p = '')
  {
    $event_add_menu = array();
    $sinc = 1;
    $item_output = '';
    $event_add_menu = imic_recur_events("future", "", "", "");
    $nos_event_menu = 1;
    ksort($event_add_menu);
    foreach ($event_add_menu as $key => $value) {
      $date_converted = date_i18n('Y-m-d', $key);
      $custom_event_url = imic_query_arg($date_converted, $value);
      $recurrence = get_post_meta($value, 'imic_event_frequency', true);
      if ($recurrence > 0) {
        $icon = ' <i class="fa fa-refresh" style="font-size:80%;" title="' . esc_html__('Recurring', 'framework') . '"></i>';
      } else {
        $icon = '';
      }
      $eventDataTitle = get_the_title($value);
      $eventDataURL = $custom_event_url;
      $day = date_i18n('l', $key) . ' |';
      $eventStartTime = get_post_meta($value, 'imic_event_start_tm', true);
      $eventTime = get_post_meta($value, 'imic_event_start_tm', true);
      $eventTime = strtotime($eventTime);
      $stime = '';
      if ($eventTime != '') {
        $stime = date_i18n(get_option('time_format'), $eventTime);
      }
      $item_output .= '<li>';
      $item_output .= '<a href="' . $eventDataURL . '">' . $eventDataTitle . $icon . '</a>';
      $item_output .= '<span class="meta-data">' . $day . '  ' . $stime . '</span>';
      $item_output .= '</li>';
      if (++$nos_event_menu > $menupost)
        break;
    }
    return $item_output;
  }
}
/* -------------------------------------------------------------------------------------
  Get Cat List.
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_get_cat_list')) {
  function imic_get_cat_list()
  {
    $amp_categories_obj = get_categories('exclude=1');

    $amp_categories = array();
    if (count($amp_categories_obj) > 0) {
      foreach ($amp_categories_obj as $amp_cat) {
        $amp_categories[$amp_cat->cat_ID] = $amp_cat->name;
      }
    }
    return $amp_categories;
  }
}
/* -------------------------------------------------------------------------------------
  Filter the Widget Title.
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_widget_titles')) {
  add_filter('dynamic_sidebar_params', 'imic_widget_titles', 20);
  function imic_widget_titles(array $params)
  {
    // $params will ordinarily be an array of 2 elements, we're only interested in the first element
    $widget = &$params[0];
    $id = $params[0]['id'];
    if ($id == 'footer-sidebar') {
      $widget['before_title'] = '<h4 class="widgettitle">';
      $widget['after_title'] = '</h4>';
    } else {
      $widget['before_title'] = '<div class="sidebar-widget-title"><h3 class="widgettitle">';
      $widget['after_title'] = '</h3></div>';
    }
    return $params;
  }
}
/* -------------------------------------------------------------------------------------
  Filter the Widget Text.
  @since NativeChurch 1.4
  ----------------------------------------------------------------------------------- */
add_filter('widget_text', 'do_shortcode');
/* -------------------------------------------------------------------------------------
  Month Translate in Default.
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_month_translate')) {
  function imic_month_translate($str)
  {
    $options = get_option('imic_options');
    $months = (isset($options["calendar_month_name"])) ? $options["calendar_month_name"] : '';
    $months = explode(',', $months);
    if (count($months) <= 1) {
      $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    }
    $sb = array();
    foreach ($months as $month) {
      $sb[] = $month;
    }
    $engMonth = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $trMonth = $sb;
    $converted = str_replace($engMonth, $trMonth, $str);
    return $converted;
  }
  /* -------------------------------------------------------------------------------------
  	  Filter the  Month name of Post.
  	  @since NativeChurch 1.4
  	----------------------------------------------------------------------------------- */
  add_filter('get_the_time', 'imic_month_translate');
  add_filter('the_date', 'imic_month_translate');
  add_filter('get_the_date', 'imic_month_translate');
  add_filter('comments_number', 'imic_month_translate');
  add_filter('get_comment_date', 'imic_month_translate');
  add_filter('get_comment_time', 'imic_month_translate');
  add_filter('date_i18n', 'imic_month_translate');
}
/* -------------------------------------------------------------------------------------
  Short Month Translate in Default.
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_short_month_translate')) {
  function imic_short_month_translate($str)
  {
    $options = get_option('imic_options');
    $months = (isset($options["calendar_month_name_short"])) ? $options["calendar_month_name_short"] : '';
    $months = explode(',', $months);
    if (count($months) <= 1) {
      $months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
    }
    $sb = array();
    foreach ($months as $month) {
      $sb[] = $month;
    }
    $engMonth = array("/\bJan\b/", "/\bFeb\b/", "/\bMar\b/", "/\bApr\b/", "/\bMay\b/", "/\bJun\b/", "/\bJul\b/", "/\bAug\b/", "/\bSep\b/", "/\bOct\b/", "/\bNov\b/", "/\bDec\b/");
    $trMonth = $sb;
    $converted = preg_replace($engMonth, $trMonth, $str);
    return $converted;
  }
  /* -------------------------------------------------------------------------------------
	  Filter the  Sort Month name of Post.
	  @since NativeChurch 1.4
	------------------------------------------------------------------------------------- */
  add_filter('get_the_time', 'imic_short_month_translate');
  add_filter('the_date', 'imic_short_month_translate');
  add_filter('get_the_date', 'imic_short_month_translate');
  add_filter('comments_number', 'imic_short_month_translate');
  add_filter('get_comment_date', 'imic_short_month_translate');
  add_filter('get_comment_time', 'imic_short_month_translate');
  add_filter('date_i18n', 'imic_short_month_translate');
}
/* -------------------------------------------------------------------------------------
  Native Church Translate Day
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_day_translate')) {
  function imic_day_translate($str)
  {
    $options = get_option('imic_options');
    $days = (isset($options["calendar_day_name"])) ? $options["calendar_day_name"] : '';
    $days = explode(',', $days);
    if (count($days) <= 1) {
      $days = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    }
    $sb = array();
    foreach ($days as $month) {
      $sb[] = $month;
    }
    $engDay = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    $trDay = $sb;
    $converted = str_replace($engDay, $trDay, $str);
    return $converted;
  }
  /* -------------------------------------------------------------------------------------
	  Filter the  Day name of Post.
	  @since NativeChurch 1.4
	------------------------------------------------------------------------------------- */
  add_filter('date_i18n', 'imic_day_translate');
}
/* -------------------------------------------------------------------------------------
  Global Month Name.
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('imic_global_month_name')) {
  function imic_global_month_name($key)
  {
    return date_i18n("M", $key);
  }
}
/* -------------------------------------------------------------------------------------
  RevSlider ShortCode
  @since NativeChurch 1.4
------------------------------------------------------------------------------------- */
if (!function_exists('RevSliderShortCode')) {
  function RevSliderShortCode()
  {
    $slidernames = array();
    if (class_exists('RevSlider')) {
      $sld = new RevSlider();
      $sliders = $sld->getArrSliders();
      if (!empty($sliders)) {
        foreach ($sliders as $slider) {
          $title = $slider->title;
          $slidernames[esc_attr($slider->id)] = $title;
        }
      }
    }
    return $slidernames;
  }
}
/* -------------------------------------------------------------------------------------
  Smart Slider ShortCode
  @since NativeChurch 3.9
------------------------------------------------------------------------------------- */
if (!function_exists('SmartSliderShortCode')) {
  function SmartSliderShortCode()
  {
    global $wpdb;
    $slidernames = $sliders = array();
    if (defined('NEXTEND_SMARTSLIDER_3_URL_PATH')) {
      $sliders = $wpdb->get_results("SELECT id, title FROM " . $wpdb->prefix . "nextend2_smartslider3_sliders");
    }
    if (!empty($sliders)) {
      foreach ($sliders as $slider) {
        $title = $slider->title;
        $slidernames[esc_attr($slider->id)] = $title;
      }
    }
    return $slidernames;
  }
}
/** -------------------------------------------------------------------------------------
 * Gallery Flexslider
 * @since NativeChurch 1.5
 * @param ID of current Post.
 * @return Div with flexslider parameter.
  ------------------------------------------------------------------------------------ */
if (!function_exists('imic_gallery_flexslider')) {
  function imic_gallery_flexslider($id)
  {
    $speed = (get_post_meta(get_the_ID(), 'imic_gallery_slider_speed', true) != '') ? get_post_meta(get_the_ID(), 'imic_gallery_slider_speed', true) : 5000;
    $pagination = get_post_meta(get_the_ID(), 'imic_gallery_slider_pagination', true);
    $auto_slide = get_post_meta(get_the_ID(), 'imic_gallery_slider_auto_slide', true);
    $direction = get_post_meta(get_the_ID(), 'imic_gallery_slider_direction_arrows', true);
    $effect = get_post_meta(get_the_ID(), 'imic_gallery_slider_effects', true);
    $pagination = !empty($pagination) ? $pagination : 'yes';
    $auto_slide = !empty($auto_slide) ? $auto_slide : 'yes';
    $direction = !empty($direction) ? $direction : 'yes';
    $effect = !empty($effect) ? $effect : 'slide';
    echo '<div class="flexslider" data-autoplay="' . $auto_slide . '" data-pagination="' . $pagination . '" data-arrows="' . $direction . '" data-style="' . $effect . '" data-pause="yes" data-speed=' . $speed . '>';
  }
}
/** -------------------------------------------------------------------------------------
 * Sermons Audio
 * @since NativeChurch 1.6
 * @param ID of current Post.
 * @return Attach Full Audio Url.
  ------------------------------------------------------------------------------------ */
if (!function_exists('imic_sermon_attach_full_audio')) {
  function imic_sermon_attach_full_audio($id)
  {
    $imic_sermons_audio_upload = get_post_meta($id, 'imic_sermons_audio_upload', true);
    if ($imic_sermons_audio_upload == 1) {
      $imic_sermons_audio = get_post_meta($id, 'imic_sermons_audio', true);
      $attach_full_audio = wp_get_attachment_url($imic_sermons_audio);
    } else {
      $imic_sermons_audio = get_post_meta($id, 'imic_sermons_url_audio', true);
      $attach_full_audio = $imic_sermons_audio;
    }
    return $attach_full_audio;
  }
}
/** -------------------------------------------------------------------------------------
 * Sermons Pdf
 * @since NativeChurch 1.6
 * @param ID of current Post.
 * @return Attach Full Pdf Url.
  ------------------------------------------------------------------------------------ */
if (!function_exists('imic_sermon_attach_full_pdf')) {
  function imic_sermon_attach_full_pdf($id)
  {
    $imic_sermons_pdf_upload_option = get_post_meta($id, 'imic_sermons_pdf_upload_option', true);
    if ($imic_sermons_pdf_upload_option == 1) {
      $imic_sermons_pdf = get_post_meta($id, 'imic_sermons_Pdf', true);
      $attach_pdf = wp_get_attachment_url($imic_sermons_pdf);
    } else {
      $attach_pdf = get_post_meta($id, 'imic_sermons_pdf_by_url', true);
    }
    return $attach_pdf;
  }
}
/** -------------------------------------------------------------------------------------
 * Add Query Arg
 * @since NativeChurch 1.6
 * @param  ID,param1,param2 of current Post.
 * @return  Url with Query arg which is passed default is event_date.
-------------------------------------------------------------------------------------- */
if (!function_exists('imic_query_arg')) {
  function imic_query_arg($date_converted, $id)
  {
    $custom_event_url = esc_url_raw(add_query_arg('event_date', $date_converted, get_permalink($id)));
    return $custom_event_url;
  }
}
/** -------------------------------------------------------------------------------------
   Add Query Arg For Event Cat
   @since NativeChurch 1.6
   @param  ID,param1 of current Post.
   @return  Url with Query arg which is passed.
-------------------------------------------------------------------------------------- */
if (!function_exists('imic_query_arg_event_cat')) {
  function imic_query_arg_event_cat($string, $url)
  {
    $imic_event_category_page_url = esc_url(add_query_arg('event_cat', $string, $url));
    return $imic_event_category_page_url;
  }
}
/** -------------------------------------------------------------------------------------
   Query Var Filter
   @since NativeChurch 1.6
   @description event_date parameter is added to query_vars filter
-------------------------------------------------------------------------------------- */
if (!function_exists('imicAddQueryVarsFilter')) {
  function imicAddQueryVarsFilter($vars)
  {
    $vars[] = "event_date";
    $vars[] = "event_cat";
    $vars[] = "pg";
    $vars[] = "login";
    $vars[] = "calendar";
    return $vars;
  }
  add_filter('query_vars', 'imicAddQueryVarsFilter');
}
/** -------------------------------------------------------------------------------------
   Convert the Format String from php to fullcalender
   @see http://arshaw.com/fullcalendar/docs/utilities/formatDate/
   @since NativeChurch 1.6
   @param $format
-------------------------------------------------------------------------------------- */
function ImicConvertDate($format)
{
  $format_rules = array(
    'a' => 't',
    'A' => 'T',
    'B' => '',
    'c' => 'u',
    'd' => 'dd',
    'D' => 'ddd',
    'F' => 'MMMM',
    'g' => 'h',
    'G' => 'H',
    'h' => 'hh',
    'H' => 'HH',
    'i' => 'mm',
    'I' => '',
    'j' => 'd',
    'l' => 'dddd',
    'L' => '',
    'm' => 'MM',
    'M' => 'MMM',
    'n' => 'M',
    'O' => '',
    'r' => 'r',
    's' => 'ss',
    'S' => 'S',
    't' => '',
    'T' => '',
    'U' => '',
    'w' => '',
    'W' => '',
    'y' => 'yy',
    'Y' => 'yyyy',
    'z' => '',
    'Z' => '',
    ':' => ':',
    'u' => 'u',
    '\\' => ''
  );
  $ret = '';
  for ($i = 0; $i < strlen($format); $i++) {
    if (isset($format_rules[$format[$i]])) {
      $ret .= $format_rules[$format[$i]];
    } else {
      $ret .= $format[$i];
    }
  }
  return $ret;
}
/** -------------------------------------------------------------------------------------
   Return 0 if category have any post
   @since NativeChurch 1.6
 ------------------------------------------------------------------------------------- */
if (!function_exists('imic_cat_count_flag')) {
  function imic_cat_count_flag()
  {
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    $flag = 1;
    if (!empty($term)) {
      $flag = $output = ($term->count == 0) ? 0 : 1;
    }
    global $cat;
    if (!empty($cat)) {
      $cat_data = get_category($cat);
      $flag = ($cat_data->count == 0) ? 0 : 1;
    }
    return $flag;
  }
}
/** -------------------------------------------------------------------------------------
  Return sidebar and set page design 
  @since NativeChurch 1.7
-------------------------------------------------------------------------------------- */
if (!function_exists('imic_page_design')) {
  function imic_page_design($id = '', $block = 9)
  {
    //Make page design according sidebar conditions
    $options = get_option('imic_options');
    $ID = (!empty($id) &&  $id != '') ? $id : get_the_ID();
    if (is_home()) {
      $ID = get_option('page_for_posts');
    }
    if ($ID == '' | $ID == 0) {
      $pageSidebar = 'main-sidebar';
    } else {
      $pageSidebar = get_post_meta($ID, 'imic_select_sidebar_from_list', true);
    }
    $post_type = get_post_type($ID);
    $sidebar_value = $sidebar = '';
    switch ($post_type) {
      case 'post':
        $sidebar_value = (isset($options['post_sidebar'])) ? $options['post_sidebar'] : 'post-sidebar';
        break;
      case 'page':
        $sidebar_value = (isset($options['page_sidebar'])) ? $options['page_sidebar'] : '';
        break;
      case 'event':
        $sidebar_value = (isset($options['event_sidebar'])) ? $options['event_sidebar'] : '';
        break;
      case 'causes':
        $sidebar_value = (isset($options['cause_sidebar'])) ? $options['cause_sidebar'] : '';
        break;
      case 'sermons':
        $sidebar_value = (isset($options['sermon_sidebar'])) ? $options['sermon_sidebar'] : '';
        break;
      case 'staff':
        $sidebar_value = (isset($options['staff_sidebar'])) ? $options['staff_sidebar'] : '';
        break;
    }
    $classMain = 'col-md-' . $block;
    if (!empty($pageSidebar)) {
      $sidebar = $pageSidebar;
    } else if (!empty($sidebar_value)) {
      $sidebar = $sidebar_value;
    } else {
      $classMain = 'col-md-12';
    }
    $pageDesign = array('class' => $classMain, 'sidebar' => $sidebar);
    return $pageDesign;
  }
}
/** -------------------------------------------------------------------------------------
   Return Recursive Event data 
   @since NativeChurch 1.7
-------------------------------------------------------------------------------------- */
if (!function_exists('imic_get_home_recursive_event_data')) {
  function imic_get_home_recursive_event_data($specific_event_cat)
  {
    $item_output = '';
    $event_add_menu = array();
    if (!empty($specific_event_cat)) {
      $event_cat_data = get_term_by('id', $specific_event_cat, 'event-category');
      $event_cat = $event_cat_data->slug;
      $sinc = 1;
      $today_specific = date_i18n('Y-m-d');
      $posts_event = get_posts(array('post_type' => 'event', 'event-category' => $event_cat, 'post_status' => 'publish', 'meta_key' => 'imic_event_start_dt', 'suppress_filters' => false, 'meta_query' => array(array('key' => 'imic_event_frequency_end', 'value' => $today_specific, 'compare' => '>=')), 'orderby' => 'meta_value', 'order' => 'ASC', 'posts_per_page' => -1));
      if (!empty($posts_event)) {
        foreach ($posts_event as $event_post_data) {
          $eventDate = strtotime(get_post_meta($event_post_data->ID, 'imic_event_start_dt', true));
          $eventTime = get_post_meta($event_post_data->ID, 'imic_event_start_tm', true);
          $frequency = get_post_meta($event_post_data->ID, 'imic_event_frequency', true);
          $frequency_count = '';
          $frequency_count = get_post_meta($event_post_data->ID, 'imic_event_frequency_count', true);
          if ($frequency > 0) {
            $frequency_count = $frequency_count;
          } else {
            $frequency_count = 0;
          }
          $seconds = intval($frequency) * 86400;
          $fr_repeat = 0;
          while ($fr_repeat <= $frequency_count) {
            $eventDate = get_post_meta($event_post_data->ID, 'imic_event_start_dt', true);
            $event_Start_time = get_post_meta($event_post_data->ID, 'imic_event_start_tm', true);
            $eventDate = strtotime($eventDate . ' ' . $event_Start_time);
            if ($frequency == 30) {
              $eventDate = strtotime("+" . $fr_repeat . " month", $eventDate);
            } else {
              $new_date = intval($seconds) * intval($fr_repeat);
              $eventDate = intval($eventDate) + intval($new_date);
            }
            $date_sec = date_i18n('Y-m-d', $eventDate);
            $exact_time = strtotime($date_sec . ' ' . $eventTime);
            if ($exact_time >= date_i18n('U')) {
              $event_add_menu[$eventDate + $sinc] = $event_post_data->ID;
              $sinc++;
            }
            $fr_repeat++;
          }
        }
        $nos_event_menu = 1;
        ksort($event_add_menu);
        foreach ($event_add_menu as $key => $value) {
          $options = get_option('imic_options');
          $eventTime = get_post_meta($value, 'imic_event_start_tm', true);
          $event_End_time = get_post_meta($value, 'imic_event_end_tm', true);
          $event_End_time = strtotime($event_End_time);
          $eventTime = strtotime($eventTime);
          $count_from = (isset($options['countdown_timer'])) ? $options['countdown_timer'] : '';
          if ($count_from == 1) {
            $counter_time = date_i18n('G:i', $event_End_time);
          } else {
            $counter_time = date_i18n('G:i', $eventTime);
          }
          $firstEventDateData = date_i18n('Y-m-d', $key) . ' ' . $counter_time;
          $firstEventTitle = get_the_title($value);
          $firstEventDate = date_i18n(get_option('date_format'), $key);
          $date_converted = date_i18n('Y-m-d', $key);
          $firstEventURL = imic_query_arg($date_converted, $value);
          $item_output .= '<h5><a href="' . $firstEventURL . '">' . $firstEventTitle . '</a></h5>';
          $item_output .= '<span class="meta-data">' . $firstEventDate . '</span></div>';
          $item_output .= '<div id="counter" class="col-md-4 col-sm-6 col-12 counter" data-date="' . strtotime($firstEventDate) . '">';
          $item_output .= '<div class="timer-col"> <span id="days"></span> <span class="timer-type">' . esc_html__('days', 'framework');
          $item_output .= '</span></div>';
          $item_output .= '<div class="timer-col"> <span id="hours"></span> <span class="timer-type">' . esc_html__('hrs', 'framework');
          $item_output .= '</span></div>';
          $item_output .= '<div class="timer-col"> <span id="minutes"></span> <span class="timer-type">' . esc_html__('mins', 'framework');
          $item_output .= '</span></div>';
          $item_output .= '<div class="timer-col"> <span id="seconds"></span> <span class="timer-type">' . esc_html__('secs', 'framework');
          $item_output .= '</span></div></div>';
          break;
        }
      }
    }
    return $item_output;
  }
}
/** -------------------------------------------------------------------------------------
   Blog Template Redirect
   @since NativeChurch 1.7
-------------------------------------------------------------------------------------- */
if (!function_exists('imicBlogTemplateRedirect')) {
  function imicBlogTemplateRedirect()
  {
    $page_for_posts = get_option('page_for_posts');
    //check by Blog
    if (is_home() && !empty($page_for_posts)) {
      $page_for_posts = get_option('page_for_posts');
      $page_template = get_post_meta(get_option('page_for_posts'), '_wp_page_template', true);
      if ($page_template != 'default' && !empty($page_template)) {
        include(TEMPLATEPATH . '/' . $page_template);
        exit;
      }
    }
  }
  // add our function to template_redirect hook
  add_action('template_redirect', 'imicBlogTemplateRedirect');
}
/** -------------------------------------------------------------------------------------
   600x400 image for Thumbnail enable
   600x1000 image for Large image
   @since NativeChurch 1.7
-------------------------------------------------------------------------------------- */
add_image_size('600x400', 600, 400, true);
add_image_size('1000x800', 1000, 800, true);
/** -------------------------------------------------------------------------------------
   Thumb And Large Size if Thumbnail enable
   @since NativeChurch 1.7
-------------------------------------------------------------------------------------- */
if (!function_exists('imicGetThumbAndLargeSize')) {
  function imicGetThumbAndLargeSize()
  {
    $imic_options = get_option('imic_options');
    if (isset($imic_options['switch-thumbnail']) && ($imic_options['switch-thumbnail'] == 1)) {
      $size_thumb = '600x400';
      $size_large = '1000x800';
    } else {
      $size_thumb = $size_large = 'full';
    }
    return array($size_thumb, $size_large);
  }
}
/** -------------------------------------------------------------------------------------
   Ajax Login Form Function
   @since NativeChurch 1.7
-------------------------------------------------------------------------------------- */
if (!function_exists('ajax_login_init')) {
  function ajax_login_init()
  {
    wp_register_script('ajax-login-script', get_template_directory_uri() . '/assets/js/ajax-login-script.js', array('jquery'));
    wp_enqueue_script('ajax-login-script');
    wp_localize_script('ajax-login-script', 'ajax_login_object', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'loadingmessage' => esc_html__('Sending user info, please wait...', 'framework')
    ));
    add_action('wp_ajax_nopriv_ajaxlogin', 'ajax_login');
  }
  if (!is_user_logged_in()) {
    add_action('init', 'ajax_login_init');
  }
}
if (!function_exists('ajax_login')) {
  function ajax_login()
  {
    check_ajax_referer('ajax-login-nonce', 'security');
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    if ($_POST['rememberme'] == 'true') {
      $info['remember'] = true;
    } else {
      $info['remember'] = false;
    }
    $user_signon = wp_signon($info, false);
    if (is_wp_error($user_signon)) {
      echo json_encode(array('loggedin' => false, 'message' => esc_html__('Wrong username or password.', 'framework')));
    } else {
      echo json_encode(array('loggedin' => true, 'message' => esc_html__('Login successful, redirecting...', 'framework')));
    }
    die();
  }
}
/** -------------------------------------------------------------------------------------
   Add role for event registrants
   @since NativeChurch 1.7
-------------------------------------------------------------------------------------- */
function nativechurch_add_registrant_role()
{
  add_role('registrant', 'Event Registrant', array('read' => false, 'level_0' => true));
}
add_action('init', 'nativechurch_add_registrant_role');
/** -------------------------------------------------------------------------------------
   Agent Register Function
   @since NativeChurch 1.7
-------------------------------------------------------------------------------------- */
function imic_agent_register()
{
  if (!$_POST) exit;
  // Email address verification, do not edit.
  function validate_email_address($email)
  {
    return (preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $email));
  }

  if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");

  $username     = $_POST['username'];
  $email    = $_POST['email'];
  $pwd1  = $_POST['pwd1'];
  $pwd2 = $_POST['pwd2'];

  if (trim($username) == '') {
    echo '<div class="alert alert-error">You must enter your username.</div>';
    exit();
  } else if (trim($email) == '') {
    echo '<div class="alert alert-error">You must enter email address.</div>';
    exit();
  } else if (!validate_email_address($email)) {
    echo '<div class="alert alert-error">You must enter a valid email address.</div>';
    exit();
  } else if (trim($pwd1) == '') {
    echo '<div class="alert alert-error">You must enter password.</div>';
    exit();
  } else if (trim($pwd2) == '') {
    echo '<div class="alert alert-error">You must enter repeat password.</div>';
    exit();
  } else if (trim($pwd1) != trim($pwd2)) {
    echo '<div class="alert alert-error">You must enter a same password.</div>';
    exit();
  }


  $err = '';
  $success = '';

  global $wpdb, $PasswordHash, $current_user, $user_ID;

  if (isset($_POST['task']) && $_POST['task'] == 'register') {
    $username = esc_sql(trim($_POST['username']));
    $pwd1 = esc_sql(trim($_POST['pwd1']));
    $pwd2 = esc_sql(trim($_POST['pwd2']));
    $email = esc_sql(trim($_POST['email']));

    if ($email == "" || $pwd1 == "" || $pwd2 == "" || $username == "") {
      $err = 'Please don\'t leave the required fields.';
    } else if ($pwd1 <> $pwd2) {
      $err = 'Password do not match.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $err = 'Invalid email address.';
    } else if (email_exists($email)) {
      $err = 'Email already exist.';
    } else {

      $user_id = wp_insert_user(
        array(
          'user_pass' => apply_filters('pre_user_user_pass', $pwd1),
          'user_login' => apply_filters('pre_user_user_login', $username),
          'user_email' => apply_filters('pre_user_user_email', $email),
          'role' => 'registrant'
        )
      );
      if (is_wp_error($user_id)) {
        $err = 'Error on user creation.';
      } else {
        do_action('user_register', $user_id);
        $success = 'You\'re successfully register';
        $info_register = array();
        $info_register['user_login'] = $username;
        $info_register['user_password'] = $pwd1;
        wp_signon($info_register, false);
      }
    }
  }

  if (!empty($err)) :
    echo '<div class="alert alert-error">' . $err . '</div>';
  endif;

  if (!empty($success)) :
    echo '<div class="alert alert-success">' . $success . '</div>';
  endif;
  die();
}
add_action('wp_ajax_nopriv_imic_agent_register', 'imic_agent_register');
add_action('wp_ajax_imic_agent_register', 'imic_agent_register');
/** -------------------------------------------------------------------------------------
   Redirect back to homepage and not allow access to WP admin for Subscribers
   @since NativeChurch 1.5
-------------------------------------------------------------------------------------- */
if (!function_exists('imic_redirect_admin')) {
  function imic_redirect_admin()
  {
    $user = wp_get_current_user();
    if (in_array('registrant', (array) $user->roles) && defined('DOING_AJAX') && !DOING_AJAX) {
      wp_redirect(site_url());
      exit;
    }
  }
  add_action('admin_init', 'imic_redirect_admin');
}
function imic_regenerate_calender_index($index, $google_event_array)
{
  $index = ($index + 1);
  if (array_key_exists($index, $google_event_array)) {
    return imic_regenerate_calender_index($index, $google_event_array);
  }
  return $index;
}
function getGoogleEvent($month_last = '')
{ }
function imicRecurrenceIcon($value)
{
  $frequency = get_post_meta($value, 'imic_event_frequency', true);
  switch ($frequency) {
    case 1:
      $recur = esc_html__('Every Day', 'framework');
      break;
    case 2:
      $recur = esc_html__('Every Second Day', 'framework');
      break;
    case 3:
      $recur = esc_html__('Every Third Day', 'framework');
      break;
    case 4:
      $recur = esc_html__('Every Fourth Day', 'framework');
      break;
    case 5:
      $recur = esc_html__('Every Fifth Day', 'framework');
      break;
    case 6:
      $recur = esc_html__('Every Sixth Day', 'framework');;
      break;
    case 7:
      $recur = esc_html__('Every Week', 'framework');
      break;
    case 30:
      $recur = esc_html__('Every Month', 'framework');
      break;
    default:
      $recur = '';
      break;
  }
  $frequency_count = get_post_meta($value, 'imic_event_frequency_count', true);
  $icon = ' <a data-placement="bottom" data-bs-toggle="tooltip-live" data-original-title="' . esc_html__('Recurring', 'framework') . ' ' . $recur . ', ' . esc_html__('for', 'framework') . ' ' . $frequency_count . ' ' . esc_html__('Times', 'framework') . '" rel="tooltip" class="recurring-info-icon"><i class="fa fa-refresh"></i></a>';
  $recurrence = get_post_meta($value, 'imic_event_frequency', true);
  if ($recurrence > 0 && $recur != '') {
    $icon = $icon;
  } else {
    $icon = '';
  }
  return $icon;
}
/**
 * IMIC SIDEBAR POSITION
 */
if (!function_exists('imic_sidebar_position_module')) {
  function imic_sidebar_position_module()
  {
    $sidebar_position = get_post_meta(get_the_ID(), 'imic_select_sidebar_position', true);
    if (is_home()) {
      $id = get_option('page_for_posts');
      $sidebar_position = get_post_meta($id, 'imic_select_sidebar_position', true);
    }
  }
}

/**
 * IMIC SHARE BUTTONS
 */
if (!function_exists('imic_share_buttons')) {
  function imic_share_buttons()
  {
    $posttitle = get_the_title();
    $postpermalink = get_permalink();
    $postexcerpt = get_the_excerpt();
    $imic_options = get_option('imic_options');;
    $facebook_share_alt = (isset($imic_options['facebook_share_alt'])) ? $imic_options['facebook_share_alt'] : '';
    $twitter_share_alt = (isset($imic_options['twitter_share_alt'])) ? $imic_options['twitter_share_alt'] : '';
    $google_share_alt = (isset($imic_options['google_share_alt'])) ? $imic_options['google_share_alt'] : '';
    $tumblr_share_alt = (isset($imic_options['tumblr_share_alt'])) ? $imic_options['tumblr_share_alt'] : '';
    $pinterest_share_alt = (isset($imic_options['pinterest_share_alt'])) ? $imic_options['pinterest_share_alt'] : '';
    $reddit_share_alt = (isset($imic_options['reddit_share_alt'])) ? $imic_options['reddit_share_alt'] : '';
    $linkedin_share_alt = (isset($imic_options['linkedin_share_alt'])) ? $imic_options['linkedin_share_alt'] : '';
    $email_share_alt = (isset($imic_options['email_share_alt'])) ? $imic_options['email_share_alt'] : '';
    $vk_share_alt = (isset($imic_options['vk_share_alt'])) ? $imic_options['vk_share_alt'] : '';


    echo '<div class="share-bar">';
    if (isset($imic_options['sharing_style']) && $imic_options['sharing_style'] == '0') {
      if ($imic_options['sharing_color'] == '0') {
        echo '<ul class="share-buttons">';
      } elseif (isset($imic_options['sharing_color']) && $imic_options['sharing_color'] == '1') {
        echo '<ul class="share-buttons share-buttons-tc">';
      } elseif (isset($imic_options['sharing_color']) && $imic_options['sharing_color'] == '2') {
        echo '<ul class="share-buttons share-buttons-gs">';
      }
    } elseif (isset($imic_options['sharing_style']) && $imic_options['sharing_style'] == '1') {
      if (isset($imic_options['sharing_color']) && $imic_options['sharing_color'] == '0') {
        echo '<ul class="share-buttons share-buttons-squared">';
      } elseif (isset($imic_options['sharing_color']) && $imic_options['sharing_color'] == '1') {
        echo '<ul class="share-buttons share-buttons-tc share-buttons-squared">';
      } elseif (isset($imic_options['sharing_color']) && $imic_options['sharing_color'] == '2') {
        echo '<ul class="share-buttons share-buttons-gs share-buttons-squared">';
      }
    };
    if (isset($imic_options['share_icon']) && $imic_options['share_icon']['1'] == '1') {
      echo '<li class="facebook-share"><a href="https://www.facebook.com/sharer/sharer.php?u=' . $postpermalink . '&amp;t=' . esc_attr($posttitle) . '" target="_blank" title="' . esc_attr($facebook_share_alt) . '"><i class="fa fa-facebook"></i></a></li>';
    }
    if (isset($imic_options['share_icon']) && $imic_options['share_icon']['2'] == '1') {
      echo '<li class="twitter-share"><a href="https://twitter.com/intent/tweet?source=' . $postpermalink . '&amp;text=' . esc_attr($posttitle) . ':' . $postpermalink . '" target="_blank" title="' . esc_attr($twitter_share_alt) . '"><i class="fa fa-twitter"></i></a></li>';
    }
    if (isset($imic_options['share_icon']) && $imic_options['share_icon']['3'] == '1') {
      echo '<li class="google-share"><a href="https://plus.google.com/share?url=' . $postpermalink . '" target="_blank" title="' . esc_attr($google_share_alt) . '"><i class="fa fa-google-plus"></i></a></li>';
    }
    if (isset($imic_options['share_icon']) && $imic_options['share_icon']['4'] == '1') {
      echo '<li class="tumblr-share"><a href="http://www.tumblr.com/share?v=3&amp;u=' . $postpermalink . '&amp;t=' . esc_attr($posttitle) . '&amp;s=" target="_blank" title="' . esc_attr($tumblr_share_alt) . '"><i class="fa fa-tumblr"></i></a></li>';
    }
    if (isset($imic_options['share_icon']) && $imic_options['share_icon']['5'] == '1') {
      echo '<li class="pinterest-share"><a href="http://pinterest.com/pin/create/button/?url=' . $postpermalink . '&amp;description=' . esc_attr($postexcerpt) . '" target="_blank" title="' . esc_attr($pinterest_share_alt) . '"><i class="fa fa-pinterest"></i></a></li>';
    }
    if (isset($imic_options['share_icon']) && $imic_options['share_icon']['6'] == '1') {
      echo '<li class="reddit-share"><a href="http://www.reddit.com/submit?url=' . $postpermalink . '&amp;title=' . esc_attr($posttitle) . '" target="_blank" title="' . esc_attr($reddit_share_alt) . '"><i class="fa fa-reddit"></i></a></li>';
    }
    if (isset($imic_options['share_icon']) && $imic_options['share_icon']['7'] == '1') {
      echo '<li class="linkedin-share"><a href="http://www.linkedin.com/shareArticle?mini=true&url=' . $postpermalink . '&amp;title=' . esc_attr($posttitle) . '&amp;summary=' . esc_attr($postexcerpt) . '&amp;source=' . $postpermalink . '" target="_blank" title="' . esc_attr($linkedin_share_alt) . '"><i class="fa fa-linkedin"></i></a></li>';
    }
    if (isset($imic_options['share_icon']) && $imic_options['share_icon']['8'] == '1') {
      echo '<li class="email-share"><a href="mailto:?subject=' . esc_attr($posttitle) . '&amp;body=' . esc_attr($postexcerpt) . ':' . $postpermalink . '" target="_blank" title="' . esc_attr($email_share_alt) . '"><i class="fa fa-envelope"></i></a></li>';
    }
    if ((isset($imic_options['share_icon']['9'])) && ($imic_options['share_icon']['9'] == '1')) {
      echo '<li class="vk-share"><a href="http://vk.com/share.php?url=' . $postpermalink . '" target="_blank" title="' . esc_attr($vk_share_alt) . '"><i class="fa fa-vk"></i></a></li>';
    }
    echo '</ul>
            </div>';
  }
}
/* EVENT GRID FUNCTION
  ================================================== */
function imic_event_grid()
{
  $EventTerm = '';
  echo '<div class="listing events-listing">
	<header class="listing-header">
		<div class="row">
			<div class="col-md-6 col-sm-6">
				<h3>' . esc_html__('All events', 'framework') . '</h3>
		  </div>
		  <div class="listing-header-sub col-md-6 col-sm-6">';
  $currentEventTime = $_POST['date'];
  $EventTerm = $_POST['term'];
  $prev_month = date_i18n('Y-m', strtotime('-1 month', strtotime($currentEventTime)));
  $next_month = date_i18n('Y-m', strtotime('+1 month', strtotime($currentEventTime)));
  echo '<h5>' . date_i18n('F', strtotime($currentEventTime)) . '</h5>
				<nav class="next-prev-nav">
					<a href="javascript:" class="upcomingEvents" rel="' . $EventTerm . '" id="' . $prev_month . '"><i class="fa fa-angle-left"></i></a>
					<a href="javascript:" class="upcomingEvents" rel="' . $EventTerm . '" id="' . $next_month . '"><i class="fa fa-angle-right"></i></a>
				</nav>
		  </div>
	  </div>
	</header>
	<section class="listing-cont">
	  <ul>';
  $today = date_i18n('Y-m');
  $curr_month = date_i18n('Y-m-t', strtotime('-1 month', strtotime($currentEventTime)));
  $currentTime = date_i18n(get_option('time_format'));
  $site_lang = substr(get_locale(), 0, 2);
  $saved_future_events = get_option('nativechurch_saved_future_events_' . $site_lang);
  $saved_past_events = get_option('nativechurch_saved_past_events_' . $site_lang);
  if ($saved_future_events) {
    $events = $saved_future_events;
  } else {
    $events = imic_recur_events('future', 'nos', '', '', 'save');
  }
  if ($saved_past_events) {
    $events_past = $saved_past_events;
  } else {
    $events_past = imic_recur_events('past', 'nos', '', '', 'save');
  }
  $events = $events + $events_past;
  if ($EventTerm) {
    $events_objects = nativechurch_get_term_objects(explode(',', $EventTerm));
    $events = array_intersect($events, $events_objects);
  }
  $all_events_data_new = array_filter($events, function ($date) use ($currentEventTime) {
    $start = date_i18n('Y-m-01 00:01', strtotime($currentEventTime));
    $end = date_i18n('Y-m-t 23:59', strtotime($currentEventTime));
    return ($date >= strtotime($start) and $date <= strtotime($end));
  }, ARRAY_FILTER_USE_KEY);
  $sp = $all_events_data_new;
  $this_month_last = strtotime(date_i18n('Y-m-t 23:59', strtotime($currentEventTime)));
  $google_events = nativechurch_fetch_google_events(date_i18n('Y-m', $this_month_last));
  if (!empty($google_events))
    $new_events = $google_events + $sp;
  else  $new_events = $sp;

  ksort($new_events);
  if (!empty($new_events)) {
    foreach ($new_events as $key => $value) {
      if (preg_match('/^[0-9]+$/', $value)) {
        $frequency = get_post_meta($value, 'imic_event_frequency', true);
        $frequency_count = get_post_meta($value, 'imic_event_frequency_count', true);
        $satime = get_post_meta($value, 'imic_event_start_tm', true);
        $satime = strtotime($satime);
        $date_converted = date_i18n('Y-m-d', $key);
        $custom_event_url =  imic_query_arg($date_converted, $value);
        $event_title = get_the_title($value);
        /* event time date formate */
        $eventStartTime =  strtotime(get_post_meta($value, 'imic_event_start_tm', true));
        $eventStartDate =  strtotime(get_post_meta($value, 'imic_event_start_dt', true));
        $eventEndTime   =  strtotime(get_post_meta($value, 'imic_event_end_tm', true));
        $eventEndDate   =  strtotime(get_post_meta($value, 'imic_event_end_dt', true));
        $evstendtime = $eventStartTime . '|' . $eventEndTime;
        $evstenddate = $eventStartDate . '|' . $eventEndDate;
        $event_dt_out = imic_get_event_timeformate($evstendtime, $evstenddate, $value, $key);
        $event_dt_out = explode('BR', $event_dt_out);
        /* event time date formate end */
      } else {
        $google_data = (explode('!', $value));
        $event_title = $google_data[0];
        $custom_event_url = $google_data[1];
        $options = get_option('imic_options');
        $satime = $key;
        /* event time date formate */
        $event_dt_out = imic_get_event_timeformate(
          $key . '|' . strtotime($google_data[2]),
          $key . '|' . $key,
          $value,
          $key
        );
        $event_dt_out = explode('BR', $event_dt_out);
        /* event time date formate end */
      }

      echo '<li class="item event-item">	
				  <div class="event-date"> <span class="date">' . date_i18n('d', $key) . '</span> <span class="month">' . imic_global_month_name($key) . '</span> </div>
				  <div class="event-detail">
                                      <h4><a href="' . $custom_event_url . '">' . $event_title . '</a>' . imicRecurrenceIcon($value) . '</h4>';

      echo '<span class="event-dayntime meta-data">' . $event_dt_out[1] . ',&nbsp;&nbsp;' . $event_dt_out[0] . '</span> </div>
				  <div class="to-event-url">
					<div><a href="' . $custom_event_url . '" class="btn btn-default btn-sm">' . esc_html__('Details', 'framework') . '</a></div>
				  </div>
				</li>';
    }
  } else {
    echo '<li class="item event-item">	
			  <div class="event-detail">
				<h4>' . esc_html__('Sorry, there are no events for this month.', 'framework') . '</h4>
			  </div>
			</li>';
  }
  echo '</ul>
	</section>
  </div>';
  die();
}
add_action('wp_ajax_nopriv_imic_event_grid', 'imic_event_grid');
add_action('wp_ajax_imic_event_grid', 'imic_event_grid');
//Event Global Function
if (!function_exists('imic_recur_events')) {
  function imic_recur_events($status, $featured = "nos", $term = '', $month = '', $save_data = '')
  {
    ##################### getset options and defaut value  ###############
    $featured                = ($featured == "yes") ? "no" : "nos";
    $today                   = date_i18n('Y-m-d');
    $imic_options            = get_option('imic_options');
    $offset                  = get_option('timezone_string');
    $offset                  = ($offset == '') ? "Australia/Melbourne" : $offset;
    $event_add               = array();
    $sinc                    = 1;
    $event_show_until        = (isset($imic_options['countdown_timer'])) ? $imic_options['countdown_timer'] : '0';
    $meta_query              = '';
    #######################################################################
    if ($month != "") {
      $stop_date = $month;
      $curr_month = date_i18n('Y-m-t 23:59', strtotime('-1 month', strtotime($stop_date)));
      $current_end_date = date_i18n('Y-m-d H:i:s', strtotime($stop_date . ' + 1 day'));
      $previous_month_end = strtotime(date_i18n('Y-m-d 00:01', strtotime($stop_date)));
      $next_month_start = strtotime(date_i18n('Y-m-d 00:01', strtotime('+1 month', strtotime($stop_date))));

      $meta_query = array(
        'relation' => 'AND',
        array(
          'key' => 'imic_event_frequency_end',
          'value' => $curr_month,
          'compare' => '>'
        ),
        array(
          'key' => 'imic_event_start_dt',
          'value' => date_i18n('Y-m-t 23:59', strtotime($stop_date)),
          'compare' => '<'
        ),
      );
    } else {
      if ($status == 'future') {
        $meta_query = array(
          array(
            'key' => 'imic_event_frequency_end',
            'value' => $today,
            'compare' => '>='
          ),
        );
      } else {

        $meta_query = array(
          array(
            'key' => 'imic_event_start_dt',
            'value' => $today,
            'compare' => '<'
          ),
        );
      }
    }

    $post_query = array(
      'post_type' => 'event',
      'post_status' => 'publish',
      'event-category' => $term,
      'meta_key' => 'imic_event_start_dt',
      'meta_query' => $meta_query,
      'orderby' => 'meta_value',
      'order' => 'ASC',
      'posts_per_page' => -1
    );
    #execute query
    query_posts($post_query);
    $sinc = '0';
    if (have_posts()) :
      while (have_posts()) : the_post();
        ###############################################################################
        $frequency            = get_post_meta(get_the_ID(), 'imic_event_frequency', true);
        $frequency_count      = get_post_meta(get_the_ID(), 'imic_event_frequency_count', true);
        $frequency_month_day  = get_post_meta(get_the_ID(), 'imic_event_day_month', true);
        $frequency_week_day   = get_post_meta(get_the_ID(), 'imic_event_week_day', true);
        $multiple_dates       = get_post_meta(get_the_ID(), 'imic_event_recurring_dt', true);
        $seconds              = intval($frequency) * 86400;
        $fr_repeat            = 0;
        ###############################################################################
        if ($frequency != '0' && $frequency != '32') {
          $frequency_count = $frequency_count;
        } elseif ($frequency == '32' && $multiple_dates) {
          $frequency_count = count($multiple_dates);
        } else {
          $frequency_count = 0;
        }
        while ($fr_repeat <= $frequency_count) {
          $event_start_dt = $eventDate = get_post_meta(get_the_ID(), 'imic_event_start_dt', true);
          $event_start_tm = $MetaStartTime  = get_post_meta(get_the_ID(), 'imic_event_start_tm', true);
          $eventEndDate   = get_post_meta(get_the_ID(), 'imic_event_end_dt', true);
          $MetaEndTime    = get_post_meta(get_the_ID(), 'imic_event_end_tm', true);
          //$inc = $sinc = '';
          $eventEndDate = $event_actual_en_date = strtotime($eventEndDate . ' ' . $MetaEndTime);
          $eventDate = $event_actual_st_date = strtotime($eventDate . ' ' . $MetaStartTime);
          $diff_start = date_i18n('Y-m-d', $eventDate);
          $diff_end = date_i18n('Y-m-d', $eventEndDate);
          $days_extra = imic_dateDiff($diff_start, $diff_end);
          $dt_tm = strtotime($event_start_dt . ' ' . $event_start_tm);
          if ($days_extra > 0) {
            $start_day = 0;
            while ($start_day <= $days_extra) {
              $diff_sec = 86400 * intval($start_day);
              $new_date = intval($eventDate) + intval($diff_sec);
              $str_only_date = date_i18n('Y-m-d', $new_date);
              $en_only_time = date_i18n("G:i", $eventEndDate);
              $start_dt_tm = strtotime($str_only_date . ' ' . $en_only_time);
              if ($start_dt_tm > date_i18n('U')) {
                $eventDate = $new_date;
                break;
              }
              $start_day++;
            }
          }
          if ($days_extra < 1) {
            if (($frequency != '35') && ($frequency != '32')) {
              if ($frequency == 30) {
                $eventDate = strtotime("+" . $fr_repeat . " month", $eventDate);
                $eventEndDate = strtotime("+" . $fr_repeat . " month", $eventEndDate);
              } else {
                $new_date = intval($seconds) * intval($fr_repeat);
                $eventDate = intval($eventDate) + intval($new_date);
                $eventEndDate = intval($eventEndDate) + intval($new_date);
              }
            } elseif ($frequency == '32') {
              if ($fr_repeat != $frequency_count) {
                $eventDate = $multiple_dates[$fr_repeat];
                $eventDate = strtotime($eventDate);
              }
            } else {
              $eventTime = date_i18n('G:i', $eventDate);
              $eventDate = strtotime(date_i18n('Y-m-01', $eventDate));
              if ($fr_repeat == 0) {
                $fr_repeat = intval($fr_repeat) + 1;
              }
              $eventDate = strtotime("+" . $fr_repeat . " month", $eventDate);
              $next_month = date('F', $eventDate);
              $next_event_year = date_i18n('Y', $eventDate);
              $freq_strtotime = $frequency_month_day . ' ' . $frequency_week_day . ' of ' . $next_month . ' ' . $next_event_year;
              $eventDate = date_i18n('Y-m-d ' . $eventTime, strtotime($freq_strtotime));
              $eventDate = strtotime($eventDate);
            }
          }
          if ($MetaStartTime != '') {
            if ($event_show_until == '1') {
              $en_tm = date_i18n("G:i", $event_actual_en_date);
            } else {
              $en_tm = date_i18n("G:i", $event_actual_st_date);
            }
          } else {
            if ($event_show_until != '1') {
              $en_tm = '00:01';
            } else {
              $en_tm = '23:59';
            }
          }
          $st_dt = date_i18n('Y-m-d', $eventDate);
          $dt_tm = strtotime($st_dt . ' ' . $en_tm);
          if ($month != '') {
            if (($dt_tm > $previous_month_end) && ($dt_tm < $next_month_start)) {
              $event_add[$sinc . $dt_tm] = get_the_ID();
              $sinc = $sinc . '0';
            }
          } else {
            if ($status == "future") {
              if ($dt_tm >= date_i18n('U')) {
                $event_add[$sinc . $dt_tm] = get_the_ID();
                $sinc = $sinc . '0';
              }
            } else {
              if ($dt_tm <= date_i18n('U')) {
                $event_add[$sinc . $dt_tm] = get_the_ID();
                $sinc = $sinc . '0';
              }
            }
          }
          if ($days_extra < 1) {
            $fr_repeat++;
          } else {
            $fr_repeat = 1000000;
          }
        }
      endwhile;
    endif;
    //global $wp_query;
    //print_r($wp_query->request);

    wp_reset_query();
    $site_lang = substr(get_locale(), 0, 2);
    if ($status == 'future' && $save_data == 'save') {
      update_option('nativechurch_saved_future_events_' . $site_lang, $event_add);
    } elseif ($status == 'past' && $save_data == 'save') {
      update_option('nativechurch_saved_past_events_' . $site_lang, $event_add);
    }

    return $event_add;
  }
}
function nativechurch_get_term_objects($terms)
{
  if (!$terms) return array();
  if (!is_array($terms) && !is_numeric($terms)) {
    $terms_slug = get_term_by('slug', $terms, 'event-category');
    $terms = $terms_slug->term_id;
  }
  if (is_array($terms) && !is_numeric($terms[0])) {
    $terms_new = array();
    foreach ($terms as $slug_term) {
      $terms_slug = get_term_by('slug', $slug_term, 'event-category');
      if ($terms_slug) {
        $terms_new[] = $terms_slug->term_id;
      }
    }
    $terms = $terms_new;
  }
  if (!$terms) return array();

  $objects = get_objects_in_term($terms, 'event-category');
  return $objects;
}
/* GET EVENT TIME FORMATE
  ================================================*/
/*
    @params $time = start time + end time
	$date = start date + end date
	$post_id = post id
	@return time + date
  */
if (!function_exists('imic_get_event_timeformate')) {
  function imic_get_event_timeformate($time, $date, $post_id = null, $key = null, $single = false)
  {
    #check all day event
    $allday    = get_post_meta($post_id, 'imic_event_all_day', true);
    $time = explode('|', $time);
    $date = explode('|', $date);
    //get event time and date option  format	
    $options = get_option('imic_options');
    $event_tm_opt = isset($options['event_tm_opt']) ? $options['event_tm_opt'] : '0';
    $event_dt_opt = isset($options['event_dt_opt']) ? $options['event_dt_opt'] : '0';
    //get time format
    $time_format = get_option('time_format');
    //get date format
    $date_format = get_option('date_format');
    $time_opt = $date_opt = '';
    $event_dt_opt = ($single == true) ? '2' : $event_dt_opt;
    switch ($event_tm_opt) {
      case '0':
        if (!empty($time[0]) && $time[0] != strtotime(date_i18n('23:59')) && !$allday) {
          $time_opt = date_i18n($time_format, $time[0]);
        } else {
          if ($allday || empty($time[0]) || $time[0] == strtotime(date_i18n('23:59'))) {
            $time_opt = esc_html__('All Day', 'framework');
          }
        }
        break;
      case '1':
        if (!empty($time[1]) && $time[1] != strtotime(date_i18n('23:59'))) {
          $time_opt = date_i18n($time_format, $time[1]);
        } else {
          if ($allday || empty($time[1]) || $time[1] == strtotime(date_i18n('23:59'))) {
            $time_opt = esc_html__('All Day', 'framework');
          }
        }
        break;
      case '2':
        if ((!empty($time[0]) && !empty($time[1])) && ($time[0] != strtotime(date_i18n('23:59')) || $time[1] != strtotime(date_i18n('23:59')))
        ) {
          $time_opt_0 = date_i18n($time_format, $time[0]);
          $time_opt_1 = date_i18n($time_format, $time[1]);
          if ($time[0] != $time[1]) {
            $time_opt =  $time_opt_0 . ' - ' . $time_opt_1;
          } else {
            $time_opt =  $time_opt_0;
          }
        } else {
          if ($allday || empty($time[0]) || $time[0] == strtotime(date_i18n('23:59')) || $time[1] == strtotime(date_i18n('23:59'))) {
            $time_opt = esc_html__('All Day', 'framework');
          }
        }
        break;
      default:
        if (!empty($time[0])) {
          $time_opt = date_i18n($time_format, $time[0]);
        }
        break;
    }
    switch ($event_dt_opt) {
      case '0':
        if (!empty($date[0])) {
          $diff_date = imic_dateDiff($date[0], $date[1]);
          if ($diff_date > 0) {
            $date_opt = date_i18n($date_format, $date[0]);
            $date_opt = '<strong>' . date_i18n('l', $date[0]) . '</strong> | ' . $date_opt;
          } else {
            $date_opt = date_i18n($date_format, $key);
            $date_opt = '<strong>' . date_i18n('l', $key) . '</strong> | ' . $date_opt;
          }
        }
        break;
      case '1':
        if (!empty($date[1])) {
          $diff_date = imic_dateDiff($date[0], $date[1]);
          if ($diff_date > 0) {
            $date_opt = date_i18n($date_format, $date[1]);
            $date_opt = '<strong>' . date_i18n('l', $date[1]) . '</strong> | ' . $date_opt;
          } else {
            $date_opt = date_i18n($date_format, $key);
            $date_opt = '<strong>' . date_i18n('l', $key) . '</strong> | ' . $date_opt;
          }
        }
        break;
      case '2':
        if (!empty($date[0]) && !empty($date[1])) {
          $date_opt_0 = date_i18n($date_format, $date[0]);
          $date_opt_0 = '<strong>' . date_i18n('l', $date[0]) . '</strong> | ' . $date_opt_0;
          if ($date[0] !== $date[1]) {
            $date_opt_1 = date_i18n($date_format, $date[1]);
            $date_opt_1 = '<strong>' . date_i18n('l', $date[1]) . '</strong> | ' . $date_opt_1;
            $date_opt =  $date_opt_0 . ' ' . esc_html__('to', 'framework') . ' ' . $date_opt_1;
          } else {
            $date_opt = date_i18n($date_format, $key);
          }
        }
        break;
      default:
        if (!empty($date[0])) {
          $diff_date = imic_dateDiff($date[0], $date[1]);
          if ($diff_date > 0) {
            $date_opt = date_i18n($date_format, $date[0]);
            $date_opt = '<strong>' . date_i18n('l', $date[0]) . '</strong> | ' . $date_opt;
          } else {
            $date_opt = date_i18n($date_format, $key);
            $date_opt = '<strong>' . date_i18n('l', $key) . '</strong> | ' . $date_opt;
          }
        }
        break;
    }
    return  $time_opt . 'BR' . $date_opt;
  }
}

/* GET TERM CATEGORY 
  ================================================*/
/*
    @params $post_id = post id of post
	$texonomy = texonomy name of page
	$category = category of texonomy @default = event-category
	@return all texonomy categories slugs
  */
if (!function_exists('imic_get_term_category')) {
  function imic_get_term_category($post_id, $texonomy, $category = 'event-category', $boolean = true)
  {

    $event_category = get_post_meta($post_id, $texonomy, $boolean);
    if ($event_category != '') {
      $event_category = explode(',', $event_category);
      $event_category_rel = '';
      foreach ($event_category as $event_cat_id) {
        $event_categories = get_term_by('id', $event_cat_id, $category);
        $event_category_rel .= $event_categories->slug . ',';
      }
      $event_category = rtrim($event_category_rel, ',');
    }
    return  $event_category;
  }
}
////////////////////////////////////////////////////////////////
///////////  CUSTOM ADMIN EVENT LIST DIAPLSY ///////////////////
////////////////////////////////////////////////////////////////
class REARRANGE_ADMIN_EVENT_LIST_COLS
{
  /**
   * Constructor
   */
  public function __construct()
  {
    #WP List table columns. Defined here so they are always available for events such as inline editing.
    add_filter('manage_event_posts_columns', array($this, 'event_columns'));
    add_filter('manage_edit-event_sortable_columns', array($this, 'sortable_event_column'));
    add_action('pre_get_posts', array($this, 'event_sort_by_start_date'));
    add_action('manage_event_posts_custom_column', array($this, 'render_event_columns'), 2);
  }
  /**
   * Define custom columns for events
   * @param  array $existing_columns
   * @return array
   */
  public function event_columns($existing_columns)
  {

    if (empty($existing_columns) && !is_array($existing_columns)) {
      $existing_columns = array();
    }
    unset($existing_columns['title'],
    $existing_columns['date'],
    $existing_columns['author'],
    $existing_columns['taxonomy-event-category']);
    $columns                                   = array();
    $columns['title']                          = esc_html__('Name', 'framework');
    $columns['author']                         = esc_html__('Owner', 'framework');
    $columns['taxonomy-event-category']        = esc_html__('Categories', 'framework');
    $columns['address']                        = esc_html__('Address', 'framework');
    $columns['attendees']                      = esc_html__('Attendees', 'framework');
    $columns['staff']                          = esc_html__('Staff', 'framework');
    $columns['recurring']                      = esc_html__('Recurring', 'framework');
    $columns['event_date']                     = esc_html__('Date', 'framework');
    return array_merge($existing_columns, $columns);
  }
  /**
   * Ouput custom columns for events
   * @param  string $column
   */
  public function render_event_columns($column)
  {
    global $post;
    switch ($column) {
      case 'contact':
        echo get_post_meta($post->ID, 'imic_event_contact', true);
        break;
      case 'address':
        echo get_post_meta($post->ID, 'imic_event_address', true);
        break;
      case 'event_date':
        $sdate = get_post_meta($post->ID, 'imic_event_start_dt', true);
        $stime = get_post_meta($post->ID, 'imic_event_start_tm', true);
        $edate = get_post_meta($post->ID, 'imic_event_end_dt', true);
        $etime = get_post_meta($post->ID, 'imic_event_end_tm', true);
        echo '<abbr title="' . $sdate . ' ' . $stime . '">' . $sdate .
          '</abbr><br title="' . $edate . ' ' . $etime . '">' . $edate;
        break;
      case 'attendees':
        $attendees = get_post_meta($post->ID, 'imic_event_attendees', true);
        echo esc_attr($attendees);
        break;
      case 'staff':
        $staff = get_post_meta($post->ID, 'imic_event_staff_members', true);
        echo esc_attr($staff);
        break;
      case 'recurring':
        $frequency = get_post_meta($post->ID, 'imic_event_frequency', true);
        $frequency_count = get_post_meta($post->ID, 'imic_event_frequency_count', true);
        if ($frequency == 1) {
          $sent = esc_html__('Every Day', 'framework');
        } elseif ($frequency == 2) {
          $sent = esc_html__('Every 2nd Day', 'framework');
        } elseif ($frequency == 3) {
          $sent = esc_html__('Every 3rd Day', 'framework');
        } elseif ($frequency == 4) {
          $sent = esc_html__('Every 4th Day', 'framework');
        } elseif ($frequency == 5) {
          $sent = esc_html__('Every 5th Day', 'framework');
        } elseif ($frequency == 6) {
          $sent = esc_html__('Every 6th Day', 'framework');
        } elseif ($frequency == 6) {
          $sent = esc_html__('Every week', 'framework');
        } elseif ($frequency == 30) {
          $sent = esc_html__('Every Month', 'framework');
        } else {
          $sent = "";
        }
        if ($frequency > 0) {
          echo '<abbr title="' . $sent . ' ' . $sent . '">' . $sent . '</abbr><br>' . $frequency_count . ' time';
        }
        break;
      default:
        break;
    }
  }
  /* make soratable by event start date asc/desc on click this */
  public function sortable_event_column($columns)
  {
    $columns['event_date'] = 'event';
    return $columns;
  }
  /* sort post event list in admin section by post meta event start date */
  public function event_sort_by_start_date($query)
  {
    global $pagenow;
    if (
      'edit.php' == $pagenow && isset($_GET['orderby']) && isset($_GET['order'])
      && isset($_GET['post_type']) && $_GET['post_type'] == 'event' && $_GET['order'] == 'asc'
    ) {
      $query->set('meta_key', 'imic_event_start_dt');
      $query->set('orderby', 'meta_value');
      $query->set('order', 'ASC');
    } elseif (
      'edit.php' == $pagenow && isset($_GET['orderby']) && isset($_GET['order'])
      && isset($_GET['post_type']) && $_GET['post_type'] == 'event' && $_GET['order'] == 'desc'
    ) {
      $query->set('meta_key', 'imic_event_start_dt');
      $query->set('orderby', 'meta_value');
      $query->set('order', 'DESC');
    } elseif (
      'edit.php' == $pagenow && !isset($_GET['orderby'])
      && isset($_GET['post_type']) && $_GET['post_type'] == 'event'
    ) {
      /*$query->set('meta_key', 'imic_event_start_dt');
			$query->set('orderby', 'meta_value');
			$query->set('order', 'DESC');*/ }
  }
}
/* acivate REARRANGE_ADMIN_EVENT_LIST_COLS if user admin */
if (is_admin()) {
  $REARRANGE_EVENT_LIST = new REARRANGE_ADMIN_EVENT_LIST_COLS();
  unset($REARRANGE_EVENT_LIST);
}
if (!function_exists('imic_recur_events_calendar')) {
  function imic_recur_events_calendar($status, $featured = "nos", $term = '', $month = '')
  {
    global $imic_options;
    $event_show_until = (isset($imic_options['countdown_timer'])) ? $imic_options['countdown_timer'] : '0';
    $featured = ($featured == "yes") ? "no" : "nos";
    $today = date_i18n('Y-m-d');
    if ($month != "") {
      $stop_date = $month;
      $curr_month = date_i18n('Y-m-t 23:59', strtotime('-1 month', strtotime($stop_date)));
      $current_end_date = date_i18n('Y-m-d H:i:s', strtotime($stop_date . ' + 1 day'));
      $previous_month_end = strtotime(date_i18n('Y-m-d 00:01', strtotime($stop_date)));
      $next_month_start = strtotime(date_i18n('Y-m-d 00:01', strtotime('+1 month', strtotime($stop_date))));
      query_posts(array('post_type' => 'event', 'event-category' => $term, 'meta_key' => 'imic_event_start_dt', 'meta_query' => array('relation' => 'AND', array('key' => 'imic_event_frequency_end', 'value' => $curr_month, 'compare' => '>'), array('key' => 'imic_event_start_dt', 'value' => date_i18n('Y-m-t 23:59', strtotime($stop_date)), 'compare' => '<')), 'orderby' => 'meta_value', 'order' => 'ASC', 'posts_per_page' => -1));
    } else {
      if ($status == 'future') {
        query_posts(array('post_type' => 'event', 'event-category' => $term, 'meta_key' => 'imic_event_start_dt', 'meta_query' => array(array('key' => 'imic_event_frequency_end', 'value' => $today, 'compare' => '>=')), 'orderby' => 'meta_value', 'order' => 'ASC', 'posts_per_page' => -1));
      } else {
        query_posts(array('post_type' => 'event', 'event-category' => $term, 'meta_key' => 'imic_event_start_dt', 'meta_query' => array(array('key' => 'imic_event_start_dt', 'value' => $today, 'compare' => '<')), 'orderby' => 'meta_value', 'order' => 'ASC', 'posts_per_page' => -1));
      }
    }
    $event_add = array();
    $sinc = '0';
    if (have_posts()) :
      while (have_posts()) : the_post();
        $frequency = get_post_meta(get_the_ID(), 'imic_event_frequency', true);
        $frequency_count = get_post_meta(get_the_ID(), 'imic_event_frequency_count', true);
        $frequency_month_day = get_post_meta(get_the_ID(), 'imic_event_day_month', true);
        $frequency_week_day = get_post_meta(get_the_ID(), 'imic_event_week_day', true);
        $multiple_dates = get_post_meta(get_the_ID(), 'imic_event_recurring_dt', true);
        if ($frequency != '0' && $frequency != '32') {
          $frequency_count = $frequency_count;
        } elseif ($frequency == '32') {
          $frequency_count = count($multiple_dates);
        } else {
          $frequency_count = 0;
        }
        $seconds = intval($frequency) * 86400;
        $fr_repeat = 0;
        while ($fr_repeat <= $frequency_count) {
          $eventDate = get_post_meta(get_the_ID(), 'imic_event_start_dt', true);
          $MetaStartTime = get_post_meta(get_the_ID(), 'imic_event_start_tm', true);
          $eventEndDate = get_post_meta(get_the_ID(), 'imic_event_end_dt', true);
          $MetaEndTime = get_post_meta(get_the_ID(), 'imic_event_end_tm', true);
          $eventEndDate = strtotime($eventEndDate . ' ' . $MetaEndTime);
          $eventDate = strtotime($eventDate . ' ' . $MetaStartTime);
          $diff_start = date_i18n('Y-m-d', $eventDate);
          $diff_end = date_i18n('Y-m-d', $eventEndDate);
          $days_extra = imic_dateDiff($diff_start, $diff_end);
          $go_cl = '';
          //echo "sn";
          if ($days_extra > 0) {
            $go_cl = '';
            $en_dt_cl = strtotime(get_post_meta(get_the_ID(), 'imic_event_end_dt', true));
            $st_dt_cl = strtotime(get_post_meta(get_the_ID(), 'imic_event_start_dt', true));
            $en_dt_cl_mn = date_i18n('m', $en_dt_cl);
            $st_dt_cl_mn = date_i18n('m', $st_dt_cl);
            if ($en_dt_cl_mn != $st_dt_cl_mn) {
              $go_cl = 1;
            }
            $start_day = 0;
            while ($start_day <= $days_extra) {
              $diff_sec = 86400 * intval($start_day);
              $new_date = intval($eventDate) + intval($diff_sec);
              $str_only_date = date_i18n('Y-m-d', $new_date);
              $en_only_time = date_i18n("G:i", $eventEndDate);
              $start_dt_tm = strtotime($str_only_date . ' ' . $en_only_time);
              //echo date('U');
              if ($start_dt_tm > date_i18n('U')) {
                $eventDate = $new_date;
                break;
              }
              $start_day++;
            }
          }
          if ($days_extra < 1) {
            if (($frequency != '35') && ($frequency != '32')) {
              if ($frequency == 30) {
                $eventDate = strtotime("+" . $fr_repeat . " month", $eventDate);
                $eventEndDate = strtotime("+" . $fr_repeat . " month", $eventEndDate);
              } else {
                $new_date = intval($seconds) * intval($fr_repeat);
                $eventDate = intval($eventDate) + intval($new_date);
                $eventEndDate = intval($eventEndDate) + intval($new_date);
              }
            } elseif ($frequency == '32') {
              if ($fr_repeat != $frequency_count) {
                $eventDate = $multiple_dates[$fr_repeat] . ' ' . $MetaStartTime;
                $eventDate = strtotime($eventDate);
              }
            } else {
              $eventTime = date_i18n('G:i', $eventDate);
              $eventDate = strtotime(date_i18n('Y-m-01', $eventDate));
              if ($fr_repeat == 0) {
                $fr_repeat = intval($fr_repeat) + 1;
              }
              $eventDate = strtotime("+" . $fr_repeat . " month", $eventDate);
              $next_month = date('F', $eventDate);
              $next_event_year = date_i18n('Y', $eventDate);
              $eventDate = date_i18n('Y-m-d ' . $eventTime, strtotime($frequency_month_day . ' ' . $frequency_week_day . ' of ' . $next_month . ' ' . $next_event_year));
              $eventDate = strtotime($eventDate);
            }
          }
          $st_dt = date_i18n('Y-m-d', $eventDate);
          if ($MetaStartTime != '') {
            if ($event_show_until == '1') {
              $en_tm = date_i18n("G:i", $eventEndDate);
            } else {
              $en_tm = date_i18n("G:i", $eventDate);
            }
          } else {
            $en_tm = "23:59";
          }
          $dt_tm = strtotime($st_dt . ' ' . $en_tm);
          if ($month != '') {
            if ((($dt_tm > $previous_month_end) && ($dt_tm < $next_month_start)) || ($go_cl == 1)) {
              $event_add[$sinc . $dt_tm] = get_the_ID();
              $sinc = $sinc . '0';
            }
          } else {
            if ($status == "future") {
              if ($dt_tm >= date_i18n('U')) {
                $event_add[$sinc . $dt_tm] = get_the_ID();
                $sinc = $sinc . '0';
              }
            } else {
              if ($dt_tm <= date_i18n('U')) {
                $event_add[$sinc . $dt_tm] = get_the_ID();
                $sinc = $sinc . '0';
              }
            }
          }
          if ($days_extra < 1) {
            $fr_repeat++;
          } else {
            $fr_repeat = 1000000;
          }
        }
      endwhile;
    endif;
    wp_reset_query();
    return $event_add;
  }
}

/* add action on init*/

function nativechurch_dynamic_category_list()
{
  $cpt = $_POST['cpt'];
  $selected_cat = $_POST['selected_cat'];
  switch ($cpt) {
    case 'product':
      $cat = 'product_cat';
      break;
    case 'causes':
      $cat = 'causes-category';
      break;
    case 'gallery':
      $cat = 'gallery-category';
      break;
    case 'staff':
      $cat = 'staff-category';
      break;
    case 'sermons':
      $cat = 'sermons-category';
      break;
    case 'event':
      $cat = 'event-category';
      break;
    default:
      $cat = 'category';
  }
  $post_cats = get_terms($cat);
  if (!empty($post_cats)) {
    echo '<option value="">' . esc_html__('Select Post Category', 'framework') . '</option>';
    foreach ($post_cats as $post_cat) {
      $name = $post_cat->name;
      $id = $post_cat->term_id;
      $activePost = ($id == $selected_cat) ? 'selected' : '';
      echo '<option value="' . $id . '"' . $activePost . '>' . $name . '</option>';
    }
  }
  die();
}
add_action('wp_ajax_nopriv_nativechurch_dynamic_category_list', 'nativechurch_dynamic_category_list');
add_action('wp_ajax_nativechurch_dynamic_category_list', 'nativechurch_dynamic_category_list');

//Functions for Front End Event Listing
if (!function_exists('imic_insert_attachment')) {
  function imic_insert_attachment($file_handler, $post_id, $setthumb = 'false')
  {
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) {
      return __return_false();
    }
    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attach_id = media_handle_upload($file_handler, $post_id);
    //set post thumbnail if setthumb is 1
    if ($setthumb == 1) update_post_meta($post_id, '_thumbnail_id', $attach_id);
    return $attach_id;
  }
}
//End Front End Event Listing
function nativechurch_get_terms_orderby($orderby, $args)
{
  if (isset($args['orderby']) && 'include' == $args['orderby']) {
    $include = implode(',', array_map('absint', $args['include']));
    $orderby = "FIELD( t.term_id, $include )";
  }
  return $orderby;
}
add_filter('get_terms_orderby', 'nativechurch_get_terms_orderby', 10, 2);
function nativechurch_get_terms_taxonomy($taxonomy = 'sermons-category', $get_term = array())
{
  $terms = get_terms($taxonomy);
  if (!is_wp_error($terms) && !empty($terms)) {
    foreach ($terms as $term) {
      $get_term[$term->term_id] = $term->name;
    }
  }
  return $get_term;
}
add_filter('nativechurch_get_terms', 'nativechurch_get_terms_taxonomy', 10, 2);


$default_attribs = array('data-shortcode' => array(), 'data-toggle' => array(), 'data-rel' => array(), 'data-parent' => array(), 'data-skin' => array(), 'data-layout' => array(), 'name' => array(), 'action' => array(), 'method' => array(), 'type' => array(), 'placeholder' => array(), 'data-padding' => array(), 'data-margin' => array(), 'data-autoplay-timeout' => array(), 'data-loop' => array(), 'data-rtl' => array(), 'data-auto-height' => array(), 'data-displayinput' => array(), 'data-readonly' => array(), 'value' => array(), 'data-fgcolor' => array(), 'data-bgcolor' => array(), 'data-thickness' => array(), 'data-linecap' => array(), 'data-option-value' => array(), 'data-style' => array(), 'data-pause' => array(), 'data-speed' => array(), 'data-option-key' => array(), 'data-sort-id' => array(), 'href' => array(), 'rel' => array(), 'data-appear-progress-animation' => array(), 'data-appear-animation-delay' => array(), 'target' => array('_blank', '_self', '_top'), 'data-items-mobile' => array(), 'data-items-tablet' => array(), 'data-items-desktop-small' => array(), 'data-items-desktop' => array(), 'data-single-item' => array(), 'data-arrows' => array(), 'data-pagination' => array(), 'data-autoplay' => array(), 'data-columns' => array(), 'data-columns-tab' => array(), 'data-columns-mobile' => array(), 'width' => array(), 'data-srcset' => array(), 'height' => array(), 'src' => array(), 'id' => array(), 'class' => array(), 'title' => array(), 'style' => array(), 'alt' => array(), 'data' => array(), 'data-mce-id' => array(), 'data-mce-style' => array(), 'data-mce-bogus' => array());

$framework_allowed_tags = array(
  'div'           => $default_attribs,
  'span'          => $default_attribs,
  'p'             => $default_attribs,
  'a'             => $default_attribs,
  'u'             => $default_attribs,
  'i'             => $default_attribs,
  'q'             => $default_attribs,
  'b'             => $default_attribs,
  'ul'            => $default_attribs,
  'ol'            => $default_attribs,
  'li'            => $default_attribs,
  'br'            => $default_attribs,
  'hr'            => $default_attribs,
  'strong'        => $default_attribs,
  'blockquote'    => $default_attribs,
  'del'           => $default_attribs,
  'strike'        => $default_attribs,
  'em'            => $default_attribs,
  'code'          => $default_attribs,
  'h1'            => $default_attribs,
  'h2'            => $default_attribs,
  'h3'            => $default_attribs,
  'h4'            => $default_attribs,
  'h5'            => $default_attribs,
  'h6'            => $default_attribs,
  'cite'          => $default_attribs,
  'img'           => $default_attribs,
  'section'       => $default_attribs,
  'iframe'        => $default_attribs,
  'input'         => $default_attribs,
  'label'         => $default_attribs,
  'canvas'        => $default_attribs,
  'form'          => $default_attribs,
  'sub'          => $default_attribs,
  'sup'          => $default_attribs,
  'nav'          => $default_attribs,
);

function nativechurch_remove_saved_events()
{
  $site_lang = substr(get_locale(), 0, 2);
  update_option('nativechurch_saved_future_events_' . $site_lang, '');
  update_option('nativechurch_saved_past_events_' . $site_lang, '');
}
add_action('save_post', 'nativechurch_remove_saved_events', 99, 2);
add_action('edit_post', 'nativechurch_remove_saved_events', 99, 2);
add_action('publish_event', 'nativechurch_remove_saved_events', 999, 2);
add_action('trashed_post', 'nativechurch_remove_saved_events', 10, 2);
add_action('untrash_post', 'nativechurch_remove_saved_events', 10, 2);

if (!function_exists('nativechurch_regenerate_calender_index')) {
  function nativechurch_regenerate_calender_index($index, $google_event_array)
  {
    $index = ($index + 1);
    if (array_key_exists($index, $google_event_array)) {
      return nativechurch_regenerate_calender_index($index, $google_event_array);
    }
    return $index;
  }
}
function nativechurch_generate_Google_events_list($calender_id = '', $api_key = '', $time = '')
{
  $time = ($time) ? $time : date_i18n('Y-m-d');
  $date_start_set = ($time == '') ? date_i18n('Y-m-d') : date_i18n('Y-m-d', strtotime($time));
  $date_end_set = ($time == '') ? date_i18n('Y-m-d', strtotime('+2 years')) : date_i18n('Y-m-t', strtotime('+2 years'));
  $GoogleEvents = array();
  $sb = str_replace('+00:00', 'Z', gmdate('c', strtotime($date_start_set)));
  $sbe = str_replace('+00:00', 'Z', gmdate('c', strtotime($date_end_set)));
  $events = wp_remote_get('https://www.googleapis.com/calendar/v3/calendars/' . $calender_id . '/events/?key=' . $api_key . '&timeMin=' . $sb . '&timeMax=' . $sbe);
  if (is_wp_error($events) || $api_key == '' || $calender_id == '') {
    return false;
  }
  $body = wp_remote_retrieve_body($events);
  $data = json_decode($body);
  $gevents = (is_object($data) && property_exists($data, 'items')) ? $data->items : array();
  if (!empty($gevents)) {
    foreach ($gevents as $ev) {
      $multi_events = array($ev);
      $recurrence = (property_exists($ev, 'recurrence')) ? '1' : '';
      $id = $ev->id;
      if ($recurrence == '1') {
        $event_instances = wp_remote_get('https://www.googleapis.com/calendar/v3/calendars/' . $calender_id . '/events/' . $id . '/instances?key=' . $api_key . '&timeMin=' . $sb . '&timeMax=' . $sbe);
        $body_instances = wp_remote_retrieve_body($event_instances);
        $data_instances = json_decode($body_instances);
        $instances_event = $data_instances->items;
        $multi_events = $instances_event;
      }
      foreach ($multi_events as $nevent) {
        if ($nevent->status != 'confirmed') continue;
        $googleEvents = array();
        $start_date = (property_exists($nevent->start, 'dateTime')) ? $nevent->start->dateTime : $nevent->start->date;
        $all_day = (property_exists($nevent->start, 'dateTime')) ? '' : '1';
        $end_date = (property_exists($nevent->end, 'dateTime')) ? $nevent->end->dateTime : $nevent->end->date;
        $htnl_link = (property_exists($nevent, 'htmlLink')) ? $nevent->htmlLink : '';
        $title = (property_exists($nevent, 'summary')) ? $nevent->summary : '';
        $description = (property_exists($nevent, 'description')) ? $nevent->description : '';
        $location = (property_exists($nevent, 'location')) ? $nevent->location : '';
        $googleEvents['start_time']  = $start_date;
        $googleEvents['end_time']    = $end_date;
        $googleEvents['url']         = $htnl_link;
        $googleEvents['title']       = $title;
        $googleEvents['event_day']   = '';
        $googleEvents['description']   = $description;
        $googleEvents['location']   = $location;
        $googleEvents['allday']   = $all_day;
        $googleEvents['color']   = '';
        $GoogleEvents[] = $googleEvents;
      }
    }
  }
  return $GoogleEvents;
}

if (!function_exists('nativechurch_fetch_google_events')) {
  function nativechurch_fetch_google_events($date_set = '')
  {
    $imic_options = get_option('imic_options');
    $google_calendar_id = (isset($imic_options['google_feed_id']) && $imic_options['google_feed_id'] != '') ? $imic_options['google_feed_id'] : '';
    $google_calendar_api = (isset($imic_options['google_feed_key']) && $imic_options['google_feed_key'] != '') ? $imic_options['google_feed_key'] : '';
    $google_event_array = array();
    $items = nativechurch_generate_Google_events_list($google_calendar_id, $google_calendar_api, $date_set);
    if (!$items) return array();
    foreach ($items as $entry) {

      $title = $entry['title'];
      $link = $entry['url'];
      $event_start_time = $entry['start_time'];
      $event_start_time = get_date_from_gmt($event_start_time, 'Y-m-d H:i');
      $google_event_end_time   = $entry['end_time'];

      $description = $entry['description'];
      $location = $entry['location'];
      $allday = $entry['allday'];
      $color = $entry['color'];
      if ($allday == '1') {
        $event_start_time = $entry['start_time'] . ' 00:00:01';
        $google_event_end_time   = $entry['end_time'] . ' 23:59:59';
      }
      $index = date_i18n('U', strtotime($event_start_time));
      if (!empty($index) && array_key_exists($index, $google_event_array)) {
        $index = nativechurch_regenerate_calender_index($index, $google_event_array);
      }
      $start_date_format = date_i18n('Y-m-d H:i:s', $index);

      if (date_i18n('U') > strtotime($start_date_format)) continue;
      $end_date_format = get_date_from_gmt(date_i18n('Y-m-d H:i:s', strtotime($google_event_end_time)), 'Y-m-d H:i:s');
      $google_event_array[strtotime($start_date_format)] = $title . '!' . $link . '!' . $end_date_format . '!' . $location;
    }
    return $google_event_array;
  }
}

function nativechurch_custom_post_type_meta()
{
  if (is_singular() || is_page()) {
    $post_type = get_post_type(get_the_ID());
    $base_class = (is_page()) ? ', .page-template' : '';
    $custom_css = '';
    $imic_options = get_option('imic_options');
    if (!isset($imic_options[$post_type . '_breadcrumb'])) return;
    $breadcrumb_switch = (isset($imic_options[$post_type . '_breadcrumb'])) ? $imic_options[$post_type . '_breadcrumb'] : '';
    $page_text = (isset($imic_options[$post_type . '_page_title'])) ? $imic_options[$post_type . '_page_title'] : '';
    $custom_desc = (isset($imic_options[$post_type . '_custom_description'])) ? $imic_options[$post_type . '_custom_description'] : '';
    $post_title = (isset($imic_options[$post_type . '_title'])) ? $imic_options[$post_type . '_title'] : '';
    $comment_count = (isset($imic_options[$post_type . '_comment_count'])) ? $imic_options[$post_type . '_comment_count'] : '';
    $post_date = (isset($imic_options[$post_type . '_date'])) ? $imic_options[$post_type . '_date'] : '';
    $post_author = (isset($imic_options[$post_type . '_author'])) ? $imic_options[$post_type . '_author'] : '';
    $post_category = (isset($imic_options[$post_type . '_categories'])) ? $imic_options[$post_type . '_categories'] : '';
    $featured_image = (isset($imic_options[$post_type . '_featured_image'])) ? $imic_options[$post_type . '_featured_image'] : '';
    $share_bar = (isset($imic_options[$post_type . '_social_icons'])) ? $imic_options[$post_type . '_social_icons'] : '';
    $show_comments = (isset($imic_options[$post_type . '_comments'])) ? $imic_options[$post_type . '_comments'] : '';
    $allow_comments = (isset($imic_options[$post_type . '_comments_allow'])) ? $imic_options[$post_type . '_comments_allow'] : '';
    if (is_page()) {
      $custom_desc = '1';
    }
    $custom_css .= ($breadcrumb_switch == 0 && $breadcrumb_switch != '') ? ".single-" . $post_type . $base_class . " .breadcrumb{
			visibility:hidden}" : '';
    $custom_css .= ($page_text == '' || $page_text == 0) ? ".single-" . $post_type . $base_class . " .cpt-page-title{
			visibility:hidden}" : '';
    $custom_css .= ($custom_desc == 0 && $custom_desc != '') ? ".single-" . $post_type . $base_class . " .custom-desc{
				visibility:hidden}" : '';
    $custom_css .= ($page_text == '' && $custom_desc == 0) ? ".single-" . $post_type . $base_class . " .detail-page-title-bar{
					display:none}" : '';
    $custom_css .= ($post_title == 0 && $post_title != '') ? ".single-" . $post_type . $base_class . " .post-title{
					visibility:hidden}" : '';
    $custom_css .= ($comment_count == 0 && $comment_count != '') ? ".single-" . $post_type . $base_class . " .post-comments-count{
						visibility:hidden}" : '';
    $custom_css .= ($post_title == 0 && $comment_count == 0 && $comment_count != '') ? ".single-" . $post_type . $base_class . " .single-post-header{
							display:none}" : '';
    $custom_css .= ($post_date == 0 && $post_date != '') ? ".single-" . $post_type . $base_class . " .post-date-meta{
							visibility:hidden}" : '';
    $custom_css .= ($post_author == 0 && $post_author != '') ? ".single-" . $post_type . $base_class . " .post-author-meta{
								visibility:hidden}" : '';
    $custom_css .= ($post_category == 0 && $post_category != '') ? ".single-" . $post_type . $base_class . " .post-category-meta{
									visibility:hidden}" : '';
    $custom_css .= ($post_category == 0 && $post_date == 0 && $post_author == 0 && $post_category != '') ? ".single-" . $post_type . $base_class . " .meta-data{
										display:none}" : '';
    $custom_css .= ($featured_image == 0 && $featured_image != '') ? ".single-" . $post_type . $base_class . " .featured-image{
											display:none}" : '';
    $custom_css .= ($share_bar == 0 && $share_bar != '') ? ".single-" . $post_type . $base_class . " .share-bar{
												display:none}" : '';
    $custom_css .= ($show_comments == 0 && $show_comments != '') ? ".single-" . $post_type . $base_class . " .post-comments{
													display:none}" : '';
    $custom_css .= ($allow_comments == 0 && $allow_comments != '') ? ".single-" . $post_type . $base_class . " #respond-wrap{
														display:none}" : '';
    wp_add_inline_style('imic_main', $custom_css);
  }
}
//add_action('wp_enqueue_scripts', 'nativechurch_custom_post_type_meta', 9999);

// Get the Page ID
if (!function_exists('imi_page_id')) {
  function imi_page_id()
  {
    $page_ID = get_the_ID();

    if (is_front_page()) {
      $page_ID = get_option('page_on_front');
    }

    if (is_home() || is_category() || is_search() || is_tag() || is_tax()) {
      $page_ID = get_option('page_for_posts');
    }

    return $page_ID;
  }
}
$imic_options = get_option('imic_options');
if (!isset($imic_options['switch-elementor']) || ($imic_options['switch-elementor'] == 0)) {
	//Custom CSS Enqueue - Inline (no extra HTTP request)
	if (!function_exists('imi_custom_style_enqueue')) {
	  add_action('wp_enqueue_scripts', 'imi_custom_style_enqueue', 9999);
	  function imi_custom_style_enqueue()
	  {
		$taxp = '1';
		$id = '';
		if (is_home()) {
		  $id = get_option('page_for_posts');
		} else {
		  $id = get_the_ID();
		}
		if (is_tax() || is_category() || is_tag() || is_archive()) {
		  $taxp = '';
		}
		$sidebar_position = get_post_meta($id, 'imic_select_sidebar_position', true);
		if (class_exists('buddypress') && is_buddypress()) {
		  $component = bp_current_component();
		  $bp_pages = get_option('bp-pages');
		  $id = $bp_pages[$component];
		  $sidebar_position = get_post_meta($id, 'imic_select_sidebar_position', true);
		}
		// Set variables that custom-css.php expects from $_REQUEST
		$_REQUEST['taxp'] = $taxp;
		$_REQUEST['pgid'] = $id;
		$_REQUEST['sidebar_pos'] = $sidebar_position;
		// Capture the CSS output instead of loading via AJAX
		ob_start();
		require IMIC_FILEPATH . '/assets/css/custom-css.php';
		$inline_css = ob_get_clean();
		// Remove the Content-type header that custom-css.php sets (not needed for inline)
		if (!headers_sent()) {
		  header_remove('Content-type');
		}
		if (!empty($inline_css)) {
		  wp_add_inline_style('imic_main', $inline_css);
		}
	  }
	}
}

function imiSermonPlayRecord()
{
  $sermon = $_REQUEST['sermon'];
  if ($sermon) {
    $allsermonsPlayed = get_option('sermons_played');
    $allsermonsPlayed = $allsermonsPlayed ? $allsermonsPlayed : 0;
    update_option('sermons_played', ++$allsermonsPlayed);
    $playRecord = get_post_meta($sermon, 'imi_play_record', true);
    update_post_meta($sermon, 'imi_play_record', ++$playRecord);
  }
  die;
}
add_action('wp_ajax_imiSermonPlayRecord', 'imiSermonPlayRecord');
add_action('wp_ajax_nopriv_imiSermonPlayRecord', 'imiSermonPlayRecord');


function disallowed_admin_pages()
  {
    global $pagenow;
    if ($pagenow == 'admin.php') {
      $domain = preg_replace( '|https?://([^/]+)|', '$1', home_url() );
      $localhost = false;
      if ( parse_url( home_url() , PHP_URL_PATH ) || 'localhost' === $domain || preg_match( '|^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$|', $domain ) ) {
        $localhost = true;
      }
      if (!$localhost && (!empty($_REQUEST['page']) && $_REQUEST['page'] == '_options' || !empty($_REQUEST['page']) && $_REQUEST['page'] == 'imi-admin-demo-importer')) {
        if (empty(get_option('nativechurch_authenticate'))) {
          wp_safe_redirect(admin_url('/admin.php?page=imi-admin-welcome'));
          exit;
        }
      }
    }
  }
  add_action('admin_init', 'disallowed_admin_pages');

  function processAuthentication()
  {
    $status = $_REQUEST['status'];
    $authCode = $_REQUEST['authCode'];
    update_option('nativechurch_authenticate', $status);
    update_option('nativechurch_auth_code', $authCode);
    wp_die();
  }
  add_action('wp_ajax_processAuthentication', 'processAuthentication');

function welcomePageElement()
{
	if(!empty($_GET['page']) && $_GET['page'] == 'imi-admin-welcome'){
	$purchaseCode = $purchaseCodeRaw = $showRegisterForm = "";
	$showUnRegisterForm = ' style="display: none;"';
	if(!empty(get_option('nativechurch_auth_code'))) {
		$purchaseCode = "xxxxxx-xxxx-xxxxxxx-xxxxx".substr(get_option('nativechurch_auth_code'), -4);
		$purchaseCodeRaw = get_option('nativechurch_auth_code');
		$showRegisterForm = ' style="display: none;"';
		$showUnRegisterForm = "";
	}
?>
	<div class="nativechurch-validation-steps" style="display: none;">
		<div class="imi-box-content imi-theme-reg-box" <?php echo ''.$showUnRegisterForm; ?>>
			<h3 style="color: green; text-align: left">Theme is registered</h3>
		</div>
		<div class="imi-box-content imi-theme-reg-box" <?php echo ''.$showRegisterForm; ?>>
				<h3 style="color: red; text-align: left">Please register your theme purchase code</h3>
		</div>
		<div class="imi-box-content">
			<form class="imi_val" <?php echo ''.$showRegisterForm; ?>>
				<label for="imi_purchase_code"><?php esc_html_e('Purchase Code: ','framework') ?></label>
				<input type="text" value="" class="native-purchase-code">
				<input type="hidden" value="<?php echo urlencode(site_url()); ?>" class="native-verified-dm">
				<input type="hidden" value="<?php echo esc_url($_SERVER['REMOTE_ADDR']); ?>" class="native-server-type">
				<button type="submit" class="imi-submit-btn">Register</button>
			<div class="native-message"></div>
			</form>

			<form class="imi_vals" <?php echo ''.$showUnRegisterForm; ?>>
				<label for="imi_purchase_code"><?php esc_html_e('Purchase Code: ','framework') ?></label>
				<input type="text" class="native-hidden-code" value="<?php echo esc_attr($purchaseCode); ?>">
				<input type="hidden" value="<?php echo urlencode(site_url()) ?>" class="native-verified-dm">
				<input type="hidden" value="<?php echo esc_url($_SERVER['REMOTE_ADDR']); ?>" class="native-server-type">
				<input type="hidden" value="<?php echo esc_attr($purchaseCodeRaw); ?>" class="native-purchase-code">
				<button type="submit" class="imi-submit-btn">Unregister</button>
				<p><small>Unregister the purchase code to use it on any other domain/website.</small></p>
			<div class="native-message"></div>
			</form>

			<div <?php echo ''.$showUnRegisterForm; ?>>
				<div class="imi-button">
					<a href="<?php echo esc_url(get_admin_url()); ?>admin.php?page=envato-market#settings">Setup theme auto updates</a>
				</div>
			</div>
			<div <?php echo ''.$showRegisterForm; ?>>
				<h3><?php _e( 'Instructions for registering your purchase code', 'framework' ); ?></h3>
				<p>Whenever you purchase an item via the ThemeForest, they will provide you with a purchase code for each item purchased. The purchase code is used for purchase validation for use of the item and also so that you can access theme support.</p>
				<ol>
					<li>Log in to ThemeForest with your Envato account.</li>
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
}
add_action('admin_init', 'welcomePageElement');