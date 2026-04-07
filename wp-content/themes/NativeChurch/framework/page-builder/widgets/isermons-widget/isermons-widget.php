<?php

/*
Widget Name: iSermons Widget
Description: A widget to add shortcodes of the iSermons Plugin
Author: imithemes
Author URI: https://imithemes.com
*/

class ISermons_Widget extends SiteOrigin_Widget {
	function __construct() {
		
		// Get All Thumbnail Sizes
		global $_wp_additional_image_sizes;
		$sizes = [];
		if (!is_wp_error($_wp_additional_image_sizes) && $_wp_additional_image_sizes) {
		foreach($_wp_additional_image_sizes as $key=>$value)
			{
				$sizes[$key] = esc_attr($value['width'].'X'.$value['height']);
			}
		}
		
		// Sermons Categories List
		$sermon_cat = [];
		$sermon_tax = get_terms(array('taxonomy'=>'imi_isermons-categories',));
		if (!is_wp_error($sermon_tax) && $sermon_tax) {
		  foreach ($sermon_tax as $stax) {
			$sermon_cat[$stax->term_id] = $stax->name;
		  }
		}
		
		// Sermons Series List
		$sermon_series = [];
		$sermon_series_tax = get_terms(array('taxonomy'=>'imi_isermons-series',));
		if (!is_wp_error($sermon_series_tax) && $sermon_series_tax) {
		  foreach ($sermon_series_tax as $sstax) {
			$sermon_series[$sstax->term_id] = $sstax->name;
		  }
		}
		
		// Sermons Books List
		$sermon_books = [];
		$sermon_books_tax = get_terms(array('taxonomy'=>'imi_isermons-books',));
		if (!is_wp_error($sermon_books_tax) && $sermon_books_tax) {
		  foreach ($sermon_books_tax as $btax) {
			$sermon_books[$btax->term_id] = $btax->name;
		  }
		}
		
		// Sermons Topics List
		$sermon_topics = [];
		$sermon_topics_tax = get_terms(array('taxonomy'=>'imi_isermons-topics',));
		if (!is_wp_error($sermon_topics_tax) && $sermon_topics_tax) {
		  foreach ($sermon_topics_tax as $ttax) {
			$sermon_topics[$ttax->term_id] = $ttax->name;
		  }
		}
		
		// Sermons Preachers List
		$sermon_preachers = [];
		$sermon_preachers_tax = get_terms(array('taxonomy'=>'imi_isermons-preachers',));
		if (!is_wp_error($sermon_preachers_tax) && $sermon_preachers_tax) {
		  foreach ($sermon_preachers_tax as $ptax) {
			$sermon_preachers[$ptax->term_id] = $ptax->name;
		  }
		}
		
		parent::__construct(
			'isermons-widget',
			__('iSermons Widget', 'framework'),
			array(
				'description' => __('A widget to add shortcodes of the iSermons Plugin', 'framework'),
				'panels_icon' => 'dashicons dashicons-list-view',
				'panels_groups' => array('framework')
			),
			array(

			),
			array(
				'sermon_shortcode_type' => array(
					'type' => 'select',
					'label' => __( 'Shortcode Type', 'framework' ),
					'description' => __('Select the shortcode which you want to generate.','framework'),
					'options' => array(
						'isermons-list' => __( 'Sermon Posts', 'framework' ),
						'isermons-terms' => __( 'Sermons Taxonomy', 'framework' ),
					),
					'state_emitter' => array(
						'callback' => 'select',
						'args' => array( 'sermon_shortcode_type' )
					),
					'default' => 'isermons-list'
				),
				'posts_fields' => array(
					'type' => 'section',
					'label' => __( 'Sermon Posts Fields', 'framework' ),
					'state_handler' => array(
						'sermon_shortcode_type[isermons-list]' => array('show'),
						'sermon_shortcode_type[isermons-terms]' => array('hide'),
					),
					'fields' => array(
						'layout' => array(
							'type' => 'select',
							'label' => __( 'Style', 'framework' ),
							'description' => __('Select the layout that you want to use for sermons.','framework'),
							'options' => array(
								'classic' => __( 'Classic', 'framework' ),
								'minimal' => __( 'Minimal', 'framework' ),
								'grid' => __( 'Grid', 'framework' ),
							),
							'default' => 'classic',
							'state_emitter' => array(
								'callback' => 'select',
								'args' => array( 'layout' )
							),
						),
						'relation' => array(
							'type' => 'select',
							'label' => __( 'Relate With', 'framework' ),
							'description' => __('Select the taxonomy to show related sermons at sermon details page.','framework'),
							'options' => array(
								'categories' => __( 'Current Post Category', 'framework' ),
								'series' => __( 'Current Post Series', 'framework' ),
								'books' => __( 'Current Post Books', 'framework' ),
								'topics' => __( 'Current Post Topics', 'framework' ),
								'preachers' => __( 'Current Post Preachers', 'framework' ),
							),
							'default' => 'categories'
						),
						'search' => array(
							'type' => 'checkboxes',
							'label' => __( 'Search & Sort', 'framework' ),
							'description' => __('Select fields for search and sort area.','framework'),
							'options' => array(
								'all' => __( 'All', 'framework' ),
								'keyword' => __( 'Keyword', 'framework' ),
								'year' => __( 'Year', 'framework' ),
								'order' => __( 'Order', 'framework' ),
							),
						),
						'filters' => array(
							'type' => 'checkboxes',
							'label' => __( 'Filters', 'framework' ),
							'description' => __('Select taxonomies for search and filter area.','framework'),
							'options' => array(
								'all' => __( 'All', 'framework' ),
								'series' => __( 'Series', 'framework' ),
								'categories' => __( 'Categories', 'framework' ),
								'topics' => __( 'Topics', 'framework' ),
								'preachers' => __( 'Preachers', 'framework' ),
							),
						),
						'watch' => array(
							'type' => 'text',
							'label' => __( 'Button', 'framework' ),
							'description' => __('Replace text for watch sermon button.','framework'),
							'default' => __( 'Watch Sermon', 'framework' ),
						),
						'columns' => array(
							'type' => 'select',
							'label' => __( 'Grid Columns', 'framework' ),
							'description' => __('Select columns for grid layout.','framework'),
							'options' => array(
								'4' => __( 'Four', 'framework' ),
								'3' => __( 'Three', 'framework' ),
								'2' => __( 'Two', 'framework' ),
								'1' => __( 'One', 'framework' ),
							),
							'default' => '4',
							'state_handler' => array(
								'layout[classic]' => array('hide'),
								'layout[minimal]' => array('hide'),
								'layout[grid]' => array('show'),
							),
						),
						'words' => array(
							'type' => 'text',
							'label' => __( 'Excerpt Length', 'framework' ),
							'description' => __('Enter the number of words to show for sermon description.','framework'),
							'default' => '25',
						),
						'image' => array(
							'type' => 'image_size',
							'label' => __( 'Image Size', 'framework' ),
							'description' => __('Select image size, these are the all thumbnail sizes which current theme and plugins added.','framework'),
							'default' => ''
						),
						'per_page' => array(
							'type' => 'select',
							'label' => __( 'Sermons Per Page', 'framework' ),
							'description' => __('Select the number of sermons to show per page.','framework'),
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
							'default' => '1',
						),
						'redirect' => array(
							'type' => 'select',
							'label' => __( 'Redirect', 'framework' ),
							'description' => __('Select no to block redirection of sermons to their details page.','framework'),
							'options' => array(
								'yes' => __( 'Yes', 'framework' ),
								'no' => __( 'No', 'framework' ),
							),
						),
						'meta_data' => array(
							'type' => 'checkboxes',
							'label' => __( 'Meta Data', 'framework' ),
							'description' => __('Select meta information to show for sermons.','framework'),
							'options' => array(
								'al' => __( 'All', 'framework' ),
								'preacher' => __( 'Preacher', 'framework' ),
								'books' => __( 'Books', 'framework' ),
								'topics' => __( 'Topics', 'framework' ),
								'categories' => __( 'Categories', 'framework' ),
								'date' => __( 'Date', 'framework' ),
								'series' => __( 'Series', 'framework' ),
								'chapter' => __( 'Chapter', 'framework' ),
								'video' => __( 'Video', 'framework' ),
								'audio' => __( 'Audio', 'framework' ),
								'download' => __( 'Download', 'framework' ),
							),
						),
						'imi_isermons_categories' => array(
							'type' => 'select',
							'label' => __( 'Sermon Categories', 'framework' ),
							'description' => __('Select terms to show sermons only from selected one.','framework'),
							'options' => $sermon_cat,
							'multiple' => true,
							'default' => ''
						),
						'imi_isermons_series' => array(
							'type' => 'select',
							'label' => __( 'Sermon Series', 'framework' ),
							'description' => __('Select terms to show sermons only from selected one.','framework'),
							'options' => $sermon_series,
							'multiple' => true,
							'default' => ''
						),
						'imi_isermons_books' => array(
							'type' => 'select',
							'label' => __( 'Sermon Books', 'framework' ),
							'description' => __('Select terms to show sermons only from selected one.','framework'),
							'options' => $sermon_books,
							'multiple' => true,
							'default' => ''
						),
						'imi_isermons_topics' => array(
							'type' => 'select',
							'label' => __( 'Sermon Topics', 'framework' ),
							'description' => __('Select terms to show sermons only from selected one.','framework'),
							'options' => $sermon_topics,
							'multiple' => true,
							'default' => ''
						),
						'imi_isermons_preachers' => array(
							'type' => 'select',
							'label' => __( 'Sermon Preachers', 'framework' ),
							'description' => __('Select terms to show sermons only from selected one.','framework'),
							'options' => $sermon_preachers,
							'multiple' => true,
							'default' => ''
						),
					)
				),
				'taxonomy_fields' => array(
					'type' => 'section',
					'label' => __( 'Sermon Taxonomy Fields', 'framework' ),
					'state_handler' => array(
						'sermon_shortcode_type[isermons-list]' => array('hide'),
						'sermon_shortcode_type[isermons-terms]' => array('show'),
					),
					'fields' => array(
						'term_layout' => array(
							'type' => 'select',
							'label' => __( 'Style', 'framework' ),
							'description' => __('Select the layout which you want to use for terms.','framework'),
							'options' => array(
								'style1' => __( 'Grid Style 1', 'framework' ),
								'style2' => __( 'Grid Style 2', 'framework' ),
								'style' => __( 'List', 'framework' ),
							),
							'default' => 'style1',
							'state_emitter' => array(
								'callback' => 'select',
								'args' => array( 'term_layout' )
							),
						),
						'term_columns' => array(
							'type' => 'select',
							'label' => __( 'Grid Columns', 'framework' ),
							'description' => __('Select columns for grid layout.','framework'),
							'options' => array(
								'4' => __( 'Four', 'framework' ),
								'3' => __( 'Three', 'framework' ),
								'2' => __( 'Two', 'framework' ),
								'1' => __( 'One', 'framework' ),
							),
							'default' => '4',
							'state_handler' => array(
								'term_layout[style1]' => array('show'),
								'term_layout[style1]' => array('show'),
								'term_layout[style]' => array('hide'),
							),
						),
						'term_words' => array(
							'type' => 'text',
							'label' => __( 'Excerpt Length', 'framework' ),
							'description' => __('Enter the number of words to show for term description.','framework'),
							'default' => '25',
						),
						'term_image' => array(
							'type' => 'image_size',
							'label' => __( 'Image Size', 'framework' ),
							'description' => __('Select image size, these are the all thumbnail sizes which current theme and plugins added.','framework'),
							'default' => ''
						),
						'term_taxonomy' => array(
							'type' => 'select',
							'label' => __( 'Select taxonomy', 'framework' ),
							'description' => __('Select the taxonomy to show terms.','framework'),
							'options' => array(
								'' => __( 'Select', 'framework' ),
								'imi_isermons-categories' => __( 'Sermon Categories', 'framework' ),
								'imi_isermons-series' => __( 'Sermon Series', 'framework' ),
								'imi_isermons-books' => __( 'Sermon Books', 'framework' ),
								'imi_isermons-topics' => __( 'Sermon Topics', 'framework' ),
								'imi_isermons-preachers' => __( 'Sermon Preachers', 'framework' ),
							),
							'state_emitter' => array(
								'callback' => 'select',
								'args' => array( 'term_taxonomy' )
							),
						),
						'term_categories' => array(
							'type' => 'select',
							'label' => __( 'Sermon Categories', 'framework' ),
							'description' => __('Select terms to show only selected one. Click on them one by one to add in the array.','framework'),
							'options' => $sermon_cat,
							'default' => '',
							'multiple' => true,
							'state_handler' => array(
								'term_taxonomy[imi-sermon_categories]' => array('show'),
								'term_taxonomy[imi_isermons-series]' => array('hide'),
								'term_taxonomy[imi_isermons-books]' => array('hide'),
								'term_taxonomy[imi_isermons-topics]' => array('hide'),
								'term_taxonomy[imi_isermons-preachers]' => array('hide'),
							),
						),
						'term_series' => array(
							'type' => 'select',
							'label' => __( 'Sermon Series', 'framework' ),
							'description' => __('Select terms to show only selected one. Click on them one by one to add in the array.','framework'),
							'options' => $sermon_series,
							'state_handler' => array(
								'term_taxonomy[imi-sermon_categories]' => array('hide'),
								'term_taxonomy[imi_isermons-series]' => array('show'),
								'term_taxonomy[imi_isermons-books]' => array('hide'),
								'term_taxonomy[imi_isermons-topics]' => array('hide'),
								'term_taxonomy[imi_isermons-preachers]' => array('hide'),
							),
							'multiple' => true,
							'default' => ''
						),
						'term_books' => array(
							'type' => 'select',
							'label' => __( 'Sermon Books', 'framework' ),
							'description' => __('Select terms to show only selected one. Click on them one by one to add in the array.','framework'),
							'options' => $sermon_books,
							'state_handler' => array(
								'term_taxonomy[imi-sermon_categories]' => array('hide'),
								'term_taxonomy[imi_isermons-series]' => array('hide'),
								'term_taxonomy[imi_isermons-books]' => array('show'),
								'term_taxonomy[imi_isermons-topics]' => array('hide'),
								'term_taxonomy[imi_isermons-preachers]' => array('hide'),
							),
							'multiple' => true,
							'default' => ''
						),
						'term_topics' => array(
							'type' => 'select',
							'label' => __( 'Sermon Topics', 'framework' ),
							'description' => __('Select terms to show only selected one. Click on them one by one to add in the array.','framework'),
							'options' => $sermon_topics,
							'state_handler' => array(
								'term_taxonomy[imi-sermon_categories]' => array('hide'),
								'term_taxonomy[imi_isermons-series]' => array('hide'),
								'term_taxonomy[imi_isermons-books]' => array('hide'),
								'term_taxonomy[imi_isermons-topics]' => array('show'),
								'term_taxonomy[imi_isermons-preachers]' => array('hide'),
							),
							'multiple' => true,
							'default' => ''
						),
						'term_preachers' => array(
							'type' => 'select',
							'label' => __( 'Sermon Preachers', 'framework' ),
							'description' => __('Select terms to show only selected one. Click on them one by one to add in the array.','framework'),
							'options' => $sermon_preachers,
							'state_handler' => array(
								'term_taxonomy[imi-sermon_categories]' => array('hide'),
								'term_taxonomy[imi_isermons-series]' => array('hide'),
								'term_taxonomy[imi_isermons-books]' => array('hide'),
								'term_taxonomy[imi_isermons-topics]' => array('hide'),
								'term_taxonomy[imi_isermons-preachers]' => array('show'),
							),
							'multiple' => true,
							'default' => ''
						),
						'filters_order' => array(
							'type' => 'select',
							'label' => __( 'Order By', 'framework' ),
							'description' => __('Select the orderby of terms.','framework'),
							'options' => array(
								'' => __( 'ID', 'framework' ),
								'count' => __( 'Count', 'framework' ),
								'name' => __( 'Name', 'framework' ),
								'slug' => __( 'Slug', 'framework' ),
								'Custom' => __( 'Custom', 'framework' ),
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
		echo '<script>';
		echo 'jQuery(function($){
		   $(document).on("change", ".siteorigin-widget-field-type-checkboxes label:nth-child(2) input", function () {
			  if ($(this).is(":checked")) {
				$(this).parents("div.siteorigin-widget-field-type-checkboxes").find("input").prop("checked", true);
			  }
			  else {
				$(this).parents("div.siteorigin-widget-field-type-checkboxes").find("input").prop("checked", false);
			  }
			});
		});';
		echo '</script>';
		return $instance;
	}


}

siteorigin_widget_register('isermons-widget', __FILE__, 'ISermons_Widget');