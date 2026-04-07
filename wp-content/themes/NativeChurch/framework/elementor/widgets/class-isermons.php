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
			return esc_html__( 'iSermons', 'framework' );
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
					'label' => esc_html__( 'Shortcode Type', 'framework' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'sermon_shortcode_type',
				[
					'label' => esc_html__( 'Shortcode Type', 'framework' ),
					'description' => esc_html__( 'Select the shortcode which you want to generate.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'isermons-list'  => esc_html__( 'Sermon Posts', 'framework' ),
						'isermons-terms'  => esc_html__( 'Sermons Taxonomy', 'framework' ),
					],
					'default' => 'isermons-list',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'posts_fields',
				[
					'label' => esc_html__( 'Sermon Posts Fields', 'framework' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
					'condition' => [
						'sermon_shortcode_type' => 'isermons-list'
					],
				]
			);

			$this->add_control(
				'layout',
				[
					'label' => esc_html__( 'Style', 'framework' ),
					'description' => esc_html__( 'Select the layout that you want to use for sermons.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'classic'  => esc_html__( 'Classic', 'framework' ),
						'minimal'  => esc_html__( 'Minimal', 'framework' ),
						'grid'  => esc_html__( 'Grid', 'framework' ),
					],
					'default' => 'classic',
				]
			);

			$this->add_control(
				'relation',
				[
					'label' => esc_html__( 'Relate With', 'framework' ),
					'description' => esc_html__( 'Select the taxonomy to show related sermons at sermon details page.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'categories'  => esc_html__( 'Current Post Category', 'framework' ),
						'series'  => esc_html__( 'Current Post Series', 'framework' ),
						'books'  => esc_html__( 'Current Post Books', 'framework' ),
						'topics'  => esc_html__( 'Current Post Topics', 'framework' ),
						'preachers'  => esc_html__( 'Current Post Preachers', 'framework' ),
					],
					'default' => 'categories',
				]
			);

			$this->add_control(
				'search',
				[
					'label' => esc_html__( 'Search & Sort', 'framework' ),
					'description' => esc_html__( 'Select fields for search and sort area.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => [
						'keyword'  => esc_html__( 'Keyword', 'framework' ),
						'year'  => esc_html__( 'Year', 'framework' ),
						'order'  => esc_html__( 'Order', 'framework' ),
					],
					'default' => '',
				]
			);

			$this->add_control(
				'filters',
				[
					'label' => esc_html__( 'Filters', 'framework' ),
					'description' => esc_html__( 'Select taxonomies for search and filter area.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => [
						'series'  => esc_html__( 'Series', 'framework' ),
						'categories'  => esc_html__( 'Categories', 'framework' ),
						'topics'  => esc_html__( 'Topics', 'framework' ),
						'preachers'  => esc_html__( 'Preachers', 'framework' ),
					],
					'default' => '',
				]
			);

			$this->add_control(
				'watch',
				[
					'label' => esc_html__( 'Button', 'framework' ),
					'description' => esc_html__( 'Replace text for watch sermon button.', 'framework' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Watch Sermon', 'framework' ),
				]
			);

			$this->add_control(
				'columns',
				[
					'label' => esc_html__( 'Grid Columns', 'framework' ),
					'description' => esc_html__( 'Select columns for grid layout.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'4'  => esc_html__( 'Four', 'framework' ),
						'3'  => esc_html__( 'Three', 'framework' ),
						'2'  => esc_html__( 'Two', 'framework' ),
						'1'  => esc_html__( 'One', 'framework' ),
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
					'label' => esc_html__( 'Excerpt Length', 'framework' ),
					'description' => esc_html__( 'Enter the number of words to show for sermon description.', 'framework' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'default' => '25',
				]
			);

			$this->add_control(
				'image',
				[
					'label' => esc_html__( 'Image Size', 'framework' ),
					'description' => esc_html__( 'Select image size, these are the all thumbnail sizes which current theme and plugins added.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $sizes,
					'default' => '',
				]
			);

			$this->add_control(
				'per_page',
				[
					'label' => esc_html__( 'Sermons Per Page', 'framework' ),
					'description' => esc_html__( 'Enter the number of sermons to show per page.', 'framework' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 50,
					'default' => 1,
				]
			);

			$this->add_control(
				'pagination',
				[
					'label' => esc_html__( 'Show Pagination?', 'framework' ),
					'description' => esc_html__( 'Select no to hide pagination.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'yes'  => esc_html__( 'Yes', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
					'default' => 'yes',
				]
			);

			$this->add_control(
				'redirect',
				[
					'label' => esc_html__( 'Redirect', 'framework' ),
					'description' => esc_html__( 'Select NO to block redirection of sermons to their details page.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'yes'  => esc_html__( 'Yes', 'framework' ),
						'no'  => esc_html__( 'No', 'framework' ),
					],
					'default' => 'yes',
				]
			);

			$this->add_control(
				'meta_data',
				[
					'label' => esc_html__( 'Meta Data', 'framework' ),
					'description' => esc_html__( 'Select meta information to show for sermons.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => [
						'preacher'  => esc_html__( 'Preacher', 'framework' ),
						'books'  => esc_html__( 'Books', 'framework' ),
						'topics'  => esc_html__( 'Topics', 'framework' ),
						'categories'  => esc_html__( 'Categories', 'framework' ),
						'date'  => esc_html__( 'Date', 'framework' ),
						'series'  => esc_html__( 'Series', 'framework' ),
						'chapter'  => esc_html__( 'Chapter', 'framework' ),
						'video'  => esc_html__( 'Video', 'framework' ),
						'audio'  => esc_html__( 'Audio', 'framework' ),
						'download'  => esc_html__( 'Downloads', 'framework' ),
					],
					'default' => '',
				]
			);

			$this->add_control(
				'imi_isermons_categories',
				[
					'label' => esc_html__( 'Sermon Categories', 'framework' ),
					'description' => esc_html__( 'Select terms to show sermons only from selected one.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_cat,
					'default' => '',
				]
			);

			$this->add_control(
				'imi_isermons_series',
				[
					'label' => esc_html__( 'Sermon Series', 'framework' ),
					'description' => esc_html__( 'Select terms to show sermons only from selected one.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_series,
					'default' => '',
				]
			);

			$this->add_control(
				'imi_isermons_books',
				[
					'label' => esc_html__( 'Sermon Books', 'framework' ),
					'description' => esc_html__( 'Select terms to show sermons only from selected one.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_books,
					'default' => '',
				]
			);

			$this->add_control(
				'imi_isermons_topics',
				[
					'label' => esc_html__( 'Sermon Topics', 'framework' ),
					'description' => esc_html__( 'Select terms to show sermons only from selected one.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_topics,
					'default' => '',
				]
			);

			$this->add_control(
				'imi_isermons_preachers',
				[
					'label' => esc_html__( 'Sermon Preachers', 'framework' ),
					'description' => esc_html__( 'Select terms to show sermons only from selected one.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $sermon_preachers,
					'default' => '',
				]
			);

			$this->end_controls_section();

			// Sermon Taxonomy Fields
			$this->start_controls_section(
				'taxonomy_fields',
				[
					'label' => esc_html__( 'Sermon Taxonomy Fields', 'framework' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
					'condition' => [
						'sermon_shortcode_type' => 'isermons-terms'
					],
				]
			);

			$this->add_control(
				'term_layout',
				[
					'label' => esc_html__( 'Style', 'framework' ),
					'description' => esc_html__( 'Select the layout which you want to use for terms.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'style1'  => esc_html__( 'Grid Style 1', 'framework' ),
						'style2'  => esc_html__( 'Grid Style 2', 'framework' ),
						'style'  => esc_html__( 'List', 'framework' ),
					],
					'default' => 'style1',
				]
			);

			$this->add_control(
				'term_columns',
				[
					'label' => esc_html__( 'Grid Columns', 'framework' ),
					'description' => esc_html__( 'Select columns for grid layout.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'4'  => esc_html__( 'Four', 'framework' ),
						'3'  => esc_html__( 'Three', 'framework' ),
						'2'  => esc_html__( 'Two', 'framework' ),
						'1'  => esc_html__( 'One', 'framework' ),
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
					'label' => esc_html__( 'Excerpt Length', 'framework' ),
					'description' => esc_html__( 'Enter the number of words to show for term description.', 'framework' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '25',
				]
			);

			$this->add_control(
				'term_image',
				[
					'label' => esc_html__( 'Image Size', 'framework' ),
					'description' => esc_html__( 'Select image size, these are the all thumbnail sizes which current theme and plugins added.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $sizes,
					'default' => '',
				]
			);

			$this->add_control(
				'term_taxonomy',
				[
					'label' => esc_html__( 'Select taxonomy', 'framework' ),
					'description' => esc_html__( 'Select the taxonomy to show terms.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'imi_isermons-categories'  => esc_html__( 'Sermon Categories', 'framework' ),
						'imi_isermons-series'  => esc_html__( 'Sermon Series', 'framework' ),
						'imi_isermons-books'  => esc_html__( 'Sermon Books', 'framework' ),
						'imi_isermons-topics'  => esc_html__( 'Sermon Topics', 'framework' ),
						'imi_isermons-preachers'  => esc_html__( 'Sermon Preachers', 'framework' ),
					],
					'default' => '',
				]
			);

			$this->add_control(
				'term_categories',
				[
					'label' => esc_html__( 'Sermon Categories', 'framework' ),
					'description' => esc_html__( 'Select terms to show only selected one. Click on them one by one to add in the array.', 'framework' ),
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
					'label' => esc_html__( 'Sermon Series', 'framework' ),
					'description' => esc_html__( 'Select terms to show only selected one. Click on them one by one to add in the array.', 'framework' ),
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
					'label' => esc_html__( 'Sermon Books', 'framework' ),
					'description' => esc_html__( 'Select terms to show only selected one. Click on them one by one to add in the array.', 'framework' ),
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
					'label' => esc_html__( 'Sermon Topics', 'framework' ),
					'description' => esc_html__( 'Select terms to show only selected one. Click on them one by one to add in the array.', 'framework' ),
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
					'label' => esc_html__( 'Sermon Preachers', 'framework' ),
					'description' => esc_html__( 'Select terms to show only selected one. Click on them one by one to add in the array.', 'framework' ),
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
					'label' => esc_html__( 'Order By', 'framework' ),
					'description' => esc_html__( 'Select the orderby of terms.', 'framework' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => [
						'id'  => esc_html__( 'ID', 'framework' ),
						'count'  => esc_html__( 'Count', 'framework' ),
						'name'  => esc_html__( 'Name', 'framework' ),
						'slug'  => esc_html__( 'Slug', 'framework' ),
						'Custom'  => esc_html__( 'Custom', 'framework' ),
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
				$layout_f = $relation_f = $search_f = $filters_f = $watch_f = $columns_f = $words_f = $image_f = $per_page_f = $redirect_f = $meta_data_f = $imi_isermons_categories_f = $imi_isermons_series_f = $imi_isermons_books_f = $imi_isermons_topics_f = $imi_isermons_preachers_f = '';
				// Attributes
				$layout = $settings['layout'];
				$relation = $settings['relation'];
				$search = $settings['search'];
				$filters = $settings['filters'];
				$watch = $settings['watch'];
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
				if($relation){$relation_f = ' relation="'.$relation.'"';}
				if($search){$search_f = ' search="'.$search.'"';}
				if($filters){$filters_f = ' filters="'.$filters.'"';}
				if($watch){$watch_f = ' watch="'.$watch.'"';}
				if($columns){$columns_f = ' columns="'.$columns.'"';}
				if($words){$words_f = ' words="'.$words.'"';}
				if($image){$image_f = ' image="'.$image.'"';}
				if($per_page){$per_page_f = ' per_page="'.$per_page.'"';}
				if($redirect){$redirect_f = ' redirect="'.$redirect.'"';}
				if($pagination){$pagination_f = ' pagination="'.$pagination.'"';}
				if($meta_data){$meta_data_f = ' meta_data="'.$meta_data.'"';}
				if($imi_isermons_categories){$imi_isermons_categories_f = ' imi_isermons-categories="'.$imi_isermons_categories.'"';}
				if($imi_isermons_series){$imi_isermons_series_f = ' imi_isermons-series="'.$imi_isermons_series.'"';}
				if($imi_isermons_books){$imi_isermons_books_f = ' imi_isermons-books="'.$imi_isermons_books.'"';}
				if($imi_isermons_topics){$imi_isermons_topics_f = ' imi_isermons-topics="'.$imi_isermons_topics.'"';}
				if($imi_isermons_preachers){$imi_isermons_preachers_f = ' imi_isermons-preachers="'.$imi_isermons_preachers.'"';}
				// Shortcode
				$shortcode = ('[isermons-list'.$layout_f.$relation_f.$search_f.$filters_f.$watch_f.$columns_f.$words_f.$image_f.$per_page_f.$per_page_f.$redirect_f.$pagination_f.$meta_data_f.$imi_isermons_categories_f.$imi_isermons_series_f.$imi_isermons_books_f.$imi_isermons_topics_f.$imi_isermons_preachers_f.']');
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
				$shortcode = ('[isermons-terms'.$term_layout_f.$term_columns_f.$term_words_f.$term_image_f.$term_taxonomy_f.$filters_order_f.']');
			}

			// OUTPUT
			echo do_shortcode($shortcode);
		}

	}
}