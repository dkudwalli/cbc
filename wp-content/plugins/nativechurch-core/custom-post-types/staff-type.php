<?php
/* ==================================================
  Staff Post Type Functions
  ================================================== */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
add_action('init', 'staff_register');
function staff_register() {
    // Permalink Structure Options
    $options = get_option('imic_options');
    $staff_post_slug = (isset($options['staff_post_slug'])) ? $options['staff_post_slug'] : '';
    if ($staff_post_slug == '') {
        $staff_post_slug = 'staff';
    }
    $staff_post_title = (isset($options['staff_post_title'])) ? $options['staff_post_title'] : '';
    if ($staff_post_title == '') {
        $staff_post_title = 'Staff';
    }
    $staff_post_singular = (isset($options['staff_post_singular'])) ? $options['staff_post_singular'] : '';
    if ($staff_post_singular == '') {
        $staff_post_singular = 'Team Member';
    }
    $staff_post_categories = (isset($options['staff_post_categories'])) ? $options['staff_post_categories'] : '';
    if ($staff_post_categories == '') {
        if ($staff_post_title != '') {
            $staff_post_categories = $staff_post_title . ' Category';
        } else {
            $staff_post_categories = 'Staff Categories';
        }
    }
    $staff_category_slug = (isset($options['staff_category_slug'])) ? $options['staff_category_slug'] : '';
    if ($staff_category_slug == '') {
        $staff_category_slug = 'staff-category';
    }
    $staff_post_icon = (isset($options['staff_post_icon'])) ? $options['staff_post_icon'] : '';
    if ($staff_post_icon == '') {
        $staff_post_icon = 'dashicons-businessman';
    }
    $staff_post_compatibility = (isset($options['staff_post_compatibility'])) ? $options['staff_post_compatibility'] : 'page';
    if ($staff_post_icon == '') {
        $staff_post_icon = 'page';
    }
    $disable_staff_archive = (isset($options['disable_staff_archive'])) ? $options['disable_staff_archive'] : 0;
    $staff_archive = $disable_staff_archive ? false : true;
    
    $args_c = array(
        "label" => $staff_post_categories,
        "singular_label" => $staff_post_title . esc_html__('Staff Category', "imithemes"),
        'public' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'rewrite' => array(
            'slug' => $staff_category_slug,
            'with_front' => false
        ),
        'query_var' => true,
	   'show_admin_column' => true,
);
register_taxonomy('staff-category', 'staff', $args_c);
    $labels = array(
        'name' => $staff_post_title,
        'singular_name' => $staff_post_singular,
        'all_items'=> $staff_post_title,
        'add_new' => esc_html__('Add New ', 'imithemes'). $staff_post_singular,
        'add_new_item' => esc_html__('Add New ', 'imithemes'). $staff_post_singular,
        'edit_item' => esc_html__('Edit ', 'imithemes').$staff_post_singular,
        'new_item' => esc_html__('New ', 'imithemes').$staff_post_singular,
        'view_item' => esc_html__('View ', 'imithemes').$staff_post_singular,
        'search_items' => esc_html__('Search ', 'imithemes').$staff_post_title,
        'not_found' => esc_html__('Nothing added yet', 'imithemes'),
        'not_found_in_trash' => esc_html__('Nothing found in Trash', 'imithemes'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
		'capability_type' => $staff_post_compatibility,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
		'rewrite' => array(
			'slug' => $staff_post_slug,
			'with_front' => false
		),
        'supports' => array('title', 'editor', 'thumbnail', 'page-attributes','excerpt', 'author'),
		'menu_icon' => $staff_post_icon,
        'has_archive' => $staff_archive,
    );
    register_post_type('staff', $args);
}
?>