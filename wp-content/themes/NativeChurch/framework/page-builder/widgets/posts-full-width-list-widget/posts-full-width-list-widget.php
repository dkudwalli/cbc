<?php

/*
Widget Name: Posts Full Width List Widget
Description: A widget to show posts list/grid view.
Author: imithemes
Author URI: http://imithemes.com
*/

class Posts_Full_Width_List_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'posts-full-width-list-widget',
			__('Posts Full Width List Widget', 'framework'),
			array(
				'description' => __('A widget to show posts in full width.', 'framework'),
				'panels_icon' => 'dashicons dashicons-list-view',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'title' => array(
					'type' => 'text',
					'label' => __('Title', 'framework'),
				),

				'allpostsbtn' => array(
					'type' => 'text',
					'label' => __('All posts button text', 'framework'),
					'default' => __('All Posts', 'framework'),
					'description' => __('This button will be displayed only if the widget has title.', 'framework'),
				),

				'allpostsurl' => array(
					'type' => 'link',
					'label' => __('All posts button URL', 'framework'),
					'description' => __('This button will be displayed only if the widget has title.', 'framework'),
				),

				'categories' => array(
					'type' => 'text',
					'label' => __('Categories (Enter comma separated post category slugs)', 'framework'),
				),
				'number_of_posts' => array(
					'type' => 'slider',
					'label' => __( 'Number of Posts to show', 'framework' ),
					'default' => 4,
					'min' => 1,
					'max' => 250,
					'integer' => true,
				),
				'show_post_meta' => array(
					'type' => 'checkbox',
					'default' => true,
					'label' => __('Show post meta like post date, author, categories, comments?', 'framework'),
				),
				'excerpt_length' => array(
					'type' => 'text',
					'default' => 50,
					'label' => __('Length of excerpt(Enter the number of words to show)? Leave blank to hide - Default is: 50', 'framework'),
				),
				'read_more_text' => array(
					'type' => 'text',
					'default' => 'Continue reading',
					'label' => __('Continue reading button text, Leave blank to hide button - Default is Continue Reading', 'framework'),
				),
			),
			plugin_dir_path(__FILE__)
		);
	}


	function get_template_name( $instance ) {
		return 'list-view';
	}

	function get_style_name($instance) {
		return false;
	}

	function get_less_variables($instance){
		return array();
	}
	function modify_instance($instance){
		return $instance;
	}


}

siteorigin_widget_register('posts-full-width-list-widget', __FILE__, 'Posts_Full_Width_List_Widget');