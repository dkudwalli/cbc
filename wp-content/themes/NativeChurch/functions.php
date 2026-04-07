<?php
/* -----------------------------------------------------------------------------------
  Here we have all the custom functions for the theme
  Please be extremely cautious editing this file,
  When things go wrong, they tend to go wrong in a big way.
  You have been warned!
  ----------------------------------------------------------------------------------- */
/*
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
  ----------------------------------------------------------------------------------- */
/* Defined */
if(!defined('IMITHEMES_NATIVECHURCH_PCODE')) define('IMITHEMES_NATIVECHURCH_PCODE', strrev('8a5171030c96-b51b-f654-ccf1-c134f256'));
if(!defined('IMITHEMES_NATIVECHURCH_PLGNSDIR')) define('IMITHEMES_NATIVECHURCH_PLGNSDIR', get_template_directory() . '/framework/tgm/plugins/');
if(!defined('IMITHEMES_NATIVECHURCH_PLGNSURI')) define('IMITHEMES_NATIVECHURCH_PLGNSURI', get_template_directory_uri() . '/framework/tgm/plugins/');
// Removed per-request update_option calls — these wrote to DB on every page load.
// Authentication options are already set in the database.

$theme_info = wp_get_theme();
define('NATIVECHURCH_THEME_VERSION', (WP_DEBUG) ? time() : $theme_info->get('Version'));
define('NATIVECHURCH_INC_PATH', get_template_directory() . '/framework');
define('IMIC_THEME_PATH', get_template_directory_uri());
define('IMIC_FILEPATH', get_template_directory());

//Remove slider revolution Admin Notice
add_action('admin_init', 'adore_remove_revslider_notice');
function adore_remove_revslider_notice()
{
    //update_option('revslider-valid-notice', false);
    //update_option('revslider-valid', true);
}

include_once(NATIVECHURCH_INC_PATH . '/includes.php');

/* -------------------------------------------------------------------------------------
  Load Translation Text Domain
  ----------------------------------------------------------------------------------- */
add_action('after_setup_theme', 'imic_theme_setup');
function imic_theme_setup()
{
    load_theme_textdomain('framework', IMIC_FILEPATH . '/language');
    require_once NATIVECHURCH_INC_PATH . '/barebones-config.php';
}
/* -------------------------------------------------------------------------------------
  Menu option
  ----------------------------------------------------------------------------------- */
function register_menu()
{
    register_nav_menu('primary-menu', esc_html__('Primary Menu', 'framework'));
    register_nav_menu('top-menu', esc_html__('Top Menu', 'framework'));
    register_nav_menu('footer-menu', esc_html__('Footer Menu', 'framework'));
}
add_action('init', 'register_menu');
/* -------------------------------------------------------------------------------------
  Set Max Content Width (use in conjuction with ".entry-content img" css)
  ----------------------------------------------------------------------------------- */
if (!isset($content_width))
    $content_width = 680;
/* -------------------------------------------------------------------------------------
  Configure WP2.9+ Thumbnails & gets the current post type in the WordPress Admin
  ----------------------------------------------------------------------------------- */
if (function_exists('add_theme_support')) {
    $post_type_name = '';
    if (isset($_GET['post'])) {
        $post_type_name = get_post_type($_GET['post']);
    }
    if (isset($_REQUEST['post_type'])) {
        $post_type_name = sanitize_key($_REQUEST['post_type']);
    }
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
    if ($post_type_name == 'gallery') {
        add_theme_support('post-formats', array(
            'video', 'image', 'gallery', 'link'
        ));
    }
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script'
	) );
    set_post_thumbnail_size(958, 9999);
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
/* -------------------------------------------------------------------------------------
  Custom Gravatar Support
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_custom_gravatar')) {
    function imic_custom_gravatar($avatar_defaults)
    {
        $imic_avatar = get_template_directory_uri() . '/assets/images/img_avatar.png';
        $avatar_defaults[$imic_avatar] = 'Custom Gravatar (/assets/images/img_avatar.png)';
        return $avatar_defaults;
    }
    add_filter('avatar_defaults', 'imic_custom_gravatar');
}
/* -------------------------------------------------------------------------------------
  For Paginate
  ----------------------------------------------------------------------------------- */
if (!function_exists('pagination')) {
    function pagination($pages = '', $range = 4)
    {
        $showitems = ($range * 2) + 1;
        global $paged;
        if (empty($paged))
            $paged = 1;
        if ($pages == '') {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if (!$pages) {
                $pages = 1;
            }
        }
        if (1 != $pages) {
            echo '<ul class="pagination">';
            echo '<li><a href="' . get_pagenum_link(1) . '" title="' . esc_html__('First', 'framework') . '"><i class="fa fa-angle-double-left"></i></a></li>';
            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $paged + $range + 3 || $i <= $paged - $range - 3) || $pages <= $showitems)) {
                    echo ''.($paged == $i) ? "<li class=\"active\"><span>" . $i . "</span></li>" : "<li><a href='" . get_pagenum_link($i) . "' class=\"\">" . $i . "</a></li>";
                }
            }
            echo '<li><a href="' . get_pagenum_link($pages) . '" title="' . esc_html__('Last', 'framework') . '"><i class="fa fa-angle-double-right"></i></a></li>';
            echo '</ul>';
        }
    }
}
/* -------------------------------------------------------------------------------------
  For Remove Dimensions from thumbnail image
  ----------------------------------------------------------------------------------- */
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10);
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10);
function remove_thumbnail_dimensions($html)
{
    if (!strpos($html, 'attachment-shop_single')) {
        $html = preg_replace('/(width|height)=\"\d*\"\s/', '', $html);
    }
    return $html;
}
/* -------------------------------------------------------------------------------------
  Add Redux Options
  ----------------------------------------------------------------------------------- */
// Redux config (barebones-config.php) is now loaded inside imic_theme_setup()
// on the after_setup_theme hook, so the 'framework' textdomain is registered
// before any esc_html__() calls trigger just-in-time translation loading.
/* -------------------------------------------------------------------------------------
  Excerpt More and  length
  ----------------------------------------------------------------------------------- */
if (!function_exists('imic_custom_read_more')) {
    function imic_custom_read_more()
    {
        return '... ';
    }
}
if (!function_exists('imic_excerpt')) {
    function imic_excerpt($limit = 50)
    {
        return '<p>' . wp_trim_words(get_the_excerpt(), $limit, imic_custom_read_more()) . '</p>';
    }
}
if (!function_exists('imic_excerpt_id')) {
    function imic_excerpt_id($id = '',$limit = 50)
    {
        return '<p>' . wp_trim_words(get_the_excerpt($id), $limit, imic_custom_read_more()) . '</p>';
    }
}
/* ----------------------------------------------------------------------------------- */
/* 	Comment Styling
  /*----------------------------------------------------------------------------------- */
if (!function_exists('imic_comment')) {
    function imic_comment($comment, $args, $depth)
    {
        $isByAuthor = false;
        if ($comment->comment_author_email == get_the_author_meta('email')) {
            $isByAuthor = true;
        }
        $GLOBALS['comment'] = $comment;
        ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
            <div class="post-comment-block">
                <div id="comment-<?php comment_ID(); ?>">
                    <div class="img-thumbnail"><?php echo get_avatar($comment, $size = '40'); ?></div>
                    <?php
                    echo preg_replace('/comment-reply-link/', 'comment-reply-link btn btn-primary btn-sm float-end', get_comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => esc_html__('Reply', 'framework')))), 1);
                    echo '<h5>' . get_comment_author() . esc_html__(' says', 'framework') . '</h5>';
                    ?>
                    <span class="meta-data">
                        <?php
                        echo get_comment_date();
                        esc_html_e(' at ', 'framework');
                        echo get_comment_time();
                        ?>
                    </span>
                    <?php if ($comment->comment_approved == '0') : ?>
                        <em class="moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'framework') ?></em>
                        <br />
                    <?php endif; ?>
                    <?php comment_text() ?>
                </div>
            </div>
        <?php
    }
}
if (!function_exists('imic_get_data_by_path')) {
    function imic_get_data_by_path($id, $imic_custom_read_more)
    {
        $slug_data = get_post($id);
        $post_type = get_post_type($id);
        $slug_thumbnail_id = get_post_meta($id, '_thumbnail_id', 'true');
        $src = wp_get_attachment_image_src($slug_thumbnail_id, 'full');
        $read_More_text = !empty($imic_custom_read_more) ? $imic_custom_read_more : $slug_data->post_title;
        if (!empty($slug_thumbnail_id)) {
            echo '<div class="col-md-4 col-sm-4 featured-block">';
            if ($post_type == 'event') {
                $customeventSt = strtotime(get_post_meta($id, 'imic_event_start_dt', true));
                $date_converted = date_i18n('Y-m-d', $customeventSt);
                $custom_event_url = imic_query_arg($date_converted, $slug_data->ID);
            } else {
                $custom_event_url = get_permalink($slug_data->ID);
            }
            echo '<a href="' . $custom_event_url . '" class="img-thumbnail"> <img src="' . $src[0] . '" alt="' . $slug_data->post_title . '"> <strong>' . $read_More_text . '</strong> <span class="more">' . esc_html__('read more', 'framework') . '</span> </a> </div>';
        }
    }
}
// One-time staff social icon migration — already executed, removed to avoid
// querying all staff posts + get_option('run_once') on every page load.
function imic_social_staff_icon()
{
    $output = '';
    $staff_icons = get_post_meta(get_the_ID(), 'imic_social_icon_list', false);
    if (!empty($staff_icons[0]) || get_post_meta(get_the_ID(), 'imic_staff_member_email', true) != '' || get_post_meta(get_the_ID(), 'imic_staff_member_phone', true) != '') {
        $output .= '<nav class="social-icons">';
        if (!empty($staff_icons[0])) {
            foreach ($staff_icons[0] as $list => $values) {
                if (!empty($values[1])) {
                    $className = preg_replace('/\s+/', '-', strtolower($values[0]));
                    $className = 'fa fa-' . $className;
                    $output .= '<a href="' . $values[1] . '" target ="_blank"><i class="' . $className . '"></i></a>';
                }
            }
        }
        if (get_post_meta(get_the_ID(), 'imic_staff_member_email', true) != '') {
            $output .= '<a href="mailto:' . get_post_meta(get_the_ID(), 'imic_staff_member_email', true) . '"><i class="fa fa-envelope"></i></a>';
        }
        if (get_post_meta(get_the_ID(), 'imic_staff_member_phone', true) != '') {
            $output .= '<span style="width:auto; background:none; color:#777; margin-top:10px; font-size:16px; display:block; text-align:left; width:100%"><i class="fa fa-phone"></i> ' . get_post_meta(get_the_ID(), 'imic_staff_member_phone', true) . '</span>';
        }
        $output .= '</nav>';
    }
    return $output;
}

// Clear saved events cache only when an event is created/updated/deleted
function nativechurch_events_reset_again($post_id){
    if (get_post_type($post_id) !== 'event') {
        return;
    }
    $site_lang = substr(get_locale(), 0, 2);
    update_option('nativechurch_saved_future_events_' . $site_lang, '');
    update_option('nativechurch_saved_past_events_' . $site_lang, '');
}
add_action('save_post', 'nativechurch_events_reset_again');
add_action('delete_post', 'nativechurch_events_reset_again');

//Remove Redux Framework Notices
add_action('admin_menu', 'imic_remove_redux_menu', 12);
function imic_remove_redux_menu()
{
    remove_submenu_page('tools.php', 'redux-about');
}

// Fix for sermon archive page pagination
add_rewrite_rule('^sermons/page/([0-9]+)', 'index.php?pagename=sermons&paged=$matches[1]', 'top');
// Native lazy loading is now enabled (WordPress 5.5+ default).

// Disabled Pro plugins licensing requirement
remove_action( 'admin_init', 'disallowed_eventer_admin_pages' );
remove_action( 'admin_init', 'disallowed_isermons_admin_pages' );

// Custom Elementor Widgets Registration
function register_ncelementor_widget( $widgets_manager ) {

	require_once(NATIVECHURCH_INC_PATH . '/elementor/widgets/class-eventer.php');
	$widgets_manager->register( new \Elementor_Eventer_Widget() );

	require_once(NATIVECHURCH_INC_PATH . '/elementor/widgets/class-isermons.php');
	$widgets_manager->register( new \Elementor_Isermons_Widget() );

}
add_action( 'elementor/widgets/register', 'register_ncelementor_widget' );


?>