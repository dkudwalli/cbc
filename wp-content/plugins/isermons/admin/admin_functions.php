<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if(!function_exists('isermons_enqueue_admin_scripts'))
{
	/*
	* Enqueue the style and js for back end
	*/
	function isermons_enqueue_admin_scripts($hook) {
		$pages = get_pages();
        $colorpicker = '';
		$list_pages = array();
		foreach ( $pages as $page )
		{
			$list_pages[] = array('label'=>$page->post_title, 'value'=>$page->ID);
		}
        if(isset($_REQUEST['taxonomy']) && ($_REQUEST['taxonomy']=='imi_isermons-categories' || $_REQUEST['taxonomy']=='imi_isermons-series' || $_REQUEST['taxonomy']=='imi_isermons-topics' || $_REQUEST['taxonomy']=='imi_isermons-books' || $_REQUEST['taxonomy']=='imi_isermons-preachers')){
				wp_enqueue_media();
		}
        if(isset($_REQUEST['page'])=='isermons_settings_options')
		{
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_style('wp-color-picker');
            $colorpicker = 1;
		}
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'isermons-admin-scripts', ISERMONS__PLUGIN_URL . 'js/admin_scripts.js', array('jquery'), '', true);
		wp_localize_script('isermons-admin-scripts', 'isermons', array('pages'=>$list_pages, 'ajax_url' => admin_url( 'admin-ajax.php' ),'root' => esc_url_raw( rest_url() ), 'nonce' => wp_create_nonce( 'wp_rest' ), 'auth_nonce' => wp_create_nonce('isermons_process_authentication'), 'color'=>$colorpicker));
		wp_enqueue_style( 'isermons-admin-style', ISERMONS__PLUGIN_URL . 'css/admin_style.css');
        wp_enqueue_style( 'jquery-ui', ISERMONS__PLUGIN_URL . 'css/smoothness-jquery-ui.css' );
        wp_enqueue_style('eventer_ui_css',ISERMONS__PLUGIN_URL . 'css/themes-jquery-ui.css',false,"1.9.0",false);

	}
	add_action( 'admin_enqueue_scripts', 'isermons_enqueue_admin_scripts' );
}

add_action( 'after_setup_theme', 'isermons_theme_setup' );
 
if ( ! function_exists( 'isermons_theme_setup' ) ) {
	function isermons_theme_setup(){
		/********* Registers an editor stylesheet for the theme ***********/
		add_action( 'admin_init', 'isermons_theme_add_editor_styles' );
		/********* TinyMCE Buttons ***********/
		add_action( 'init', 'isermons_buttons' );
	}
}

/********* Registers an editor stylesheet for the theme ***********/
if ( ! function_exists( 'isermons_theme_add_editor_styles' ) ) {
	function isermons_theme_add_editor_styles() {
	    add_editor_style( 'custom-editor-style.css' );
	}
}
 
/********* TinyMCE Buttons ***********/
if ( ! function_exists( 'isermons_buttons' ) ) {
	function isermons_buttons() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
	        return;
	    }
 
	    if ( get_user_option( 'rich_editing' ) !== 'true' ) {
	        return;
	    }
 
	    add_filter( 'mce_external_plugins', 'isermons_add_buttons' );
	    add_filter( 'mce_buttons', 'isermons_register_buttons' );
	}
}
 
if ( ! function_exists( 'isermons_add_buttons' ) ) {
	function isermons_add_buttons( $plugin_array ) {
	    $plugin_array['isermons'] = ISERMONS__PLUGIN_URL.'js/tinymce_buttons.js';
	    return $plugin_array;
	}
}
 
if ( ! function_exists( 'isermons_register_buttons' ) ) {
	function isermons_register_buttons( $buttons ) {
	    array_push( $buttons, 'isermons' );
	    return $buttons;
	}
}

function isermons_generate_shortcode()
{
	$series = isermons_get_terms_front('imi_isermons-series');
	$categories = isermons_get_terms_front('imi_isermons-categories');
	$books = isermons_get_terms_front('imi_isermons-books');
	$topics = isermons_get_terms_front('imi_isermons-topics');
	$preachers = isermons_get_terms_front('imi_isermons-preachers');
	$taxonomies = array(esc_html__('Categories', 'isermons')=>$categories, esc_html__('Series', 'isermons')=>$series, esc_html__('Books', 'isermons')=>$books, esc_html__('Topics', 'isermons')=>$topics, esc_html__('Preachers', 'isermons')=>$preachers);
	$hide_fields = ' style="display:none;"';
	?>
	<div class="ui-sortable meta-box-sortables isermons-admin-thickbox-content">
        <div class="postbox">
            <div class="inside">
				<table class="form-table">
					<tr valign="top" class="">
						<th scope="row"><?php esc_html_e( 'Select shortcode', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-name">
								<option data-relate="sermon-admin-list" value="isermons-list"><?php esc_html_e('Sermon Posts', 'isermons'); ?></option>
								<option data-relate="sermon-admin-series" value="isermons-terms"><?php esc_html_e('Sermon Taxonomy', 'isermons'); ?></option>
							</select>
							<p class="description"><?php esc_html_e('Select the shortcode which you want to generate.', 'isermons'); ?></p>
						</td>
					</tr>
					
					<tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Style', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields" data-short="layout">
								<option value="classic"><?php esc_html_e('Classic', 'isermons'); ?></option>
								<option value="minimal"><?php esc_html_e('Minimal', 'isermons'); ?></option>
								<option value="grid"><?php esc_html_e('Grid', 'isermons'); ?></option>
							</select>
							<p class="description"><?php esc_html_e('Select the layout that you want to use for sermons.', 'isermons'); ?></p>
						</td>
					</tr>
					
                    
                    <tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Relate with', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields" data-short="relation">
								<option value="categories"><?php esc_html_e('Current post category', 'isermons'); ?></option>
								<option value="series"><?php esc_html_e('Current post series', 'isermons'); ?></option>
								<option value="books"><?php esc_html_e('Current post books', 'isermons'); ?></option>
                                <option value="topics"><?php esc_html_e('Current post topics', 'isermons'); ?></option>
                                <option value="preachers"><?php esc_html_e('Current post preachers', 'isermons'); ?></option>
							</select>
							<p class="description"><?php esc_html_e('Select the taxonomy to show related sermons at sermon details page.', 'isermons'); ?></p>
						</td>
					</tr>
                    
					<tr valign="top" class="sermon-admin-series isermons-shortcode-fields sermon-admin-term-imi_isermons-categories  sermon-admin-term-imi_isermons-topics  sermon-admin-term-imi_isermons-books  sermon-admin-term-imi_isermons-series  sermon-admin-term-imi_isermons-preachers isermons-taxonomy-shortcode-fields" <?php echo wp_kses($hide_fields, isermons_allowed_html()); ?>>
						<th scope="row"><?php esc_html_e( 'Style', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields" data-short="layout">
								<option value="style1"><?php esc_html_e('Grid style1', 'isermons'); ?></option>
								<option value="style2"><?php esc_html_e('Grid style2', 'isermons'); ?></option>
								<option value="style"><?php esc_html_e('List', 'isermons'); ?></option>
							</select>
							<p class="description"><?php esc_html_e('Select the layout which you want to use for terms.', 'isermons'); ?></p>
						</td>
					</tr>
					
					
					<tr valign="top" class="sermon-admin-series isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Orderby', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields isermons-sermons-orderby" data-short="sermons_orderby">
								<option value="date"><?php esc_html_e('Published Date', 'isermons'); ?></option>
								<option value="meta_value"><?php esc_html_e('Preached Date', 'isermons'); ?></option>
								<option value="ID"><?php esc_html_e('ID', 'isermons'); ?></option>
								<option value="title"><?php esc_html_e('Title', 'isermons'); ?></option>
								<option value="name"><?php esc_html_e('Name', 'isermons'); ?></option>
							</select>
							<p class="description"><?php esc_html_e('Select the order by for the sermons.', 'isermons'); ?></p>
						</td>
					</tr>
					
					
					<tr valign="top" class="sermon-admin-series isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Order', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields isermons-sermons-orderby" data-short="sermons_order">
								<option value="DESC"><?php esc_html_e('Descending', 'isermons'); ?></option>
								<option value="ASC"><?php esc_html_e('Ascending', 'isermons'); ?></option>
							</select>
							<p class="description"><?php esc_html_e('Select the order of the sermons.', 'isermons'); ?></p>
						</td>
					</tr>
					
					<tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Search & Sort', 'isermons' ); ?></th>
						<td>
                            <label>
                            <input type="checkbox" class="isermons-admin-shortcode-fields isermons-admin-selectall" name="search" data-short="search" value="all">
                            <?php esc_html_e('All', 'isermons'); ?>
							
                            </label><br/>
							<label>
                            <input type="checkbox" class="isermons-admin-shortcode-fields" name="search" data-short="search" value="keyword">
                            <?php esc_html_e('Keyword', 'isermons'); ?>
							
                            </label><br/>
							<label>
                            <input type="checkbox" name="search" value="year">
                            <?php esc_html_e('Years', 'isermons'); ?>
							
                            </label><br/>
							<label>
                            <input type="checkbox" name="search" value="order">
                            <?php esc_html_e('Order', 'isermons'); ?>
							
                            </label><br/>
							<p class="description"><?php esc_html_e('Select fields for search and sort area.', 'isermons'); ?></p>
						</td>
					</tr>
                    
                    
					
					<tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Filters', 'isermons' ); ?></th>
						<td>
                            <label>
							<input type="checkbox" class="isermons-admin-shortcode-fields isermons-admin-selectall" name="filters" data-short="filters" value="all">
                            <?php esc_html_e('All', 'isermons'); ?>
                            </label><br/>
							<label>
							<input type="checkbox" class="isermons-admin-shortcode-fields" name="filters" data-short="filters" value="series">
                            <?php esc_html_e('Series', 'isermons'); ?>
                            </label><br/>
							<label>
							<input type="checkbox" name="filters" value="categories">
                            <?php esc_html_e('Categories', 'isermons'); ?>
                            </label><br/>
							<label>
							<input type="checkbox" name="filters" value="books">
                            <?php esc_html_e('Books', 'isermons'); ?>
                            </label><br/>
							<label>
							<input type="checkbox" name="filters" value="topics">
                            <?php esc_html_e('Topics', 'isermons'); ?>
                            </label><br/>
							<label>
							<input type="checkbox" name="filters" value="preachers">
                            <?php esc_html_e('Preachers', 'isermons'); ?>
                            </label><br/>
							<p class="description"><?php esc_html_e('Select taxonomies for search and filter area.', 'isermons'); ?></p>
						</td>
					</tr>
					
					<!--<tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Filters Operator', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields" data-short="filters_operator">
								<option value="AND"><?php esc_html_e('And', 'isermons'); ?></option>
								<option value="OR"><?php esc_html_e('OR', 'isermons'); ?></option>
							</select>
							<p class="description"><?php esc_html_e('Select the operator for filters, Ex-{And} filter will sort all terms to show matching from previously selected values.', 'isermons'); ?></p>
						</td>
					</tr>-->
                    
                    <tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Watch sermon', 'isermons' ); ?></th>
						<td>
							<input type="text" value="<?php esc_html_e('Watch sermon', 'isermons'); ?>" class="isermons-admin-shortcode-fields" data-short="watch">
							<p class="description"><?php esc_html_e('Replace text for watch sermon button.', 'isermons'); ?></p>
						</td>
					</tr>
                    
                    <tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Listen sermon', 'isermons' ); ?></th>
						<td>
							<input type="text" value="<?php esc_html_e('Listen sermon', 'isermons'); ?>" class="isermons-admin-shortcode-fields" data-short="listen">
							<p class="description"><?php esc_html_e('Button text for sermons without video media.', 'isermons'); ?></p>
						</td>
					</tr>
                    
                    <tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'View sermon', 'isermons' ); ?></th>
						<td>
							<input type="text" value="<?php esc_html_e('View sermon', 'isermons'); ?>" class="isermons-admin-shortcode-fields" data-short="details">
							<p class="description"><?php esc_html_e('Button text for sermons without video/audio media.', 'isermons'); ?></p>
						</td>
					</tr>
                    
                    <tr valign="top" class="sermon-admin-series isermons-shortcode-fields sermon-admin-term-imi_isermons-categories  sermon-admin-term-imi_isermons-topics  sermon-admin-term-imi_isermons-books  sermon-admin-term-imi_isermons-series  sermon-admin-term-imi_isermons-preachers isermons-taxonomy-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Excerpt length', 'isermons' ); ?></th>
						<td>
							<input type="text" value="25" class="isermons-admin-shortcode-fields" data-short="words">
							<p class="description"><?php esc_html_e('Enter the number of words to show for term description.', 'isermons'); ?></p>
						</td>
					</tr>
                    
                    <!--<tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Image hover', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields" data-short="hover">
								<option value="enable"><?php esc_html_e('Enable', 'isermons'); ?></option>
								<option value="disable"><?php esc_html_e('Disable', 'isermons'); ?></option>
							</select>
							<p class="description"><?php esc_html_e('Enable/Disable hover effect on image.', 'isermons'); ?></p>
						</td>
					</tr>-->
					
					<tr valign="top" class="sermon-admin-series isermons-shortcode-fields sermon-admin-term-imi_isermons-categories  sermon-admin-term-imi_isermons-topics  sermon-admin-term-imi_isermons-books  sermon-admin-term-imi_isermons-series  sermon-admin-term-imi_isermons-preachers isermons-taxonomy-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Grid column', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields" data-short="columns">
								<option value="4"><?php esc_html_e('Four', 'isermons'); ?></option>
								<option value="3"><?php esc_html_e('Three', 'isermons'); ?></option>
								<option value="2"><?php esc_html_e('Two', 'isermons'); ?></option>
								<option value="1"><?php esc_html_e('One', 'isermons'); ?></option>
							</select>
							<p class="description"><?php esc_html_e('Select columns for grid layout.', 'isermons'); ?></p>
						</td>
					</tr>
                    
                    <tr valign="top" class="isermons-shortcode-fields sermon-admin-series sermon-admin-list sermon-admin-term-imi_isermons-categories  sermon-admin-term-imi_isermons-topics  sermon-admin-term-imi_isermons-books  sermon-admin-term-imi_isermons-series  sermon-admin-term-imi_isermons-preachers isermons-taxonomy-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Image size', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields" data-short="image">
                                <?php
                                global $_wp_additional_image_sizes;
                                foreach($_wp_additional_image_sizes as $key=>$value)
                                {
                                    echo '<option value="'.esc_attr($key).'">'.esc_attr($value['width'].'X'.$value['height']).'</option>';
                                }
                                ?>
							</select>
							<p class="description"><?php esc_html_e('Select image size, these are the all thumbnail sizes which current theme and plugins added.', 'isermons'); ?></p>
						</td>
					</tr>
					
					<tr valign="top" class="sermon-admin-series isermons-shortcode-fields" <?php echo wp_kses($hide_fields, isermons_allowed_html()); ?>>
						<th scope="row"><?php esc_html_e( 'Select taxonomy', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-name isermons-admin-shortcode-fields" data-short="taxonomy">
                                <option data-relate="isermons-taxonomy-shortcode-fields" value=""><?php esc_html_e('Select', 'isermons'); ?></option>
								<option data-relate="sermon-admin-term-imi_isermons-categories" value="imi_isermons-categories"><?php esc_html_e('Sermon Categories', 'isermons'); ?></option>
								<option data-relate="sermon-admin-term-imi_isermons-series" value="imi_isermons-series"><?php esc_html_e('Sermon Series', 'isermons'); ?></option>
								<option data-relate="sermon-admin-term-imi_isermons-books" value="imi_isermons-books"><?php esc_html_e('Sermon Books', 'isermons'); ?></option>
								<option data-relate="sermon-admin-term-imi_isermons-topics" value="imi_isermons-topics"><?php esc_html_e('Sermon Topics', 'isermons'); ?></option>
								<option data-relate="sermon-admin-term-imi_isermons-preachers" value="imi_isermons-preachers"><?php esc_html_e('Sermon Preachers', 'isermons'); ?></option>
							</select>
							<p class="description"><?php esc_html_e('Select the taxonomy to show terms.', 'isermons'); ?></p>
						</td>
					</tr>
                    
                    <tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Sermons per page', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields" data-short="per_page">
                                <?php
                                for($count=1; $count<=50; $count++)
                                {
                                    echo '<option value="'.esc_attr($count).'">'.esc_attr($count).'</option>';
                                }
                                ?>
								
							</select>
							<p class="description"><?php esc_html_e('Select the number of sermons to show per page.', 'isermons'); ?></p>
						</td>
					</tr>
                    
                    <tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Show Pagination?', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields" data-short="pagination">
                                <option value="yes"><?php esc_html_e('Yes', 'isermons'); ?></option>
                                <option value="no"><?php esc_html_e('No', 'isermons'); ?></option>
							</select>
							<p class="description"><?php esc_html_e('Select no to hide pagination.', 'isermons'); ?></p>
						</td>
					</tr>
                    
                    <tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Redirect to details page', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields" data-short="redirect">
                                <option value="yes"><?php esc_html_e('Yes', 'isermons'); ?></option>
                                <option value="no"><?php esc_html_e('No', 'isermons'); ?></option>
							</select>
							<p class="description"><?php esc_html_e('Select no to block redirection of sermons to their details page.', 'isermons'); ?></p>
						</td>
					</tr>
					
					<tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Meta Data', 'isermons' ); ?></th>
						<td>
                            <label>
							<input type="checkbox" class="isermons-admin-shortcode-fields isermons-admin-selectall" name="meta_data" data-short="meta_data" value="al">
                            <?php esc_html_e('All', 'isermons'); ?>
                            </label><br/>
							<label>
							<input type="checkbox" class="isermons-admin-shortcode-fields" name="meta_data" data-short="meta_data" value="preacher">
                            <?php esc_html_e('Preacher', 'isermons'); ?>
                            </label><br/>
                            <label>
							<input type="checkbox" name="meta_data" value="books">
                            <?php esc_html_e('Books', 'isermons'); ?>
                            </label><br/>
                            <label>
							<input type="checkbox"name="meta_data" value="topics">
                            <?php esc_html_e('Topics', 'isermons'); ?>
                            </label><br/>
                            <label>
							<input type="checkbox" name="meta_data" value="categories">
                            <?php esc_html_e('Categories', 'isermons'); ?>
                            </label><br/>
							<label>
							<input type="checkbox" name="meta_data" value="date">
                            <?php esc_html_e('Date', 'isermons'); ?>
                            </label><br/>
							<label>
							<input type="checkbox" name="meta_data" value="series">
                            <?php esc_html_e('Series', 'isermons'); ?>
                            </label><br/>
							<label>
							<input type="checkbox" name="meta_data" value="chapter">
                            <?php esc_html_e('Chapter', 'isermons'); ?>
                            </label><br/>
							<label>
							<input type="checkbox" name="meta_data" value="video">
                            <?php esc_html_e('Video', 'isermons'); ?>
                            </label><br/>
							<label>
							<input type="checkbox" name="meta_data" value="audio">
                            <?php esc_html_e('Audio', 'isermons'); ?>
                            </label><br/>
							<label>
							<input type="checkbox" name="meta_data" value="download">
                            <?php esc_html_e('Download', 'isermons'); ?>
                            </label><br/>
							<p class="description"><?php esc_html_e('Select meta data for sermons.', 'isermons'); ?></p>
						</td>
					</tr>
					
					<?php
					foreach($taxonomies as $label=>$taxo)
					{
						foreach($taxo as $key=>$value)
						{
							if(empty($value)) continue;
					?>
					<tr valign="top" class="sermon-admin-list isermons-shortcode-fields">
						<th scope="row"><?php esc_html_e( 'Select ', 'isermons' ); echo esc_attr($label); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields" data-short="<?php echo esc_attr($key); ?>" multiple="multiple">
                            
								<?php foreach($value as $term)
								{
								?>
								<option value="<?php echo esc_attr($term['id']); ?>"><?php echo esc_attr($term['name']); ?></option>
								<?php
								}
								?>
							</select>
							<p class="description"><?php esc_html_e('Select terms to show sermons only from selected one.', 'isermons'); ?></p>
						</td>
					</tr>
					<?php }
					} ?>
					
					<?php
					foreach($taxonomies as $label=>$taxo)
					{
						foreach($taxo as $key=>$value)
						{
							if(empty($value)) continue;
					?>
					<tr valign="top" class="sermon-admin-term-<?php echo esc_attr($key); ?> isermons-shortcode-fields" <?php echo wp_kses($hide_fields, isermons_allowed_html()); ?>>
						<th scope="row"><?php esc_html_e( 'Select ', 'isermons' ); echo esc_attr($label); ?></th>
						<td>
							<div class="row">
								<div class="column">
									<select class="isermons-admin-shortcode-fields isermons_admin_list" data-short="<?php echo esc_attr($key); ?>">
										<option value=""><?php esc_html_e('Select', 'isermons'); ?></option>
									<?php foreach($value as $term)
									{
									?>
										<option value="<?php echo esc_attr($term['id']); ?>"><?php echo esc_attr($term['name']); ?></option>
									<?php
									}
									?>
									</select>
								</div>
								
							</div>
                            <div class="isermons-admin-enabled-area">
								</div>
							<p class="description"><?php esc_html_e('Select terms to show sermons only from selected one.', 'isermons'); ?></p>
						</td>
						<input type="hidden" class="isermons-admin-shortcode-fields" value="" data-short="custom_order_term">
					</tr>
					<?php }
					} ?>
					
					
					<tr valign="top" class="sermon-admin-series isermons-shortcode-fields sermon-admin-term-imi_isermons-categories  sermon-admin-term-imi_isermons-topics  sermon-admin-term-imi_isermons-books  sermon-admin-term-imi_isermons-series  sermon-admin-term-imi_isermons-preachers isermons-taxonomy-shortcode-fields" <?php echo wp_kses($hide_fields, isermons_allowed_html()); ?>>
						<th scope="row"><?php esc_html_e( 'Orderby', 'isermons' ); ?></th>
						<td>
							<select class="isermons-admin-shortcode-fields isermons-term-orderby" data-short="filters_order">
								<option value="id"><?php esc_html_e('ID', 'isermons'); ?></option>
								<option value="count"><?php esc_html_e('Count', 'isermons'); ?></option>
								<option value="name"><?php esc_html_e('Name', 'isermons'); ?></option>
								<option value="slug"><?php esc_html_e('Slug', 'isermons'); ?></option>
								<option value="Custom"><?php esc_html_e('Custom', 'isermons'); ?></option>
							</select>
							<p class="description"><?php esc_html_e('Select the order of terms.', 'isermons'); ?></p>
						</td>
					</tr>
					
					<tr valign="top">
						<th><button id="<?php echo wp_rand(1, 100); ?>" class="button button-primary isermons-admin-generate-shortcode"> <?php esc_html_e( 'Insert Shortcode', 'isermons' ); ?></button></th>
					</tr>
					
				</table>
			</div>
		</div>
	</div>
	<?php
	wp_die();
}

// Function to update Sermons Preached Date format to allow order by this custom date values.
// Enqueue jQuery and the custom script
add_action('admin_enqueue_scripts', 'isermons_enqueue_db_scripts');
function isermons_enqueue_db_scripts($hook) {
    // Only enqueue on the specific settings page
    
    wp_enqueue_script('jquery');
    wp_enqueue_script('isermons-progress', plugin_dir_url(__FILE__) . '../js/isermons-progress.js', array('jquery'), null, true);

    // Localize the script with admin-ajax URL and nonce
    wp_localize_script('isermons-progress', 'isermons_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('isermons_update_date_format')
    ));
}

add_action('wp_ajax_isermons_update_date_format', 'isermons_handle_update_date_format_ajax');

function isermons_handle_update_date_format_ajax() {
    // Check nonce for security
    check_ajax_referer('isermons_update_date_format', 'nonce');

    // Ensure the user has the required capability
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'You do not have permission to perform this action.'));
    }

    // Get batch size and offset from AJAX request
    $batch_size = intval($_POST['batch_size']);
    $offset = intval($_POST['offset']);

    // Perform the date format update
    global $wpdb;

    // Get total number of sermon posts (only once)
    $total_posts = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}postmeta WHERE meta_key = 'isermons_date_preached'");

    if ($total_posts <= 0) {
        // No posts to update
        wp_send_json_error(array('message' => 'No sermon posts found to update.'));
    }

    // Get batch of sermon posts
    $posts = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT meta_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'isermons_date_preached' ORDER BY meta_id ASC LIMIT %d, %d",
            $offset,
            $batch_size
        )
    );

    // Process the posts
    foreach ($posts as $row) {
        // Convert date format from 'February 8, 2024' to 'Y-m-d'
        $old_date_format = $row->meta_value;
        $new_date_format = date('Y-m-d', strtotime($old_date_format));

        // Update the date in the database
        $wpdb->update(
            "{$wpdb->prefix}postmeta",
            array('meta_value' => $new_date_format),
            array('meta_id' => $row->meta_id)
        );
    }

    // Send progress response
    wp_send_json_success(array(
        'processed' => count($posts),
        'total' => $total_posts,
        'redirect_url' => add_query_arg('date_format_updated', '1', admin_url('edit.php?post_type=imi_isermons&page=isermons_settings_options&tab=db_update'))
    ));
}
