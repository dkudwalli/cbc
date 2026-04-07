<?php
/* ==================================================
SHORTCODES OUTPUT
================================================== */
/* BUTTON SHORTCODE
================================================== */
function nativehcurch_imic_button($atts, $content = null)
{
    extract(shortcode_atts(array(
        "colour" => "",
        "type" => "",
        "link" => "#",
        "target" => '_self',
        "size" => '',
        "extraclass" => '',
    ), $atts));
    $button_output = "";
    $button_class = 'btn ' . $colour . ' ' . $extraclass . ' ' . $size;
    $buttonType = ($type == 'disabled') ? 'disabled="disabled"' : '';
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $button_output .= '<a class="' . $button_class . '" href="' . $link . '" target="' . $target . '" ' . $buttonType . '>' . $shortcode_content . '</a>';
    return $button_output;
}
add_shortcode('imic_button', 'nativehcurch_imic_button');
/* ICON SHORTCODE
================================================== */
function nativehcurch_imic_icon($atts, $content = null)
{
    extract(shortcode_atts(array(
        "image" => "",
    ), $atts));
    return '<i class="fa ' . $image . '"></i>';
}
add_shortcode('icon', 'nativehcurch_imic_icon');
/* STAFF SHORTCODE
================================================== */
function nativehcurch_imic_staff($atts, $content = null)
{
    extract(shortcode_atts(array(
        "number" => "",
        "order" => "",
        "category" => "",
        "column" => "",
        "excerpt_length" => "",
    ), $atts));
    $output = '';
    if ($order == "no") {
        $orderby = "ID";
        $sort_order = "DESC";
    } else {
        $orderby = "menu_order";
        $sort_order = "ASC";
    }
    if ($excerpt_length == '') {
        $excerpt_length = 30;
    }
    if ($column == 3) {
        $column = 4;
    } elseif ($column == 4) {
        $column = 3;
    } elseif ($column == 2) {
        $column = 6;
    } elseif ($column == 1) {
        $column = 12;
    } else {
        $column = 4;
    }
    query_posts(array(
        'post_type' => 'staff',
        'staff-category' => $category,
        'posts_per_page' => $number,
        'orderby' => $orderby,
        'order' => $sort_order,
    ));
    if (have_posts()) :
        $output .= '<div class="row">';
        while (have_posts()) : the_post();
            $custom = get_post_custom(get_the_ID());
            $output .= '<div class="col-md-' . $column . ' col-sm-' . $column . '">																																				<div class="grid-item staff-item">																																										<div class="grid-item-inner">';
            if (has_post_thumbnail()) :
                $output .= '<div class="media-box"><a href="' . get_permalink(get_the_ID()) . '">';
                $output .= get_the_post_thumbnail(get_the_ID(), 'full');
                $output .= '</a></div>';
            endif;
            $job_title = get_post_meta(get_the_ID(), 'imic_staff_job_title', true);
            $job = '';
            if (!empty($job_title)) {
                $job = '<div class="meta-data">' . $job_title . '</div>';
            }
            $output .= '<div class="grid-content">																																												<h3> <a href="' . get_permalink(get_the_ID()) . '">' . get_the_title() . '</a></h3>';
            $output .= $job;
            $output .= imic_social_staff_icon();
            $excerpt_length;
            $description = imic_excerpt($excerpt_length);
            if ($excerpt_length != 0) {
                if (!empty($description)) {
                    $output .= $description;
                }
            }
            global $imic_options;
            if ($excerpt_length != 0) {
                $staff_read_more_text = $imic_options['staff_read_more_text'];
                if ($imic_options['switch_staff_read_more'] == 1 && $imic_options['staff_read_more'] == '0') {
                    $output .= '<p><a href="' . get_permalink() . '" class="btn btn-default">' . $staff_read_more_text . '</a></p>';
                } elseif ($imic_options['switch_staff_read_more'] == 1 && $imic_options['staff_read_more'] == '1') {
                    $output .= '<p><a href="' . get_permalink() . '">' . $staff_read_more_text . '</a></p>';
                }
            }
            $output .= '</div></div></div></div>';
        endwhile;
        $output .= '</div>';
    endif;
    wp_reset_query();
    return $output;
}
add_shortcode('staff', 'nativehcurch_imic_staff');
/* Sermon SHORTCODE
================================================== */
function nativehcurch_imic_sermon($atts, $content = null)
{
    extract(shortcode_atts(array(
        "number" => "",
        "title" => "",
        "category" => "",
        "column" => "",
    ), $atts));
    $output = '';
    query_posts(array(
        'post_type' => 'sermons',
        'sermons-category' => $category,
        'posts_per_page' => $number,
        'orderby' => 'ID',
        'order' => 'DESC',
    ));
    if (have_posts()) :
        $output .= '<div class="col-md-' . $column . ' sermon-archive">';
        if (!empty($title)) :
            $output .= '<h2>' . $title . '</h2>';
        endif;
        ?>
        <!-- Sermons Listing -->
        <?php
        while (have_posts()) : the_post();
            if ('' != get_the_post_thumbnail()) {
                $class = "col-md-8";
            } else {
                $class = "col-md-12";
            }
            $custom = get_post_custom(get_the_ID());
            $output .= '<article class="post sermon">																																											<header class="post-title">';
            $output .= '<div class="row">																																														<div class="col-md-9 col-sm-9">																																											<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            $output .= '<span class="meta-data"><i class="fa fa-calendar"></i>' . esc_html__('Posted on ', 'imithemes') . get_the_time(get_option('date_format'));
            $output .= get_the_term_list(get_the_ID(), 'sermons-speakers', ' | ' . esc_html__('Pastor: ', 'imithemes'), ', ', '');
            $output .= '</span></div>';
            $output .= '<div class="col-md-3 col-sm-3 sermon-actions">';
            if (!empty($custom['imic_sermons_url'][0])) {
                $output .= '<a href="' . get_permalink() . '" data-placement="top" data-toggle="tooltip" data-original-title="' . esc_html__('Video', 'imithemes') . '" rel="tooltip"><i class="fa fa-video-camera"></i></a>';
            }
            $attach_full_audio = imic_sermon_attach_full_audio(get_the_ID());
            if (!empty($attach_full_audio)) {
                $output .= '<a href="' . get_permalink() . '/#play-audio" data-placement="top" data-toggle="tooltip" data-original-title="' . esc_html__('Audio', 'imithemes') . '" rel="tooltip"><i class="fa fa-headphones"></i></a>';
            }
            $output .= '<a href="' . get_permalink() . '" data-placement="top" data-toggle="tooltip" data-original-title="' . esc_html__('Read online', 'imithemes') . '" rel="tooltip"><i class="fa fa-file-text-o"></i></a>';
            $attach_pdf = imic_sermon_attach_full_pdf(get_the_ID());
            if (!empty($attach_pdf)) {
                $output .= '<a href="' . $attach_pdf . '" download data-placement="top" data-toggle="tooltip" data-original-title="' . esc_html__('Download PDF', 'imithemes') . '" rel="tooltip"><i class="fa fa-book"></i></a>';
            }
            $output .= '</div></div>';
            $output .= '</header>																																													<div class="post-content">																																													<div class="row">';
            if (has_post_thumbnail()) :
                $output .= '<div class="col-md-4">																																	                                    <a href="' . get_permalink(get_the_ID()) . '" class="media-box">';
                $output .= get_the_post_thumbnail(get_the_ID(), 'full', array('class' => "img-thumbnail"));
                $output .= '</a></div>';
            endif;
            $output .= '<div class="' . $class . '">';
            $description = imic_excerpt(100);
            if (!empty($description)) {
                $output .= $description;
            }
            $output .= '<p><a href="' . get_permalink() . '" class="btn btn-primary">' . esc_html__('Continue reading ', 'imithemes') . '<i class="fa fa-long-arrow-right"></i></a></p>';
			$output .= '</div></div></div></article>';
        endwhile;
        $output .= '</div>';
    endif;
    wp_reset_query();
    return $output;
}
add_shortcode('sermon', 'nativehcurch_imic_sermon');
/* Event SHORTCODE
================================================== */
function nativehcurch_imic_event($atts, $content = null)
{
    extract(shortcode_atts(array(
        "number" => 10,
        "title" => "",
        "category" => "",
        "style" => "",
        "type" => "",
    ), $atts));
    $site_lang = substr(get_locale(), 0, 2);
    $output = '';
    $number = ($number == '') ? 10 : $number;
    $site_lang = substr(get_locale(), 0, 2);
    if ($type == 'future') {
        $saved_future_events = get_option('nativechurch_saved_future_events_' . $site_lang);
        if ($saved_future_events) {
            $saved_events_raw = $saved_future_events;
        } else {
            $saved_events_raw = imic_recur_events('future', 'nos', '', '', 'save');
        }
        $future_events = $saved_events_raw;
        if ($category) {
            $events_objects = nativechurch_get_term_objects(explode(',', $category));
            $future_events = array_intersect($saved_events_raw, $events_objects);
        }
        $google_events = nativechurch_fetch_google_events();
        $events = $future_events + $google_events;
        ksort($events);
    } else {
        $saved_future_events = get_option('nativechurch_saved_past_events_' . $site_lang);
        if ($saved_future_events) {
            $saved_events_raw = $saved_future_events;
        } else {
            $saved_events_raw = imic_recur_events('past', 'nos', '', '', 'save');
        }
        $events = $saved_events_raw;
        if ($category) {
            $events_objects = nativechurch_get_term_objects(explode(',', $category));
            $events = array_intersect($saved_events_raw, $events_objects);
        }
        krsort($events);
    }
    if ($style == "list") {
        $count = 1;
        $output .= '<div class="listing events-listing">
	<header class="listing-header">
            	<div class="row">
                	<div class="col-md-12 col-sm-12">
          				<h3>' . esc_attr($title) . '</h3>
                  </div>
							</div>
							</header>';
        $output .= '<section class="listing-cont">
              <ul>';
        if (!empty($events)) {
            foreach ($events as $key => $value) {
                if (preg_match('/^[0-9]+$/', $value)) {
                    $eventStartTime = strtotime(get_post_meta($value, 'imic_event_start_tm', true));
                    $eventStartDate = strtotime(get_post_meta($value, 'imic_event_start_dt', true));
                    $eventEndTime = strtotime(get_post_meta($value, 'imic_event_end_tm', true));
                    $eventEndDate = strtotime(get_post_meta($value, 'imic_event_end_dt', true));
                    $evstendtime = $eventStartTime . '|' . $eventEndTime;
                    $evstenddate = $eventStartDate . '|' . $eventEndDate;
                    $date_converted = date('Y-m-d', $key);
                    $custom_event_url = imic_query_arg($date_converted, $value);
                    $event_dt_out = imic_get_event_timeformate($evstendtime, $evstenddate, $value, $key);
                    $event_dt_out = explode('BR', $event_dt_out);
                    if ($eventStartTime != '') {
                        $eventStartTime = date(get_option('time_format'), $eventStartTime);
                    }
                    $custom_event_url = imic_query_arg($date_converted, $value);
                    $event_title = get_the_title($value);
                    $stime = '';
                    if ($eventStartTime != '') {
                        $stime = ' | ' . $eventStartTime;
                    }
                } else {
                    $google_data = (explode('!', $value));
                    $event_title = $google_data[0];
                    $custom_event_url = $google_data[1];
                    $options = get_option('imic_options');

                    $eventTime = $key;
                    if ($eventTime != '') {
                        $eventTime = date_i18n(get_option('time_format'), $key);
                    }
                    $eventEndTime = $google_data[2];
                    if ($eventEndTime != '') {
                        $eventEndTime = ' - ' . date_i18n(get_option('time_format'), strtotime($eventEndTime));
                    }
                    $eventAddress = $google_data[3];
                    $event_dt_out = imic_get_event_timeformate($key . '|' . strtotime($google_data[2]), $key . '|' . $key, $value, $key);
                    $event_dt_out = explode('BR', $event_dt_out);
                }
                $output .= '<li class="item event-item">
             			<div class="event-date">
										<span class="date">' . date_i18n('d', $key) . '</span>
                       <span class="month">' . imic_global_month_name($key) . '</span>
									</div>
                	<div class="event-detail">
            				<h4>
                			<a href="' . $custom_event_url . '">
					   ' . $event_title . ' </a>' . imicRecurrenceIcon($value) . '
                 		</h4>
                  	<span class="event-dayntime meta-data">
					   				' . $event_dt_out[1] . ',&nbsp;&nbsp;' . $event_dt_out[0]
                    . '</span>
									</div>
                	<div class="to-event-url">
              	<div>
								<a href="' . $custom_event_url . '" class="btn btn-default btn-sm">' . esc_html__('Details', 'imithemes') . '</a></div>
                      </div>
                    </li>';
                if ($count++ >= $number) {
                    break;
                }
            }
        }
        $output .= '</ul>
				</section></div>';
    } else {
        $output .= '<header class="listing-header">
            	<div class="row">
                	<div class="col-md-12 col-sm-12">
          				<h3>' . esc_attr($title) . '</h3>
                  </div>
							</div>
							</header>';
        $output .= '<div class="row">';
        $output .= '<ul class="grid-holder col-3 events-grid">';

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $count = 1;
        $grid_item = 1;
        $perPage = get_option('posts_per_page');
        $paginate = 1;
        if ($paged > 1) {
            $paginate = ($paged - 1) * $perPage;
            $paginate = $paginate + 1;
        }
        $TotalEvents = count($events);
        if ($TotalEvents % $perPage == 0) {
            $TotalPages = $TotalEvents / $perPage;
        } else {
            $TotalPages = $TotalEvents / $perPage;
            $TotalPages = $TotalPages + 1;
        }
        foreach ($events as $key => $value) {
            if (preg_match('/^[0-9]+$/', $value)) {
                $google_flag = 1;
            } else {
                $google_flag = 2;
            }
            if ($google_flag == 1) {
                setup_postdata(get_post($value));
                $eventStartTime = strtotime(get_post_meta($value, 'imic_event_start_tm', true));
                $eventStartDate = strtotime(get_post_meta($value, 'imic_event_start_dt', true));
                $eventEndTime = strtotime(get_post_meta($value, 'imic_event_end_tm', true));
                $eventEndDate = strtotime(get_post_meta($value, 'imic_event_end_dt', true));
                $event_dt_out = imic_get_event_timeformate($eventStartTime . '|' . $eventEndTime, $eventStartDate . '|' . $eventEndDate, $value, $key);
                $event_dt_out = explode('BR', $event_dt_out);
                $registration_status = get_post_meta($value, 'imic_event_registration_status', true);
                /** Event Details Manage **/
                if ($registration_status == 1 && (function_exists('imic_get_currency_symbol'))) {
                    $eventDetailIcons = array('fa-calendar', 'fa-clock-o', 'fa-map-marker', 'fa-money');
                } else {
                    $eventDetailIcons = array('fa-calendar', 'fa-clock-o', 'fa-map-marker');
                }
                $stime = "";
                $etime = "";
                if ($eventStartTime != '') {
                    $stime = ' | ' . date_i18n(get_option('time_format'), $eventStartTime);
                }
                if ($eventEndTime != '') {
                    $etime = ' - ' . date_i18n(get_option('time_format'), $eventEndTime);
                }
                if ($registration_status == 1 && (function_exists('imic_get_currency_symbol'))) {
                    $event_registration_fee = get_post_meta($value, 'imic_event_registration_fee', true);
                    $registration_charge = ($event_registration_fee == '') ? 'Free' : imic_get_currency_symbol(get_option('paypal_currency_options')) . get_post_meta($value, 'imic_event_registration_fee', true);
                    $eventDetailsData = array($event_dt_out[1], $event_dt_out[0], get_post_meta($value, 'imic_event_address', true), $registration_charge);
                    /*
                $eventDetailsData = array(date_i18n('j M, ',$key).date_i18n('l',$key). $stime .  $etime, get_post_meta($value,'imic_event_address',true),$registration_charge);
                 */
                } else {
                    /*$eventDetailsData = array(date_i18n('j M, ',$key).date_i18n('l',$key). $stime .  $etime, get_post_meta($value,'imic_event_address',true));*/
                    $eventDetailsData = array($event_dt_out[1], $event_dt_out[0], get_post_meta($value, 'imic_event_address', true));
                }
                $eventValues = array_filter($eventDetailsData, 'strlen');
            }
            if ($count == $paginate && $grid_item <= $perPage) {
                $paginate++;
                $grid_item++;
                if ($google_flag == 1) {
                    $frequency = get_post_meta($value, 'imic_event_frequency', true);
                }
                //if ('' != get_the_post_thumbnail($value)) {
                $output .= '<li class="grid-item format-standard">';
                if ($google_flag == 1) {
                    $date_converted = date('Y-m-d', $key);
                    $custom_event_url = imic_query_arg($date_converted, $value);
                }
                if ($google_flag == 2) {
                    $google_data = (explode('!', $value));
                    $event_title = $google_data[0];
                    $custom_event_url = $google_data[1];
                    $stime = "";
                    $etime = "";
                    $etime = $google_data[2];
                    if ($key != '') {
                        $stime = ' | ' . date_i18n(get_option('time_format'), $key);
                    }
                    if ($etime != '') {
                        $etime = ' - ' . date_i18n(get_option('time_format'), strtotime($etime));
                    }
                    $eventAddress = $google_data[3];
                    /* $eventDetailsData = array(date_i18n('j M, ',$key).date_i18n('l',$key). $stime .  $etime,$eventAddress);*/
                    $event_dt_out = imic_get_event_timeformate($key . '|' . $google_data[2], $key . '|' . $key, $value, $key);
                    $event_dt_out = explode('BR', $event_dt_out);
                    $eventDetailsData = array($event_dt_out[1], $event_dt_out[0], $eventAddress);
                    $eventValues = array_filter($eventDetailsData, 'strlen');
                    $eventDetailIcons = array('fa-calendar', 'fa-clock-o', 'fa-map-marker');
                }
                $output .= '<div class="grid-item-inner">';
                if ($google_flag == 1) {
                    $output .= '<a href="' . $custom_event_url . '" class="media-box">';
                    $output .= get_the_post_thumbnail($value, 'full');
                    $output .= '</a>';
                    $event_title = get_the_title($value);
                }
                $output .= '<div class="grid-content">';
                $output .= '<h3><a href="' . $custom_event_url . '">' . $event_title . '</a>' . imicRecurrenceIcon($value) . '</h3>';
                if ($google_flag == 1) {
                    $output .= '<div class="page-content">';
                    $output .= imic_excerpt_id($value, 25);
                    $output .= '</div>';
                }
                $output .= '</div>';
                if (!empty($eventValues)) {
                    $output .= '<ul class="info-table">';
                    $flag = 0;
                    foreach ($eventDetailsData as $edata) {
                        if (!empty($edata)) {
                            $output .= '<li><i class="fa ' . $eventDetailIcons[$flag] . '"></i> ' . $edata . ' </li>';
                        }
                        $flag++;
                    }
                    $output .= '</ul>';
                    //}
                    $output .= '</div>
		</li>';
                }
            }
            $count++;
        }
        $output .= '</ul></div>';
    }
    return $output;
}
add_shortcode('event', 'nativehcurch_imic_event');
/* IMAGE SHORTCODE
================================================== */
function nativehcurch_imic_imagebanner($atts, $content = null)
{
    extract(shortcode_atts(array(
        "image" => "",
        "width" => "",
        "height" => "",
        "extraclass" => "",
    ), $atts));
    $imgWidth = (!empty($width)) ? 'width="' . $width . '"' : '';
    $imgHeight = (!empty($height)) ? ' height="' . $height . '"' : '';
    $image_banner = '';
    $image_banner .= '<div class="post-image">
			<figure class="post-thumbnail"><img src="' . $image . '" alt="image" class="thumbnail" ' . $imgWidth . $imgHeight . '></figure>
	  	</div>';
    return $image_banner;
}
add_shortcode('imic_image', 'nativehcurch_imic_imagebanner');
/* COLUMN SHORTCODES
================================================== */
function nativehcurch_imic_one_full($atts, $content = null)
{
    extract(shortcode_atts(array(
        "extra" => '',
        "anim" => '',
    ), $atts));
    $animation = (!empty($anim)) ? 'data-appear-animation="' . $anim . '"' : '';
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="col-md-12 ' . $extra . '" ' . $animation . '>' . $shortcode_content . '</div>';
}
add_shortcode('one_full', 'nativehcurch_imic_one_full');
function nativehcurch_imic_one_half($atts, $content = null)
{
    extract(shortcode_atts(array(
        "extra" => '',
        "anim" => '',
    ), $atts));
    $animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="col-md-6 ' . $extra . '" ' . $animation . '>' . $shortcode_content . '</div>';
}
add_shortcode('one_half', 'nativehcurch_imic_one_half');
function nativehcurch_imic_one_third($atts, $content = null)
{
    extract(shortcode_atts(array(
        "extra" => '',
        "anim" => '',
    ), $atts));
    $animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="col-md-4 ' . $extra . '" ' . $animation . '>' . $shortcode_content . '</div>';
}
add_shortcode('one_third', 'nativehcurch_imic_one_third');
function nativehcurch_imic_one_fourth($atts, $content = null)
{
    extract(shortcode_atts(array(
        "extra" => '',
        "anim" => '',
    ), $atts));
    $animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="col-md-3 ' . $extra . '" ' . $animation . '>' . $shortcode_content . '</div>';
}
add_shortcode('one_fourth', 'nativehcurch_imic_one_fourth');
function nativehcurch_imic_one_sixth($atts, $content = null)
{
    extract(shortcode_atts(array(
        "extra" => '',
        "anim" => '',
    ), $atts));
    $animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="col-md-2 ' . $extra . '" ' . $animation . '>' . $shortcode_content . '</div>';
}
add_shortcode('one_sixth', 'nativehcurch_imic_one_sixth');
function nativehcurch_imic_two_third($atts, $content = null)
{
    extract(shortcode_atts(array(
        "extra" => '',
        "anim" => '',
    ), $atts));
    $animation = ($anim != 0) ? 'data-appear-animation="bounceInRight"' : '';
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="col-md-8 ' . $extra . '" ' . $animation . '>' . $shortcode_content . '</div>';
}
add_shortcode('two_third', 'nativehcurch_imic_two_third');
/* TABLE SHORTCODES
================================================= */
function nativehcurch_imic_table_wrap($atts, $content = null)
{
    extract(shortcode_atts(array(
        "type" => '',
    ), $atts));
    $output = '<table class="table ' . $type . '">';
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output .= $shortcode_content . '</table>';
    return $output;
}
add_shortcode('htable', 'nativehcurch_imic_table_wrap');
function nativehcurch_imic_table_headtag($atts, $content = null)
{
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output = '<thead>' . $shortcode_content . '</thead>';
    return $output;
}
add_shortcode('thead', 'nativehcurch_imic_table_headtag');
function nativehcurch_imic_table_body($atts, $content = null)
{
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output = '<tbody>' . $shortcode_content . '</tbody>';
    return $output;
}
add_shortcode('tbody', 'nativehcurch_imic_table_body');
function nativehcurch_imic_table_row($atts, $content = null)
{
    $output = '<tr>';
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output .= $shortcode_content . '</tr>';
    return $output;
}
add_shortcode('trow', 'nativehcurch_imic_table_row');
function nativehcurch_imic_table_column($atts, $content = null)
{
    $output = '<td>';
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output .= $shortcode_content . '</td>';
    return $output;
}
add_shortcode('tcol', 'nativehcurch_imic_table_column');
function nativehcurch_imic_table_head($atts, $content = null)
{
    $output = '<th>';
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output .= $shortcode_content . '</th>';
    return $output;
}
add_shortcode('thcol', 'nativehcurch_imic_table_head');
/* TYPOGRAPHY SHORTCODES
================================================= */
// Anchor tag
function nativehcurch_imic_anchor($atts, $content = null)
{
    extract(shortcode_atts(array(
        "href" => '',
        "extra" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<a href="' . $href . '" class="' . $extra . '" >' . $shortcode_content . ' </a>';
}
add_shortcode('anchor', 'nativehcurch_imic_anchor');
// Alert tag
function nativehcurch_imic_alert($atts, $content = null)
{
    extract(shortcode_atts(array(
        "type" => '',
        "close" => '',
    ), $atts));
    $closeButton = ($close == 'yes') ? '<a class="close" data-dismiss="alert" href="#">&times;</a>' : '';
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="alert ' . $type . ' fade in">  ' . $closeButton . $shortcode_content . ' </div>';
}
add_shortcode('alert', 'nativehcurch_imic_alert');
// Heading Tag
function nativehcurch_imic_heading_tag($atts, $content = null)
{
    extract(shortcode_atts(array(
        "size" => '',
        "extra" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output = '<' . $size . ' class="' . $extra . '">' . $shortcode_content . '</' . $size . '>';
    return $output;
}
add_shortcode("heading", "nativehcurch_imic_heading_tag");
// Divider Tag
function nativehcurch_imic_divider_tag($atts, $content = null)
{
    extract(shortcode_atts(array(
        "extra" => '',
    ), $atts));
    return '<hr class="' . $extra . '">';
}
add_shortcode("divider", "nativehcurch_imic_divider_tag");
// Paragraph type
function nativehcurch_imic_paragraph($atts, $content = null)
{
    extract(shortcode_atts(array(
        "extra" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<p class="' . $extra . '">' . $shortcode_content . '</p>';
}
add_shortcode("paragraph", "nativehcurch_imic_paragraph");
// Span type
function nativehcurch_imic_span($atts, $content = null)
{
    extract(shortcode_atts(array(
        "extra" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<span class="' . $extra . '">' . $shortcode_content . '</span>';
}
add_shortcode("span", "nativehcurch_imic_span");
// Container type
function nativehcurch_imic_container($atts, $content = null)
{
    extract(shortcode_atts(array(
        "extra" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="' . $extra . '">' . $shortcode_content . '</div>';
}
add_shortcode("container", "nativehcurch_imic_container");
// Section type
function nativehcurch_imic_section($atts, $content = null)
{
    extract(shortcode_atts(array(
        "extra" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<section class="' . $extra . '">' . $shortcode_content . '</section>';
}
add_shortcode("section", "nativehcurch_imic_section");
// Dropcap type
function nativehcurch_imic_dropcap($atts, $content = null)
{
    extract(shortcode_atts(array(
        "type" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<p class="drop-caps ' . $type . '">' . $shortcode_content . '</p>';
}
add_shortcode("dropcap", "nativehcurch_imic_dropcap");
// Blockquote type
function nativehcurch_imic_blockquote($atts, $content = null)
{
    extract(shortcode_atts(array(
        "name" => '',
    ), $atts));
    if (!empty($name)) {
        $authorName = '<cite>- ' . $name . '</cite>';
    } else {
        $authorName = '';
    }
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<blockquote><p>' . $shortcode_content . '</p>' . $authorName . '</blockquote>';
}
add_shortcode("blockquote", "nativehcurch_imic_blockquote");
// Code type
function nativehcurch_imic_code($atts, $content = null)
{
    extract(shortcode_atts(array(
        "type" => '',
    ), $atts));
    if ($type == 'inline') {
        $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
        return '<code>' . $shortcode_content . '</code>';
    } else {
        $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
        return '<pre>' . $shortcode_content . '</pre>';
    }
}
add_shortcode("code", "nativehcurch_imic_code");
// Label Tag
function nativehcurch_imic_label_tag($atts, $content = null)
{
    extract(shortcode_atts(array(
        "type" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output = '<span class="label ' . $type . '">' . $shortcode_content . '</span>';
    return $output;
}
add_shortcode("label", "nativehcurch_imic_label_tag");
// Spacer Tag
function nativehcurch_imic_spacer_tag($atts, $content = null)
{
    extract(shortcode_atts(array(
        "size" => '',
    ), $atts));
    $output = '<div class="' . $size . '"></div>';
    return $output;
}
add_shortcode("spacer", "nativehcurch_imic_spacer_tag");
/* LISTS SHORTCODES
================================================= */
function nativehcurch_imic_list($atts, $content = null)
{
    extract(shortcode_atts(array(
        "type" => '',
        "extra" => '',
        "icon" => '',
    ), $atts));
    if ($type == 'ordered') {
        $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
        $output = '<ol>' . $shortcode_content . '</ol>';
    } else if ($type == 'desc') {
        $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
        $output = '<dl>' . $shortcode_content . '</dl>';
    } else {
        $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
        $output = '<ul class="chevrons ' . $type . ' ' . $extra . '">' . $shortcode_content . '</ul>';
    }
    return $output;
}
add_shortcode('list', 'nativehcurch_imic_list');
function nativehcurch_imic_list_item($atts, $content = null)
{
    extract(shortcode_atts(array(
        "icon" => '',
        "type" => '',
    ), $atts));
    if (($type == 'icon') || ($type == 'inline')) {
        $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
        $output = '<li><i class="fa ' . $icon . '"></i> ' . $shortcode_content . '</li>';
    } else {
        $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
        $output = '<li>' . $shortcode_content . '</li>';
    }
    return $output;
}
add_shortcode('list_item', 'nativehcurch_imic_list_item');
function nativehcurch_imic_list_item_dt($atts, $content = null)
{
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output = '<dt>' . $shortcode_content . '</dt>';
    return $output;
}
add_shortcode('list_item_dt', 'nativehcurch_imic_list_item_dt');
function nativehcurch_imic_list_item_dd($atts, $content = null)
{
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output = '<dd>' . $shortcode_content . '</dd>';
    return $output;
}
add_shortcode('list_item_dd', 'nativehcurch_imic_list_item_dd');
function nativehcurch_imic_page_first($atts, $content = null)
{
    return '<li><a href="#"><i class="fa fa-chevron-left"></i></a></li>';
}
add_shortcode('page_first', 'nativehcurch_imic_page_first');
function nativehcurch_imic_page_last($atts, $content = null)
{
    return '<li><a href="#"><i class="fa fa-chevron-right"></i></a></li>';
}
add_shortcode('page_last', 'nativehcurch_imic_page_last');
function nativehcurch_imic_page($atts, $content = null)
{
    extract(shortcode_atts(array(
        "class" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<li class="' . $class . '"><a href="#">' . $shortcode_content . ' </a></li>';
}
add_shortcode('page', 'nativehcurch_imic_page');
/* SIDEBAR SHORTCODES
=================================================*/
function nativehcurch_imic_sidebar($atts, $content = null)
{
    extract(shortcode_atts(array(
        "id" => "",
        "column" => 4,
    ), $atts));
    ob_start();
    dynamic_sidebar($id);
    $html = ob_get_contents();
    ob_end_clean();
    return '
<div class="col-md-' . $column . ' col-sm-' . $column . '">' . $html . '</div>';
}
add_shortcode('sidebar', 'nativehcurch_imic_sidebar');

/* TABS SHORTCODES
================================================= */
function nativehcurch_imic_tabs($atts, $content = null)
{
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="tabs">' . $shortcode_content . '</div>';
}
add_shortcode('tabs', 'nativehcurch_imic_tabs');
function nativehcurch_imic_tabh($atts, $content = null)
{
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<ul class="nav nav-tabs">' . $shortcode_content . '</ul>';
}
add_shortcode('tabh', 'nativehcurch_imic_tabh');
function nativehcurch_imic_tab($atts, $content = null)
{
    extract(shortcode_atts(array(
        "id" => '',
        "class" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<li class="' . $class . '"> <a data-toggle="tab" href="#' . $id . '"> ' . $shortcode_content . ' </a> </li>';
}
add_shortcode('tab', 'nativehcurch_imic_tab');
function nativehcurch_imic_tabc($atts, $content = null)
{
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="tab-content">' . $shortcode_content . '</div>';
}
add_shortcode('tabc', 'nativehcurch_imic_tabc');
function nativehcurch_imic_tabrow($atts, $content = null)
{
    extract(shortcode_atts(array(
        "id" => '',
        "class" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output = '<div id="' . $id . '" class="tab-pane ' . $class . '">' . $shortcode_content . '</div>';
    return $output;
}
add_shortcode('tabrow', 'nativehcurch_imic_tabrow');
/* ACCORDION SHORTCODES
================================================= */
function nativehcurch_imic_accordions($atts, $content = null)
{
    extract(shortcode_atts(array(
        "id" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="accordion" id="accordion' . $id . '">' . $shortcode_content . '</div>';
}
add_shortcode('accordions', 'nativehcurch_imic_accordions');
function nativehcurch_imic_accgroup($atts, $content = null)
{
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="accordion-group panel">' . $shortcode_content . '</div>';
}
add_shortcode('accgroup', 'nativehcurch_imic_accgroup');
function nativehcurch_imic_acchead($atts, $content = null)
{
    extract(shortcode_atts(array(
        "id" => '',
        "class" => '',
        "tab_id" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output = '<div class="accordion-heading accordionize"> <a class="accordion-toggle ' . $class . '" data-toggle="collapse" data-parent="#accordion' . $id . '" href="#' . $tab_id . '"> ' . $shortcode_content . ' <i class="fa fa-angle-down"></i> </a> </div>';
    return $output;
}
add_shortcode('acchead', 'nativehcurch_imic_acchead');
function nativehcurch_imic_accbody($atts, $content = null)
{
    extract(shortcode_atts(array(
        "tab_id" => '',
        "in" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output = '<div id="' . $tab_id . '" class="accordion-body ' . $in . ' collapse">
					  <div class="accordion-inner"> ' . $shortcode_content . ' </div>
					</div>';
    return $output;
}
add_shortcode('accbody', 'nativehcurch_imic_accbody');
/* TOGGLE SHORTCODES
================================================= */
function nativehcurch_imic_toggles($atts, $content = null)
{
    extract(shortcode_atts(array(
        "id" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="accordion" id="toggle' . $id . '">' . $shortcode_content . '</div>';
}
add_shortcode('toggles', 'nativehcurch_imic_toggles');
function nativehcurch_imic_togglegroup($atts, $content = null)
{
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    return '<div class="accordion-group panel">' . $shortcode_content . '</div>';
}
add_shortcode('togglegroup', 'nativehcurch_imic_togglegroup');
function nativehcurch_imic_togglehead($atts, $content = null)
{
    extract(shortcode_atts(array(
        "id" => '',
        "tab_id" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output = '<div class="accordion-heading togglize"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#" href="#' . $tab_id . '"> ' . $shortcode_content . ' <i class="fa fa-plus-circle"></i> <i class="fa fa-minus-circle"></i> </a> </div>';
    return $output;
}
add_shortcode('togglehead', 'nativehcurch_imic_togglehead');
function nativehcurch_imic_togglebody($atts, $content = null)
{
    extract(shortcode_atts(array(
        "tab_id" => '',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $output = '<div id="' . $tab_id . '" class="accordion-body collapse">
              <div class="accordion-inner"> ' . $shortcode_content . '  </div>
            </div>';
    return $output;
}
add_shortcode('togglebody', 'nativehcurch_imic_togglebody');
/* PROGRESS BAR SHORTCODE
================================================= */
function nativehcurch_imic_progress_bar($atts)
{
    extract(shortcode_atts(array(
        "percentage" => '',
        "name" => '',
        "type" => '',
        "value" => '',
        "colour" => '',
    ), $atts));
    if ($type == 'progress-striped') {
        $typeClass = $type;
    } else {
        $typeClass = "";
    }
    if ($colour == 'progress-bar-warning') {
        $warningText = '(warning)';
    } else {
        $warningText = "";
    }
    $service_bar_output = '';
    if ($type == "") {
        $type = "standard";
        if (!empty($name)) {
            $service_bar_output = '<div class="progress-label"> <span>' . $name . '</span> </div>';
        }
    }
    $service_bar_output .= '<div class="progress ' . $typeClass . '">';
    if ($type == 'progress-striped') {
        $service_bar_output .= '<div class="progress-bar ' . $colour . '" style="width: ' . $value . '%">';
        $service_bar_output .= '<span class="sr-only">' . $value . '% ' . esc_html__('Complete(success)', 'imithemes') . ' </span>';
        $service_bar_output .= '</div>';
    } else if ($type == 'colored') {
        if (!empty($warningText)) {
            $spanClass = '';
        } else {
            $spanClass = 'sr-only';
        }
        $service_bar_output .= '<div class="progress-bar ' . $colour . '" style="width: ' . $value . '%"> <span class="' . $spanClass . '">' . $value . '% ' . esc_html__('Complete', 'imithemes') . $warningText . '</span> </div>';
    } else {
        $service_bar_output .= '<div class="progress-bar progress-bar-primary" data-appear-progress-animation="' . $value . '%" data-appear-animation-delay="200"> <span class="progress-bar-tooltip">' . $value . '%</span> </div>';
    }
    $service_bar_output .= '</div>';
    return $service_bar_output;
}
add_shortcode('progress_bar', 'nativehcurch_imic_progress_bar');
/* TOOLTIP SHORTCODE
================================================= */
function nativehcurch_imic_tooltip($atts, $content = null)
{
    extract(shortcode_atts(array(
        "title" => '',
        "link" => '#',
        "direction" => 'top',
    ), $atts));
    $shortcode_content = preg_replace("/(<br\s\/>)/", "", do_shortcode($content));
    $tooltip_output = '<a href="' . $link . '" rel="tooltip" data-toggle="tooltip" data-original-title="' . $title . '" data-placement="' . $direction . '">' . $shortcode_content . '</a>';
    return $tooltip_output;
}
add_shortcode('imic_tooltip', 'nativehcurch_imic_tooltip');
/* YEAR SHORTCODE
================================================= */
function nativehcurch_imic_year_shortcode()
{
    $year = date('Y');
    return $year;
}
add_shortcode('the-year', 'nativehcurch_imic_year_shortcode');
/* WORDPRESS LINK SHORTCODE
================================================= */
function nativehcurch_imic_wordpress_link()
{
    return '<a href="http://wordpress.org/" target="_blank">' . esc_html__('WordPress', 'imithemes') . '</a>';
}
add_shortcode('wp-link', 'nativehcurch_imic_wordpress_link');
/* COUNT SHORTCODE
================================================= */
function nativehcurch_imic_count($atts)
{
    extract(shortcode_atts(array(
        "speed" => '2000',
        "to" => '',
        "icon" => '',
        "subject" => '',
        "textstyle" => '',
    ), $atts));
    $count_output = '';
    if ($speed == "") {
        $speed = '2000';
    }
    $count_output .= '<div class="countdown">';
    $count_output .= '<div class="fact-ico"> <i class="fa ' . $icon . ' fa-4x"></i> </div>';
    $count_output .= '<div class="clearfix"></div>';
    $count_output .= '<div class="timer" data-perc="' . $speed . '"> <span class="count">' . $to . '</span></div>';
    $count_output .= '<div class="clearfix"></div>';
    if ($textstyle == "h3") {
        $count_output .= '<h3>' . $subject . '</h3>';
    } else if ($textstyle == "h6") {
        $count_output .= '<h6>' . $subject . '</h6>';
    } else {
        $count_output .= '<span class="fact">' . $subject . '</span>';
    }
    $count_output .= '</div>';
    return $count_output;
}
add_shortcode('imic_count', 'nativehcurch_imic_count');
/* MODAL BOX SHORTCODE
================================================== */
function nativehcurch_imic_modal_box($atts, $content = null)
{
    extract(shortcode_atts(array(
        "id" => "",
        "title" => "",
        "text" => "",
        "button" => "",
    ), $atts));
    $modalBox = '<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#' . $id . '">' . $button . '</button>
            <div class="modal fade" id="' . $id . '" tabindex="-1" role="dialog" aria-labelledby="' . $id . 'Label" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="' . $id . 'Label">' . $title . '</h4>
                  </div>
                  <div class="modal-body"> ' . $text . ' </div>
                  <div class="modal-footer">
               <button type="button" class="btn btn-default inverted" data-dismiss="modal">' . esc_html__('Close', 'imithemes') . '</button>
                  </div>
                </div>
              </div>
            </div>';
    return $modalBox;
}
add_shortcode('modal_box', 'nativehcurch_imic_modal_box');
/* FORM SHORTCODE
================================================== */
function nativehcurch_imic_form_code($atts, $content = null)
{
    extract(shortcode_atts(array(
        "form_email" => '',
    ), $atts));
    if (!empty($form_email)) {
        $admin_email = $form_email;
    } else {
        $admin_email = get_option('admin_email');
    }
    $subject_email = esc_html__('Contact Form', 'imithemes');
    $formCode = '<form action="' . plugin_dir_url( __FILE__ ) . 'mail/contact.php" type="post" class="contact-form-native">
					  <div class="row">
						<div class="form-group">
						  <div class="col-md-6">
							<label>' . esc_html__('Your name', 'imithemes') . ' *</label>
							<input type="text" value="" maxlength="100" class="form-control" name="name" id="name">
						  </div>
						  <div class="col-md-6">
							<label>' . esc_html__('Your email address', 'imithemes') . ' *</label>
							<input type="email" value="" maxlength="100" class="form-control" name="email" id="email">
						  </div>
                                                  <div class="col-md-12">
							<label>' . esc_html__('Your Phone Number', 'imithemes') . '</label>
							<input type="text" id="phone" name="phone" class="form-control input-lg">
						  </div>
						</div>
					  </div>
					  <div class="row">
                                          <input type ="hidden" name ="image_path" id="image_path" value ="' . plugin_dir_url( __FILE__ ) . '">
                                          <input type="hidden" id="phone" name="phone" class="form-control input-lg">
                                          <input id="admin_email" name="admin_email" type="hidden" value ="' . $admin_email . '">
                                              <input id="subject" name="subject" type="hidden" value ="' . $subject_email . '">
						<div class="form-group">
						  <div class="col-md-12">
							<label>' . esc_html__('Comment', 'imithemes') . '</label>
							<textarea maxlength="5000" rows="10" class="form-control" name="comments" id="comments" style="height: 138px;"></textarea>
						  </div>
						</div>
					  </div>
					  <div class="row">
						<div class="col-md-12">
						  <input type="submit" name ="submit" id ="submit" value="' . esc_html__('Submit', 'imithemes') . '" class="btn btn-primary" data-loading-text="' . esc_html__('Loading...', 'imithemes') . '">
						</div>
					  </div>
					</form><div class="clearfix"></div>
                    <div id="message"></div>';
    return $formCode;
}
add_shortcode('imic_form', 'nativehcurch_imic_form_code');
/* FULLSCREEN VIDEO SHORTCODE
================================================= */
function nativehcurch_imic_fullscreen_video($atts, $content = null)
{
    extract(shortcode_atts(array(
        "videourl" => '',
        "fullwidth" => '',
        "autoplay" => 0,
    ), $atts));
    $fw_video_output = "";
    if (!empty($videourl)) {
        if ($fullwidth == "yes") {
            $fw_video_output .= '<div class="fw-video">' . imic_video_embed($videourl, 300, 200, $autoplay) . '</div>';
        } else {
            $fw_video_output .= imic_video_embed($videourl, 300, 200, $autoplay);
        }
    }
    return $fw_video_output;
}
add_shortcode('fullscreenvideo', 'nativehcurch_imic_fullscreen_video');
/* Event Calendar SHORTCODE
================================================= */
function nativehcurch_event_calendar($atts)
{
    extract(shortcode_atts(array(
        "category_id" => '',
        "google_cal_id" => '',
        "filter" => '',
        "google_cal_id1" => '',
        "google_cal_id2" => '',
        "view" => 'month',
    ), $atts));
    global $imic_options;

    $event_google_open_link = isset($imic_options['event_google_open_link']) ? $imic_options['event_google_open_link'] : 0;
    $calendar_header_view = $imic_options['calendar_header_view'];
    $calendar_event_limit = $imic_options['calendar_event_limit'];
    $google_api_key = $imic_options['google_feed_key'];
    if ($google_cal_id !== "") {
        $google_calendar_id = $google_cal_id;
    } else {
        $google_calendar_id = $imic_options['google_feed_id'];
    }
    $calendar_today = (isset($imic_options['calendar_today'])) ? $imic_options['calendar_today'] : 'Today';
    $calendar_month = (isset($imic_options['calendar_month'])) ? $imic_options['calendar_month'] : 'Month';
    $calendar_week = (isset($imic_options['calendar_week'])) ? $imic_options['calendar_week'] : 'Week';
    $calendar_day = (isset($imic_options['calendar_day'])) ? $imic_options['calendar_day'] : 'Day';
    $google_calendar_id1 = $google_cal_id1;
    $google_calendar_id2 = $google_cal_id2;
    wp_enqueue_script('imic_fullcalendar');
    wp_enqueue_script('imic_gcal');
    wp_enqueue_script('fullcalendar-locale');
    wp_enqueue_script('imic_calender_events');
    $format = ImicConvertDate(get_option('time_format'));
    $term_output = '';
    if ($filter == 1) {
        $e_terms = get_terms('event-category');
        $_color_bg = '';
        foreach ($e_terms as $term) {
            $color_bg_cat = get_option("category_" . $term->term_id);
            if ($color_bg_cat) {
                $_color_bg = $color_bg_cat['catBG'];
            }
        }
        $term_output .= '<div class="events-listing-header"><input type="radio" class="calender_filter" value="" checked="checked" id="calender_filter_#" name="calender_filter" value="#">' . '<label for="calender_filter_#">' . esc_html__('All', 'imithemes') . '</label>';
        foreach ($e_terms as $term) {
            $color_bg_cat = get_option("category_" . $term->term_id);
            $customColor_bg = isset($imic_options['custom_theme_color']) ? $imic_options['custom_theme_color'] : '';
            $color_bg_class = '';
            $color_bg = '';
            $style = '';
            if ($color_bg_cat && $_color_bg != '') {
                $color_bg = $color_bg_cat['catBG'];
                $style = "background-color:$color_bg;color:white";
            } else if ($customColor_bg && $_color_bg != '' && $imic_options['theme_color_type'] == 1) {
                $color_bg = $customColor_bg;
                $style = "background-color:$color_bg;color:white";
            } else if ($_color_bg != '') {
                $color_bg_class = 'accent-bg';
                $style = "color:white";
            }
            $term_output .= '<input type="radio" id="calender_filter_' . $term->term_id . '" class="calender_filter" name="calender_filter" value="' . $term->term_id . '"><label for="calender_filter_' . $term->term_id . '" style="' . $style . '" class="' . $color_bg_class . '">' . $term->name . '</label>';
        }
        $term_output .= '</div>';
    }
    wp_localize_script('imic_calender_events', 'calenderEvents', array('homeurl' => get_template_directory_uri(), 'time_format' => $format, 'start_of_week' => get_option('start_of_week'), 'googlekey' => $google_api_key, 'googlecalid' => $google_calendar_id, 'googlecalid1' => $google_calendar_id1, 'googlecalid2' => $google_calendar_id2, 'calheadview' => $calendar_header_view, 'eventLimit' => $calendar_event_limit, 'today' => $calendar_today, 'month' => $calendar_month, 'week' => $calendar_week, 'day' => $calendar_day, 'view' => $view, 'google_target' => $event_google_open_link, 'sitelan' => substr(get_locale(), 0, 2)));
    return $term_output . '<div class="col-md-12"><div id ="' . $category_id . '" class ="event_calendar calendar"></div></div>';
}
add_shortcode('event_calendar', 'nativehcurch_event_calendar');
?>