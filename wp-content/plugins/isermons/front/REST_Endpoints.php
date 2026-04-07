<?php
add_action('rest_api_init', 'isermons_load_sermons');
/**
 * Login event manager
 *
 * @param  WP_REST_Request $request Full details about the request.
 * @return array $args.
 **/
function isermons_load_sermons($request) {
  /**
   * Handle Register User request.
   */
  register_rest_route('isermons', 'sermons/', array(
    'methods' => 'POST',
    'callback' => 'isermons_generate_shortcode_data',
	'permission_callback' => '__return_true'
  ));
}
function isermons_generate_shortcode_data($request = null)
{
   $response = array();
   $parameters = $request->get_json_params();
   $result = '[isermons-list';
   foreach($parameters as $key=>$value)
   {
    $result .= ' '.$key.'="'.$value.'"';
   }
   $result .= ']';
   $response['shortcode'] = do_shortcode($result);
   return new WP_REST_Response($response, 123);
}