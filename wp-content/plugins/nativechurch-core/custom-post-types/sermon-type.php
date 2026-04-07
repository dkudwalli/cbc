<?php
/* ==================================================
  Sermons Post Type Functions
  ================================================== */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
add_action('init', 'sermons_register', 0);
function sermons_register() {
    // Permalink Structure Options
    $options = get_option('imic_options');
    $sermon_post_slug = (isset($options['sermon_post_slug'])) ? $options['sermon_post_slug'] : '';
    if ($sermon_post_slug == '') {
        $sermon_post_slug = 'sermons';
    }
    $sermon_post_title = (isset($options['sermon_post_title'])) ? $options['sermon_post_title'] : '';
    if ($sermon_post_title == '') {
        $sermon_post_title = 'Sermons';
    }
    $sermon_post_singular = (isset($options['sermon_post_singular'])) ? $options['sermon_post_singular'] : '';
    if ($sermon_post_singular == '') {
        $sermon_post_singular = 'Sermon';
    }
    $sermon_post_categories = (isset($options['sermon_post_categories'])) ? $options['sermon_post_categories'] : '';
    if ($sermon_post_categories == '') {
        if ($sermon_post_title != '') {
            $sermon_post_categories = $sermon_post_title . ' Category';
        } else {
            $sermon_post_categories = 'Sermons Categories';
        }
    }
    $sermon_category_slug = (isset($options['sermon_category_slug'])) ? $options['sermon_category_slug'] : '';
    if ($sermon_category_slug == '') {
        $sermon_category_slug = 'sermons-category';
    }
    $sermon_post_tag = (isset($options['sermon_post_tags'])) ? $options['sermon_post_tags'] : '';
    if ($sermon_post_tag == '') {
        if ($sermon_post_title != '') {
            $sermon_post_tag = $sermon_post_title . ' Tags';
        } else {
            $sermon_post_tag = 'Sermons Tags';
        }
    }
    $sermon_tag_slug = (isset($options['sermon_tag_slug'])) ? $options['sermon_tag_slug'] : '';
    if ($sermon_tag_slug == '') {
        $sermon_tag_slug = 'sermons-tag';
    }
    $sermon_post_speaker = (isset($options['sermon_post_speaker'])) ? $options['sermon_post_speaker'] : '';
    if ($sermon_post_speaker == '') {
        if ($sermon_post_title != '') {
            $sermon_post_speaker = $sermon_post_title . ' Speakers';
        } else {
            $sermon_post_speaker = 'Sermons Speakers';
        }
    }
    $sermon_speaker_slug = (isset($options['sermon_speaker_slug'])) ? $options['sermon_speaker_slug'] : '';
    if ($sermon_speaker_slug == '') {
        $sermon_speaker_slug = 'sermons-speakers';
    }
    $sermon_post_icon = (isset($options['sermon_post_icon'])) ? $options['sermon_post_icon'] : '';
    if ($sermon_post_icon == '') {
        $sermon_post_icon = 'dashicons-controls-volumeon';
    }
    $disable_sermon_archive = (isset($options['disable_sermon_archive'])) ? $options['disable_sermon_archive'] : 0;
    $sermon_archive = $disable_sermon_archive ? false : true;
    
    $args_c = array(
    "label" => $sermon_post_categories,
    "singular_label" => $sermon_post_title . esc_html__(' Category','imithemes'),
    'public' => true,
    'hierarchical' => true,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'rewrite' => array(
        'slug' => $sermon_category_slug,
        'with_front' => false
    ),
    'query_var' => true,
    'show_admin_column' => true,
);
register_taxonomy('sermons-category', 'sermons',$args_c);
$args_tag = array(
    "label" => $sermon_post_tag,
    "singular_label" => $sermon_post_title . esc_html__(' Tag','imithemes'),
    'public' => true,
    'hierarchical' => false,
    'show_ui' => true,
    'show_in_nav_menus' => false,
    'rewrite' => array(
        'slug' => $sermon_tag_slug,
        'with_front' => false
    ),
    'query_var' => true,
    'show_admin_column' => true,
);
register_taxonomy('sermons-tag', 'sermons', $args_tag);
$args_sermons_speaker = array(
    "label" => $sermon_post_speaker,
    "singular_label" => $sermon_post_speaker . esc_html__(' Speaker','imithemes'),
    'public' => true,
    'hierarchical' => true,
    'show_ui' => true,
    'show_in_nav_menus' => false,
    'rewrite' => array(
        'slug' => $sermon_speaker_slug,
        'with_front' => false
    ),
    'query_var' => true,
    'show_admin_column' => true,
);
register_taxonomy('sermons-speakers', 'sermons',$args_sermons_speaker);
    $labels = array(
        'name' => $sermon_post_title,
        'singular_name' => $sermon_post_singular,
        'add_new' => esc_html__('Add New ', 'imithemes').$sermon_post_singular,
        'add_new_item' => esc_html__('Add New ', 'imithemes').$sermon_post_singular,
        'edit_item' => esc_html__('Edit ', 'imithemes').$sermon_post_singular,
        'new_item' => esc_html__('New ', 'imithemes').$sermon_post_singular,
        'view_item' => esc_html__('View ', 'imithemes').$sermon_post_singular,
        'search_items' => esc_html__('Search ', 'imithemes').$sermon_post_title,
        'not_found' => esc_html__('Nothing added yet', 'imithemes'),
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
            'slug' => $sermon_post_slug,
            'with_front' => false
        ),
        'supports' => array('title', 'editor', 'thumbnail','comments', 'author'),
		'menu_icon' => $sermon_post_icon,
        'has_archive' => $sermon_archive,
        'taxonomies' => array('sermons-tag','sermons-category','sermons-speakers')
    );
     register_post_type('sermons', $args);
     register_taxonomy_for_object_type('sermons-category','sermons');
     register_taxonomy_for_object_type('sermons-tag','sermons');
     register_taxonomy_for_object_type('sermons-speakers','sermons');
}
?>