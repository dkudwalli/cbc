<?php
defined('ABSPATH') or die('No script kiddies please!');
/* ==================================================
  iSermons Post Type Functions
  ================================================== */
if (!defined('ABSPATH'))
  exit; // Exit if accessed directly
add_action('init', 'isermons_admin_register_post_type', 0);
function isermons_admin_register_post_type()
{

  //Getting isermons custom post type custom slug from isermons permalinks settings page
  $sermon_permalinks = isermons_get_settings('isermons_sermons_permalink');
  $sermon_permalinks = empty($sermon_permalinks) ? 'imi_sermon' : $sermon_permalinks;
  $sermon_taxonomy_categories = (empty(isermons_get_settings('isermons_taxonomy_categories'))) ? array() : isermons_get_settings('isermons_taxonomy_categories');
  $sermon_taxonomy_topics = (empty(isermons_get_settings('isermons_taxonomy_topics'))) ? array() : isermons_get_settings('isermons_taxonomy_topics');
  $sermon_taxonomy_books = (empty(isermons_get_settings('isermons_taxonomy_books'))) ? array() : isermons_get_settings('isermons_taxonomy_books');
  $sermon_taxonomy_series = (empty(isermons_get_settings('isermons_taxonomy_series'))) ? array() : isermons_get_settings('isermons_taxonomy_series');
  $sermon_taxonomy_preachers = (empty(isermons_get_settings('isermons_taxonomy_preachers'))) ? array() : isermons_get_settings('isermons_taxonomy_preachers');
  $sermon_taxonomies = array('categories', 'topics', 'books', 'series', 'preachers');
  //Custom slug for category
  $sermon_category_permalinks = isermons_get_settings('isermons_sermons_category_permalink');
  $sermon_category_permalinks = empty($sermon_category_permalinks) ? 'imi_sermon-category' : $sermon_category_permalinks;
  //Custom slug for series
  $sermon_series_permalinks = isermons_get_settings('isermons_sermons_series_permalink');
  $sermon_series_permalinks = empty($sermon_series_permalinks) ? 'imi_sermon-series' : $sermon_series_permalinks;
  //Custom slug for books
  $sermon_books_permalinks = isermons_get_settings('isermons_sermons_books_permalink');
  $sermon_books_permalinks = empty($sermon_books_permalinks) ? 'imi_sermon-books' : $sermon_books_permalinks;
  //Custom slug for topics
  $sermon_topics_permalinks = isermons_get_settings('isermons_sermons_topics_permalink');
  $sermon_topics_permalinks = empty($sermon_topics_permalinks) ? 'imi_sermon-topics' : $sermon_topics_permalinks;
  //Custom slug for preachers
  $sermon_preachers_permalinks = isermons_get_settings('isermons_sermons_preachers_permalink');
  $sermon_preachers_permalinks = empty($sermon_preachers_permalinks) ? 'imi_sermon-preachers' : $sermon_preachers_permalinks;

  $sermon_archive_switch = isermons_get_settings('isermons_sermons_archive_switch');
  $sermon_archive_set = (empty($sermon_archive_switch) || $sermon_archive_switch == 'on') ? true : false;

  $labels = array(
    'name' => esc_html__('iSermons', 'isermons'),
    'singular_name' => esc_html__('Sermon', 'isermons'),
    'add_new' => esc_html__('Add New', 'isermons'),
    'all_items' => esc_html__('All Sermons', 'isermons'),
    'add_new_item' => esc_html__('Add New', 'isermons'),
    'edit_item' => esc_html__('Edit', 'isermons'),
    'new_item' => esc_html__('New', 'isermons'),
    'view_item' => esc_html__('View', 'isermons'),
    'search_items' => esc_html__('Search', 'isermons'),
    'not_found' => esc_html__('Nothing found', 'isermons'),
    'not_found_in_trash' => esc_html__('Nothing found in Trash', 'isermons'),
    'parent_item_colon' => '',
  );
  $args_categories = array(
    "label" => esc_html__('Categories', 'isermons'),
    "singular_label" => esc_html__('Categroy', "isermons"),
    'hierarchical' => (in_array('hierarchical', $sermon_taxonomy_categories)) ? true : false,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_rest'       => true,
    'rest_base'          => 'imi_isermons-categories',
    'rewrite' => array(
      'slug' => untrailingslashit($sermon_category_permalinks),
      'with_front' => false,
      'ep_mask' => EP_ALL,
      'feeds' => true
    ),
    'query_var' => true,
    'show_admin_column' => (in_array('column', $sermon_taxonomy_categories)) ? true : false,
  );

  $labels_series = array(
    'name'              => esc_html__('Series', 'isermons'),
    'singular_name'     => esc_html__('Series', 'isermons'),
    'search_items'      => esc_html__('Search Series', 'isermons'),
    'all_items'         => esc_html__('All Series', 'isermons'),
    'parent_item'       => esc_html__('Parent Series', 'isermons'),
    'parent_item_colon' => esc_html__('Parent Series:', 'isermons'),
    'edit_item'         => esc_html__('Edit Series', 'isermons'),
    'update_item'       => esc_html__('Update Series', 'isermons'),
    'add_new_item'      => esc_html__('Add New Series', 'isermons'),
    'new_item_name'     => esc_html__('New Series Name', 'isermons'),
    'menu_name'         => esc_html__('Series', 'isermons'),
  );
  $args_series = array(
    "labels" => $labels_series,
    "singular_label" => esc_html__('Series', "isermons"),
    'public' => true,
    'hierarchical' => (in_array('hierarchical', $sermon_taxonomy_series)) ? true : false,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_rest'       => true,
    'rest_base'          => 'imi_isermons-series',
    'rewrite' => array(
      'slug' => untrailingslashit($sermon_series_permalinks),
      'with_front' => false,
      'ep_mask' => EP_ALL,
      'feeds' => true
    ),
    'query_var' => true,
    'show_admin_column' => (in_array('column', $sermon_taxonomy_series)) ? true : false,
  );

  $labels_books = array(
    'name'              => esc_html__('Books', 'isermons'),
    'singular_name'     => esc_html__('Book', 'isermons'),
    'search_items'      => esc_html__('Search Books', 'isermons'),
    'all_items'         => esc_html__('All Books', 'isermons'),
    'parent_item'       => esc_html__('Parent Book', 'isermons'),
    'parent_item_colon' => esc_html__('Parent Book:', 'isermons'),
    'edit_item'         => esc_html__('Edit Books', 'isermons'),
    'update_item'       => esc_html__('Update Books', 'isermons'),
    'add_new_item'      => esc_html__('Add New Book', 'isermons'),
    'new_item_name'     => esc_html__('New Book Name', 'isermons'),
    'menu_name'         => esc_html__('Books', 'isermons'),
  );
  $args_books = array(
    "labels" => $labels_books,
    "singular_label" => esc_html__('Book', "isermons"),
    'public' => true,
    'hierarchical' => (in_array('hierarchical', $sermon_taxonomy_books)) ? true : false,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_rest'       => true,
    'rest_base'          => 'imi_isermons-books',
    'rewrite' => array(
      'slug' => untrailingslashit($sermon_books_permalinks),
      'with_front' => false,
      'ep_mask' => EP_ALL,
      'feeds' => true
    ),
    'query_var' => true,
    'show_admin_column' => (in_array('column', $sermon_taxonomy_books)) ? true : false,
  );

  $labels_topics = array(
    'name'              => esc_html__('Topics', 'isermons'),
    'singular_name'     => esc_html__('Topic', 'isermons'),
    'search_items'      => esc_html__('Search Topics', 'isermons'),
    'all_items'         => esc_html__('All Topics', 'isermons'),
    'parent_item'       => esc_html__('Parent Topic', 'isermons'),
    'parent_item_colon' => esc_html__('Parent Topic:', 'isermons'),
    'edit_item'         => esc_html__('Edit Topic', 'isermons'),
    'update_item'       => esc_html__('Update Topic', 'isermons'),
    'add_new_item'      => esc_html__('Add New Topic', 'isermons'),
    'new_item_name'     => esc_html__('New Topic Name', 'isermons'),
    'menu_name'         => esc_html__('Topics', 'isermons'),
  );
  $args_topics = array(
    "labels" => $labels_topics,
    "singular_label" => esc_html__('Topic', "isermons"),
    'public' => true,
    'hierarchical' => (in_array('hierarchical', $sermon_taxonomy_topics)) ? true : false,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_rest'       => true,
    'rest_base'          => 'imi_isermons-topics',
    'rewrite' => array(
      'slug' => untrailingslashit($sermon_topics_permalinks),
      'with_front' => false,
      'ep_mask' => EP_ALL,
      'feeds' => true
    ),
    'query_var' => true,
    'show_admin_column' => (in_array('column', $sermon_taxonomy_topics)) ? true : false,
  );

  $labels_preachers = array(
    'name'              => esc_html__('Preachers', 'isermons'),
    'singular_name'     => esc_html__('Preacher', 'isermons'),
    'search_items'      => esc_html__('Search Preachers', 'isermons'),
    'all_items'         => esc_html__('All Preachers', 'isermons'),
    'parent_item'       => esc_html__('Parent Preacher', 'isermons'),
    'parent_item_colon' => esc_html__('Parent Preacher:', 'isermons'),
    'edit_item'         => esc_html__('Edit Preachers', 'isermons'),
    'update_item'       => esc_html__('Update Preachers', 'isermons'),
    'add_new_item'      => esc_html__('Add New Preacher', 'isermons'),
    'new_item_name'     => esc_html__('New Preacher Name', 'isermons'),
    'menu_name'         => esc_html__('Preachers', 'isermons'),
  );
  $args_preachers = array(
    "labels" => $labels_preachers,
    "singular_label" => esc_html__('Preacher', "isermons"),
    'public' => true,
    'hierarchical' => (in_array('hierarchical', $sermon_taxonomy_preachers)) ? true : false,
    'show_ui' => true,
    'show_in_nav_menus' => true,
    'show_in_rest'       => true,
    'rest_base'          => 'imi_isermons-preachers',
    'rewrite' => array(
      'slug' => untrailingslashit($sermon_preachers_permalinks),
      'with_front' => false,
      'ep_mask' => EP_ALL,
      'feeds' => true
    ),
    'query_var' => true,
    'show_admin_column' => (in_array('column', $sermon_taxonomy_preachers)) ? true : false,
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'hierarchical' => false,
    'rewrite' => array(
      'slug' => untrailingslashit($sermon_permalinks),
      'with_front' => false,
      'feeds' => true
    ),
    'show_in_rest'       => true,
    'rest_base'          => 'imi_isermons',
    'rest_controller_class' => 'WP_REST_Posts_Controller',
    'supports' => array('title', 'thumbnail', 'editor', 'author', 'excerpt'),
    'has_archive' => $sermon_archive_set,
    'menu_icon' => 'dashicons-book-alt',
  );
  register_post_type('imi_isermons', $args);
  foreach ($sermon_taxonomies as $taxonomy) {
    if (!in_array($taxonomy, ${'sermon_taxonomy_' . $taxonomy})) continue;
    $tax_args = ${'args_' . $taxonomy};
    register_taxonomy('imi_isermons-' . $taxonomy, 'imi_isermons', $tax_args);
    register_taxonomy_for_object_type('imi_isermons-' . $taxonomy, 'imi_isermons');
  }

  $rewrite_rules_must_be_fluhed = true;
  $rewrite_rules = get_option('rewrite_rules');
  if ($rewrite_rules) {
    foreach ($rewrite_rules  as $key => $rule) :
      if (strpos($key, 'imi_sermon') === 0) {
        $rewrite_rules_must_be_fluhed = false;
        break;
      }
    endforeach;
    if ($rewrite_rules_must_be_fluhed) {
      flush_rewrite_rules(true);
    }
  }
}
