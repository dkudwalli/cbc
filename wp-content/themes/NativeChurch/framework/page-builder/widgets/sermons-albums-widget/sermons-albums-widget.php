<?php

/*
Widget Name: Sermons Albums Widget
Description: A widget to show categories/albums.
Author: imithemes
Author URI: http://imithemes.com
*/

class Sermons_Albums_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'sermons-albums-widget',
			__('Sermons Albums Widget', 'framework'),
			array(
				'description' => __('A widget to show sermon categories/albums.', 'framework'),
				'panels_icon' => 'dashicons dashicons-category',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'orderby' => array(
					'type' => 'select',
					'label' => __('Select Orderby', 'framework'),
					'description' => __('Select how you want to show albums by. Default is by count', 'framework'),
					'state_name' => 'count',
					'prompt' => __( 'Order By', 'framework' ),
					'options' => array(
						'count' => __( 'Count', 'framework' ),
						'ID' => __( 'ID', 'framework' ),
						'name' => __( 'Name', 'framework' ),
						'slug' => __( 'Slug', 'framework' ),
						'include' => __( 'Custom', 'framework' ),
					)
				),
				'custom_categories' => array(
					'type' => 'text',
					'label' => __( 'Sermon Categories', 'framework'),
					'description' => __('Enter comma separated IDs of your sermons categories to add and show in the widget ', 'framework')
				),
				'sortby' => array(
					'type' => 'select',
					'label' => __('Select Sortby', 'framework'),
					'description' => __('Select how you want to sort albums. Default is by ASC', 'framework'),
					'state_name' => 'ASC',
					'prompt' => __( 'Sort By', 'framework' ),
					'options' => array(
						'ASC' => __( 'Ascending', 'framework' ),
						'DESC' => __( 'Descending', 'framework' ),
					)
				),
				'number_of_posts' => array(
					'type' => 'slider',
					'label' => __( 'Number of Albumns to show', 'framework' ),
					'default' => 4,
					'min' => 1,
					'max' => 250,
					'integer' => true,
				),
				'show_post_meta' => array(
					'type' => 'checkbox',
					'default' => true,
					'label' => __('Show media(audio/video) count?', 'framework'),
				),
				'excerpt_length' => array(
					'type' => 'text',
					'default' => 50,
					'label' => __('Length of excerpt(Enter the number of words to show)? Leave blank to hide - Default is: 50', 'framework'),
				),
				'read_more_text' => array(
					'type' => 'text',
					'default' => 'Play',
					'label' => __('Play button text, Leave blank to hide button - Default is Play', 'framework'),
				),
				'layout_type'    => array(
					'type'    => 'radio',
					'default' => 'list',
					'label'   => __( 'Layout Type', 'framework' ),
					'options' => array(
						'list' => __( 'List View', 'framework' ),
						'grid'      => __( 'Grid View', 'framework' ),
						)
				),
			),
			plugin_dir_path(__FILE__)
		);
	}


	
	function get_template_name( $instance ) {
		return $instance['layout_type'] == 'list' ? 'list-view' : 'grid-view';
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

siteorigin_widget_register('sermons-albums-widget', __FILE__, 'Sermons_Albums_Widget');