<?php

/*
Widget Name: Event Grid Minimal Style Widget
Description: A widget to show list of events in a compact way.
Author: imithemes
Author URI: http://imithemes.com
*/

class Event_Grid_Minimal_Style_Widget extends SiteOrigin_Widget {
	function __construct() {

		parent::__construct(
			'event-grid-minimal-style-widget',
			__('Event Grid Minimal Style Widget', 'framework'),
			array(
				'description' => __('A widget to show upcoming events in a grid style.', 'framework'),
				'panels_icon' => 'dashicons dashicons-testimonial',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'categories' => array(
					'type' => 'text',
					'label' => __('Event Categories (Enter comma separated events category slugs)', 'framework'),
				),
				'title' => array(
					'type' => 'text',
					'label' => __('Widget Title', 'framework'),
					'default' => 'More coming events'
				),

				'allpostsurl' => array(
					'type' => 'link',
					'label' => __('All events button URL', 'framework'),
					'description' => __('This button will be displayed only if the widget has title.', 'framework'),
				),

				'allpostsbtn' => array(
					'type' => 'text',
					'label' => __('All events button text', 'framework'),
					'default' => __('All Events', 'framework'),
				),
				'number_of_events' => array(
					'type' => 'slider',
					'label' => __( 'Number of Events to show', 'framework' ),
					'default' => 4,
					'min' => 1,
					'max' => 250,
					'integer' => true
				),
				'date_type' => array(
					'type' => 'checkbox',
					'default' => false,
					'label' => __('Show event date as Date format set at Settings => General ', 'framework'),
				),
			),
			plugin_dir_path(__FILE__)
		);
	}

	function get_template_name($instance) {
		return 'event-grid-minimal-style-widget-template';
	}

	function get_style_name($instance) {
		return false;
	}

}

siteorigin_widget_register('event-grid-minimal-style-widget', __FILE__, 'Event_Grid_Minimal_Style_Widget');