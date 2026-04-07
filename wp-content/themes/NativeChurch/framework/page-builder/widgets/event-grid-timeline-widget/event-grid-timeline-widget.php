<?php

/*
Widget Name: imithemes - Event Grid and Timeline Style Widget
Description: A widget to show events in grid and timeline style.
Author: imithemes
Author URI: http://imithemes.com
*/

class Nativechurch_Event_Grid_Timeline_List extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'event-grid-timeline-list-widget',
			esc_html__('imithemes - Event Grid and Timeline Style Widget', 'framework'),
			array(
				'description' => esc_html__('A widget to show events in grid and timeline style.', 'framework'),
				'panels_icon' => 'dashicons dashicons-list-view',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'categories' => array(
					'type' => 'text',
					'label' => __('Event Categories (Enter comma separated events category slugs)', 'framework'),
				),
				'listing_layout' => array(
					'type' => 'section',
					'label' => esc_html__( 'Layout', 'framework' ),
					'hide' => false,
					'description' => esc_html__( 'Choose listing layout.', 'framework' ),
					'fields' => array(
						'layout_type'    => array(
							'type'    => 'radio',
							'default' => 'grid',
							'label'   => esc_html__( 'Layout Type', 'framework' ),
							'options' => array(
								'grid' => esc_html__( 'Grid Style', 'framework' ),
								'timeline'      => esc_html__( 'Timeline Style', 'framework' ),
							),
							'state_emitter' => array(
								'callback' => 'select',
								'args' => array( 'layout_type' )
							),
						),
						'number_of_posts' => array(
							'type' => 'slider',
							'label' => __( 'Number of events to show', 'framework' ),
							'default' => 4,
							'min' => 1,
							'max' => 500,
							'integer' => true,
							'state_name' => 'grid',
							'state_handler' => array(
								'layout_type[grid]' => array('show'),
								'layout_type[timeline]' => array('hide'),
				        	)
						),
						'number_of_posts_timeline' => array(
							'type' => 'slider',
							'state_name' => 'grid',
							'label' => __( 'Number of events to show', 'framework' ),
							'default' => 20,
							'min' => 1,
							'max' => 500,
							'integer' => true,
							'state_handler' => array(
								'layout_type[grid]' => array('hide'),
								'layout_type[timeline]' => array('show'),
				        	)
						),
						
				        'show_pagination' => array(
							'type' => 'checkbox',
							'default' => true,
							'label' => __('Show pagination', 'framework'),
						),
						'grid_column' => array(
							'type' => 'select',
							'state_name' => 'grid',
							'label' => __( 'Choose Grid Column', 'framework' ),
							'options' => array(
								'1' => __( 'One', 'framework' ),
								'2' => __( 'Two', 'framework' ),
								'3' => __( 'Three', 'framework' ),
								'4' => __( 'Four', 'framework' ),
							),
							'state_handler' => array(
								'layout_type[grid]' => array('show'),
								'layout_type[timeline]' => array('hide'),
							),
						),
						'event_type' => array(
							'type' => 'select',
							'state_name' => 'layout_type',
							'label' => __( 'Events Type', 'framework' ),
							'options' => array(
								'future' => __( 'Future', 'framework' ),
								'past' => __( 'Past', 'framework' ),
							),
							'state_handler' => array(
								'layout_type[grid]' => array('hide'),
								'layout_type[timeline]' => array('show'),
							),
						),
					),
				)),
			plugin_dir_path(__FILE__)
		);
	}

	function get_template_name( $instance ) {
		return $instance['listing_layout']['layout_type'] == 'grid' ? 'template-events_grid' : 'template-events-timeline';
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

siteorigin_widget_register('event-grid-timeline-list-widget', __FILE__, 'Nativechurch_Event_Grid_Timeline_List');