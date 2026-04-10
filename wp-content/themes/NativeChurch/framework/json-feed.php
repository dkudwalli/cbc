<?php
header('Content-Type:application/json');

if (!defined('ABSPATH')) {
    $absolute_path = __FILE__;
	$path_to_file = explode('wp-content', $absolute_path);
	$path_to_wp = $path_to_file[0];
	require_once($path_to_wp . '/wp-load.php');
}

if (function_exists('nativechurch_send_calendar_feed_response')) {
    nativechurch_send_calendar_feed_response($_REQUEST);
}

status_header(500);
echo wp_json_encode(array('error' => esc_html__('Calendar feed is unavailable.', 'framework')));
exit;
