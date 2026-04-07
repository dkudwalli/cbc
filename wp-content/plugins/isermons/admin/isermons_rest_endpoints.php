<?php
add_action('rest_api_init', 'wp_rest_import_endpoints');
function wp_rest_import_endpoints($request) {
  /**
   * Handle Register User request.
   */
  register_rest_route('isermons', 'import/', array(
    'methods' => 'POST',
    'callback' => 'isermons_register_isermons_manager',
	'permission_callback' => function() {
		return current_user_can( 'manage_options' );
	},
  ));
}
function isermons_register_isermons_manager($request = null)
{
  $response = array();
  $parameters = $request->get_json_params();
  $file = $_FILES['file']['tmp_name'];
	$fields = $array = array(); 
	$i = 0;
	$handle = @fopen($file, "r");
	if ($handle) 
	{
		while (($row = fgetcsv($handle, 4096)) !== false) 
		{
      if(empty($row[0])) continue;
			if (empty($fields)) 
			{
				$fields = $row;
				continue;
			}
      foreach ($row as $k=>$value) 
			{
				$array[$i][$fields[$k]] = $value; 
			}
			$i++;
    }
  }
  $response['label'] = $fields;
  $response['values'] = '<span class="isermons-import-row-data" style="display:none;">'.esc_attr(json_encode($array)).'</span>';
  $sermon_fields = '<select name="isermons-import-fields" class="isermons-import-fields">';
  $sermon_fields .= '<option value="">'.esc_html__('Select Field', 'isermons').'</option>';
  $sermon_fields .= '<option value="title">'.esc_html__('Sermon Title', 'isermons').'</option>';
  $sermon_fields .= '<option value="content">'.esc_html__('Sermon Content', 'isermons').'</option>';
  $sermon_fields .= '<option value="image">'.esc_html__('Featured Image', 'isermons').'</option>';
  $sermon_fields .= '<option value="isermons_date_preached">'.esc_html__('Date Preached', 'isermons').'</option>';
  $sermon_fields .= '<option value="isermons_audio_file">'.esc_html__('Audio File URL', 'isermons').'</option>';
  $sermon_fields .= '<option value="isermons_bulletin_file">'.esc_html__('Bulletin File URL', 'isermons').'</option>';
  $sermon_fields .= '<option value="isermons_notes_file">'.esc_html__('Note File URL', 'isermons').'</option>';
  $sermon_fields .= '<option value="isermons_video_url">'.esc_html__('Video URL', 'isermons').'</option>';
  $sermon_fields .= '<option value="imi_isermons-categories">'.esc_html__('Categories', 'isermons').'</option>';
  $sermon_fields .= '<option value="imi_isermons-series">'.esc_html__('Series', 'isermons').'</option>';
  $sermon_fields .= '<option value="imi_isermons-topics">'.esc_html__('Topics', 'isermons').'</option>';
  $sermon_fields .= '<option value="imi_isermons-books">'.esc_html__('Books', 'isermons').'</option>';
  $sermon_fields .= '<option value="imi_isermons-preachers">'.esc_html__('Preachers', 'isermons').'</option>';
  $sermon_fields .= '</select>';
  $response['fields'] = $sermon_fields;
  return new WP_REST_Response($response, 123);
}
add_action('rest_api_init', 'isermons_add_media_attachment');
function isermons_add_media_attachment($request) {
  /**
   * Handle Register User request.
   */
  register_rest_route('isermons', 'attach/', array(
    'methods' => 'POST',
    'callback' => 'isermons_upload_media',
	'permission_callback' => function() {
		return current_user_can( 'manage_options' );
	},
  ));
}
function isermons_upload_media($request = null)
{
  $response = array();
  $parameters = $request->get_json_params();
  $url = (isset($parameters['url']))?$parameters['url']:'';
  if(!$url)
  {
    $response['error'] = esc_html__('There is something went wrong', 'isermons');
    return new WP_REST_Response($response, 200);
  }
  require(ABSPATH . 'wp-admin/includes/media.php');
  require_once(ABSPATH . 'wp-admin/includes/file.php');
  require_once(ABSPATH . 'wp-admin/includes/image.php');
  $image = media_sideload_image($url, '', esc_html__('Sermon featured image', 'isermons'), 'id');
  $response['id'] = $image;
  return new WP_REST_Response($response, 123);
}
add_action("rest_insert_imi_isermons", function ($post, $request, $creating) {
  
   $terms = $request->get_param('terms');
   $custom_fields = $request->get_param('fields');
   if(!empty($custom_fields))
   {
      foreach($custom_fields as $key=>$value)
      {
        update_post_meta($post->ID, $key, $value);
      }
   }
   if(!empty($terms))
   {
      foreach($terms as $key=>$value)
      {
         $term_id = wp_set_object_terms($post->ID, $value, $key);
      }
   }    
}, 99, 3);