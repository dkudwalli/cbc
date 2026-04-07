<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Eventer Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
if(!class_exists('Elementor_Eventer_Widget')){
	class Elementor_Eventer_Widget extends \Elementor\Widget_Base {

		/**
		 * Get widget name.
		 *
		 * Retrieve Eventer widget name.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return string Widget name.
		 */
		public function get_name() {
			return 'eventer';
		}

		/**
		 * Get widget title.
		 *
		 * Retrieve Eventer widget title.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return string Widget title.
		 */
		public function get_title() {
			return esc_html__( 'Eventer', 'framework' );
		}

		/**
		 * Get widget icon.
		 *
		 * Retrieve Eventer widget icon.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return string Widget icon.
		 */
		public function get_icon() {
			return 'eicon-editor-list-ul';
		}

		/**
		 * Get custom help URL.
		 *
		 * Retrieve a URL where the user can get more information about the widget.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return string Widget help URL.
		 */
		public function get_custom_help_url() {
			return 'https://support.imithemes.com/manual/eventer/';
		}

		/**
		 * Get widget categories.
		 *
		 * Retrieve the list of categories the Eventer widget belongs to.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return array Widget categories.
		 */
		public function get_categories() {
			return [ 'general' ];
		}

		/**
		 * Get widget keywords.
		 *
		 * Retrieve the list of keywords the Eventer widget belongs to.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return array Widget keywords.
		 */
		public function get_keywords() {
			return [ 'eventer', 'event' ];
		}

		/**
		 * Register Eventer widget controls.
		 *
		 * Add input fields to allow the user to customize the widget settings.
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function register_controls() {

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

			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Shortcode Type', 'framework' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'shortcode_type',
				[
					'label' => esc_html__( 'Shortcode Type', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'eventer_counter',
					'options' => [
						'eventer_counter'  => esc_html__( 'Upcoming Event Countdown', 'framework' ),
						'eventer_list'  => esc_html__( 'Events List', 'framework' ),
						'eventer_grid'  => esc_html__( 'Events Grid', 'framework' ),
						'eventer_slider'  => esc_html__( 'Events Slider', 'framework' ),
						'eventer_calendar'  => esc_html__( 'Events Calendar', 'framework' ),
					],
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'counter_fields',
				[
					'label' => esc_html__( 'Countdown Shortcode', 'framework' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
					'condition' => [
						'shortcode_type' => 'eventer_counter'
					],
				]
			);

			$this->add_control(
				'counter_ids',
				[
					'label' => esc_html__( 'Select Event', 'framework' ),
					'description' => esc_html__( 'You can select a specific event to show at the upcoming event counter.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $arr,
				]
			);

			$this->add_control(
				'counter_terms_cats',
				[
					'label' => esc_html__( 'Select Event Category', 'framework' ),
					'description' => esc_html__( 'Select event categories from which events will be used in the upcoming event counter.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $event_cat,
				]
			);

			$this->add_control(
				'counter_terms_tags',
				[
					'label' => esc_html__( 'Select Event Tag', 'framework' ),
					'description' => esc_html__( 'Select event tags, from which events will be used in the upcoming event counter.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $event_tag,
				]
			);

			$this->add_control(
				'counter_terms_venues',
				[
					'label' => esc_html__( 'Select Event Venue', 'framework' ),
					'description' => esc_html__( 'Select event venues, from which events will be used in the upcoming event counter.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $event_venue,
				]
			);

			$this->add_control(
				'counter_terms_organizers',
				[
					'label' => esc_html__( 'Select Event Organiser', 'framework' ),
					'description' => esc_html__( 'Select event organisers, from which events will be used in the upcoming event counter.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $event_organiser,
				]
			);

			$this->add_control(
				'counter_venue',
				[
					'label' => esc_html__( 'Show Event Venue?', 'framework' ),
					'description' => esc_html__( 'Select Yes if you want to show your event venue address in the upcoming event counter.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'Yes', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
				]
			);

			$this->add_control(
				'counter_type',
				[
					'label' => esc_html__( 'Event Type', 'framework' ),
					'description' => esc_html__( 'Select which event type you want to show in the upcoming event counter.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'1'  => esc_html__( 'WP', 'framework' ),
						'2'  => esc_html__( 'Google', 'framework' ),
					],
					'default' => '1'
				]
			);

			$this->add_control(
				'counter_event_until',
				[
					'label' => esc_html__( 'Show Counter Till', 'framework' ),
					'description' => esc_html__( 'Select till what time an event will be shown in the upcoming event counter.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'Start Time', 'framework' ),
						'2'  => esc_html__( 'End Time', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'list_fields',
				[
					'label' => esc_html__( 'List Shortcode', 'framework' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
					'condition' => [
						'shortcode_type' => 'eventer_list'
					],
				]
			);

			$this->add_control(
				'list_ids',
				[
					'label' => esc_html__( 'Select Event', 'framework' ),
					'description' => esc_html__( 'You can select specific events to show in the list. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $arr,
					'multiple' => true,
				]
			);

			$this->add_control(
				'list_terms_cats',
				[
					'label' => esc_html__( 'Event Category/s', 'framework' ),
					'description' => esc_html__( 'Select event category, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_cat,
					'multiple' => true,
				]
			);

			$this->add_control(
				'list_terms_tags',
				[
					'label' => esc_html__( 'Event Tag/s', 'framework' ),
					'description' => esc_html__( 'Select event tags, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_tag,
					'multiple' => true,
				]
			);

			$this->add_control(
				'list_terms_venues',
				[
					'label' => esc_html__( 'Event Venue/s', 'framework' ),
					'description' => esc_html__( 'Select event venues, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_venue,
					'multiple' => true,
				]
			);

			$this->add_control(
				'list_terms_organizers',
				[
					'label' => esc_html__( 'Event Organiser/s', 'framework' ),
					'description' => esc_html__( 'Select event organisers, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_organiser,
					'multiple' => true,
				]
			);

			$this->add_control(
				'list_type',
				[
					'label' => esc_html__( 'Event Type', 'framework' ),
					'description' => esc_html__( 'Select event type for the list. You can choose All to show both WordPress and Google Calendar events in the list or WP/Google to show selected events only.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'All', 'framework' ),
						'1'  => esc_html__( 'WP', 'framework' ),
						'2'  => esc_html__( 'Google', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->add_control(
				'list_featured',
				[
					'label' => esc_html__( 'Featured Events', 'framework' ),
					'description' => esc_html__( 'Select yes to show featured events at the top of list view.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'No', 'framework' ),
						'1'  => esc_html__( 'Yes', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->add_control(
				'list_month_filter',
				[
					'label' => esc_html__( 'Filter Bar', 'framework' ),
					'description' => esc_html__( 'Select Yes to show a month filter above the list of events, which allows users to go to next/prev months or to the next 12 months events.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'345'  => esc_html__( 'No', 'framework' ),
						'1'  => esc_html__( 'Yes', 'framework' ),
					],
					'default' => '345'
				]
			);

			$this->add_control(
				'list_calview',
				[
					'label' => esc_html__( 'Filter Views', 'framework' ),
					'description' => esc_html__( 'Select the calendar view tabs of the events to show in the list.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => [
						'yearly'  => esc_html__( 'Year View', 'framework' ),
						'monthly'  => esc_html__( 'Month View', 'framework' ),
						'weekly'  => esc_html__( 'Week View', 'framework' ),
						'daily'  => esc_html__( 'Day View', 'framework' ),
						'today'  => esc_html__( 'Today', 'framework' ),
						'date_range'  => esc_html__( 'Date Range', 'framework' ),
					],
					'default' => '',
					'multiple' => true,
					'condition' => [
						'list_month_filter' => '1'
					],
				]
			);

			$this->add_control(
				'list_status',
				[
					'label' => esc_html__( 'Event Status', 'framework' ),
					'description' => esc_html__( 'Select the status of the events to show in the list.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'Future', 'framework' ),
						'past'  => esc_html__( 'Past', 'framework' ),
						'yearly'  => esc_html__( 'Yearly', 'framework' ),
						'monthly'  => esc_html__( 'Monthly', 'framework' ),
						'weekly'  => esc_html__( 'Weekly', 'framework' ),
						'daily'  => esc_html__( 'Daily', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->add_control(
				'list_filters',
				[
					'label' => esc_html__( 'Taxonomy Filters', 'framework' ),
					'description' => esc_html__( 'Select filters for the custom taxonomies of the events.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => [
						'category'  => esc_html__( 'Categories', 'framework' ),
						'tag'  => esc_html__( 'Tags', 'framework' ),
						'venue'  => esc_html__( 'Venues', 'framework' ),
						'organizer'  => esc_html__( 'Organisers', 'framework' ),
					],
					'default' => '',
					'multiple' => true,
					'condition' => [
						'list_month_filter' => '1'
					],
				]
			);

			$this->add_control(
				'list_view',
				[
					'label' => esc_html__( 'List Style', 'framework' ),
					'description' => esc_html__( 'Select style of the list for the events.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'Compact', 'framework' ),
						'minimal'  => esc_html__( 'Minimal', 'framework' ),
						'classic'  => esc_html__( 'Classic', 'framework' ),
						'native'  => esc_html__( 'Native', 'framework' ),
						'detailed'  => esc_html__( 'Detailed', 'framework' ),
						'modern'  => esc_html__( 'Modern', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->add_control(
				'list_venue',
				[
					'label' => esc_html__( 'Show Venue?', 'framework' ),
					'description' => esc_html__( 'Select Yes to show event venue address for every event in the list.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'Yes(Show full address)', 'framework' ),
						'name'  => esc_html__( 'Yes(Show venue name)', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->add_control(
				'list_count',
				[
					'label' => esc_html__( 'Events Per Page', 'framework' ),
					'description' => esc_html__( 'Enter number of events to show per page when event month filter is shown.', 'framework' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 50,
					'default' => 1,
					'condition' => [
						'list_month_filter' => '345'
					],
				]
			);

			$this->add_control(
				'list_pagination',
				[
					'label' => esc_html__( 'Show Pagination?', 'framework' ),
					'description' => esc_html__( 'Select Yes to show pagination below the events list. This will use events per page option.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'No', 'framework' ),
						'yes'  => esc_html__( 'Yes', 'framework' ),
					],
					'default' => '',
					'condition' => [
						'list_month_filter' => '345'
					],
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'grid_fields',
				[
					'label' => esc_html__( 'Grid Shortcode', 'framework' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
					'condition' => [
						'shortcode_type' => 'eventer_grid'
					],
				]
			);

			$this->add_control(
				'grid_layout',
				[
					'label' => esc_html__( 'Grid Style', 'framework' ),
					'description' => esc_html__( 'Select the layout for grid view.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'Default', 'framework' ),
						'clean'  => esc_html__( 'Clean', 'framework' ),
						'featured'  => esc_html__( 'Featured', 'framework' ),
						'hidden'  => esc_html__( 'Featured Hidden', 'framework' ),
						'modern'  => esc_html__( 'Modern', 'framework' ),
						'products'  => esc_html__( 'Product', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->add_control(
				'grid_ids',
				[
					'label' => esc_html__( 'Select Event', 'framework' ),
					'description' => esc_html__( 'You can select specific events to show in the grid. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $arr,
					'multiple' => true,
				]
			);

			$this->add_control(
				'grid_terms_cats',
				[
					'label' => esc_html__( 'Event Category/s', 'framework' ),
					'description' => esc_html__( 'Select event category, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_cat,
					'multiple' => true,
				]
			);

			$this->add_control(
				'grid_terms_tags',
				[
					'label' => esc_html__( 'Event Tag/s', 'framework' ),
					'description' => esc_html__( 'Select event tags, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_tag,
					'multiple' => true,
				]
			);

			$this->add_control(
				'grid_terms_venues',
				[
					'label' => esc_html__( 'Event Venue/s', 'framework' ),
					'description' => esc_html__( 'Select event venues, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_venue,
					'multiple' => true,
				]
			);

			$this->add_control(
				'grid_terms_organizers',
				[
					'label' => esc_html__( 'Event Organiser/s', 'framework' ),
					'description' => esc_html__( 'Select event organisers, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_organiser,
					'multiple' => true,
				]
			);

			$this->add_control(
				'grid_type',
				[
					'label' => esc_html__( 'Event Type', 'framework' ),
					'description' => esc_html__( 'Select event type for the grid. You can choose All to show both WordPress and Google Calendar events in the grid or WP/Google to show selected events only.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'All', 'framework' ),
						'1'  => esc_html__( 'WP', 'framework' ),
						'2'  => esc_html__( 'Google', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->add_control(
				'grid_featured',
				[
					'label' => esc_html__( 'Featured Events', 'framework' ),
					'description' => esc_html__( 'Select yes to show featured events at the top of grid view.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'No', 'framework' ),
						'1'  => esc_html__( 'Yes', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->add_control(
				'grid_month_filter',
				[
					'label' => esc_html__( 'Filter Bar', 'framework' ),
					'description' => esc_html__( 'Select Yes to show a month filter above the list of events, which allows users to go to next/prev months or to the next 12 months events.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'345'  => esc_html__( 'No', 'framework' ),
						'1'  => esc_html__( 'Yes', 'framework' ),
					],
					'default' => '345'
				]
			);

			$this->add_control(
				'grid_calview',
				[
					'label' => esc_html__( 'Filter Views', 'framework' ),
					'description' => esc_html__( 'Select the calendar view tabs of the events to show in the grid, this will not work with the status of future or past.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => [
						'yearly'  => esc_html__( 'Year View', 'framework' ),
						'monthly'  => esc_html__( 'Month View', 'framework' ),
						'weekly'  => esc_html__( 'Week View', 'framework' ),
						'daily'  => esc_html__( 'Day View', 'framework' ),
						'today'  => esc_html__( 'Today', 'framework' ),
						'date_range'  => esc_html__( 'Date Range', 'framework' ),
					],
					'default' => '',
					'multiple' => true,
					'condition' => [
						'grid_month_filter' => '1'
					],
				]
			);

			$this->add_control(
				'grid_status',
				[
					'label' => esc_html__( 'Event Status', 'framework' ),
					'description' => esc_html__( 'Select the status of the events to show in the grid.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'Future', 'framework' ),
						'past'  => esc_html__( 'Past', 'framework' ),
						'yearly'  => esc_html__( 'Yearly', 'framework' ),
						'monthly'  => esc_html__( 'Monthly', 'framework' ),
						'weekly'  => esc_html__( 'Weekly', 'framework' ),
						'daily'  => esc_html__( 'Daily', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->add_control(
				'grid_filters',
				[
					'label' => esc_html__( 'Taxonomy Filters', 'framework' ),
					'description' => esc_html__( 'Select filters for the custom taxonomies of the events.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => [
						'category'  => esc_html__( 'Categories', 'framework' ),
						'tag'  => esc_html__( 'Tags', 'framework' ),
						'venue'  => esc_html__( 'Venues', 'framework' ),
						'organizer'  => esc_html__( 'Organisers', 'framework' ),
					],
					'default' => '',
					'multiple' => true,
					'condition' => [
						'grid_month_filter' => '1'
					],
				]
			);

			$this->add_control(
				'grid_venue',
				[
					'label' => esc_html__( 'Show Venue?', 'framework' ),
					'description' => esc_html__( 'Select Yes to show event venue address for every event in the grid.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'Yes(Show full address)', 'framework' ),
						'name'  => esc_html__( 'Yes(Show venue name)', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->add_control(
				'grid_background',
				[
					'label' => esc_html__( 'Grid Events Background', 'framework' ),
					'description' => esc_html__( 'Select the background option for the grid items. Default will show featured image if available else Category selected color as background if available else it will be plain white background.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'Default - Featured Image/Category Color/Plain', 'framework' ),
						'3'  => esc_html__( 'Plain', 'framework' ),
						'1'  => esc_html__( 'Event Category Color', 'framework' ),
						'2'  => esc_html__( 'Featured Image', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->add_control(
				'grid_column',
				[
					'label' => esc_html__( 'Grid Columns', 'framework' ),
					'description' => esc_html__( 'Select columns for the grid.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'3'  => esc_html__( 'Default (3 Columns)', 'framework' ),
						'1'  => esc_html__( '1 Column', 'framework' ),
						'2'  => esc_html__( '2 Columns', 'framework' ),
						'4'  => esc_html__( '4 Columns', 'framework' ),
					],
					'default' => '3'
				]
			);

			$this->add_control(
				'grid_count',
				[
					'label' => esc_html__( 'Events Per Page', 'framework' ),
					'description' => esc_html__( 'Enter number of events to show per page when event month filter is shown.', 'framework' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 50,
					'default' => 1,
					'condition' => [
						'grid_month_filter' => '345'
					],
				]
			);

			$this->add_control(
				'grid_pagination',
				[
					'label' => esc_html__( 'Show Pagination?', 'framework' ),
					'description' => esc_html__( 'Select Yes to show pagination below the events grid. This will use events per page option.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'no'  => esc_html__( 'No', 'framework' ),
						'yes'  => esc_html__( 'Yes', 'framework' ),
						'carousel' => esc_html__('Carousel','framework'),
					],
					'default' => '',
					'condition' => [
						'grid_month_filter' => '345'
					],
				]
			);

			$this->add_control(
				'carousel_autoplay',
				[
					'label' => esc_html__( 'Autoplay Carousel?', 'framework' ),
					'description' => esc_html__( 'Select Yes to autoplay carousel.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'yes'  => esc_html__( 'Yes', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
					'default' => 'yes',
					'condition' => [
						'grid_pagination' => 'carousel'
					],
				]
			);

			$this->add_control(
				'carousel_interval',
				[
					'label' => esc_html__( 'Autoplay Timeout', 'framework' ),
					'description' => esc_html__( 'Enter value for after how many seconds carousel should auto move to next slide.', 'framework' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '4000',
					'condition' => [
						'grid_pagination' => 'carousel',
						'carousel_autoplay' => 'yes'
					],
				]
			);

			$this->add_control(
				'carousel_pagination',
				[
					'label' => esc_html__( 'Show Carousel Pagination?', 'framework' ),
					'description' => esc_html__( 'Select Yes to display pagination dots with the carousel.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'yes'  => esc_html__( 'Yes', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
					'default' => 'yes',
					'condition' => [
						'grid_pagination' => 'carousel'
					],
				]
			);

			$this->add_control(
				'carousel_arrows',
				[
					'label' => esc_html__( 'Show Carousel Arrows?', 'framework' ),
					'description' => esc_html__( 'Select Yes to display navigation arrows with the carousel.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'yes'  => esc_html__( 'Yes', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
					'default' => 'yes',
					'condition' => [
						'grid_pagination' => 'carousel'
					],
				]
			);

			$this->add_control(
				'carousel_rtl',
				[
					'label' => esc_html__( 'RTL Carousel?', 'framework' ),
					'description' => esc_html__( 'Select Yes if your website is for the RTL language.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'yes'  => esc_html__( 'Yes', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
					'default' => 'no',
					'condition' => [
						'grid_pagination' => 'carousel'
					],
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'slider_fields',
				[
					'label' => esc_html__( 'Slider Shortcode', 'framework' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
					'condition' => [
						'shortcode_type' => 'eventer_slider'
					],
				]
			);

			$this->add_control(
				'slider_layout',
				[
					'label' => esc_html__( 'Slider Style', 'framework' ),
					'description' => esc_html__( 'Select the layout for slider view.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'Style 1', 'framework' ),
						'type2'  => esc_html__( 'Style 2', 'framework' ),
						'type3'  => esc_html__( 'Style 3', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->add_control(
				'slider_ids',
				[
					'label' => esc_html__( 'Select Event', 'framework' ),
					'description' => esc_html__( 'You can select specific events to show in the slider. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $arr,
					'multiple' => true,
				]
			);

			$this->add_control(
				'slider_terms_cats',
				[
					'label' => esc_html__( 'Event Category/s', 'framework' ),
					'description' => esc_html__( 'Select event category, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_cat,
					'multiple' => true,
				]
			);

			$this->add_control(
				'slider_terms_tags',
				[
					'label' => esc_html__( 'Event Tag/s', 'framework' ),
					'description' => esc_html__( 'Select event tags, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_tag,
					'multiple' => true,
				]
			);

			$this->add_control(
				'slider_terms_venues',
				[
					'label' => esc_html__( 'Event Venue/s', 'framework' ),
					'description' => esc_html__( 'Select event venues, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_venue,
					'multiple' => true,
				]
			);

			$this->add_control(
				'slider_terms_organizers',
				[
					'label' => esc_html__( 'Event Organiser/s', 'framework' ),
					'description' => esc_html__( 'Select event organisers, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_organiser,
					'multiple' => true,
				]
			);

			$this->add_control(
				'slider_count',
				[
					'label' => esc_html__( 'No. of Slides', 'framework' ),
					'description' => esc_html__( 'Enter the number of how many slides you would like to show in the slider.', 'framework' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 50,
					'default' => 1,
					'condition' => [
						'grid_month_filter' => '345'
					],
				]
			);

			$this->add_control(
				'slider_autoplay',
				[
					'label' => esc_html__( 'Autoplay Slider?', 'framework' ),
					'description' => esc_html__( 'Select Yes to autoplay slider.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'yes'  => esc_html__( 'Yes', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
					'default' => 'yes',
				]
			);

			$this->add_control(
				'slider_interval',
				[
					'label' => esc_html__( 'Autoplay Timeout', 'framework' ),
					'description' => esc_html__( 'Enter value for after how many seconds slider should auto move to next slide.', 'framework' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '4000',
					'condition' => [
						'slider_autoplay' => 'yes'
					],
				]
			);

			$this->add_control(
				'slider_pagination',
				[
					'label' => esc_html__( 'Show Slider Pagination?', 'framework' ),
					'description' => esc_html__( 'Select Yes to display pagination dots with the slider.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'yes'  => esc_html__( 'Yes', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
					'default' => 'yes',
				]
			);

			$this->add_control(
				'slider_arrows',
				[
					'label' => esc_html__( 'Show Slider Arrows?', 'framework' ),
					'description' => esc_html__( 'Select Yes to display navigation arrows with the slider.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'yes'  => esc_html__( 'Yes', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
					'default' => 'yes',
				]
			);

			$this->add_control(
				'slider_rtl',
				[
					'label' => esc_html__( 'RTL Slider?', 'framework' ),
					'description' => esc_html__( 'Select Yes if your website is for the RTL language.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'yes'  => esc_html__( 'Yes', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
					'default' => 'no',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'calendar_fields',
				[
					'label' => esc_html__( 'Calendar Shortcode', 'framework' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
					'condition' => [
						'shortcode_type' => 'eventer_calendar'
					],
				]
			);

			$this->add_control(
				'calendar_terms_cats',
				[
					'label' => esc_html__( 'Event Category/s', 'framework' ),
					'description' => esc_html__( 'Select event category, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_cat,
					'multiple' => true,
				]
			);

			$this->add_control(
				'calendar_terms_tags',
				[
					'label' => esc_html__( 'Event Tag/s', 'framework' ),
					'description' => esc_html__( 'Select event tags, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_tag,
					'multiple' => true,
				]
			);

			$this->add_control(
				'calendar_terms_venues',
				[
					'label' => esc_html__( 'Event Venue/s', 'framework' ),
					'description' => esc_html__( 'Select event venues, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_venue,
					'multiple' => true,
				]
			);

			$this->add_control(
				'calendar_terms_organizers',
				[
					'label' => esc_html__( 'Event Organiser/s', 'framework' ),
					'description' => esc_html__( 'Select event organisers, from which you want to show events specifically. You can use ctrl/command key to select/deselect multiple values.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $event_organiser,
					'multiple' => true,
				]
			);

			$this->add_control(
				'calendar_type',
				[
					'label' => esc_html__( 'Event Type', 'framework' ),
					'description' => esc_html__( 'Select event type for the calendar. You can choose All to show both WordPress and Google Calendar events in the calendar or WP/Google to show selected events only.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'All', 'framework' ),
						'1'  => esc_html__( 'WP', 'framework' ),
						'2'  => esc_html__( 'Google', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->add_control(
				'calendar_preview',
				[
					'label' => esc_html__( 'Event Preview', 'framework' ),
					'description' => esc_html__( 'Select Yes to enable event details preview when hovered over the events on the calendar.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						''  => esc_html__( 'Yes', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
					'default' => ''
				]
			);

			$this->end_controls_section();

		}

		/**
		 * Render Eventer widget output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function render() {

			$settings = $this->get_settings_for_display();

			$shortcode_type = $settings['shortcode_type'];

			// Countdown Shortcode
			if($shortcode_type == 'eventer_counter'){
				$counter_ids_f = $counter_terms_cats_f = $counter_terms_tags_f = $counter_terms_venues_f = $counter_terms_organizers_f = $counter_venue_f = $counter_type_f = $counter_event_until_f = '';
				// Attributes
				$counter_ids = $settings['counter_ids'];
				$counter_terms_cats = $settings['counter_terms_cats'];
				$counter_terms_tags = $settings['counter_terms_tags'];
				$counter_terms_venues = $settings['counter_terms_venues'];
				$counter_terms_organizers = $settings['counter_terms_organizers'];
				$counter_venue = $settings['counter_venue'];
				$counter_type = $settings['counter_type'];
				$counter_event_until = $settings['counter_event_until'];

				if($counter_ids){$counter_ids_f = ' ids="'.$counter_ids.'"';}
				if($counter_terms_cats){$counter_terms_cats_f = ' terms_cats="'.$counter_terms_cats.'"';}
				if($counter_terms_tags){$counter_terms_tags_f = ' terms_tags="'.$counter_terms_tags.'"';}
				if($counter_terms_venues){$counter_terms_venues_f = ' terms_venue="'.$counter_terms_venues.'"';}
				if($counter_terms_organizers){$counter_terms_organizers_f = ' terms_organizer="'.$counter_terms_organizers.'"';}
				if($counter_venue){$counter_venue_f = ' venue="'.$counter_venue.'"';}
				if($counter_type){$counter_type_f = ' type="'.$counter_type.'"';}
				if($counter_event_until){$counter_event_until_f = ' event_until="'.$counter_event_until.'"';}

				// Shortcode
				$shortcode = ('[eventer_counter'.$counter_ids_f.$counter_terms_cats_f.$counter_terms_tags_f.$counter_terms_venues_f.$counter_terms_organizers_f.$counter_venue_f.$counter_type_f.$counter_event_until_f.']');
			}

			// List Shortcode
			if($shortcode_type == 'eventer_list'){
				$list_view_f = $list_ids_f = $list_terms_cats_f = $list_terms_tags_f = $list_terms_venues_f = $list_terms_organizers_f = $list_type_f = $list_featured_f = $list_month_filter_f = $list_calview_f = $list_status_f = $list_filters_f = $list_venue_f = $list_count_f = $list_pagination_f = '';
				// Attributes
				$list_ids = $settings['list_ids'];
				$list_terms_cats = $settings['list_terms_cats'];
				$list_terms_tags = $settings['list_terms_tags'];
				$list_terms_venues = $settings['list_terms_venues'];
				$list_terms_organizers = $settings['list_terms_organizers'];
				$list_type = $settings['list_type'];
				$list_featured = $settings['list_featured'];
				$list_month_filter = $settings['list_month_filter'];
				$list_calview = $settings['list_calview'];
				$list_status = $settings['list_status'];
				$list_filters = $settings['list_filters'];
				$list_view = $settings['list_view'];
				$list_venue = $settings['list_venue'];
				$list_count = $settings['list_count'];
				$list_pagination = $settings['list_pagination'];

				if(is_array($list_ids)){ $list_ids = implode(',', $list_ids);}
				if(is_array($list_terms_cats)){ $list_terms_cats = implode(',', $list_terms_cats);}
				if(is_array($list_terms_tags)){ $list_terms_tags = implode(',', $list_terms_tags);}
				if(is_array($list_terms_venues)){ $list_terms_venues = implode(',', $list_terms_venues);}
				if(is_array($list_terms_organizers)){ $list_terms_organizers = implode(',', $list_terms_organizers);}
				if(is_array($list_calview)){ $list_calview = implode(',', $list_calview);}
				if(is_array($list_filters)){ $list_filters = implode(',', $list_filters);}

				if($list_view){$list_view_f = ' view="'.$list_view.'"';}
				if($list_ids){$list_ids_f = ' ids="'.$list_ids.'"';}
				if($list_terms_cats){$list_terms_cats_f = ' terms_cats="'.$list_terms_cats.'"';}
				if($list_terms_tags){$list_terms_tags_f = ' terms_tags="'.$list_terms_tags.'"';}
				if($list_terms_venues){$list_terms_venues_f = ' terms_venue="'.$list_terms_venues.'"';}
				if($list_terms_organizers){$terms_organizer_f = ' terms_organizer="'.$list_terms_organizers.'"';}
				if($list_type){$list_type_f = ' type="'.$list_type.'"';}
				if($list_featured){$list_featured_f = ' featured="'.$list_featured.'"';}
				if($list_month_filter){$list_month_filter_f = ' month_filter="'.$list_month_filter.'"';}
				if($list_calview){$list_calview_f = ' calview="'.$list_calview.'"';}
				if($list_status){$list_status_f = ' status="'.$list_status.'"';}
				if($list_filters){$list_filters_f = ' filters="'.$list_filters.'"';}
				if($list_venue){$list_venue_f = ' venue="'.$list_venue.'"';}
				if($list_count){$list_count_f = ' count="'.$list_count.'"';}
				if($list_pagination){$list_pagination_f = ' pagination="'.$list_pagination.'"';}

				// Shortcode
				$shortcode = ('[eventer_list'.$list_ids_f.$list_terms_cats_f.$list_terms_tags_f.$list_terms_venues_f.$list_terms_organizers_f.$list_type_f.$list_featured_f.$list_month_filter_f.$list_calview_f.$list_status_f.' series=""'.$list_filters_f.$list_view_f.$list_venue_f.$list_count_f.$list_pagination_f.']');
			}

			// Grid Shortcode
			if($shortcode_type == 'eventer_grid'){
				$grid_layout_f = $grid_ids_f = $grid_terms_cats_f = $grid_terms_tags_f = $grid_terms_venues_f = $grid_terms_organizers_f = $grid_type_f = $grid_featured_f = $grid_month_filter_f = $grid_calview_f = $grid_status_f = $grid_filters_f = $grid_venue_f = $grid_background_f = $grid_column_f = $grid_count_f = $grid_pagination_f = $grid_carousel_f = '';
				// Attributes
				$grid_layout = $settings['grid_layout'];
				$grid_ids = $settings['grid_ids'];
				$grid_terms_cats = $settings['grid_terms_cats'];
				$grid_terms_tags = $settings['grid_terms_tags'];
				$grid_terms_venues = $settings['grid_terms_venues'];
				$grid_terms_organizers = $settings['grid_terms_organizers'];
				$grid_type = $settings['grid_type'];
				$grid_featured = $settings['grid_featured'];
				$grid_month_filter = $settings['grid_month_filter'];
				$grid_calview = $settings['grid_calview'];
				$grid_status = $settings['grid_status'];
				$grid_filters = $settings['grid_filters'];
				$grid_venue = $settings['grid_venue'];
				$grid_background = $settings['grid_background'];
				$grid_column = $settings['grid_column'];
				$grid_count = $settings['grid_count'];
				$grid_pagination = $settings['grid_pagination'];
				$carousel[] = $settings['carousel_autoplay'];
				$carousel[] = $settings['carousel_interval'];
				$carousel[] = $settings['carousel_pagination'];
				$carousel[] = $settings['carousel_arrows'];
				$carousel[] = $settings['carousel_rtl'];

				if(is_array($grid_ids)){ $grid_ids = implode(',', $grid_ids);}
				if(is_array($grid_terms_cats)){ $grid_terms_cats = implode(',', $grid_terms_cats);}
				if(is_array($grid_terms_tags)){ $grid_terms_tags = implode(',', $grid_terms_tags);}
				if(is_array($grid_terms_venues)){ $grid_terms_venues = implode(',', $grid_terms_venues);}
				if(is_array($grid_terms_organizers)){ $grid_terms_organizers = implode(',', $grid_terms_organizers);}
				if(is_array($grid_calview)){ $grid_calview = implode(',', $grid_calview);}
				if(is_array($grid_filters)){ $grid_filters = implode(',', $grid_filters);}
				if(is_array($carousel)){ $grid_carousel = implode(',', $carousel);}

				if($grid_layout){$grid_layout_f = ' layout="'.$grid_layout.'"';}
				if($grid_ids){$grid_ids_f = ' ids="'.$grid_ids.'"';}
				if($grid_terms_cats){$grid_terms_cats_f = ' terms_cats="'.$grid_terms_cats.'"';}
				if($grid_terms_tags){$grid_terms_tags_f = ' terms_tags="'.$grid_terms_tags.'"';}
				if($grid_terms_venues){$grid_terms_venues_f = ' terms_venue="'.$grid_terms_venues.'"';}
				if($grid_terms_organizers){$grid_terms_organizers_f = ' terms_organizer="'.$grid_terms_organizers.'"';}
				if($grid_type){$grid_type_f = ' type="'.$grid_type.'"';}
				if($grid_featured){$grid_featured_f = ' featured="'.$grid_featured.'"';}
				if($grid_month_filter){$grid_month_filter_f = ' month_filter="'.$grid_month_filter.'"';}
				if($grid_calview){$grid_calview_f = ' calview="'.$grid_calview.'"';}
				if($grid_status){$grid_status_f = ' status="'.$grid_status.'"';}
				if($grid_filters){$grid_filters_f = ' filters="'.$grid_filters.'"';}
				if($grid_venue){$grid_venue_f = ' venue="'.$grid_venue.'"';}
				if($grid_background){$grid_background_f = ' background="'.$grid_background.'"';}
				if($grid_column){$grid_column_f = ' column="'.$grid_column.'"';}
				if($grid_count){$grid_count_f = ' count="'.$grid_count.'"';}
				if($grid_pagination){$grid_pagination_f = ' pagination="'.$grid_pagination.'"';}
				if($grid_carousel){$grid_carousel_f = ' carousel="'.$grid_carousel.'"';}


				// Shortcode
				$shortcode = ('[eventer_grid'.$grid_layout_f.$grid_ids_f.$grid_terms_cats_f.$grid_terms_tags_f.$grid_terms_venues_f.$grid_terms_organizers_f.$grid_type_f.$grid_featured_f.$grid_month_filter_f.$grid_calview_f.$grid_status_f.$grid_filters_f.' series=""'.$grid_background_f.$grid_column_f.$grid_venue_f.$grid_count_f.$grid_pagination_f.$grid_carousel_f.']');
			}

			// Slider Shortcode
			if($shortcode_type == 'eventer_slider'){
				$slider_layout_f = $slider_ids_f = $slider_terms_cats_f = $slider_terms_tags_f = $slider_terms_venues_f = $slider_terms_organizers_f = $slider_count_f = $slider_carousel_f = '';
				// Attributes
				$slider_layout = $settings['slider_layout'];
				$slider_ids = $settings['slider_ids'];
				$slider_terms_cats = $settings['slider_terms_cats'];
				$slider_terms_tags = $settings['slider_terms_tags'];
				$slider_terms_venues = $settings['slider_terms_venues'];
				$slider_terms_organizers = $settings['slider_terms_organizers'];
				$slider_count = $settings['slider_count'];
				$slider_carousel[] = $settings['slider_autoplay'];
				$slider_carousel[] = $settings['slider_interval'];
				$slider_carousel[] = $settings['slider_pagination'];
				$slider_carousel[] = $settings['slider_arrows'];
				$slider_carousel[] = $settings['slider_rtl'];

				if(is_array($slider_ids)){ $slider_ids = implode(',', $slider_ids);}
				if(is_array($slider_terms_cats)){ $slider_terms_cats = implode(',', $slider_terms_cats);}
				if(is_array($slider_terms_tags)){ $slider_terms_tags = implode(',', $slider_terms_tags);}
				if(is_array($slider_terms_venues)){ $slider_terms_venues = implode(',', $slider_terms_venues);}
				if(is_array($slider_terms_organizers)){ $slider_terms_organizers = implode(',', $slider_terms_organizers);}
				$atts_carousel = implode(',', $slider_carousel);

				if($slider_layout){$slider_layout_f = ' layout="'.$slider_layout.'"';}
				if($slider_ids){$slider_ids_f = ' ids="'.$slider_ids.'"';}
				if($slider_terms_cats){$slider_terms_cats_f = ' terms_cats="'.$slider_terms_cats.'"';}
				if($slider_terms_tags){$slider_terms_tags_f = ' terms_tags="'.$slider_terms_tags.'"';}
				if($slider_terms_venues){$slider_terms_venues_f = ' terms_venue="'.$slider_terms_venues.'"';}
				if($slider_terms_organizers){$slider_terms_organizers_f = ' terms_organizer="'.$slider_terms_organizers.'"';}
				if($slider_count){$slider_count_f = ' count="'.$slider_count.'"';}
				if($atts_carousel){$slider_carousel_f = ' carousel="'.$atts_carousel.'"';}

				// Shortcode
				$shortcode = ('[eventer_slider'.$slider_layout_f.$slider_ids_f.$slider_terms_cats_f.$slider_terms_tags_f.$slider_terms_venues_f.$slider_terms_organizers_f.$slider_count_f.$slider_carousel_f.']');
			}

			// Calendar Shortcode
			if($shortcode_type == 'eventer_calendar'){
				$calendar_terms_cats_f = $calendar_terms_tags_f = $calendar_terms_venues_f = $calendar_terms_organizers_f = $calendar_type_f = $calendar_preview_f = '';
				// Attributes
				$calendar_terms_cats = $settings['slider_terms_cats'];
				$calendar_terms_tags = $settings['slider_terms_tags'];
				$calendar_terms_venues = $settings['slider_terms_venues'];
				$calendar_terms_organizers = $settings['slider_terms_organizers'];
				$calendar_type = $settings['calendar_type'];
				$calendar_preview = $settings['calendar_preview'];

				if(is_array($calendar_terms_cats)){ $calendar_terms_cats = implode(',', $calendar_terms_cats);}
				if(is_array($calendar_terms_tags)){ $calendar_terms_tags = implode(',', $calendar_terms_tags);}
				if(is_array($calendar_terms_venues)){ $calendar_terms_venues = implode(',', $calendar_terms_venues);}
				if(is_array($calendar_terms_organizers)){ $calendar_terms_organizers = implode(',', $calendar_terms_organizers);}

				if($calendar_terms_cats){$calendar_terms_cats_f = ' terms_cats="'.$calendar_terms_cats.'"';}
				if($calendar_terms_tags){$calendar_terms_tags_f = ' terms_tags="'.$calendar_terms_tags.'"';}
				if($calendar_terms_venues){$calendar_terms_venues_f = ' terms_venue="'.$calendar_terms_venues.'"';}
				if($calendar_terms_organizers){$calendar_terms_organizers_f = ' terms_organizer="'.$calendar_terms_organizers.'"';}
				if($calendar_type){$calendar_type_f = ' type="'.$calendar_type.'"';}
				if($calendar_preview){$calendar_preview_f = ' preview="'.$calendar_preview.'"';}

				// Shortcode
				$shortcode = ('[eventer_calendar'.$calendar_terms_cats_f.$calendar_terms_tags_f.$calendar_terms_venues_f.$calendar_terms_organizers_f.$calendar_type_f.$calendar_preview_f.']');
			}

			// OUTPUT
			echo do_shortcode($shortcode);

		}

	}
}