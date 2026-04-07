<?php
$site_lang = substr(get_locale(), 0, 2);
$upcomingEvents = '';
$post_title = wp_kses_post($instance['title']);
$allpostsbtn = wp_kses_post($instance['allpostsbtn']);
$allpostsurl = sow_esc_url($instance['allpostsurl']);
/** Upcoming Events Loop ** */
$today = date_i18n('Y-m-d');
$currentTime = date(get_option('time_format'));
$event_category = wp_kses_post($instance['categories']);
$imic_events_to_show_on = (!empty($instance['number_of_events'])) ? $instance['number_of_events'] : 4;
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
$google_events = nativechurch_fetch_google_events();
$nos_event = 1;
if (!empty($google_events)) {
    $new_events = $google_events + $event_add;
} else {
    $new_events = $event_add;
}

ksort($new_events);
if (!empty($new_events)) {
    foreach ($new_events as $key => $value) {
        if (preg_match('/^[0-9]+$/', $value)) {
            $eventTime = get_post_meta($value, 'imic_event_start_tm', true);
            $eventEndTime = get_post_meta($value, 'imic_event_end_tm', true);
            $eventTime = strtotime($eventTime);
            $eventEndTime = strtotime($eventEndTime);
            if ($eventTime != '') {
                $eventTime = date_i18n(get_option('time_format'), $eventTime);
            }
            if ($eventEndTime != '') {
                $eventEndTime = date_i18n(get_option('time_format'), $eventEndTime);
            }
            $stime = '';
            $setime = '';
            if ($eventTime != '') {
                $stime = ' | ' . $eventTime;
                $setime = $eventTime;
            }
            $etime = '';
            if ($eventEndTime != '') {
                $etime = ' - ' . $eventEndTime;
            }
            $date_converted = date_i18n('Y-m-d', $key);
            $custom_event_url = imic_query_arg($date_converted, $value);
            $google_events_flag = 1;
            $event_title = get_the_title($value);
        } else {
            $google_events_flag = 2;
            $google_data = (explode('!', $value));
            $event_title = $google_data[0];
            $custom_event_url = $google_data[1];
            $stime = '';
            $setime = '';
            if ($key != '') {
                $stime = ' | ' . date_i18n(get_option('time_format'), $key);
                $setime = date_i18n(get_option('time_format'), $key);
            }
            $etime = $google_data[2];
            if ($etime != '') {$etime = ' - ' . date_i18n(get_option('time_format'), strtotime($etime));}
        }
        $upcomingEvents .= '<li class="col-md-3 format-standard">
                    	<div class="grid-item-inner">';
        if ($google_events_flag == 1) {
            $thumb_id = get_post_thumbnail_id($value);
            if (!empty($thumb_id)):
                $upcomingEvents .= '<a href="' . $custom_event_url . '" class="media-box">' . get_the_post_thumbnail($value, '600x400') . '</a>';
            endif;
        }
        $upcomingEvents .= '<div class="grid-content">';

        if ($google_events_flag == 1) {
            $e_term = get_the_terms($value, 'event-category');
            $term_link = '';
            if (!empty($e_term)) {
                $pages_e = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'template-event-category.php',
                ));
                $imic_event_category_page_url = !empty($pages_e[0]->ID) ? get_permalink($pages_e[0]->ID) : '';
                $i = 1;
                foreach ($e_term as $terms) {
                    if ($i == 1) {
                        if (!empty($imic_event_category_page_url)) {
                            $term_link = imic_query_arg_event_cat($terms->slug, $imic_event_category_page_url);
                            $term_link = '<a href="' . $term_link . '">' . $terms->name . '</a>';
                        }}
                    $i++;
                }}
            if (!empty($term_link)) {
                $upcomingEvents .= '<div class="label label-primary event-cat">' . $term_link . '</div>';
            }
        }
		if($instance['date_type']){
			$e_date_format = date_i18n(get_option('date_format'), $key);
		} else {
			$e_date_format = date_i18n('l', $key) . $stime . $etime;
		}
		
        $upcomingEvents .= '<h5><a href="' . $custom_event_url . '">' . $event_title . '</a>' . imicRecurrenceIcon($value) . '</h5>                       	<span class="meta-data"><i class="fa fa-calendar"></i>' . $e_date_format . '</span>';
        $upcomingEvents .= '</div>';
        $upcomingEvents .= '</div></li>';
        if (++$nos_event > $imic_events_to_show_on) {
            break;
        }

    }
} else {
    $no_upcoming_events_msg = __('No Upcoming Events Found', 'framework');
}
?>
            	<div class="listing">
                    <header class="listing-header">
					<?php if (!empty($instance['title'])) {?>
                    <?php if (!empty($instance['allpostsurl'])) {?><a href="<?php echo esc_url($allpostsurl); ?>" class="btn btn-primary float-end push-btn"><?php echo esc_attr($allpostsbtn); ?></a><?php }?>
                    <h3><?php echo esc_attr($post_title); ?></h3>
                    <?php }?>
                    </header>
                    <section class="listing-cont">
                        <ul class="event-blocks row">
                            <?php
echo '' . $upcomingEvents;
if (isset($no_upcoming_events_msg)):
    echo '<li class="col-md-12">' . $no_upcoming_events_msg . '</li>';
endif;
?>
                        </ul>
					</section>
                </div>