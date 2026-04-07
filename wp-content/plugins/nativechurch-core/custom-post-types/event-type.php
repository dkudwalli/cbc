<?php
/* ==================================================
  Event Post Type Functions
  ================================================== */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
add_action('init', 'event_register');
function event_register() {
    // Permalink Structure Options
    $options = get_option('imic_options');
    $event_post_slug = (isset($options['event_post_slug'])) ? $options['event_post_slug'] : '';
    if ($event_post_slug == '') {
        $event_post_slug = 'event';
    }
    $event_post_title = (isset($options['event_post_title'])) ? $options['event_post_title'] : '';
    if ($event_post_title == '') {
        $event_post_title = 'Events';
    }
    $event_post_singular = (isset($options['event_post_singular'])) ? $options['event_post_singular'] : '';
    if ($event_post_singular == '') {
        $event_post_singular = 'Event';
    }
    $event_post_categories = (isset($options['event_post_categories'])) ? $options['event_post_categories'] : '';
    if ($event_post_categories == '') {
        if ($event_post_title != '') {
            $event_post_categories = $event_post_title . ' Category';
        } else {
            $event_post_categories = 'Event Categories';
        }
    }
    global $event_post_payment_label;
    $event_post_payment_label = (isset($options['event_post_payment'])) ? $options['event_post_payment'] : '';
    if ($event_post_payment_label == '') {
        if ($event_post_title != '') {
            $event_post_payment_label = $event_post_title . ' Payments';
        } else {
            $event_post_payment_label = 'Events Payments';
        }
    }
    $event_category_slug = (isset($options['event_category_slug'])) ? $options['event_category_slug'] : '';
    if ($event_category_slug == '') {
        $event_category_slug = 'event-category';
    }
    $event_post_icon = (isset($options['event_post_icon'])) ? $options['event_post_icon'] : '';
    if ($event_post_icon == '') {
        $event_post_icon = 'dashicons-calendar';
    }
    $disable_event_archive = (isset($options['disable_event_archive'])) ? $options['disable_event_archive'] : 0;
    $event_archive = $disable_event_archive ? false : true;
    
    $args_c = array(
    "label" => $event_post_categories,
    "singular_label" => $event_post_title . esc_html__(' Category', "imithemes"),
    'public' => true,
    'hierarchical' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'rewrite' => array(
        'slug' => $event_category_slug,
        'with_front' => false
    ),
    'query_var' => true,
	'show_admin_column' => true,
);
register_taxonomy('event-category', 'event', $args_c);
    $labels = array(
        'name' => $event_post_title,
        'singular_name' => $event_post_singular,
        'add_new' => esc_html__('Add New ', 'imithemes') . $event_post_singular,
        'add_new_item' => esc_html__('Add New ', 'imithemes') . $event_post_singular,
        'edit_item' => esc_html__('Edit ', 'imithemes') . $event_post_singular,
        'new_item' => esc_html__('New ', 'imithemes') . $event_post_singular,
        'view_item' => esc_html__('View ', 'imithemes') . $event_post_singular,
        'search_items' => esc_html__('Search ', 'imithemes') . $event_post_title,
        'not_found' => esc_html__('No added yet', 'imithemes'),
        'not_found_in_trash' => esc_html__('Nothing found in Trash', 'imithemes'),
        'parent_item_colon' => '',
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'hierarchical' => false,
		'rewrite' => array(
			'slug' => $event_post_slug,
			'with_front' => false
		),
        'supports' => array('title', 'editor', 'thumbnail', 'author','excerpt'),
		'menu_icon' => $event_post_icon,
        'has_archive' => $event_archive,
        'taxonomies' => array('event-category'),
	
    );
    register_post_type('event', $args);
    register_taxonomy_for_object_type('event-category','event');
}
?>