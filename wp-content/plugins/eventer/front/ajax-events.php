<?php
defined('ABSPATH') or die('No script kiddies please!');

if (!function_exists('eventer_handle_month_wise_events_ajax')) {
    function eventer_handle_month_wise_events_ajax()
    {
        $request_nonce = isset($_REQUEST['nonce']) ? sanitize_text_field(wp_unslash($_REQUEST['nonce'])) : '';
        if (!wp_verify_nonce($request_nonce, 'eventer_create_nonce_for_month')) {
            wp_send_json_error(array('message' => esc_html__('Invalid request.', 'eventer')), 403);
        }

        $halfyear = $fullyear = $halfyear_msg = $fullyear_msg = $next_class = $next_result = '';
        $output = $monthsgrid = array();
        $get_month_act = isset($_REQUEST['get_month']) ? sanitize_text_field(wp_unslash($_REQUEST['get_month'])) : '';
        $arrow = isset($_REQUEST['arrow']) ? sanitize_text_field(wp_unslash($_REQUEST['arrow'])) : '';
        $shortcode_attr_raw = (isset($_REQUEST['shortcode_attr']) && is_array($_REQUEST['shortcode_attr'])) ? wp_unslash($_REQUEST['shortcode_attr']) : array();
        $filters = (isset($_REQUEST['filters']) && is_array($_REQUEST['filters'])) ? wp_unslash($_REQUEST['filters']) : array();
        $status = isset($_REQUEST['stat']) ? sanitize_text_field(wp_unslash($_REQUEST['stat'])) : '';
        $shortcode_attr = array(
            'count' => isset($shortcode_attr_raw['count']) ? max(1, absint($shortcode_attr_raw['count'])) : 1,
            'view' => isset($shortcode_attr_raw['view']) ? sanitize_text_field($shortcode_attr_raw['view']) : '',
            'ids' => isset($shortcode_attr_raw['ids']) ? eventer_sanitize_id_list($shortcode_attr_raw['ids']) : array(),
            'efrom' => isset($shortcode_attr_raw['efrom']) ? eventer_sanitize_date_input($shortcode_attr_raw['efrom']) : '',
            'eto' => isset($shortcode_attr_raw['eto']) ? eventer_sanitize_date_input($shortcode_attr_raw['eto']) : '',
            'terms_cats' => isset($shortcode_attr_raw['terms_cats']) ? eventer_sanitize_id_list($shortcode_attr_raw['terms_cats']) : array(),
            'terms_tags' => isset($shortcode_attr_raw['terms_tags']) ? eventer_sanitize_id_list($shortcode_attr_raw['terms_tags']) : array(),
            'terms_venue' => isset($shortcode_attr_raw['terms_venue']) ? eventer_sanitize_id_list($shortcode_attr_raw['terms_venue']) : array(),
            'terms_organizer' => isset($shortcode_attr_raw['terms_organizer']) ? eventer_sanitize_id_list($shortcode_attr_raw['terms_organizer']) : array(),
            'eventerid' => isset($shortcode_attr_raw['eventerid']) ? eventer_sanitize_id_list($shortcode_attr_raw['eventerid']) : array(),
            'pagination' => isset($shortcode_attr_raw['pagination']) ? sanitize_text_field($shortcode_attr_raw['pagination']) : '',
            'month_filter' => isset($shortcode_attr_raw['month_filter']) ? sanitize_text_field($shortcode_attr_raw['month_filter']) : '',
            'type' => isset($shortcode_attr_raw['type']) ? sanitize_text_field($shortcode_attr_raw['type']) : '',
            'event_until' => isset($shortcode_attr_raw['event_until']) ? sanitize_text_field($shortcode_attr_raw['event_until']) : '',
            'pass' => isset($shortcode_attr_raw['pass']) ? sanitize_text_field($shortcode_attr_raw['pass']) : '',
        );
        $event_count = $shortcode_attr['count'];
        if ($status == 'month') {
            $event_count = 1000;
        }

        $list_layout = $shortcode_attr['view'];
        $ids = $shortcode_attr['ids'];
        $from_date = isset($shortcode_attr['efrom']) ? $shortcode_attr['efrom'] : '';
        $to_date = isset($shortcode_attr['eto']) ? $shortcode_attr['eto'] : '';
        $terms_cats = isset($filters['terms_cats']) ? array_merge($shortcode_attr['terms_cats'], eventer_sanitize_id_list($filters['terms_cats'])) : $shortcode_attr['terms_cats'];
        $terms_tags = isset($filters['terms_tags']) ? array_merge($shortcode_attr['terms_tags'], eventer_sanitize_id_list($filters['terms_tags'])) : $shortcode_attr['terms_tags'];
        $terms_venue = isset($filters['terms_venue']) ? array_merge($shortcode_attr['terms_venue'], eventer_sanitize_id_list($filters['terms_venue'])) : $shortcode_attr['terms_venue'];
        $terms_organizer = isset($filters['terms_organizer']) ? array_merge($shortcode_attr['terms_organizer'], eventer_sanitize_id_list($filters['terms_organizer'])) : $shortcode_attr['terms_organizer'];
        $event_ids = eventer_merge_all_ids($ids, $terms_cats, $terms_tags, $terms_venue, $terms_organizer);
        $eventer_keyword_id = $shortcode_attr['eventerid'];
        $eventer_new_ids = array_merge($event_ids, (array) $eventer_keyword_id);
        $jump = isset($_REQUEST['datajump']) ? min(11, absint($_REQUEST['datajump'])) : 0;
        $pagination = $shortcode_attr['pagination'];
        $pagin = $pagination ? get_query_var('pagin') : 1;
        $last_event_date = get_option('eventer_extreme_last_event_date');
        $last_event_date = ($last_event_date == '') ? '2100-01-01' : $last_event_date;
        $first_event_date = get_option('eventer_extreme_first_event_date');

        for ($i = 0; $i <= $jump; $i++) {
            if ($shortcode_attr['month_filter'] != '') {
                $filtering_data = eventer_filtering_values($status, $get_month_act, $i);
                $tabs = $filtering_data['tabs'];
                $tabs_date = $filtering_data['tabs_format'];
                $tab_length = $filtering_data['tabs_length'];
                $date_start = $filtering_data['start_dt'];
                $date_end = $filtering_data['end_dt'];
                $label_month = $filtering_data['label_month'];
                $label_year = $filtering_data['label_year'];
                $get_months = $filtering_data['get_dates'];
                $increment_format = $filtering_data['inc_format'];
                $event_count = 1000;
            }
            $date_array = ($from_date != '' && $to_date != '') ? array($from_date, $to_date) : array($date_start, $date_end);
            if (is_search()) {
                $events = eventer_search_result_data($eventer_new_ids, $status, $date_array, $pagin, $event_count, $shortcode_attr['type'], $shortcode_attr['event_until']);
            } else {
                $events = eventer_get_events_array($eventer_new_ids, $status, $date_array, $pagin, $event_count, $shortcode_attr['type'], $shortcode_attr['event_until'], $shortcode_attr['pass']);
            }
            if ($events['results'] > 0) {
                break;
            }
        }

        $eventer = $events['events'];
        for ($is = 1; $is <= $tab_length; $is++) {
            if (strtotime($last_event_date) < strtotime(date_i18n('Y-m-d 23:59', strtotime('+' . $is . ' ' . $tabs, strtotime($get_months))))) {
                break;
            }

            $next_result = 1;
            $monthsgrid[] = array(
                'lival' => date_i18n($increment_format, strtotime('+' . $is . ' ' . $tabs, strtotime($get_months))),
                'lishow' => date_i18n($tabs_date, strtotime('+' . $is . ' ' . $tabs, strtotime($get_months))),
            );
        }
        $previous_result = (strtotime($first_event_date) < strtotime($get_months)) ? 1 : '';
        $prevmonth = date_i18n($increment_format, strtotime('-1 ' . $tabs, strtotime($get_months)));
        $nextmonth = date_i18n($increment_format, strtotime('+1 ' . $tabs, strtotime($get_months)));
        $longjump = '';
        if ($arrow == '1' && $tabs == 'month') {
            $halfyear_msg = esc_html__('Search events for next six months.', 'eventer');
            $fullyear_msg = esc_html__('Search events for next twelve months.', 'eventer');
            $next_class = 'next-month';
            $longjump = $nextmonth;
        } elseif ($tabs == 'month') {
            $halfyear_msg = esc_html__('Search events for previous six months.', 'eventer');
            $fullyear_msg = esc_html__('Search events for previous twelve months.', 'eventer');
            $next_class = '';
            $longjump = $prevmonth;
        }
        $datacon = empty($eventer) ? '1' : '';

        $stime_format = esc_attr(eventer_get_settings('start_time_format'));
        $etime_format = esc_attr(eventer_get_settings('end_time_format'));
        $time_separator = esc_attr(eventer_get_settings('time_separator'));
        $date_format = esc_attr(eventer_get_settings('eventer_date_format'));
        $stime_format = ($stime_format == '') ? get_option('time_format') : $stime_format;
        $etime_format = ($etime_format == '') ? get_option('time_format') : $etime_format;
        $date_format = ($date_format == '') ? get_option('date_format') : $date_format;
        $time_separator = ($time_separator == '') ? ' - ' : $time_separator;
        $title_data_passed = array();

        foreach ($eventer as $event_data) {
            $key = $event_data['start'];
            $keyend = $event_data['end'];
            $value = $event_data['id'];
            if (get_post_status($value) != 'publish') {
                continue;
            }

            $string_date = strtotime($key);
            $eventer_data = eventer_explore_event_ids($string_date, $value, $stime_format, $etime_format, $time_separator, $shortcode_attr['event_until']);
            $event_all_dates = get_post_meta($value, 'eventer_event_frequency_type', true);
            $event_dynamic_dates = get_post_meta($value, 'eventer_event_multiple_dt_inc', true);
            $recurring_icon_switch = eventer_get_settings('eventer_recurring_icon_yes');
            $recurring_icon = (($recurring_icon_switch == 'on' && is_numeric($event_all_dates)) || ($recurring_icon_switch == 'on' && $event_dynamic_dates != '')) ? '1' : '';
            $event_ymd = date_i18n('Y-m-d', $eventer_data['show_counter']);
            $eventer_url = ($eventer_data['google_url'] == '') ? eventer_generate_endpoint_url('edate', $event_ymd, get_permalink($value)) : $eventer_data['google_url'];
            $event_month = date_i18n('F', $eventer_data['show_counter']);
            $event_year = esc_attr(date_i18n(' Y', $eventer_data['show_counter']));
            $event_time = $eventer_data['etime'];
            $event_venue = $eventer_data['elocation'] != '' ? $eventer_data['elocation'] : '';
            $image_url = has_post_thumbnail($value) ? get_the_post_thumbnail_url($value, 'eventer-thumb-170x170') : '';

            $title_data_passed['event_cdate'] = strtotime($key);
            $title_data_passed['event_edate'] = strtotime($keyend);
            $title_data_passed['all_dates'] = get_post_meta($value, 'eventer_all_dates', true);
            $title_data_passed['booked_tickets'] = eventer_get_date_wise_ticket_snapshot($value, date_i18n('Y-m-d 00:00:00', strtotime($key)));
            $event_title = apply_filters('eventer_styled_listing_title', $title = '', $value, $title_data_passed);

            if ($list_layout == 'minimal') {
                $single_day_set = (isset($eventer_data['multiday_start']) && $eventer_data['multiday_start'] == '') ? '<span class="eventer-event-day pull-left">' . date_i18n('d', $eventer_data['show_counter']) . '</span>' : '';
                $event_year = ($single_day_set != '') ? $event_year : '';
                $event_month = ($single_day_set != '') ? $event_month : $eventer_data['multiday_start'] . '-' . $eventer_data['multiday_end'];
            } else {
                $multiday_class = (isset($eventer_data['multiday_start']) && $eventer_data['multiday_start'] != '') ? 'eventer-event-multiday ' : 'eventer-event-day ';
                $multiday_start = (isset($eventer_data['multiday_start']) && $eventer_data['multiday_start'] != '') ? '<span class="' . esc_attr($multiday_class) . ' eventer-event-multiday-border">' . $eventer_data['multiday_start'] . '</span>' : '';
                $multiday_end = (isset($eventer_data['multiday_end']) && $eventer_data['multiday_end'] != '') ? '<span class="' . esc_attr($multiday_class) . '">' . $eventer_data['multiday_end'] . '</span>' : '';
                $single_day_set = (isset($eventer_data['multiday_start']) && $eventer_data['multiday_start'] != '') ? $multiday_start . $multiday_end : '<span class="' . esc_attr($multiday_class) . '">' . date_i18n('d', $eventer_data['show_counter']) . '</span>';
            }

            $border_left_color = ($eventer_data['color']) ? ' style="border-left-color:' . $eventer_data['color'] . '"' : '';
            $border_top_color = ($eventer_data['color']) ? ' style="border-top-color:' . $eventer_data['color'] . '"' : '';
            $time_icon = ($shortcode_attr['view'] == 'compact') ? '<i class="eventer-icon-clock"></i>' : '';
            $output[] = array(
                'da' => $single_day_set,
                'multidays' => isset($multiday_start) ? $multiday_start : '',
                'mon' => $event_month,
                'year' => $event_year,
                'time' => $event_time,
                'venue' => $event_venue,
                'title' => $event_title,
                'bordertop' => $border_top_color,
                'borderleft' => $border_left_color,
                'image_url' => $image_url,
                'ticon' => $time_icon,
                'color' => $eventer_data['color'],
                'eventer_url' => $eventer_url,
                'recurring_icon' => $recurring_icon,
                'tabs' => $tabs,
            );
        }

        wp_send_json(
            array(
                'layout' => $shortcode_attr['view'],
                'lidata' => $output,
                'noresult' => $datacon,
                'thismonth' => $label_month,
                'thisyear' => $label_year,
                'prevmonth' => $prevmonth,
                'nextmonth' => $nextmonth,
                'blank' => esc_html__('Sorry, no more events available for this month.', 'eventer'),
                'halfyear' => '<a class="eventer-btn show_month_events ' . $next_class . '" data-jump="5" data-arrow="' . $longjump . '">' . $halfyear_msg . '</a>',
                'fullyear' => '<a class="eventer-btn show_month_events ' . $next_class . '" data-jump="11" data-arrow="' . $longjump . '">' . $fullyear_msg . '</a>',
                'showmsg' => esc_html__('Sorry, there no more events found for your request.', 'eventer'),
                'monthsgrid' => $monthsgrid,
                'next_result' => $next_result,
                'previous_result' => $previous_result,
            )
        );
    }
}

if (!function_exists('eventer_handle_contact_organizer_ajax')) {
    function eventer_handle_contact_organizer_ajax()
    {
        eventer_verify_public_ajax_request('eventer_create_nonce_for_corganizer');

        $organizer_fields = (isset($_POST['org_data']) && is_array($_POST['org_data'])) ? wp_unslash($_POST['org_data']) : array();
        $eventer_id = isset($_POST['eventer_id']) ? absint($_POST['eventer_id']) : 0;
        $eventer_date = isset($_POST['eventer_date']) ? sanitize_text_field(wp_unslash($_POST['eventer_date'])) : '';
        if (!empty($organizer_fields) && !empty($eventer_id)) {
            $organizer = get_the_terms($eventer_id, 'eventer-organizer');
            $organizer_email = '';
            $headers = array();
            if (!is_wp_error($organizer) && !empty($organizer)) {
                $organizer_id = $organizer[0]->term_id;
                $organizer_email = get_term_meta($organizer_id, 'organizer_email', true);
            }
            $sender = eventer_get_settings('email_from_address') ? eventer_get_settings('email_from_address') : get_option('admin_email');
            $sender_name = eventer_get_settings('email_from_name') ? eventer_get_settings('email_from_name') : get_bloginfo('name');
            $sender = ($organizer_email != '') ? $organizer_email : $sender;
            $headers[] = 'From: ' . $sender_name . ' <' . $sender . '>';
            $headers[] = "MIME-Version: 1.0\r\n";
            $headers[] = 'Content-Type: text/html; charset=' . get_bloginfo('charset') . "\r\n";
            $message = '<p>' . esc_html__('Someone contacted for below event', 'eventer') . '<p>';
            $message .= '<p>' . esc_url(eventer_generate_endpoint_url('edate', $eventer_date, get_permalink($eventer_id))) . '</p>';
            foreach ($organizer_fields as $field) {
                $field_name = isset($field['name']) ? sanitize_text_field($field['name']) : '';
                $field_value = isset($field['value']) ? sanitize_textarea_field($field['value']) : '';
                $message .= '<p>' . esc_html($field_name) . ': ' . esc_html($field_value) . '</p>';
            }
            $message = wpautop($message);
            $subject = esc_html__('Query for event:', 'eventer') . ' ' . apply_filters('eventer_raw_event_title', '', $eventer_id);
            send_eventer_custom_email($sender, $subject, $message, $headers);
        }
        wp_die();
    }
}
