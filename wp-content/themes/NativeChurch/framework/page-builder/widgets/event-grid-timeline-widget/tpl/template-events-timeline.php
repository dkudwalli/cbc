<ul class="timeline">
<?php
$site_lang = substr(get_locale(), 0, 2);
$today = date_i18n('Y-m-d');
$currentTime = date_i18n(get_option('time_format'));
$upcomingEvents = '';
$event_category = wp_kses_post($instance['categories']);
$pagination = $instance['listing_layout']['show_pagination'];
$event_view_type = $instance['listing_layout']['event_type'];
if ($event_view_type == '') {
	$event_view_type = 'future';
}
$event_add = imic_recur_events($event_view_type, 'nos', $event_category, '');
$saved_future_events = get_option('nativechurch_saved_' . $event_view_type . '_events_' . $site_lang);
if ($saved_future_events) {
	$saved_events_raw = $saved_future_events;
} else {
	$saved_events_raw = imic_recur_events($event_view_type, 'nos', '', '', 'save');
}
$event_add = $saved_events_raw;
if ($event_category) {
    $events_objects = nativechurch_get_term_objects(explode(',', $event_category));
    $event_add = array_intersect($saved_events_raw, $events_objects);
}
$nos_event = 1;
$month_check = 1;
$google_events = nativechurch_fetch_google_events();
if (!empty($google_events)) {
    $new_events = $google_events + $event_add;
} else {
    $new_events = $event_add;
}

if ($event_view_type == 'future') {
	ksort($new_events);
} else {
	krsort($new_events);
}
$month_tag = '';
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$count_events = 0;
$events_per_page = $instance['listing_layout']['number_of_posts_timeline'];
$events_per_page = ($events_per_page == '') ? 1000 : $events_per_page;
$event_offset = ($paged > 1) ? ($paged - 1) * $events_per_page : 0;
$total_events = count($new_events);
$pages_created = $total_events / $events_per_page;
$nearest_pages_count = (int) $pages_created;
$set_total_pages = ($nearest_pages_count < $pages_created) ? $nearest_pages_count + 1 : $nearest_pages_count;
foreach ($new_events as $key => $value) {
	if ($event_offset > $count_events) {
        $count_events++;
        continue;
    }
    if ($count_events >= $event_offset + $events_per_page) break;
	
    $frequency = get_post_meta(get_the_ID(), 'imic_event_frequency', true);
    $frequency_count = get_post_meta(get_the_ID(), 'imic_event_frequency_count', true);

    if ($month_tag != imic_global_month_name($key)) {$month_check = 1;}
    $year_tag = date_i18n('Y', $key);
    if ($month_check == 1) {
        $month_tag = imic_global_month_name($key);}if ($month_check == 1) {$tag = '<div class="timeline-badge">' . $month_tag . '<span>' . $year_tag . '</span></div>';} else { $tag = '';}
    $month_check++;
    if (preg_match('/^[0-9]+$/', $value)) {
        $eventAddress = get_post_meta($value, 'imic_event_address', true);
        $eventContact = get_post_meta($value, 'imic_event_contact', true);
        $date_converted = date_i18n('Y-m-d', $key);
        $custom_event_url = imic_query_arg($date_converted, $value);
        $eventTime = get_post_meta($value, 'imic_event_start_tm', true);
        $eventEndTime = get_post_meta($value, 'imic_event_end_tm', true);

        //covert to timestamp
        $eventStartTime = strtotime(get_post_meta($value, 'imic_event_start_tm', true));
        $eventStartDate = strtotime(get_post_meta($value, 'imic_event_start_dt', true));
        $eventEndTime = strtotime(get_post_meta($value, 'imic_event_end_tm', true));
        $eventEndDate = strtotime(get_post_meta($value, 'imic_event_end_dt', true));

        $event_dt_out = imic_get_event_timeformate($eventStartTime . '|' . $eventEndTime, $eventStartDate . '|' . $eventEndDate, $value, $key);
        $event_dt_out = explode('BR', $event_dt_out);

        $eventTime = strtotime($eventTime);
        if ($eventTime != '') {
            $eventTime = date_i18n(get_option('time_format'), $eventTime);
        }
        $eventEndTime = strtotime($eventEndTime);
        if ($eventEndTime != '') {
            $eventEndTime = ' - ' . date_i18n(get_option('time_format'), $eventEndTime);
        }

        $stime = '';
        $setime = '';
        if ($eventTime != '') {
            $stime = ' | ' . $eventTime;
            $setime = $eventTime;
        }
        $event_title = get_the_title($value);
    } else {

        $google_data = (explode('!', $value));
        $event_title = $google_data[0];
        $custom_event_url = $google_data[1];
        $eventTime = $key;
        if ($eventTime != '') {$eventTime = date_i18n(get_option('time_format'), $key);}
        $eventEndTime = $google_data[2];
        if ($eventEndTime != '') {
            $eventEndTime = ' - ' . date_i18n(get_option('time_format'), strtotime($eventEndTime));
        }
        $eventAddress = $google_data[3];

        $event_dt_out = imic_get_event_timeformate($key . '|' . strtotime($google_data[2]), $key . '|' . $key, $value, $key);
        $event_dt_out = explode('BR', $event_dt_out);
    }
    if ($nos_event % 2 == 0) {$class = 'timeline-inverted';} else { $class = '';}
    echo '<li class="' . $class . '">
              ' . $tag . '
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h3 class="timeline-title"><a href="' . $custom_event_url . '">' . $event_title . '</a> ' . imicRecurrenceIcon($value) . '</h3>
                </div>
                <div class="timeline-body">

                    <ul class="info-table">
                      <li><i class="fa fa-calendar"></i>' . $event_dt_out[1] . '</li>';
    if (!empty($eventTime)) {
        echo '<li><i class="fa fa-clock-o"></i>' . $event_dt_out[0] . '</li>';}
    if (!empty($eventAddress)) {
        echo '<li><i class="fa fa-map-marker"></i> ' . $eventAddress . '</li>';}
    if (!empty($eventContact)) {
        echo '<li><i class="fa fa-phone"></i> ' . $eventContact . '</li>';}
    echo '</ul>
                </div>
              </div>
            </li>';
    $nos_event++;
    $count_events++;
	} ?>
</ul>
<div class="spacer-20"></div>
<?php if($pagination){
	pagination($set_total_pages, 4);
} ?>