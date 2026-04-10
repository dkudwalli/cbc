<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
	* isermons_enqueue_scripts function
	* Enqueue the style and js for front end
	* Variables of strings are used to send them in js file using wp_localize_script function, so that they can be fully translatable
	* wp_add_inline_style function used to generate dynamic css of color selected by user through settings page
*/
if (!function_exists('isermons_enqueue_scripts')) 
{
   function isermons_enqueue_scripts() 
	{
        $plugin_data = array('Version'=>1.0);
        //JS
        wp_enqueue_script('isermons-plugins', ISERMONS__PLUGIN_URL . 'js/plugins.js', array('jquery'), $plugin_data['Version'], true);
        //wp_enqueue_script('isermons-plyr', 'https://cdn.plyr.io/3.7.8/plyr.polyfilled.js', array('jquery'), $plugin_data['Version'], true);
        wp_enqueue_script('isermons-polyfilled', ISERMONS__PLUGIN_URL . 'vendor/plyr.io/plyr.polyfilled.min.js', array('jquery'), $plugin_data['Version'], true);
        wp_enqueue_script('isermons-init', ISERMONS__PLUGIN_URL . 'js/init.js', array('jquery'), $plugin_data['Version'], true);
        wp_enqueue_script('image-loaded', ISERMONS__PLUGIN_URL . 'vendor/imagesloaded.pkgd.min.js', array('jquery'), $plugin_data['Version'], true);
        wp_enqueue_script('isermons-filters', ISERMONS__PLUGIN_URL . 'js/filters.js', array('jquery'), $plugin_data['Version'], true);
        wp_localize_script('isermons-filters', 'filters', array('ajax_url' => admin_url( 'admin-ajax.php' ),
		'root' => esc_url_raw( rest_url() ),
		'nonce' => wp_create_nonce( 'wp_rest' )));
        //CSS
		wp_enqueue_style('isermons-style', ISERMONS__PLUGIN_URL . 'css/style.css', array(), $plugin_data['Version'], 'all');
        wp_enqueue_style('isermons-line-icons', ISERMONS__PLUGIN_URL . 'css/simple-line-icons.css', array(), $plugin_data['Version'], 'all');
        wp_enqueue_style('isermons-tip', ISERMONS__PLUGIN_URL . 'css/isermons-tip.css', array(), $plugin_data['Version'], 'all');
        wp_enqueue_style('isermons-plyr', ISERMONS__PLUGIN_URL . 'vendor/plyr.io/plyr.css', array(), $plugin_data['Version'], 'all');
        $isermons_default_color = isermons_get_settings('isermons_default_color');
			$isermons_color = ($isermons_default_color)?$isermons_default_color:'#007F7B';
			$css = '.isermons .isermons-btn-primary,.isermons .isermons-btn-primary:hover,.isermons input[type="radio"]:checked, .isermons input[type="checkbox"]:checked,.isermons-btn-default:hover{
                        border-color: '.$isermons_color.';
                    }
                    .isermons-loader,.isermons-btn-primary,.isermons .isermons-btn-primary:hover,.isermons input[type="radio"]:checked:before, .isermons input[type="checkbox"]:checked:before,.isermons .isermons-btn-default:hover,.isermons-default-placeholder,.isermons-pagination span.current{
                        background-color: '.$isermons_color.'
                    }
                    .plyr--audio .plyr__control.plyr__tab-focus,.plyr--audio .plyr__control:hover,.plyr--audio .plyr__control[aria-expanded=true],.plyr__control--overlaid:before,.plyr__control--overlaid:focus,.plyr__control--overlaid:hover,.plyr--video .plyr__controls .plyr__control.plyr__tab-focus,.plyr--video .plyr__controls .plyr__control:hover,.plyr--video .plyr__controls .plyr__control[aria-expanded=true],.plyr__menu__container label.plyr__control input[type=radio]:checked+span,.isermons-single .isermons-sermons-list-minimal .isermons-list-item .isermons-media:before{
                        background: '.$isermons_color.'
                    }
                    .isermons a,.isermons-media .isermons-feed-link:hover,.isermons-grid-item h4 a:hover, .isermons-list-item h4 a:hover,.isermons-sermon-actions > ul > li > a:hover,.isermons-download-files li a:hover,.plyr--full-ui input[type=range]{
                        color: '.$isermons_color.'
            }';
		 	wp_add_inline_style( 'isermons-plyr', $css );
   }
   add_action('wp_enqueue_scripts', 'isermons_enqueue_scripts');
}

$default_attribs = array('data-plyr-embed-id' => array(),'data-plyr-provider' => array(), 'name' => array(), 'class' => array(), 'maxlength' => array(),  'multiple' => array(), 'id' => array(), 'data-tprice' => array(), 'type'=>array(), 'name'=>array(), 'value' => array(), 'class'=>array(), 'data-mprice' => array(), 'style' => array(), 'data-booked' => array(), 'data-ticketid' => array(), 'data-tooltip' => array(), 'data-pid'=>array());

$isermons_allowed_tags = array(
	'select' 		=> $default_attribs,
	'p'             => $default_attribs,
	'strong'        => $default_attribs,
	'div'           => $default_attribs,
	'label'         => $default_attribs,
	'input'         => $default_attribs,
	'del'           => $default_attribs,
	'span'			=> $default_attribs,
);
$isermons_btn_allowed_tags = array(
	'span'          => $default_attribs,
	'u'             => $default_attribs,
	'i'             => $default_attribs,
	'b'             => $default_attribs,
	'br'            => $default_attribs,
	'strong'        => $default_attribs,
	'del'           => $default_attribs,
	'strike'        => $default_attribs,
	'em'            => $default_attribs,
	'img'           => $default_attribs,
);

function isermons_get_terms_data($taxonomy = '', $sermon = '', $metas = array(), $orderby = 'name', $ready = '', $set_terms = array())
{
   $isermons_terms = ($sermon!='')?get_the_terms($sermon, $taxonomy):get_terms($taxonomy, array('parent'=>0, 'orderby'=>$orderby));
   $result = ($ready!='')?'':array();
   if(!is_wp_error($isermons_terms)&&!empty($isermons_terms))
   {
    $start = 1;
      foreach($isermons_terms as $term)
      {
        if(!empty($set_terms))
        {
            $objects = get_objects_in_term($term->term_id, $taxonomy);
            if(count(array_intersect($objects, $set_terms))<=0) continue;
        }
        $meta_vals = $children = array();
         if(!empty($metas))
         {
            
            foreach($metas as $meta)
            {
               if($meta=='description')
               {
                  $meta_vals[$meta] = $term->description;
               }
               else
               {
                  $meta_vals[$meta] = get_term_meta($term->term_id, $meta, true);
               }
               
            }
         }
         $term_children = get_terms($taxonomy, array('parent'=>$term->term_id));
         if(!empty($term_children))
         {
            foreach($term_children as $cterm)
            {
                $sub_children = array();
                $nterm_children = get_terms($taxonomy, array('parent'=>$cterm->term_id));
                if(!empty($nterm_children))
                {
                    foreach($nterm_children as $nterm)
                    {
                        $sub_children[] = array("depth"=>0, "name"=>$nterm->name, "id"=>$nterm->term_id);
                    }
                }
                $children[] = array("depth"=>2, "name"=>$cterm->name, "id"=>$cterm->term_id, "children"=>$sub_children);
            }
         }
         if($ready!='')
         {
            $sep = ($start==count($isermons_terms))?'':', ';
            $result .= '<a href="'.esc_url(get_term_link($term->term_id, $taxonomy)).'">'.esc_attr($term->name).'</a>'.$sep;
         }
         else
         {
            $result[] = array("depth"=>1, "name"=>$term->name, "count"=>$term->count, "slug"=>$term->slug, "id"=>$term->term_id, "children"=>$children, "metas"=>$meta_vals);
         }
         $start++;
      }
   }
   return $result;
}

function isermons_append_template_with_arguments($slug = null, $name = null, array $params = array()) {
    global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;
      $located = '';
    do_action("get_template_part_{$slug}", $slug, $name);
    $templates = array();
    if (isset($name))
        $template_name = "{$slug}-{$name}.php";
   if ( file_exists( trailingslashit( get_stylesheet_directory() ) . '' . $template_name ) ) {
			$located = trailingslashit( get_stylesheet_directory() ) . '' . $template_name;
			//break;
 
		// Check parent theme next
		} elseif ( file_exists( trailingslashit( get_template_directory() ) . '' . $template_name ) ) {
			$located = trailingslashit( get_template_directory() ) . '' . $template_name;
			//break;
 
		// Check theme compatibility last
		} elseif ( file_exists( trailingslashit( ISERMONS__PLUGIN_PATH ) . $template_name ) ) {
			$located = trailingslashit( ISERMONS__PLUGIN_PATH ) . $template_name;
			//break;
		}
    if (is_array($wp_query->query_vars)) {
        extract($wp_query->query_vars, EXTR_SKIP);
    }
    if(!$located) return;
    extract($params, EXTR_SKIP);
    require($located);
}

function isermons_extract_video_info($url)
{
    if (stristr($url,'youtu'))
    {
        preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $video_parts);
        return array('type'=>'youtube', 'id'=>$video_parts[1]);
	}
    else if (stristr($url,'vim'))
    {
        preg_match('/https?:\/\/vimeo.com\/(\d+)$/', $url, $video_id);
        return array('type'=>'vimeo', 'id'=>$video_id[1]);
	}
    else
    {
        
        return array('type'=>'self', 'id'=>'', 'url'=>$url, 'ext'=>pathinfo($url, PATHINFO_EXTENSION));
    }
}

function isermons_get_download_path($sermon_id, $type) {
	$meta_value = get_post_meta($sermon_id, 'isermons_' . $type . '_file', true);
	if (empty($meta_value)) {
		return false;
	}

	$uploads = wp_upload_dir();
	$uploads_dir = realpath($uploads['basedir']);
	if (!$uploads_dir) {
		return false;
	}

	$attachment_id = 0;
	if (is_numeric($meta_value)) {
		$attachment_id = absint($meta_value);
	} elseif (is_string($meta_value)) {
		$attachment_id = attachment_url_to_postid($meta_value);
	}

	$file = '';
	if ($attachment_id > 0) {
		$file = get_attached_file($attachment_id);
	} elseif (is_string($meta_value)) {
		$meta_value = esc_url_raw($meta_value);
		if (strpos($meta_value, $uploads['baseurl']) === 0) {
			$relative_path = ltrim(substr($meta_value, strlen($uploads['baseurl'])), '/');
			$file = trailingslashit($uploads['basedir']) . $relative_path;
		}
	}

	if (empty($file)) {
		return false;
	}

	$real_file = realpath($file);
	if (!$real_file || !is_file($real_file) || strpos($real_file, trailingslashit($uploads_dir)) !== 0) {
		return false;
	}

	return $real_file;
}

function isermons_download_files() {
	$nonce = isset($_REQUEST['captcha']) ? sanitize_text_field(wp_unslash($_REQUEST['captcha'])) : '';
	if (!wp_verify_nonce($nonce, 'isermons-files-download')) {
		wp_die('Security check failed', '', array('response' => 403));
	}

	$type = (isset($_REQUEST['file']) && $_REQUEST['file'] != '') ? sanitize_key(wp_unslash($_REQUEST['file'])) : '';
	$sermon = (isset($_REQUEST['sermon']) && $_REQUEST['sermon'] != '') ? absint($_REQUEST['sermon']) : 0;
	$allowed_types = array('audio', 'bulletin', 'notes');
	if ($sermon <= 0 || !in_array($type, $allowed_types, true)) {
		wp_die('Invalid download request', '', array('response' => 400));
	}

	$file = isermons_get_download_path($sermon, $type);
	if (!$file) {
		wp_die('Requested file not found', '', array('response' => 404));
	}

	$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
	$allowed_extensions = array('pdf', 'mp3', 'mpa', 'ra', 'wav', 'wma', 'mid', 'm4a', 'm3u', 'iff', 'aif');
	if (!in_array($ext, $allowed_extensions, true)) {
		wp_die('Unsupported file type', '', array('response' => 400));
	}

	$filetype = wp_check_filetype($file);
	$mime_type = !empty($filetype['type']) ? $filetype['type'] : 'application/octet-stream';
	nocache_headers();
	header('Content-Description: File Transfer');
	header('Content-Type: ' . $mime_type);
	header('Content-Disposition: attachment; filename="' . basename($file) . '"');
	header('Content-Length: ' . filesize($file));

	while (ob_get_level()) {
		ob_end_clean();
	}

	readfile($file);
	exit;
}
add_action('wp_ajax_isermons_download_files', 'isermons_download_files');
add_action('wp_ajax_nopriv_isermons_download_files', 'isermons_download_files');
function isermons_single_post_content($content)
{
   if ( !in_the_loop() || !is_main_query() || !is_singular('imi_isermons'))
   {
      return $content;
   }
   $related_sermons = isermons_get_settings('isermons_sermons_related_taxonomy');
   $related_sermons_switch = isermons_get_settings('isermons_details_related');
   $related_terms = isermons_get_settings('isermons_terms_related_taxonomy');
   $related_terms_switch = isermons_get_settings('isermons_details_recent');
   $isermons_content = do_shortcode('[isermons-tabs]');
   $isermons_content .= $content;
   if($related_sermons_switch=='related')
   {
     $recent_sermons = do_shortcode($related_sermons);
     if($recent_sermons){
      $isermons_content .= '<div class="isermons-spacer-30"></div>
			<div class="isermons-inline-title"><span>'.esc_html__('Other Sermons', 'isermons').'</span></div>';
        $isermons_content .= $recent_sermons;
     } 
   }
   if($related_terms_switch=='recent')
   {
        
        $recent_terms_single = do_shortcode($related_terms);
        if($recent_terms_single){
          $isermons_content .= '<div class="isermons-spacer-30"></div>
      <div class="isermons-inline-title"><span>'.esc_html__('Recent terms', 'isermons').'</span></div>';
          $isermons_content .= $recent_terms_single;
        }
   }
   
   remove_filter( 'the_content', 'isermons_single_post_content' );
   $isermons_content_wrapper_start = '<div style="max-width: 1140px; margin: 0 auto">
		<div class="isermons isermons-single">';
    $isermons_content_wrapper_end = '</div></div>';
   return $isermons_content_wrapper_start.$isermons_content.$isermons_content_wrapper_end;
}

if(!function_exists('isermons_add_query_var'))
{
	function isermons_add_query_var( $vars )
	{
		$vars[] = "sermons";
		return $vars;
	}
	add_filter('query_vars','isermons_add_query_var');
}

if(!function_exists('isermons_url_endpoint'))
{
	function isermons_url_endpoint() {
			add_rewrite_endpoint( 'isermons', EP_ALL );
			add_rewrite_endpoint( 'pagin', EP_ALL );
	}
	add_action( 'init', 'isermons_url_endpoint' );
}

if(!function_exists('isermons_setup_seo_endpoint'))
{
	function isermons_setup_seo_endpoint() {
        add_rewrite_rule( '^(.*)/sermons/([^/]*)/?', 'index.php?pagename=$matches[1]&sermons=$matches[2]', 'top' );
	}
	add_action( 'init', 'isermons_setup_seo_endpoint', 1);
}

if(!function_exists('isermons_generate_endpoint_url'))
{
	add_action('init', 'isermons_generate_endpoint_url');
	function isermons_generate_endpoint_url($qarg = '', $qval = '', $qurl = '', $default='')
	{
		if($qarg!=''&&$qval!='')
		{
			$raw_url = $qurl;
			$query = array();
			$parts = parse_url($qurl);
			if(isset($parts['query']))
			{
				parse_str($parts['query'], $query);
				$raw_url = strtok($qurl,'?');
			} 
			$arg = esc_attr($qarg);
			$val = esc_attr($qval);
			$qurl = ($qurl=='')?get_permalink():$raw_url;
			$url = rtrim($qurl,"/");
			$permalink_status = get_option('permalink_structure');
			if($permalink_status!=''&&$default!=1)
			{
				$st_url = esc_url($url).'/'.$arg.'/'.$val;
				return esc_url(add_query_arg($query, $st_url));
			}
			else
			{
				$query[$qarg] = $qval;
				return esc_url(add_query_arg($query, $url));
			}
		}
	}
}

function isermons_content()
{
    echo do_shortcode('[isermons-terms]');
}

if (!function_exists('isermons_set_template')) 
{
	function isermons_set_template($template)   
	{    
		global $post;
        if(is_tax('imi_isermons-categories') || is_tax('imi_isermons-series') || is_tax('imi_isermons-topics') || is_tax('imi_isermons-books') || is_tax('imi_isermons-preachers'))
        {
            if (file_exists( trailingslashit( get_stylesheet_directory() ) . 'isermons.php'))
            {
                return trailingslashit(get_stylesheet_directory()).'isermons.php';
            }
            elseif (file_exists( trailingslashit( get_template_directory() ) . 'isermons.php' ))
            {
                return trailingslashit(get_template_directory()).'isermons.php';
            }
            else
            {
                return trailingslashit(ISERMONS__PLUGIN_PATH).'/templates/sermons/sermons.php';
            }
        }
		else
		{
         if(is_singular('imi_isermons'))
         {
            add_filter( 'the_content', 'isermons_single_post_content' );
         }
			return $template;
		}
	}
	add_filter('template_include', 'isermons_set_template', 99);    
}
function isermons_sermon_archive_template_redirect()
{
    $sermon_archive = isermons_get_settings('isermons_sermons_template');
    if(is_post_type_archive('imi_isermons') && $sermon_archive!='' && !is_feed())
    {
        wp_redirect(get_permalink($sermon_archive));
        die;
    }
}
add_action( 'template_redirect', 'isermons_sermon_archive_template_redirect' );
function isermons_custom_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'isermons_custom_excerpt_length', 9999 );
add_action( 'init', 'isermons_add_image_size' );
function isermons_add_image_size() {
    add_image_size( 'isermons-200-200', 200, 200, true );
    add_image_size( 'isermons-400-400', 400, 400, true );
    add_image_size( 'isermons-800-800', 800, 800, true );
    add_image_size( 'isermons-600-400', 600, 400, true );
    add_image_size( 'isermons-800-600', 600, 400, true );
}

function isermons_allowed_html(){
  $allowed_tags = array(
    'div' => array(
      'id' => array(),
      'class' => array(),
      'data-plyr-provider' => array(),
      'data-plyr-embed-id' => array()
    ),
    'abbr' => array(

    ),
    'form' => array(
      'action' => array(),
      'method' => array(),
      'class' => array()
    ),
    'input' => array(
      'type' => array(),
      'name' => array(),
      'value' => array(),
      'class' => array()
    ),
    'ul' => array(
      'class' => array()
    ),
    'i' => array(
      'class' => array()
    ),
    'li' => array(
      'class' => array()
    ),
    'h4' => array(
      'class' => array()
    ),
    'a' => array(
      'href' => array(),
      'rel' => array(),
      'class' => array(),
      'data-val' => array(),
      'tooltip-label' => array()
    ),
    'tr' => array(
      'style' => array()
    ),
    'video' => array(
      'poster' => array(),
      'id' => array(),
      'playsinline' => array(),
      'controls' => array()
    ),
    'audio' => array(
      'id' => array(),
      'playsinline' => array(),
      'controls' => array(),
      'class' => array()
    ),
    'source' => array(
      'src' => array(),
      'type' => array()
    )
  );
  return $allowed_tags;
}
