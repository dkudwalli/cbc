<?php
header('Content-Type:application/json');

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once($parse_uri[0] . 'wp-load.php');

if (function_exists('eventer_send_calendar_feed_response')) {
  eventer_send_calendar_feed_response($_REQUEST);
}

status_header(500);
echo wp_json_encode(array('error' => esc_html__('Calendar feed is unavailable.', 'eventer')));
exit;
