<?php
/* ==================================================
  Gallery Post Type Functions
  ================================================== */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
add_action('init', 'gallery_register', 0);
function gallery_register() {
    // Permalink Structure Options
    $options = get_option('imic_options');
    $gallery_post_slug = (isset($options['gallery_post_slug'])) ? $options['gallery_post_slug'] : '';
    if ($gallery_post_slug == '') {
        $gallery_post_slug = 'gallery';
    }
    $gallery_post_title = (isset($options['gallery_post_title'])) ? $options['gallery_post_title'] : '';
    if ($gallery_post_title == '') {
        $gallery_post_title = 'Gallery';
    }
    $gallery_post_singular = (isset($options['gallery_post_singular'])) ? $options['gallery_post_singular'] : '';
    if ($gallery_post_singular == '') {
        $gallery_post_singular = 'Gallery Item';
    }
    $gallery_post_categories = (isset($options['gallery_post_categories'])) ? $options['gallery_post_categories'] : '';
    if ($gallery_post_categories == '') {
        if ($gallery_post_title != '') {
            $gallery_post_categories = $gallery_post_title . ' Category';
        } else {
            $gallery_post_categories = 'Gallery Categories';
        }
    }
    $gallery_category_slug = (isset($options['gallery_category_slug'])) ? $options['gallery_category_slug'] : '';
    if ($gallery_category_slug == '') {
        $gallery_category_slug = 'gallery-category';
    }
    $gallery_post_icon = (isset($options['gallery_post_icon'])) ? $options['gallery_post_icon'] : '';
    if ($gallery_post_icon == '') {
        $gallery_post_icon = 'dashicons-format-gallery';
    }
    $disable_gallery_archive = (isset($options['disable_gallery_archive'])) ? $options['disable_gallery_archive'] : 0;
    $gallery_archive = $disable_gallery_archive ? false : true;
    
	$args_c = array(
    "label" => $gallery_post_categories,
    "singular_label" => $gallery_post_title . esc_html__(' Category', "imithemes"),
    'public' => true,
    'hierarchical' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'rewrite' => array(
        'slug' => $gallery_category_slug,
        'with_front' => false
    ),
    'query_var' => true,
	'show_admin_column' => true,
);
register_taxonomy('gallery-category', 'gallery', $args_c);
    $labels = array(
        'name' => $gallery_post_title,
        'singular_name' => $gallery_post_singular,
        'add_new' => esc_html__('Add New ', 'imithemes').$gallery_post_singular,
        'all_items'=> $gallery_post_title,
        'add_new_item' => esc_html__('Add New ', 'imithemes').$gallery_post_singular,
        'edit_item' => esc_html__('Edit ', 'imithemes').$gallery_post_singular,
        'new_item' => esc_html__('New ', 'imithemes').$gallery_post_singular,
        'view_item' => esc_html__('View ', 'imithemes').$gallery_post_singular,
        'search_items' => esc_html__('Search ', 'imithemes').$gallery_post_title,
        'not_found' => esc_html__('Nothing added yet', 'imithemes'),
        'not_found_in_trash' => esc_html__('Nothing found in Trash', 'imithemes'),
        'parent_item_colon' => '',
    );
   $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'hierarchical' => false,
		'rewrite' => array(
			'slug' => $gallery_post_slug,
			'with_front' => false
		),
        'supports' => array('title', 'thumbnail','post-formats', 'author'),
		'menu_icon' => $gallery_post_icon,
        'has_archive' => $gallery_archive,
       );
    register_post_type('gallery', $args);
	register_taxonomy_for_object_type('gallery-category','gallery');
}
?>