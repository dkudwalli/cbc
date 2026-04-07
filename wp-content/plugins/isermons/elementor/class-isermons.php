<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor iSermons Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
if(!class_exists('Elementor_Isermons_Widget')){

	class Elementor_Isermons_Widget extends \Elementor\Widget_Base {

		/**
		 * Get widget name.
		 *
		 * Retrieve iSermons widget name.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return string Widget name.
		 */
		public function get_name() {
			return 'isermons';
		}

		/**
		 * Get widget title.
		 *
		 * Retrieve iSermons widget title.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return string Widget title.
		 */
		public function get_title() {
			return esc_html__( 'iSermons', 'isermons' );
		}

		/**
		 * Get widget icon.
		 *
		 * Retrieve iSermons widget icon.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return string Widget icon.
		 */
		public function get_icon() {
			return 'eicon-headphones';
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
			return 'https://support.imithemes.com/manual/isermons/';
		}

		/**
		 * Get widget categories.
		 *
		 * Retrieve the list of categories the iSermons widget belongs to.
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
		 * Retrieve the list of keywords the iSermons widget belongs to.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return array Widget keywords.
		 */
		public function get_keywords() {
			return [ 'sermons', 'message' ];
		}

		/**
		 * Register iSermons widget controls.
		 *
		 * Add input fields to allow the user to customize the widget settings.
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function register_controls() {

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

			$this->start_controls_section(
				'content_section',
				[
					'label' => esc_html__( 'Shortcode Type', 'isermons' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'sermon_shortcode_type',
				[
					'label' => esc_html__( 'Shortcode Type', 'isermons' ),
					'description' => esc_html__( 'Select the shortcode which you want to generate.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'isermons-list'  => esc_html__( 'Sermon Posts', 'isermons' ),
						'isermons-terms'  => esc_html__( 'Sermons Taxonomy', 'isermons' ),
					],
					'default' => 'isermons-list',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'posts_fields',
				[
					'label' => esc_html__( 'Sermon Posts Fields', 'isermons' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
					'condition' => [
						'sermon_shortcode_type' => 'isermons-list'
					],
				]
			);

			$this->add_control(
				'layout',
				[
					'label' => esc_html__( 'Style', 'isermons' ),
					'description' => esc_html__( 'Select the layout that you want to use for sermons.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'classic'  => esc_html__( 'Classic', 'isermons' ),
						'minimal'  => esc_html__( 'Minimal', 'isermons' ),
						'grid'  => esc_html__( 'Grid', 'isermons' ),
					],
					'default' => 'classic',
				]
			);

			$this->add_control(
				'sermons_orderby',
				[
					'label' => esc_html__( 'Order By', 'isermons' ),
					'description' => esc_html__( 'Select the order by for the sermons.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'date'  => esc_html__( 'Published Date', 'isermons' ),
						'meta_value'  => esc_html__( 'Preached Date', 'isermons' ),
						'ID'  => esc_html__( 'ID', 'isermons' ),
						'title'  => esc_html__( 'Title', 'isermons' ),
						'name'  => esc_html__( 'Name', 'isermons' ),
					],
					'default' => 'date',
				]
			);

			$this->add_control(
				'sermons_order',
				[
					'label' => esc_html__( 'Order', 'isermons' ),
					'description' => esc_html__( 'Select the order of the sermons.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'DESC'  => esc_html__( 'Descending', 'isermons' ),
						'ASC'  => esc_html__( 'Ascending', 'isermons' ),
					],
					'default' => 'DESC',
				]
			);

			$this->add_control(
				'relation',
				[
					'label' => esc_html__( 'Relate With', 'isermons' ),
					'description' => esc_html__( 'Select the taxonomy to show related sermons at sermon details page.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'categories'  => esc_html__( 'Current Post Category', 'isermons' ),
						'series'  => esc_html__( 'Current Post Series', 'isermons' ),
						'books'  => esc_html__( 'Current Post Books', 'isermons' ),
						'topics'  => esc_html__( 'Current Post Topics', 'isermons' ),
						'preachers'  => esc_html__( 'Current Post Preachers', 'isermons' ),
					],
					'default' => 'categories',
				]
			);

			$this->add_control(
				'search',
				[
					'label' => esc_html__( 'Search & Sort', 'isermons' ),
					'description' => esc_html__( 'Select fields for search and sort area.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => [
						'keyword'  => esc_html__( 'Keyword', 'isermons' ),
						'year'  => esc_html__( 'Year', 'isermons' ),
						'order'  => esc_html__( 'Order', 'isermons' ),
					],
					'default' => '',
				]
			);

			$this->add_control(
				'filters',
				[
					'label' => esc_html__( 'Filters', 'isermons' ),
					'description' => esc_html__( 'Select taxonomies for search and filter area.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => [
						'series'  => esc_html__( 'Series', 'isermons' ),
						'categories'  => esc_html__( 'Categories', 'isermons' ),
						'topics'  => esc_html__( 'Topics', 'isermons' ),
						'preachers'  => esc_html__( 'Preachers', 'isermons' ),
					],
					'default' => '',
				]
			);

			$this->add_control(
				'watch',
				[
					'label' => esc_html__( 'Button', 'isermons' ),
					'description' => esc_html__( 'Replace text for watch sermon button.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Watch Sermon', 'isermons' ),
				]
			);

			$this->add_control(
				'listen',
				[
					'label' => esc_html__( 'Button', 'isermons' ),
					'description' => esc_html__( 'Button text for sermons without video media.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Listen Sermon', 'isermons' ),
				]
			);

			$this->add_control(
				'details',
				[
					'label' => esc_html__( 'Button', 'isermons' ),
					'description' => esc_html__( 'Button text for sermons without video/audio media.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'View Sermon', 'isermons' ),
				]
			);

			$this->add_control(
				'columns',
				[
					'label' => esc_html__( 'Grid Columns', 'isermons' ),
					'description' => esc_html__( 'Select columns for grid layout.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'4'  => esc_html__( 'Four', 'isermons' ),
						'3'  => esc_html__( 'Three', 'isermons' ),
						'2'  => esc_html__( 'Two', 'isermons' ),
						'1'  => esc_html__( 'One', 'isermons' ),
					],
					'default' => '4',
					'condition' => [
						'layout' => 'grid'
					],
				]
			);

			$this->add_control(
				'words',
				[
					'label' => esc_html__( 'Excerpt Length', 'isermons' ),
					'description' => esc_html__( 'Enter the number of words to show for sermon description.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'default' => '25',
				]
			);

			$this->add_control(
				'image',
				[
					'label' => esc_html__( 'Image Size', 'isermons' ),
					'description' => esc_html__( 'Select image size, these are the all thumbnail sizes which current theme and plugins added.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $sizes,
					'default' => '',
				]
			);

			$this->add_control(
				'per_page',
				[
					'label' => esc_html__( 'Sermons Per Page', 'isermons' ),
					'description' => esc_html__( 'Enter the number of sermons to show per page.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 50,
					'default' => 1,
				]
			);

			$this->add_control(
				'pagination',
				[
					'label' => esc_html__( 'Show Pagination?', 'isermons' ),
					'description' => esc_html__( 'Select no to hide pagination.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'yes'  => esc_html__( 'Yes', 'isermons' ),
						'no'  => esc_html__( 'No', 'isermons' ),
					],
					'default' => 'yes',
				]
			);

			$this->add_control(
				'redirect',
				[
					'label' => esc_html__( 'Redirect', 'isermons' ),
					'description' => esc_html__( 'Select NO to block redirection of sermons to their details page.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'yes'  => esc_html__( 'Yes', 'isermons' ),
						'no'  => esc_html__( 'No', 'isermons' ),
					],
					'default' => 'yes',
				]
			);

			$this->add_control(
				'meta_data',
				[
					'label' => esc_html__( 'Meta Data', 'isermons' ),
					'description' => esc_html__( 'Select meta information to show for sermons.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => [
						'preacher'  => esc_html__( 'Preacher', 'isermons' ),
						'books'  => esc_html__( 'Books', 'isermons' ),
						'topics'  => esc_html__( 'Topics', 'isermons' ),
						'categories'  => esc_html__( 'Categories', 'isermons' ),
						'date'  => esc_html__( 'Date', 'isermons' ),
						'series'  => esc_html__( 'Series', 'isermons' ),
						'chapter'  => esc_html__( 'Chapter', 'isermons' ),
						'video'  => esc_html__( 'Video', 'isermons' ),
						'audio'  => esc_html__( 'Audio', 'isermons' ),
						'download'  => esc_html__( 'Downloads', 'isermons' ),
					],
					'default' => '',
				]
			);

			$this->add_control(
				'imi_isermons_categories',
				[
					'label' => esc_html__( 'Sermon Categories', 'isermons' ),
					'description' => esc_html__( 'Select terms to show sermons only from selected one.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_cat,
					'default' => '',
				]
			);

			$this->add_control(
				'imi_isermons_series',
				[
					'label' => esc_html__( 'Sermon Series', 'isermons' ),
					'description' => esc_html__( 'Select terms to show sermons only from selected one.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_series,
					'default' => '',
				]
			);

			$this->add_control(
				'imi_isermons_books',
				[
					'label' => esc_html__( 'Sermon Books', 'isermons' ),
					'description' => esc_html__( 'Select terms to show sermons only from selected one.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_books,
					'default' => '',
				]
			);

			$this->add_control(
				'imi_isermons_topics',
				[
					'label' => esc_html__( 'Sermon Topics', 'isermons' ),
					'description' => esc_html__( 'Select terms to show sermons only from selected one.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_topics,
					'default' => '',
				]
			);

			$this->add_control(
				'imi_isermons_preachers',
				[
					'label' => esc_html__( 'Sermon Preachers', 'isermons' ),
					'description' => esc_html__( 'Select terms to show sermons only from selected one.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_preachers,
					'default' => '',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'taxonomy_fields',
				[
					'label' => esc_html__( 'Sermon Taxonomy Fields', 'isermons' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
					'condition' => [
						'sermon_shortcode_type' => 'isermons-terms'
					],
				]
			);

			$this->add_control(
				'term_layout',
				[
					'label' => esc_html__( 'Style', 'isermons' ),
					'description' => esc_html__( 'Select the layout which you want to use for terms.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'style1'  => esc_html__( 'Grid Style 1', 'isermons' ),
						'style2'  => esc_html__( 'Grid Style 2', 'isermons' ),
						'style'  => esc_html__( 'List', 'isermons' ),
					],
					'default' => 'style1',
				]
			);

			$this->add_control(
				'term_columns',
				[
					'label' => esc_html__( 'Grid Columns', 'isermons' ),
					'description' => esc_html__( 'Select columns for grid layout.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'4'  => esc_html__( 'Four', 'isermons' ),
						'3'  => esc_html__( 'Three', 'isermons' ),
						'2'  => esc_html__( 'Two', 'isermons' ),
						'1'  => esc_html__( 'One', 'isermons' ),
					],
					'default' => '4',
					'condition' => [
						'term_layout' => [ 'style1', 'style2' ]
					],
				]
			);

			$this->add_control(
				'term_words',
				[
					'label' => esc_html__( 'Excerpt Length', 'isermons' ),
					'description' => esc_html__( 'Enter the number of words to show for term description.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '25',
				]
			);

			$this->add_control(
				'term_image',
				[
					'label' => esc_html__( 'Image Size', 'isermons' ),
					'description' => esc_html__( 'Select image size, these are the all thumbnail sizes which current theme and plugins added.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $sizes,
					'default' => '',
				]
			);

			$this->add_control(
				'term_taxonomy',
				[
					'label' => esc_html__( 'Select taxonomy', 'isermons' ),
					'description' => esc_html__( 'Select the taxonomy to show terms.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'imi_isermons-categories'  => esc_html__( 'Sermon Categories', 'isermons' ),
						'imi_isermons-series'  => esc_html__( 'Sermon Series', 'isermons' ),
						'imi_isermons-books'  => esc_html__( 'Sermon Books', 'isermons' ),
						'imi_isermons-topics'  => esc_html__( 'Sermon Topics', 'isermons' ),
						'imi_isermons-preachers'  => esc_html__( 'Sermon Preachers', 'isermons' ),
					],
					'default' => '',
				]
			);

			$this->add_control(
				'term_categories',
				[
					'label' => esc_html__( 'Sermon Categories', 'isermons' ),
					'description' => esc_html__( 'Select terms to show only selected one. Click on them one by one to add in the array.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_cat,
					'default' => '',
					'condition' => [
						'term_taxonomy' => 'imi_isermono-categories'
					],
				]
			);

			$this->add_control(
				'term_series',
				[
					'label' => esc_html__( 'Sermon Series', 'isermons' ),
					'description' => esc_html__( 'Select terms to show only selected one. Click on them one by one to add in the array.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_series,
					'default' => '',
					'condition' => [
						'term_taxonomy' => 'imi_isermons-series'
					],
				]
			);

			$this->add_control(
				'term_books',
				[
					'label' => esc_html__( 'Sermon Books', 'isermons' ),
					'description' => esc_html__( 'Select terms to show only selected one. Click on them one by one to add in the array.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_books,
					'default' => '',
					'condition' => [
						'term_taxonomy' => 'imi_isermons-books'
					],
				]
			);

			$this->add_control(
				'term_topics',
				[
					'label' => esc_html__( 'Sermon Topics', 'isermons' ),
					'description' => esc_html__( 'Select terms to show only selected one. Click on them one by one to add in the array.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_topics,
					'default' => '',
					'condition' => [
						'term_taxonomy' => 'imi_isermons-topics'
					],
				]
			);

			$this->add_control(
				'term_preachers',
				[
					'label' => esc_html__( 'Sermon Preachers', 'isermons' ),
					'description' => esc_html__( 'Select terms to show only selected one. Click on them one by one to add in the array.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_preachers,
					'default' => '',
					'condition' => [
						'term_taxonomy' => 'imi_isermons-preachers'
					],
				]
			);

			$this->add_control(
				'filters_order',
				[
					'label' => esc_html__( 'Order By', 'isermons' ),
					'description' => esc_html__( 'Select the order of terms.', 'isermons' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'id'  => esc_html__( 'ID', 'isermons' ),
						'count'  => esc_html__( 'Count', 'isermons' ),
						'name'  => esc_html__( 'Name', 'isermons' ),
						'slug'  => esc_html__( 'Slug', 'isermons' ),
						'Custom'  => esc_html__( 'Custom', 'isermons' ),
					],
					'default' => 'id',
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

			$shortcode_type = $settings['sermon_shortcode_type'];

			// Sermon Posts Shortcode
			if($shortcode_type == 'isermons-list'){
				$layout_f = $orderby_f = $order_f = $relation_f = $search_f = $filters_f = $watch_f = $listen_f = $details_f = $columns_f = $words_f = $image_f = $per_page_f = $redirect_f = $meta_data_f = $imi_isermons_categories_f = $imi_isermons_series_f = $imi_isermons_books_f = $imi_isermons_topics_f = $imi_isermons_preachers_f = '';
				// Attributes
				$layout = $settings['layout'];
				$orderby = $settings['sermons_orderby'];
				$order = $settings['sermons_order'];
				$relation = $settings['relation'];
				$search = $settings['search'];
				$filters = $settings['filters'];
				$watch = $settings['watch'];
				$listen = $settings['listen'];
				$details = $settings['details'];
				$columns = $settings['columns'];
				$words = $settings['words'];
				$image = $settings['image'];
				$per_page = $settings['per_page'];
				$pagination = $settings['pagination'];
				$redirect = $settings['redirect'];
				$meta_data = $settings['meta_data'];
				$imi_isermons_categories = $settings['imi_isermons_categories'];
				$imi_isermons_series = $settings['imi_isermons_series'];
				$imi_isermons_books = $settings['imi_isermons_books'];
				$imi_isermons_topics = $settings['imi_isermons_topics'];
				$imi_isermons_preachers = $settings['imi_isermons_preachers'];

				if(is_array($search)){ $search = implode(',', $search);}
				if(is_array($filters)){ $filters = implode(',', $filters);}
				if(is_array($meta_data)){ $meta_data = implode(',', $meta_data);}
				if(is_array($imi_isermons_categories)){ $imi_isermons_categories = implode(',', $imi_isermons_categories);}
				if(is_array($imi_isermons_series)){ $imi_isermons_series = implode(',', $imi_isermons_series);}
				if(is_array($imi_isermons_books)){ $imi_isermons_books = implode(',', $imi_isermons_books);}
				if(is_array($imi_isermons_topics)){ $imi_isermons_topics = implode(',', $imi_isermons_topics);}
				if(is_array($imi_isermons_preachers)){ $imi_isermons_preachers = implode(',', $imi_isermons_preachers);}

				if($layout){$layout_f = ' layout="'.$layout.'"';}
				if($orderby){$orderby_f = ' orderby="'.$orderby.'"';}
				if($order){$order_f = ' order="'.$order.'"';}
				if($relation){$relation_f = ' relation="'.$relation.'"';}
				if($search){$search_f = ' search="'.$search.'"';}
				if($filters){$filters_f = ' filters="'.$filters.'"';}
				if($watch){$watch_f = ' watch="'.$watch.'"';}
				if($listen){$listen_f = ' listen="'.$listen.'"';}
				if($details){$details_f = ' details="'.$details.'"';}
				if($columns){$columns_f = ' columns="'.$columns.'"';}
				if($words){$words_f = ' words="'.$words.'"';}
				if($image){$image_f = ' image="'.$image.'"';}
				if($per_page){$per_page_f = ' per_page="'.$per_page.'"';}
				if($redirect){$redirect_f = ' redirect="'.$redirect.'"';}
				$pagination_f = ' pagination="'.$pagination.'"';
				if($meta_data){$meta_data_f = ' meta_data="'.$meta_data.'"';}
				if($imi_isermons_categories){$imi_isermons_categories_f = ' imi_isermons-categories="'.$imi_isermons_categories.'"';}
				if($imi_isermons_series){$imi_isermons_series_f = ' imi_isermons-series="'.$imi_isermons_series.'"';}
				if($imi_isermons_books){$imi_isermons_books_f = ' imi_isermons-books="'.$imi_isermons_books.'"';}
				if($imi_isermons_topics){$imi_isermons_topics_f = ' imi_isermons-topics="'.$imi_isermons_topics.'"';}
				if($imi_isermons_preachers){$imi_isermons_preachers_f = ' imi_isermons-preachers="'.$imi_isermons_preachers.'"';}
				// Shortcode
				$shortcode = ('[isermons-list'.$layout_f.$orderby_f.$order_f.$relation_f.$search_f.$filters_f.$watch_f.$listen_f.$details_f.$columns_f.$words_f.$image_f.$per_page_f.$per_page_f.$redirect_f.$pagination_f.$meta_data_f.$imi_isermons_categories_f.$imi_isermons_series_f.$imi_isermons_books_f.$imi_isermons_topics_f.$imi_isermons_preachers_f.']');
			}

			// Sermons Taxonomy Shortcode
			if($shortcode_type == 'isermons-terms'){
				$term_layout_f = $term_columns_f = $term_words_f = $term_image_f = $term_taxonomy_f = $filters_order_f = $imi_isermons_categories_f = $imi_isermons_series_f = $imi_isermons_books_f = $imi_isermons_topics_f = $imi_isermons_preachers_f = '';
				// Attributes
				$term_layout = $settings['term_layout'];
				$term_columns = $settings['term_columns'];
				$term_words = $settings['term_words'];
				$term_image = $settings['term_image'];
				$term_taxonomy = $settings['term_taxonomy'];
				$filters_order = $settings['filters_order'];
				$imi_isermons_categories = $settings['term_categories'];
				$imi_isermons_series = $settings['term_series'];
				$imi_isermons_books = $settings['term_books'];
				$imi_isermons_topics = $settings['term_topics'];
				$imi_isermons_preachers = $settings['term_preachers'];

				if(is_array($imi_isermons_categories)){ $imi_isermons_categories = implode(',', $imi_isermons_categories);}
				if(is_array($imi_isermons_series)){ $imi_isermons_series = implode(',', $imi_isermons_series);}
				if(is_array($imi_isermons_books)){ $imi_isermons_books = implode(',', $imi_isermons_books);}
				if(is_array($imi_isermons_topics)){ $imi_isermons_topics = implode(',', $imi_isermons_topics);}
				if(is_array($imi_isermons_preachers)){ $imi_isermons_preachers = implode(',', $imi_isermons_preachers);}

				if($term_layout){$term_layout_f = ' layout="'.$term_layout.'"';}
				if($term_columns){$term_columns_f = ' columns="'.$term_columns.'"';}
				if($term_words){$term_words_f = ' words="'.$term_words.'"';}
				if($term_image){$term_image_f = ' image="'.$term_image.'"';}
				if($term_taxonomy){$term_taxonomy_f = ' taxonomy="'.$term_taxonomy.'"';}
				if($filters_order != 'Custom'){
					if($filters_order){$filters_order_f = ' filters_order="'.$filters_order.'"';}
				} else {
					if($imi_isermons_categories){$filters_order_f = ' filters_order="'.$imi_isermons_categories.'"';}
					if($imi_isermons_series){$filters_order_f = ' filters_order="'.$imi_isermons_series.'"';}
					if($imi_isermons_books){$filters_order_f = ' filters_order="'.$imi_isermons_books.'"';}
					if($imi_isermons_topics){$filters_order_f = ' filters_order="'.$imi_isermons_topics.'"';}
					if($imi_isermons_preachers){$filters_order_f = ' filters_order="'.$imi_isermons_preachers.'"';}
				}
				// Shortcode
				$shortcode = ('[isermons-terms'.$term_layout_f.$term_columns_f.$term_words_f.$term_image_f.$term_taxonomy_f.$filters_order_f.$imi_isermons_categories_f.$imi_isermons_series_f.$imi_isermons_books_f.$imi_isermons_topics_f.$imi_isermons_preachers_f.']');
			}

			// OUTPUT
			echo do_shortcode($shortcode);
		}

	}	
}