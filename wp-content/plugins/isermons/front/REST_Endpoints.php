<?php
if (!function_exists('isermons_rest_forbidden_response')) {
  function isermons_rest_forbidden_response($message = '')
  {
    return new WP_Error(
      'isermons_rest_forbidden',
      ($message !== '') ? $message : esc_html__('You are not allowed to access this resource.', 'isermons'),
      array('status' => rest_authorization_required_code())
    );
  }
}

if (!function_exists('isermons_rest_bad_request_response')) {
  function isermons_rest_bad_request_response($message)
  {
    return new WP_Error('isermons_rest_bad_request', $message, array('status' => 400));
  }
}

if (!function_exists('isermons_rest_require_nonce_permission')) {
  function isermons_rest_require_nonce_permission($request)
  {
    $nonce = $request->get_header('X-WP-Nonce');
    if (empty($nonce)) {
      $nonce = $request->get_param('_wpnonce');
    }
    $nonce = is_string($nonce) ? sanitize_text_field(wp_unslash($nonce)) : '';

    if (!empty($nonce) && wp_verify_nonce($nonce, 'wp_rest')) {
      return true;
    }

    return isermons_rest_forbidden_response(esc_html__('A valid request token is required.', 'isermons'));
  }
}

if (!function_exists('isermons_sanitize_shortcode_attribute_value')) {
  function isermons_sanitize_shortcode_attribute_value($attribute, $value)
  {
    if (is_array($value)) {
      $value = implode(',', array_map('sanitize_text_field', wp_unslash($value)));
    } else {
      $value = (string) wp_unslash($value);
    }

    switch ($attribute) {
      case 'per_page':
      case 'paged':
        return (string) absint($value);
      default:
        return sanitize_text_field($value);
    }
  }
}

if (!function_exists('isermons_build_list_shortcode')) {
  function isermons_build_list_shortcode($parameters)
  {
    $allowed_attributes = array(
      'layout',
      'ss',
      'years',
      'orderby',
      'order',
      'search',
      'filters',
      'filters_operator',
      'columns',
      'meta_data',
      'relation',
      'pagination',
      'imi_isermons-categories',
      'imi_isermons-series',
      'imi_isermons-books',
      'imi_isermons-topics',
      'imi_isermons-preachers',
      'tabs',
      'source',
      'redirect',
      'image',
      'hover',
      'watch',
      'listen',
      'details',
      'per_page',
      'paged',
    );

    $shortcode = '[isermons-list';
    foreach ($allowed_attributes as $attribute) {
      if (!isset($parameters[$attribute])) {
        continue;
      }

      $value = isermons_sanitize_shortcode_attribute_value($attribute, $parameters[$attribute]);
      if ($value === '') {
        continue;
      }

      $shortcode .= sprintf(' %s="%s"', sanitize_key($attribute), esc_attr($value));
    }
    $shortcode .= ']';

    return $shortcode;
  }
}

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
	'permission_callback' => 'isermons_rest_require_nonce_permission'
  ));
}
function isermons_generate_shortcode_data($request = null)
{
   $response = array();
   $parameters = $request->get_json_params();
   $result = isermons_build_list_shortcode((array) $parameters);
   if ($result === '[isermons-list]') {
    return isermons_rest_bad_request_response(esc_html__('Invalid shortcode request.', 'isermons'));
   }
   $response['shortcode'] = do_shortcode($result);
   return new WP_REST_Response($response, 200);
}
