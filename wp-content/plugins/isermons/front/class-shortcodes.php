<?php
class isermons_generate_shortcode
{
    function __construct()
    {
        add_shortcode('isermons-terms', array($this, 'isermons_terms_shortcode'));
        add_shortcode('isermons-list', array($this, 'isermons_sermon_shortcode'));
        add_shortcode('isermons-tabs', array($this, 'isermons_single_tabs'));
    }
    function isermons_terms_shortcode($atts)
    {
        $atts = shortcode_atts( array(
            'taxonomy' => '',
            'filters_order' => '',
            'custom_order_term' => '',
            'layout' => '',
            'columns' => '',
            'single' => '',
            'image' => 'full',
            'count' => '50',
            'words' => '25',
        ), $atts );
        $parameters = array();
        $taxonomy_template = isermons_get_settings('isermons_sermons_taxonomy_template');
        $parameters['term_url'] = $taxonomy_template;
        if((is_tax() || get_query_var('sermons')) && $atts['single']=='')
        {
            if(is_tax())
            {
                $term_slug = get_queried_object()->slug;
                $term_taxonomy = get_queried_object()->taxonomy;
            }
            else
            {
                $term_slug = get_query_var('sermons');
                $term_taxonomy = $atts['taxonomy'];
            }
            ob_start();
            echo '<div class="isermons-container">
                    <div class="isermons isermons-single">';
            $term_info = get_term_by('slug', $term_slug, $term_taxonomy);
            $parameters['term_name'] = $term_info->name;
            $parameters['term_slug'] = $term_info->slug;
            $parameters['term_id'] = $term_info->term_id;
            $parameters['taxonomy'] = $term_taxonomy;
            $parameters['desc'] = $term_info->description;
            $parameters['image_id'] = get_term_meta($term_info->term_id, $term_taxonomy.'_image', true);
            $parameters['catBG'] = get_term_meta($term_info->term_id, $term_taxonomy.'_catBG', true);
            $objects = get_objects_in_term($term_info->term_id, $term_taxonomy);
            $preached = get_option('isermons_sermons_data_saved');
            $dates = array();
            if(!empty($preached))
            {
                foreach($preached as $key=>$value)
                {
                    if(in_array($key, $objects))
                    {
                        $dates[] = $value['preached'];
                    }
                }
            }
            // Prevent ValueError in PHP 8 when $dates is empty
            if (!empty($dates)) {
                $parameters['min_date'] = min($dates);
            } else {
                // Handle scenario with no sermon dates found
                $parameters['min_date'] = '';
            }
            if(count($dates)>1)
            {
                $parameters['max_date'] = max($dates);
            }
            $parameters['image'] = $atts['image'];
            $preachers = wp_get_object_terms($objects, 'imi_isermons-preachers');
            $preachers_data = array();
            $parameters['sermons'] = count($objects);
            if(!is_wp_error($preachers) && !empty($preachers))
            {
                foreach($preachers as $preacher)
                {
                    $preacher_image = get_term_meta($preacher->term_id, 'imi_isermons-preachers_image', true);
                    if($preacher_image=='') continue;
                    $image = wp_get_attachment_image_src($preacher_image, 'full');
                    $preachers_data[$preacher->term_id] = array('name'=>$preacher->name, 'image'=>$image[0]);
                }
            }
            $parameters['preachers'] = $preachers_data;
            isermons_append_template_with_arguments('templates/taxonomy/single', 'term', $parameters);
            echo '</div>
            </div>';
            $output = ob_get_contents();
            ob_end_clean();
        }
        else
        {
            $layout_file = ($atts['layout']=='style')?'list':'grid';
            if($atts['filters_order']=='id' || $atts['filters_order']=='count' || $atts['filters_order']=='name' || $atts['filters_order']=='slug')
            {
                $terms_data = isermons_get_terms_data($atts['taxonomy'], '', array($atts['taxonomy'].'_image', 'description'), $atts['filters_order']);
            }
            else
            {
                $custom_order = explode(',', $atts['filters_order']);
                $counting = 1;
                foreach($custom_order as $term_custom)
                {
                    $current_series = ($counting==1)?'1':'';
                    $custom_term_data = get_term_by('id', $term_custom, $atts['taxonomy']);
                    $term_image = get_term_meta($term_custom, $atts['taxonomy'].'_image', true);
                    $terms_data[] = array("name"=>$custom_term_data->name, "count"=>$custom_term_data->count, "slug"=>$custom_term_data->slug, "id"=>$custom_term_data->term_id, "metas"=>array('description'=>$custom_term_data->description, $atts['taxonomy'].'_image'=>$term_image), 'current'=>$current_series);
                    $counting++;
                }
            }
            $parameters['image'] = $atts['image'];
            $parameters['count'] = $atts['count'];
            $parameters['terms'] = $terms_data;
            $parameters['columns'] = $atts['columns'];
            $parameters['taxonomy'] = $atts['taxonomy'];
            $parameters['words'] = $atts['words'];
            ob_start();
            if($terms_data){
              isermons_append_template_with_arguments('templates/taxonomy/'.$atts['layout'], $layout_file, $parameters);
            }
            
            $output = ob_get_contents();
            ob_end_clean();
        }
        
        return $output;
    }
    function isermons_single_tabs()
    {
        $audio_url = get_post_meta(get_the_ID(), 'isermons_audio_file', true);
        $video_url = get_post_meta(get_the_ID(), 'isermons_video_url', true);
        $bulletin = get_post_meta(get_the_ID(), 'isermons_bulletin_file', true);
        $notes = get_post_meta(get_the_ID(), 'isermons_notes_file', true);
        $parameters = array();
        $parameters['audio'] = $audio_url;
        $parameters['video'] = $video_url;
        $parameters['bulletin'] = $bulletin;
        $parameters['notes'] = $notes;
        ob_start();
        isermons_append_template_with_arguments('templates/sermons/sermon', 'tabs', $parameters);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
    function isermons_sermon_shortcode($atts)
    {
        $atts = shortcode_atts( array(
            'layout' => 'classic',
            'ss' => '',
            'years' => '',
            'orderby' => 'date',
            'order' => 'DESC',
            'search' => '',
            'filters' => '',
            'filters_operator' => '',
            'columns' => '',
            'meta_data' => '',
            'relation' => '',
            'pagination' => 'yes',
            'imi_isermons-categories' => '',
            'imi_isermons-series' => '',
            'imi_isermons-books' => '',
            'imi_isermons-topics' => '',
            'imi_isermons-preachers' => '',
            'tabs' => 'search',
            'source' => '',
            'redirect' => 'yes',
            'image' => 'full',
            'hover' => 'enable',
            'watch' => esc_html__('Watch sermon', 'isermons'),
            'listen' => esc_html__('Listen sermon', 'isermons'),
            'details' => esc_html__('View sermon', 'isermons'),
            'per_page' => get_option('posts_per_page')
        ), $atts, 'isermons-list' );
        
        $paged = (get_query_var('paged'))?get_query_var('paged'):1;
        $selected_cats = (isset($_REQUEST['imi_isermons-categories']))?$_REQUEST['imi_isermons-categories']:$atts['imi_isermons-categories'];
        $selected_series = (isset($_REQUEST['imi_isermons-series']))?$_REQUEST['imi_isermons-series']:$atts['imi_isermons-series'];
        $selected_books = (isset($_REQUEST['imi_isermons-books']))?$_REQUEST['imi_isermons-books']:$atts['imi_isermons-books'];
        $selected_topics = (isset($_REQUEST['imi_isermons-topics']))?$_REQUEST['imi_isermons-topics']:$atts['imi_isermons-topics'];
        $selected_preachers = (isset($_REQUEST['imi_isermons-preachers']))?$_REQUEST['imi_isermons-preachers']:$atts['imi_isermons-preachers'];
        $parameters = array();
        $key = (isset($_REQUEST['ss']))?$_REQUEST['ss']:$atts['ss'];
        $parameters['taxonomies'] = array('imi_isermons-categories', '', '', '', '');
        $parameters['watch'] = $atts['watch'];
        $parameters['listen'] = $atts['listen'];
        $parameters['details'] = $atts['details'];
        $post_in = array();
        if(is_singular('imi_isermons'))
        {
            $object_terms = wp_get_object_terms(get_the_ID(), 'imi_isermons-'.$atts['relation']);
            if(!empty($object_terms) && !is_wp_error($object_terms))
            {
                $term_find = $object_terms[0]->term_id;
                $post_in = get_objects_in_term($term_find, 'imi_isermons-'.$atts['relation']);
            }
            if(empty($post_in)) return;
            $post_in = array_filter($post_in, function($ids){
              return $ids!=get_the_ID();
            });
        }
        $parameters['column'] = $atts['columns'];
        $order = (isset($_REQUEST['order']))?$_REQUEST['order']:$atts['order'];
        $sermons_args = array('post_type'=>'imi_isermons', 'posts_per_page'=>$atts['per_page'], 'post_status'=>'publish', 's'=>$key, 'orderby'=>$atts['orderby'], 'order'=>$order, 'paged' => $paged, 'post__in'=>$post_in,
       'post__not_in'=>array(142));
		if($atts['orderby'] == 'meta_value'){
			$sermons_args['meta_key'] = 'isermons_date_preached';
			$sermons_args['meta_type'] = 'DATE';
		}
        $categories = $series = $books = $topics = $preachers = $term_to_match = $term_cats_match = '';
        $cat_terms = $series_terms = $books_terms = $topics_terms = $preachers_terms = '';
        $year = (isset($_REQUEST['years']))?$_REQUEST['years']:$atts['years'];
        if($year!='')
        {
            $sermons_args['meta_query'] = array(array('key'=>'isermons_date_preached', 'value'=>$year, 'compare'=>'LIKE'));
        }
        if($selected_cats!='' || $selected_series!='' || $selected_books!='' || $selected_topics!='' || $selected_preachers!='')
        {
            
            if($selected_cats!='')
            {
                $terms = explode(',', $selected_cats);
                $categories = array('taxonomy'=>'imi_isermons-categories', 'field'=>'term_id', 'terms'=>$terms);
                $term_to_match = $cat_terms = $terms[0];
                $term_cats_match = 'imi_isermons-categories';
            }
            if($selected_series!='')
            {
                $terms = explode(',', $selected_series);
                $series = array('taxonomy'=>'imi_isermons-series', 'field'=>'term_id', 'terms'=>$terms);
                $series_terms = $terms[0];
                $term_to_match = ($term_to_match=='')?$terms[0]:$term_to_match;
                $term_cats_match = ($term_cats_match=='')?'imi_isermons-series':$term_cats_match;
            }
            if($selected_books!='')
            {
                $terms = explode(',', $selected_books);
                $books = array('taxonomy'=>'imi_isermons-books', 'field'=>'term_id', 'terms'=>$terms);
                $term_to_match = ($term_to_match=='')?$terms[0]:$term_to_match;
                $books_terms = $terms[0];
                $term_cats_match = ($term_cats_match=='')?'imi_isermons-books':$term_cats_match;
            }
            if($selected_topics!='')
            {
                $terms = explode(',', $selected_topics);
                $topics = array('taxonomy'=>'imi_isermons-topics', 'field'=>'term_id', 'terms'=>$terms);
                $term_to_match = ($term_to_match=='')?$terms[0]:$term_to_match;
                $topics_terms = $terms[0];
                $term_cats_match = ($term_cats_match=='')?'imi_isermons-topics':$term_cats_match;
            }
            if($selected_preachers!='')
            {
                $terms = explode(',', $selected_preachers);
                $preachers = array('taxonomy'=>'imi_isermons-preachers', 'field'=>'term_id', 'terms'=>$terms);
                $term_to_match = ($term_to_match=='')?$terms[0]:$term_to_match;
                $preachers_terms = $terms[0];
                $term_cats_match = ($term_cats_match=='')?'imi_isermons-preachers':$term_cats_match;
            }
            $sermons_args['tax_query'] = array($atts['filters_operator'], $categories, $series, $books, $topics, $preachers);
        }
        global $wp_query;
        $original_query = $wp_query;
        $sermons_list = new WP_Query($sermons_args);
        $wp_query = $sermons_list;
        $total_sermons = $wp_query->found_posts;
        //if($total_sermons<=0) return;
        $search_vals = explode(',', $atts['search']);
        $terms_vals = explode(',', $atts['filters']);
        ob_start();
        echo ($atts['source']=='')?'<div class="isermons-list-view" data-shortcode="'.esc_attr(json_encode($atts)).'">':'';
        $search = count(array_filter($search_vals));
        $filters = count(array_filter($terms_vals));
        if($search>0 || $filters>0)
        {
            $tabs = (isset($_REQUEST['tabs']))?$_REQUEST['tabs']:$atts['tabs'];
            $objects = get_objects_in_term($term_to_match, $term_cats_match);
            $objects = (!is_wp_error($objects) && !empty($objects))?$objects:array();
            $filters_param = array();
            $filters_param['search'] = $search_vals;
            $filters_param['terms'] = $terms_vals;
            $filters_param['objects'] = $objects;
            $filters_param['categories'] = $cat_terms;
            $filters_param['books'] = $books_terms;
            $filters_param['topics'] = $topics_terms;
            $filters_param['series'] = $series_terms;
            $filters_param['preachers'] = $preachers_terms;
            $filters_param['default'] = $tabs;
            $filters_param['year'] = $year;
            $filters_param['order'] = $order;
            $filters_param['key'] = $key;
            echo '<div class="isermons isermons-tabs isermons-filter-tabs">';
            echo ($search>0)?isermons_append_template_with_arguments('templates/filters/filter', 'search', $filters_param):'';
            echo ($filters>0)?isermons_append_template_with_arguments('templates/filters/filter', 'terms', $filters_param):'';
            echo '</div>';
        }
        
        $parameters['query'] = $sermons_list;
        $parameters['image'] = $atts['image'];
        $parameters['redirect'] = $atts['redirect'];
        $meta_data = explode(',', $atts['meta_data']);
        $parameters['meta_data'] = $meta_data;
        echo '<div class="isermons-sermon-result" data-shortcode="'.esc_attr(json_encode($atts)).'">';
        if($total_sermons>0){
          isermons_append_template_with_arguments('templates/sermons/list', $atts['layout'], $parameters);
        } else{
          isermons_append_template_with_arguments('templates/sermons/list', 'none', $parameters);
        }
        
        echo '</div>';
		if($atts['pagination'] == 'yes'){
			echo '<div class="isermons-pagination">';
			echo get_the_posts_pagination();
			echo '</div>';
		}
        wp_reset_postdata();
        $wp_query = $original_query;
        echo ($atts['source']=='')?'</div>':'';
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
function isermons_initialize_shortcodes()
{
    new isermons_generate_shortcode;
}
add_action('init', 'isermons_initialize_shortcodes');