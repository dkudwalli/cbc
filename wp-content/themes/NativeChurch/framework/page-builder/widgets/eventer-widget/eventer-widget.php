<?php

/*
Widget Name: Eventer Widget
Description: A widget to add shortcodes of the Eventer Plugin
Author: imithemes
Author URI: https://imithemes.com
*/

class Eventer_Widget extends SiteOrigin_Widget {
	function __construct() {
		
		// Get Eventer Posts List
		$arr = array();
		$arg = array('post_type' => array('eventer'), 'posts_per_page' => -1);
		$events_list = new WP_Query($arg);
		$arr[''] = __( 'Next Upcoming Event', 'framework' );
		if ($events_list->have_posts()) : while ($events_list->have_posts()) : $events_list->the_post();
			$arr[get_the_ID()] = get_the_title();
		  endwhile;
		endif;
		wp_reset_postdata();
		
		// Eventer Categories List
		$event_cat = [];
		$event_tax = get_terms(array('taxonomy'=>'eventer-category',));
		$event_cat[''] = __( 'All', 'framework' );
		if (!is_wp_error($event_tax) && $event_tax) {
		  foreach ($event_tax as $etax) {
			$event_cat[$etax->term_id] = $etax->name;
		  }
		}
		
		// Eventer Tags List
		$event_tag = [];
		$event_tag_tax = get_terms(array('taxonomy'=>'eventer-tag'));
		$event_tag[''] = __( 'All', 'framework' );
		if (!is_wp_error($event_tag_tax) && $event_tag_tax) {
		  foreach ($event_tag_tax as $etag) {
			$event_tag[$etag->term_id] = $etag->name;
		  }
		}
		
		// Eventer Venues List
		$event_venue = [];
		$event_venue_tax = get_terms(array('taxonomy'=>'eventer-venue'));
		$event_venue[''] = __( 'All', 'framework' );
		if (!is_wp_error($event_venue_tax) && $event_venue_tax) {
		  foreach ($event_venue_tax as $evenue) {
			$event_venue[$evenue->term_id] = $evenue->name;
		  }
		}
		
		// Eventer Organisers List
		$event_organiser = [];
		$event_organiser_tax = get_terms(array('taxonomy'=>'eventer-organizer'));
		$event_organiser[''] = __( 'All', 'framework' );
		if (!is_wp_error($event_organiser_tax) && $event_organiser_tax) {
		  foreach ($event_organiser_tax as $eorg) {
			$event_organiser[$eorg->term_id] = $eorg->name;
		  }
		}
		
		parent::__construct(
			'eventer-widget',
			__('Eventer Widget', 'framework'),
			array(
				'description' => __('A widget to add shortcodes of the Eventer Plugin', 'framework'),
				'panels_icon' => 'dashicons dashicons-list-view',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'shortcode_type' => array(
					'type' => 'select',
					'label' => __( 'Shortcode Type', 'framework' ),
					'prompt' => __( 'Choose Type', 'framework' ),
					'options' => array(
						'eventer_counter' => __( 'Upcoming Event Countdown', 'framework' ),
						'eventer_list' => __( 'Events List', 'framework' ),
						'eventer_grid' => __( 'Events Grid', 'framework' ),
						'eventer_slider' => __( 'Events Slider', 'framework' ),
						'eventer_calendar' => __( 'Events Calendar', 'framework' ),
					),
					'state_emitter' => array(
						'callback' => 'select',
						'args' => array( 'shortcode_type' )
					),
					'default' => 'eventer_counter'
				),
				'counter_fields' => array(
					'type' => 'section',
					'label' => __( 'Countdown Shortcode', 'framework' ),
					'state_handler' => array(
						'shortcode_type[eventer_counter]' => array('show'),
						'shortcode_type[eventer_list]' => array('hide'),
						'shortcode_type[eventer_grid]' => array('hide'),
						'shortcode_type[eventer_slider]' => array('hide'),
						'shortcode_type[eventer_calendar]' => array('hide'),
					),
					'fields' => array(
						'counter_ids' => array(
							'type' => 'select',
							'label' => __( 'Select Event', 'framework' ),
							'description' => __('You can select a specific event to show at the upcoming event counter.','framework'),
							'options' => $arr,
							'default' => ''
						),
						'counter_terms_cats' => array(
							'type' => 'select',
							'label' => __( 'Select Event Category', 'framework' ),
							'description' => __('Select event categories from which events will be used in the upcoming event counter.','framework'),
							'options' => $event_cat,
							'default' => ''
						),
						'counter_terms_tags' => array(
							'type' => 'select',
							'label' => __( 'Select Event Tag', 'framework' ),
							'description' => __('Select event tags, from which events will be used in the upcoming event counter.','framework'),
							'options' => $event_tag,
							'default' => ''
						),
						'counter_terms_venues' => array(
							'type' => 'select',
							'label' => __( 'Select Event Venue', 'framework' ),
							'description' => __('Select event venues, from which events will be used in the upcoming event counter.','framework'),
							'options' => $event_venue,
							'default' => ''
						),
						'counter_terms_organizers' => array(
							'type' => 'select',
							'label' => __( 'Select Event Organiser', 'framework' ),
							'description' => __('Select event organisers, from which events will be used in the upcoming event counter.','framework'),
							'options' => $event_organiser,
							'default' => ''
						),
						'counter_venue' => array(
							'type' => 'select',
							'label' => __( 'Show Event Venue?', 'framework' ),
							'description' => __('Select Yes if you want to show your event venue address in the upcoming event counter.','framework'),
							'options' => array(
								'' => __('Yes','framework'),
								'no' => __('No','framework')
							),
							'default' => ''
						),
						'counter_type' => array(
							'type' => 'select',
							'label' => __( 'Event Type', 'framework' ),
							'description' => __('Select which event type you want to show in the upcoming event counter.','framework'),
							'options' => array(
								'1' => __('WP','framework'),
								'2' => __('Google','framework')
							),
							'default' => '1'
						),
						'counter_event_until' => array(
							'type' => 'select',
							'label' => __( 'Show Counter Till', 'framework' ),
							'description' => __('Select till what time an event will be shown in the upcoming event counter.','framework'),
							'options' => array(
								'' => __('Start Time','framework'),
								'2' => __('End Time','framework')
							),
							'default' => ''
						),
					)
				),
				'list_fields' => array(
					'type' => 'section',
					'label' => __( 'List Shortcode', 'framework' ),
					'state_handler' => array(
						'shortcode_type[eventer_counter]' => array('hide'),
						'shortcode_type[eventer_list]' => array('show'),
						'shortcode_type[eventer_grid]' => array('hide'),
						'shortcode_type[eventer_slider]' => array('hide'),
						'shortcode_type[eventer_calendar]' => array('hide'),
					),
					'fields' => array(
						'list_ids' => array(
							'type' => 'select',
							'label' => __( 'Select Event', 'framework' ),
							'description' => __('You can select specific events to show in the list. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'prompt' => __( 'Next Upcoming Event', 'framework' ),
							'options' => $arr,
							'default' => ''
						),
						'list_terms_cats' => array(
							'type' => 'select',
							'label' => __( 'Event Category/s', 'framework' ),
							'description' => __('Select event category, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_cat,
							'default' => ''
						),
						'list_terms_tags' => array(
							'type' => 'select',
							'label' => __( 'Event Tag/s', 'framework' ),
							'description' => __('Select event tags, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_tag,
							'default' => ''
						),
						'list_terms_venues' => array(
							'type' => 'select',
							'label' => __( 'Event Venue/s', 'framework' ),
							'description' => __('Select event venues, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'prompt' => __( 'All', 'framework' ),
							'options' => $event_venue,
							'default' => ''
						),
						'list_terms_organizers' => array(
							'type' => 'select',
							'label' => __( 'Event Organiser/s', 'framework' ),
							'description' => __('Select event organisers, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_organiser,
							'default' => ''
						),
						'list_type' => array(
							'type' => 'select',
							'label' => __( 'Event Type', 'framework' ),
							'description' => __('Select event type for the list. You can choose All to show both WordPress and Google Calendar events in the list or WP/Google to show selected events only.','framework'),
							'options' => array(
								'' => __('All','framework'),
								'1' => __('WP','framework'),
								'2' => __('Google','framework')
							),
							'default' => ''
						),
						'list_featured' => array(
							'type' => 'select',
							'label' => __( 'Featured Events', 'framework' ),
							'description' => __('Select yes to show featured events at the top of list view.','framework'),
							'options' => array(
								'' => __('No','framework'),
								'1' => __('Yes','framework')
							),
							'default' => ''
						),
						'list_month_filter' => array(
							'type' => 'select',
							'label' => __( 'Filter Bar', 'framework' ),
							'description' => __('Select Yes to show a month filter above the list of events, which allows users to go to next/prev months or to the next 12 months events.','framework'),
							'options' => array(
								'345' => __('No','framework'),
								'1' => __('Yes','framework')
							),
							'default' => '',
							'state_emitter' => array(
								'callback' => 'select',
								'args' => array( 'list_month_filter' )
							),
						),
						'list_calview' => array(
							'type' => 'select',
							'label' => __( 'Filter Views', 'framework' ),
							'description' => __('Select the calendar view tabs of the events to show in the list.','framework'),
							'multiple' => true,
							'options' => array(
								'' => __('None','framework'),
								'yearly' => __('Year View','framework'),
								'monthly' => __('Month View','framework'),
								'weekly' => __('Week View','framework'),
								'daily' => __('Day View','framework'),
								'today' => __('Today','framework'),
								'date_range' => __('Date Range','framework'),
							),
							'default' => '',
							'state_handler' => array(
								'list_month_filter[345]' => array('hide'),
								'list_month_filter[1]' => array('show'),
							),
						),
						'list_status' => array(
							'type' => 'select',
							'label' => __( 'Event Status', 'framework' ),
							'description' => __('Select the status of the events to show in the list.','framework'),
							'options' => array(
								'' => __('Future','framework'),
								'past' => __('Past','framework'),
								'yearly' => __('Yearly','framework'),
								'monthly' => __('Monthly','framework'),
								'weekly' => __('Weekly','framework'),
								'daily' => __('Daily','framework'),
							),
							'default' => ''
						),
						'list_filters' => array(
							'type' => 'select',
							'label' => __( 'Taxonomy Filters', 'framework' ),
							'description' => __('Select filters for the custom taxonomies of the events.','framework'),
							'multiple' => true,
							'options' => array(
								'' => __('None','framework'),
								'category' => __('Categories','framework'),
								'tag' => __('Tags','framework'),
								'venue' => __('Venues','framework'),
								'organizer' => __('Organisers','framework'),
							),
							'default' => '',
							'state_handler' => array(
								'list_month_filter[345]' => array('hide'),
								'list_month_filter[1]' => array('show'),
							),
						),
						'list_view' => array(
							'type' => 'select',
							'label' => __( 'List Style', 'framework' ),
							'description' => __('Select style of the list for the events.','framework'),
							'options' => array(
								'' => __('Compact','framework'),
								'minimal' => __('Minimal','framework'),
								'classic' => __('Classic','framework'),
								'native' => __('Native','framework'),
								'detailed' => __('Detailed','framework'),
								'modern' => __('Modern','framework'),
							),
							'default' => ''
						),
						'list_venue' => array(
							'type' => 'select',
							'label' => __( 'Show Venue?', 'framework' ),
							'description' => __('Select Yes to show event venue address for every event in the list.','framework'),
							'options' => array(
								'' => __('Yes(Show full address)','framework'),
								'name' => __('Yes(Show venue name)','framework'),
								'no' => __('No','framework'),
							),
							'default' => ''
						),
						'list_count' => array(
							'type' => 'select',
							'label' => __( 'Events Per Page', 'framework' ),
							'description' => __('Enter number of events to show per page when event month filter is shown.','framework'),
							'options' => array(
								'' => __('Default','framework'),
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
								'5' => '5',
								'6' => '6',
								'7' => '7',
								'8' => '8',
								'9' => '9',
								'10' => '10',
								'11' => '11',
								'12' => '12',
								'13' => '13',
								'14' => '14',
								'15' => '15',
								'16' => '16',
								'17' => '17',
								'18' => '18',
								'19' => '19',
								'20' => '20',
								'21' => '21',
								'22' => '22',
								'23' => '23',
								'24' => '24',
								'25' => '25',
								'26' => '26',
								'27' => '27',
								'28' => '28',
								'29' => '29',
								'30' => '30',
								'31' => '31',
								'32' => '32',
								'33' => '33',
								'34' => '34',
								'35' => '35',
								'36' => '36',
								'37' => '37',
								'38' => '38',
								'39' => '39',
								'40' => '40',
								'41' => '41',
								'42' => '42',
								'43' => '43',
								'44' => '44',
								'45' => '45',
								'46' => '46',
								'47' => '47',
								'48' => '48',
								'49' => '49',
								'50' => '50',
							),
							'default' => '',
							'state_handler' => array(
								'list_month_filter[345]' => array('show'),
								'list_month_filter[1]' => array('hide'),
							),
						),
						'list_pagination' => array(
							'type' => 'select',
							'label' => __( 'Show Pagination?', 'framework' ),
							'description' => __('Select Yes to show pagination below the events list. This will use events per page option.','framework'),
							'options' => array(
								'' => __('No','framework'),
								'yes' => __('Yes','framework'),
							),
							'default' => '',
							'state_handler' => array(
								'list_month_filter[345]' => array('show'),
								'list_month_filter[1]' => array('hide'),
							),
						),
					)
				),
				'grid_fields' => array(
					'type' => 'section',
					'label' => __( 'Grid Shortcode', 'framework' ),
					'state_handler' => array(
						'shortcode_type[eventer_counter]' => array('hide'),
						'shortcode_type[eventer_list]' => array('hide'),
						'shortcode_type[eventer_grid]' => array('show'),
						'shortcode_type[eventer_slider]' => array('hide'),
						'shortcode_type[eventer_calendar]' => array('hide'),
					),
					'fields' => array(
						'grid_layout' => array(
							'type' => 'select',
							'label' => __( 'Grid Style', 'framework' ),
							'description' => __('Select the layout for grid view.','framework'),
							'options' => array(
								'' => __('Default','framework'),
								'clean' => __('Clean','framework'),
								'featured' => __('Featured','framework'),
								'hidden' => __('Featured Hidden','framework'),
								'modern' => __('Modern','framework'),
								'product' => __('Product','framework'),
							),
							'default' => ''
						),
						'grid_ids' => array(
							'type' => 'select',
							'label' => __( 'Select Event', 'framework' ),
							'description' => __('You can select specific events to show in the grid. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $arr,
							'default' => ''
						),
						'grid_terms_cats' => array(
							'type' => 'select',
							'label' => __( 'Event Category/s', 'framework' ),
							'description' => __('Select event category, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_cat,
							'default' => ''
						),
						'grid_terms_tags' => array(
							'type' => 'select',
							'label' => __( 'Event Tag/s', 'framework' ),
							'description' => __('Select event tags, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_tag,
							'default' => ''
						),
						'grid_terms_venues' => array(
							'type' => 'select',
							'label' => __( 'Event Venue/s', 'framework' ),
							'description' => __('Select event venues, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_venue,
							'default' => ''
						),
						'grid_terms_organizers' => array(
							'type' => 'select',
							'label' => __( 'Event Organiser/s', 'framework' ),
							'description' => __('Select event organisers, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_organiser,
							'default' => ''
						),
						'grid_type' => array(
							'type' => 'select',
							'label' => __( 'Event Type', 'framework' ),
							'description' => __('Select event type for the grid. You can choose All to show both WordPress and Google Calendar events in the grid or WP/Google to show selected events only.','framework'),
							'options' => array(
								'' => __('All','framework'),
								'1' => __('WP','framework'),
								'2' => __('Google','framework')
							),
							'default' => ''
						),
						'grid_featured' => array(
							'type' => 'select',
							'label' => __( 'Featured Events', 'framework' ),
							'description' => __('Select yes to show featured events at the top of grid view.','framework'),
							'options' => array(
								'' => __('No','framework'),
								'1' => __('Yes','framework')
							),
							'default' => ''
						),
						'grid_month_filter' => array(
							'type' => 'select',
							'label' => __( 'Filter Bar', 'framework' ),
							'description' => __('Select Yes to show a month filter above the list of events, which allows users to go to next/prev months or to the next 12 months events.','framework'),
							'options' => array(
								'345' => __('No','framework'),
								'1' => __('Yes','framework')
							),
							'default' => '',
							'state_emitter' => array(
								'callback' => 'select',
								'args' => array( 'grid_month_filter' )
							),
						),
						'grid_calview' => array(
							'type' => 'select',
							'label' => __( 'Filter Views', 'framework' ),
							'description' => __('Select the calendar view tabs of the events to show in the grid, this will not work with the status of future or past.','framework'),
							'multiple' => true,
							'options' => array(
								'' => __('None','framework'),
								'yearly' => __('Year View','framework'),
								'monthly' => __('Month View','framework'),
								'weekly' => __('Week View','framework'),
								'daily' => __('Day View','framework'),
								'today' => __('Today','framework'),
								'date_range' => __('Date Range','framework'),
							),
							'default' => '',
							'state_handler' => array(
								'grid_month_filter[345]' => array('hide'),
								'grid_month_filter[1]' => array('show'),
							),
						),
						'grid_status' => array(
							'type' => 'select',
							'label' => __( 'Event Status', 'framework' ),
							'description' => __('Select the status of the events to show in the grid.','framework'),
							'options' => array(
								'' => __('Future','framework'),
								'past' => __('Past','framework'),
								'yearly' => __('Yearly','framework'),
								'monthly' => __('Monthly','framework'),
								'weekly' => __('Weekly','framework'),
								'daily' => __('Daily','framework'),
							),
							'default' => ''
						),
						'grid_filters' => array(
							'type' => 'select',
							'label' => __( 'Taxonomy Filters', 'framework' ),
							'description' => __('Select filters for the custom taxonomies of the events.','framework'),
							'multiple' => true,
							'options' => array(
								'' => __('None','framework'),
								'category' => __('Categories','framework'),
								'tag' => __('Tags','framework'),
								'venue' => __('Venues','framework'),
								'organizer' => __('Organisers','framework'),
							),
							'default' => '',
							'state_handler' => array(
								'grid_month_filter[345]' => array('hide'),
								'grid_month_filter[1]' => array('show'),
							),
						),
						'grid_venue' => array(
							'type' => 'select',
							'label' => __( 'Show Venue?', 'framework' ),
							'description' => __('Select Yes to show event venue address for every event in the grid.','framework'),
							'options' => array(
								'' => __('Yes(Show full address)','framework'),
								'name' => __('Yes(Show venue name)','framework'),
								'no' => __('No','framework'),
							),
							'default' => ''
						),
						'grid_background' => array(
							'type' => 'select',
							'label' => __( 'Grid Events Background', 'framework' ),
							'description' => __('Select the background option for the grid items. Default will show featured image if available else Category selected color as background if available else it will be plain white background.','framework'),
							'options' => array(
								'' => __('Default - Featured Image/Category Color/Plain','framework'),
								'3' => __('Plain','framework'),
								'1' => __('Event Category Color','framework'),
								'2' => __('Featured Image','framework'),
							),
							'default' => ''
						),
						'grid_column' => array(
							'type' => 'select',
							'label' => __( 'Grid Columns', 'framework' ),
							'description' => __('Select columns for the grid.','framework'),
							'options' => array(
								'3' => __('Default (3 Columns)','framework'),
								'1' => __('1 Column','framework'),
								'2' => __('2 Columns','framework'),
								'4' => __('4 Columns','framework'),
							),
							'default' => ''
						),
						'grid_count' => array(
							'type' => 'select',
							'label' => __( 'Events Per Page', 'framework' ),
							'description' => __('Enter number of events to show per page when event month filter is shown.','framework'),
							'options' => array(
								'' => __('Default','framework'),
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
								'5' => '5',
								'6' => '6',
								'7' => '7',
								'8' => '8',
								'9' => '9',
								'10' => '10',
								'11' => '11',
								'12' => '12',
								'13' => '13',
								'14' => '14',
								'15' => '15',
								'16' => '16',
								'17' => '17',
								'18' => '18',
								'19' => '19',
								'20' => '20',
								'21' => '21',
								'22' => '22',
								'23' => '23',
								'24' => '24',
								'25' => '25',
								'26' => '26',
								'27' => '27',
								'28' => '28',
								'29' => '29',
								'30' => '30',
								'31' => '31',
								'32' => '32',
								'33' => '33',
								'34' => '34',
								'35' => '35',
								'36' => '36',
								'37' => '37',
								'38' => '38',
								'39' => '39',
								'40' => '40',
								'41' => '41',
								'42' => '42',
								'43' => '43',
								'44' => '44',
								'45' => '45',
								'46' => '46',
								'47' => '47',
								'48' => '48',
								'49' => '49',
								'50' => '50',
							),
							'default' => '',
							'state_handler' => array(
								'grid_month_filter[345]' => array('show'),
								'grid_month_filter[1]' => array('hide'),
							),
						),
						'grid_pagination' => array(
							'type' => 'select',
							'label' => __( 'Show Pagination?', 'framework' ),
							'description' => __('Select Yes to show pagination below the events grid. This will use events per page option.','framework'),
							'options' => array(
								'no' => __('No','framework'),
								'yes' => __('Yes','framework'),
								'carousel' => __('Carousel','framework'),
							),
							'default' => '',
							'state_handler' => array(
								'grid_month_filter[345]' => array('show'),
								'grid_month_filter[1]' => array('hide'),
							),
							'state_emitter' => array(
								'callback' => 'select',
								'args' => array( 'grid_pagination' )
							),
						),
						'carousel_autoplay' => array(
							'type' => 'select',
							'label' => __( 'Autoplay Carousel?', 'framework' ),
							'description' => __('Select Yes to autoplay carousel.','framework'),
							'options' => array(
								'yes' => __('Yes','framework'),
								'no' => __('No','framework'),
							),
							'default' => 'yes',
							'state_handler' => array(
								'grid_pagination[carousel]' => array('show'),
								'grid_pagination[yes]' => array('hide'),
								'grid_pagination[no]' => array('hide'),
							),
						),
						'carousel_interval' => array(
							'type' => 'text',
							'label' => __( 'Autoplay Timeout', 'framework' ),
							'description' => __('Enter value for after how many seconds carousel should auto move to next slide.','framework'),
							'default' => '4000',
							'state_handler' => array(
								'grid_pagination[carousel]' => array('show'),
								'grid_pagination[yes]' => array('hide'),
								'grid_pagination[no]' => array('hide'),
							),
						),
						'carousel_pagination' => array(
							'type' => 'select',
							'label' => __( 'Show Carousel Pagination?', 'framework' ),
							'description' => __('Select Yes to display pagination dots with the carousel.','framework'),
							'options' => array(
								'yes' => __('Yes','framework'),
								'no' => __('No','framework'),
							),
							'default' => 'yes',
							'state_handler' => array(
								'grid_pagination[carousel]' => array('show'),
								'grid_pagination[yes]' => array('hide'),
								'grid_pagination[no]' => array('hide'),
							),
						),
						'carousel_arrows' => array(
							'type' => 'select',
							'label' => __( 'Show Carousel Arrows?', 'framework' ),
							'description' => __('Select Yes to display navigation arrows with the carousel.','framework'),
							'options' => array(
								'yes' => __('Yes','framework'),
								'no' => __('No','framework'),
							),
							'default' => 'yes',
							'state_handler' => array(
								'grid_pagination[carousel]' => array('show'),
								'grid_pagination[yes]' => array('hide'),
								'grid_pagination[no]' => array('hide'),
							),
						),
						'carousel_rtl' => array(
							'type' => 'select',
							'label' => __( 'RTL Carousel?', 'framework' ),
							'description' => __('Select Yes if your website is for the RTL language.','framework'),
							'options' => array(
								'yes' => __('Yes','framework'),
								'no' => __('No','framework'),
							),
							'default' => 'no',
							'state_handler' => array(
								'grid_pagination[carousel]' => array('show'),
								'grid_pagination[yes]' => array('hide'),
								'grid_pagination[no]' => array('hide'),
							),
						),
					)
				),
				'slider_fields' => array(
					'type' => 'section',
					'label' => __( 'Slider Shortcode', 'framework' ),
					'state_handler' => array(
						'shortcode_type[eventer_counter]' => array('hide'),
						'shortcode_type[eventer_list]' => array('hide'),
						'shortcode_type[eventer_grid]' => array('hide'),
						'shortcode_type[eventer_slider]' => array('show'),
						'shortcode_type[eventer_calendar]' => array('hide'),
					),
					'fields' => array(
						'slider_layout' => array(
							'type' => 'select',
							'label' => __( 'Slider Style', 'framework' ),
							'description' => __('Select the layout for slider view.','framework'),
							'options' => array(
								'' => __('Style 1','framework'),
								'type2' => __('Style 2','framework'),
								'type3' => __('Style 3','framework'),
							),
							'default' => ''
						),
						'slider_ids' => array(
							'type' => 'select',
							'label' => __( 'Select Event', 'framework' ),
							'description' => __('You can select specific events to show in the slider. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $arr,
							'default' => ''
						),
						'slider_terms_cats' => array(
							'type' => 'select',
							'label' => __( 'Event Category/s', 'framework' ),
							'description' => __('Select event category, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_cat,
							'default' => ''
						),
						'slider_terms_tags' => array(
							'type' => 'select',
							'label' => __( 'Event Tag/s', 'framework' ),
							'description' => __('Select event tags, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_tag,
							'default' => ''
						),
						'slider_terms_venues' => array(
							'type' => 'select',
							'label' => __( 'Event Venue/s', 'framework' ),
							'description' => __('Select event venues, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'prompt' => __( 'All', 'framework' ),
							'options' => $event_venue,
							'default' => ''
						),
						'slider_terms_organizers' => array(
							'type' => 'select',
							'label' => __( 'Event Organiser/s', 'framework' ),
							'description' => __('Select event organisers, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_organiser,
							'default' => ''
						),
						'slider_count' => array(
							'type' => 'select',
							'label' => __( 'No. of Slides', 'framework' ),
							'description' => __('Choose how many slides you would like to show in the slider.','framework'),
							'options' => array(
								'' => __('Default','framework'),
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
								'5' => '5',
								'6' => '6',
								'7' => '7',
								'8' => '8',
								'9' => '9',
								'10' => '10',
								'11' => '11',
								'12' => '12',
								'13' => '13',
								'14' => '14',
								'15' => '15',
								'16' => '16',
								'17' => '17',
								'18' => '18',
								'19' => '19',
								'20' => '20',
								'21' => '21',
								'22' => '22',
								'23' => '23',
								'24' => '24',
								'25' => '25',
								'26' => '26',
								'27' => '27',
								'28' => '28',
								'29' => '29',
								'30' => '30',
								'31' => '31',
								'32' => '32',
								'33' => '33',
								'34' => '34',
								'35' => '35',
								'36' => '36',
								'37' => '37',
								'38' => '38',
								'39' => '39',
								'40' => '40',
								'41' => '41',
								'42' => '42',
								'43' => '43',
								'44' => '44',
								'45' => '45',
								'46' => '46',
								'47' => '47',
								'48' => '48',
								'49' => '49',
								'50' => '50',
							),
							'default' => '',
						),
						'slider_autoplay' => array(
							'type' => 'select',
							'label' => __( 'Autoplay Carousel?', 'framework' ),
							'description' => __('Select Yes to autoplay carousel.','framework'),
							'options' => array(
								'yes' => __('Yes','framework'),
								'no' => __('No','framework'),
							),
							'default' => 'yes',
						),
						'slider_interval' => array(
							'type' => 'text',
							'label' => __( 'Autoplay Timeout', 'framework' ),
							'description' => __('Enter value for after how many seconds carousel should auto move to next slide.','framework'),
							'default' => '4000',
						),
						'slider_pagination' => array(
							'type' => 'select',
							'label' => __( 'Show Carousel Pagination?', 'framework' ),
							'description' => __('Select Yes to display pagination dots with the carousel.','framework'),
							'options' => array(
								'yes' => __('Yes','framework'),
								'no' => __('No','framework'),
							),
							'default' => 'yes',
						),
						'slider_arrows' => array(
							'type' => 'select',
							'label' => __( 'Show Carousel Arrows?', 'framework' ),
							'description' => __('Select Yes to display navigation arrows with the carousel.','framework'),
							'options' => array(
								'yes' => __('Yes','framework'),
								'no' => __('No','framework'),
							),
							'default' => 'yes',
						),
						'slider_rtl' => array(
							'type' => 'select',
							'label' => __( 'RTL Carousel?', 'framework' ),
							'description' => __('Select Yes if your website is for the RTL language.','framework'),
							'options' => array(
								'yes' => __('Yes','framework'),
								'no' => __('No','framework'),
							),
							'default' => 'no',
						),
					)
				),
				'calendar_fields' => array(
					'type' => 'section',
					'label' => __( 'Calendar Shortcode', 'framework' ),
					'state_handler' => array(
						'shortcode_type[eventer_counter]' => array('hide'),
						'shortcode_type[eventer_list]' => array('hide'),
						'shortcode_type[eventer_grid]' => array('hide'),
						'shortcode_type[eventer_slider]' => array('hide'),
						'shortcode_type[eventer_calendar]' => array('show'),
					),
					'fields' => array(
						'calendar_terms_cats' => array(
							'type' => 'select',
							'label' => __( 'Event Category/s', 'framework' ),
							'description' => __('Select event category, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_cat,
							'default' => ''
						),
						'calendar_terms_tags' => array(
							'type' => 'select',
							'label' => __( 'Event Tag/s', 'framework' ),
							'description' => __('Select event tags, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_tag,
							'default' => ''
						),
						'calendar_terms_venues' => array(
							'type' => 'select',
							'label' => __( 'Event Venue/s', 'framework' ),
							'description' => __('Select event venues, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_venue,
							'default' => ''
						),
						'calendar_terms_organizers' => array(
							'type' => 'select',
							'label' => __( 'Event Organiser/s', 'framework' ),
							'description' => __('Select event organisers, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.','framework'),
							'multiple' => true,
							'options' => $event_organiser,
							'default' => ''
						),
						'calendar_type' => array(
							'type' => 'select',
							'label' => __( 'Events Type', 'framework' ),
							'description' => __('Select event type for the calendar. You can choose All to show both WordPress and Google Calendar events in the calendar or WP/Google to show selected events only.','framework'),
							'options' => array(
								'' => __('All','framework'),
								'1' => 'WP',
								'2' => 'Google',
							),
							'default' => '',
						),
						'calendar_preview' => array(
							'type' => 'select',
							'label' => __( 'Event Preview', 'framework' ),
							'description' => __('Select Yes to enable event details preview when hovered over the events on the calendar.','framework'),
							'options' => array(
								'' => __('Yes','framework'),
								'no' => 'No',
							),
							'default' => '',
						),
					)
				),
			),
			plugin_dir_path(__FILE__)
		);
	}
	
	
	function get_template_name( $instance ) {
		return 'template';
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

siteorigin_widget_register('eventer-widget', __FILE__, 'Eventer_Widget');