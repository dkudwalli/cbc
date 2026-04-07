<?php

/*
Widget Name: Staff Grid Widget
Description: A widget to show Staff members in grid view.
Author: imithemes
Author URI: http://imithemes.com
*/

class Staff_Grid_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'staff-grid-widget',
			__('Staff Grid Widget', 'framework'),
			array(
				'description' => esc_html__('A widget to show Staff members in grid view.', 'framework'),
				'panels_icon' => 'dashicons dashicons-format-gallery',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'title' => array(
					'type' => 'text',
					'label' => esc_html__('Title', 'framework'),
				),

				'allpostsbtn' => array(
					'type' => 'text',
					'label' => esc_html__('All staff button text', 'framework'),
					'default' => esc_html__('All Staff', 'framework'),
					'description' => esc_html__('This button will be displayed only if the widget has title.', 'framework'),
				),

				'allpostsurl' => array(
					'type' => 'link',
					'label' => esc_html__('All staff button URL', 'framework'),
					'description' => esc_html__('This button will be displayed only if the widget has title.', 'framework'),
				),

				'categories' => array(
					'type' => 'text',
					'label' => esc_html__('Categories (Enter comma separated sermon category slugs)', 'framework'),
				),
				'orderby' => array(
					'type' => 'select',
					'label' => esc_html__('Order staff posts by', 'framework'),
					'state_name' => 'no',
					'prompt' => esc_html__( 'Orderby', 'framework' ),
					'options' => array(
                    	'ID' => esc_html__('ID', 'framework'),
                    	'title' => esc_html__('Title', 'framework'),
                    	'date' => esc_html__('Date', 'framework'),
                    	'menu_order' => esc_html__('Menu Order', 'framework'),
					)
				),
				'sortorder' => array(
					'type' => 'select',
					'label' => esc_html__('Order staff posts', 'framework'),
					'state_name' => 'no',
					'prompt' => esc_html__( 'Order', 'framework' ),
					'options' => array(
                    	'ASC' => esc_html__('Ascending', 'framework'),
                    	'DESC' => esc_html__('Descending', 'framework'),
					)
				),
				'number_of_posts' => array(
					'type' => 'slider',
					'label' => __( 'Number of Staff Members to show', 'framework' ),
					'default' => 3,
					'min' => 1,
					'max' => 250,
					'integer' => true,
				),
				'show_post_meta' => array(
					'type' => 'checkbox',
					'default' => false,
					'label' => __('Show social profile icons, member role/position', 'framework'),
				),
				'excerpt_length' => array(
					'type' => 'text',
					'default' => 50,
					'label' => __('Length of excerpt(Enter the number of words to show)? Leave blank to hide - Default is: 50', 'framework'),
				),
				'read_more_text' => array(
					'type' => 'text',
					'default' => 'Read More',
					'label' => __('Read More, Leave blank to hide button - Default is Read More', 'framework'),
				),
				'grid_column' => array(
					'type' => 'select',
					'state_name' => 'grid',
					'prompt' => __( 'Choose Grid Column', 'framework' ),
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
		return 'grid-view';
	}

	function get_style_name($instance) {
		return false;
	}

	function get_less_variables($instance){
		return false;
	}


}

siteorigin_widget_register('staff-grid-widget', __FILE__, 'Staff_Grid_Widget');