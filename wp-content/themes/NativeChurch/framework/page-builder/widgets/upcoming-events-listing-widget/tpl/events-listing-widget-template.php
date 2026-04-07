<?php $post_title = wp_kses_post($instance['widget_title']);
$number = wp_kses_post($instance['number_of_events']);
$numberEvent = (!empty($number)) ? $number : 4;
$allpostsbtn = wp_kses_post($instance['allpostsbtn']);
$allpostsurl = sow_esc_url($instance['allpostsurl']);
$event_category = wp_kses_post($instance['categories']);
$EventHeading = (!empty($post_title)) ? $post_title : __('Upcoming Events', 'framework');
$today = date_i18n('Y-m-d');
$site_lang = substr(get_locale(), 0, 2);
$saved_future_events = get_option('nativechurch_saved_future_events_' . $site_lang);
if ($saved_future_events) {
    $saved_events_raw = $saved_future_events;
} else {
    $saved_events_raw = imic_recur_events('future', 'nos', '', '', 'save');
}
$event_add = $saved_events_raw;
if ($event_category) {
    $events_objects = nativechurch_get_term_objects(explode(',', $event_category));
    $event_add = array_intersect($saved_events_raw, $events_objects);
}
$nos_event = 1;
$google_events = nativechurch_fetch_google_events();
if (!empty($google_events)) {
    $new_events = $google_events + $event_add;
} else {
    $new_events = $event_add;
}

ksort($new_events);
if (!empty($new_events)) {
    echo '<div class="listing events-listing"><header class="listing-header">';
	if (!empty($instance['allpostsurl'])) {?>
		<a href="<?php echo esc_url($allpostsurl); ?>" class="btn btn-primary float-end push-btn"><?php echo esc_attr($allpostsbtn); ?></a>
	<?php }?>
	<?php echo '<h3>' . esc_attr($post_title) . '</h3></header>';
    echo '<section class="listing-cont"><ul>';
    foreach ($new_events as $key => $value) {
        if (preg_match('/^[0-9]+$/', $value)) {
            $eventTime = get_post_meta($value, 'imic_event_start_tm', true);
            if (!empty($eventTime)) {
                $eventTime = strtotime($eventTime);
                $eventTime = date_i18n(get_option('time_format'), $eventTime);
            }
            $date_converted = date_i18n('Y-m-d', $key);
            $custom_event_url = imic_query_arg($date_converted, $value);
            $event_title = get_the_title($value);
        } else {
            $google_data = (explode('!', $value));
            $event_title = $google_data[0];
            $custom_event_url = $google_data[1];
            $eventTime = '';
            if (!empty($key)) {
                $eventTime = ' | ' . date_i18n(get_option('time_format'), $key);
            }
        }
        echo '<li class="item event-item clearfix">
				<div class="event-date"> <span class="date">' . date_i18n('d', $key) . '</span> <span class="month">' . imic_global_month_name($key) . '</span> </div>
					<div class="event-detail">
						<h4><a href="' . $custom_event_url . '">' . $event_title . '</a>' . imicRecurrenceIcon($value) . '</h4>';
						$stime = '';if ($eventTime != '') {$stime = ' | ' . $eventTime;}
						$allday = get_post_meta($value, 'imic_event_all_day', true);
						$time = ($allday!='1')?date_i18n('l', $key) . $stime:esc_html__('All day', 'framework');
						echo '<span class="event-dayntime meta-data">' . $time . '</span> </div>
						<div class="to-event-url">
						<div><a href="' . $custom_event_url . '" class="btn btn-default btn-sm">' . __('Details', 'framework') . '</a></div>
					</div>
				</li>';
        if (++$nos_event > $numberEvent) {
            break;
        }

    }
    echo '</ul></section></div>';
} else {
    _e('No Upcoming Events Found', 'framework');
}
?>