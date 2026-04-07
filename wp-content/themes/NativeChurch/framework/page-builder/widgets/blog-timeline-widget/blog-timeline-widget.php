<?php

/*
Widget Name: Blog Timeline Widget
Description: A widget to show blog item timeline view.
Author: imithemes
Author URI: http://imithemes.com
*/

class Blog_Timeline_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'blog-timeline-widget',
			__('Blog Timeline Widget', 'framework'),
			array(
				'description' => __('A widget to show blog item timeline view.', 'framework'),
				'panels_icon' => 'dashicons dashicons-format-gallery',
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
					'default' => __('All posts', 'framework'),
					'description' => __('This button will be displayed only if the widget has title.', 'framework'),
				),

				'allpostsurl' => array(
					'type' => 'link',
					'label' => __('All posts button URL', 'framework'),
					'description' => __('This button will be displayed only if the widget has title.', 'framework'),
				),

				'categories' => array(
					'type' => 'text',
					'label' => __('Categories (Enter comma separated sermon category slugs)', 'framework'),
				),
				'number_of_posts' => array(
					'type' => 'slider',
					'label' => __( 'Number of Gallery Items to show', 'framework' ),
					'default' => 4,
					'min' => 1,
					'max' => 250,
					'integer' => true,
				),
		        'gallery_page_pagination' => array(
                    'label' => __('Enabled/Disable Pagination', 'framework'),
                    'desc' => __("Select Enabled to active Pagination.", 'framework'),
                    'type' => 'select',
                    'options' => array(
		                '1' => __('Enable', 'framework'),
		                '0' => __('Disable','framework'),
                                       ),
	                'std' => 0,
                ),
				'show_post_meta' => array(
					'type' => 'checkbox',
					'default' => false,
					'label' => __('Show gallery item titles', 'framework'),
				),
				'filters' => array(
					'type' => 'checkbox',
					'default' => false,
					'label' => __('Show categories filter', 'framework'),
				),
				'grid_column' => array(
					'type' => 'select',
					'state_name' => 'grid',
					'label' => __( 'Choose Grid Column', 'framework' ),
					'options' => array(
						'12' => __( 'One', 'framework' ),
						'6' => __( 'Two', 'framework' ),
						'4' => __( 'Three', 'framework' ),
						'3' => __( 'Four', 'framework' ),
					)
				),
				
			),
			plugin_dir_path(__FILE__)
		);
	}


	
	function get_template_name( $instance ) {
		return 'template-blog-timeline';
	}

	function get_style_name($instance) {
		return false;
	}

	function get_less_variables($instance){
		return false;
	}


}

siteorigin_widget_register('blog-timeline-widget', __FILE__, 'Blog_Timeline_Widget');