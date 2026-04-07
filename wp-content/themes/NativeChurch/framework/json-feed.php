<?php
// - standalone json feed -
header('Content-Type:application/json');
// - grab wp load, wherever it's hiding -
if (!defined('ABSPATH')) {
    $absolute_path = __FILE__;
	$path_to_file = explode( 'wp-content', $absolute_path );
	$path_to_wp = $path_to_file[0];

	// Access WordPress
	require_once( $path_to_wp . '/wp-load.php' );
}
// - grab date barrier -
//$today6am = strtotime('today 6:00') + ( get_option( 'gmt_offset' ) * 3600 );
$today = date('Y-m-d');
// - query -
$event_cat_id = '';
$month_event = $_POST['month_event'];
if (isset($_POST['event_cat_id']) && !empty($_POST['event_cat_id'])) {
    $event_cat_id = $_POST['event_cat_id'];
    $term_data = get_term_by('id', $event_cat_id, 'event-category', '', '');
    $event_cat_id = $term_data->slug;
}
$prev_month = date_i18n('Y-m', strtotime($month_event . " - 7 day"));
$prev_events = imic_recur_events_calendar('', '', $event_cat_id, $prev_month);
$current_events = imic_recur_events_calendar('', '', $event_cat_id, $month_event);
$next_month = date_i18n('Y-m', strtotime($month_event . " + 7 day"));
$next_events = imic_recur_events_calendar('', '', $event_cat_id, $next_month);
$events = $prev_events + $current_events + $next_events;
ksort($events);
$jsonevents = $duplicate_finder = array();
// - loop -
if ($events) :
    //print_r($events);
    //global $post;
    $imic_options = get_option('imic_options');
    foreach ($events as $key => $value) :
        $custom_event_url = $color = $stime = $etime = '';
        $event_start_date = get_post_meta($value, 'imic_event_start_dt', true);
        $event_end_date = get_post_meta($value, 'imic_event_end_dt', true);
        $start_date_str = strtotime($event_start_date);
        $end_date_str = strtotime($event_end_date);
        $event_start_time = get_post_meta($value, 'imic_event_start_tm', true);
        $event_end_time = get_post_meta($value, 'imic_event_end_tm', true);
        $start_time_str = strtotime($event_start_time);
        $end_time_str = strtotime($event_end_time);
        $start_date_time = strtotime(date_i18n('Y-m-d ', $start_date_str) . date_i18n('G:i', $start_time_str));
        $cat_id = wp_get_post_terms($value, 'event-category', array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all'));
        $event_color = '';
        if (!empty($cat_id)) {
            $cat_id = $cat_id[0]->term_id;
            $cat_data = get_option("category_" . $cat_id);
            $event_default_color = (isset($imic_options['event_default_color'])) ? $imic_options['event_default_color'] : '';
            $event_color = ($cat_data['catBG'] != '') ? $cat_data['catBG'] : $event_default_color;
        }
        $frequency_count = '';
        $frequency_count = get_post_meta($value, 'imic_event_frequency_count', true);
        if ($frequency > 0) {
            $event_recurring_color = (isset($imic_options['recurring_event_color'])) ? $imic_options['recurring_event_color'] : '';
            $color = ($event_color != '') ? $event_color : $event_recurring_color;
            $frequency_count = $frequency_count;
        } else {
            $frequency_count = 0;
            $color = $event_color;
        }
        $end_date_time = strtotime(date_i18n('Y-m-d ', $end_date_str) . date_i18n('G:i', $end_time_str));
        if (date_i18n('Y-m-d', $start_date_str) != date_i18n('Y-m-d', $end_date_str)) {
            $stime = date_i18n('c', $start_date_time);
            $etime = date_i18n('c', $end_date_time);
        } else {
            $stime = date_i18n('c', $key);
            $etime = date_i18n('c', $key);
        }
        $date_converted = date('Y-m-d', $key);
$custom_event_url = imic_query_arg($date_converted, $value);
if(in_array($custom_event_url, $duplicate_finder)){
continue;
}
$duplicate_finder[] = $custom_event_url;
        // - json items -
        $jsonevents[] = array(
            //'title' => html_entity_decode(get_the_title($value),ENT_QUOTES,ini_get("default_charset")),
            'title' => get_the_title($value),
            'allDay' => (get_post_meta($value, 'imic_event_all_day', true) != 1) ? false : true, // <- true by default with FullCalendar
            'start' => $stime,
            'end' => $etime,
            'url' => $custom_event_url,
            'backgroundColor' => $color,
            'borderColor' => $color
        );
    endforeach;
    // - fire away -
    $events_feeds = (isset($imic_options['event_feeds'])) ? $imic_options['event_feeds'] : '';
    if ($events_feeds == 1) {
        echo json_encode($jsonevents);
    }
endif;