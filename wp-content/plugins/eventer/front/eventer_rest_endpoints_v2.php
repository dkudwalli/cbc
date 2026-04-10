<?php
add_action('rest_api_init', 'eventer_app_list');
function eventer_app_list($request)
{
    register_rest_route(
        'imi',
        '/v2/events/',
        array(
            'methods' => 'GET',
            'callback' => 'eventer_get_app_events',
            'permission_callback' => 'eventer_rest_allow_app_or_admin'
        )
    );
}

function eventer_get_app_events($request = null)
{
    $eventers = array();
    $message = "";
    $event_args = array(
        'post_type' => 'eventer',
        'posts_per_page' => -1,
        'meta_query' => [
            'registration' => [
                'key' => 'eventer_event_registration_swtich',
                'value' => '1',
            ]
        ]
    );
    $event_list = new WP_Query($event_args);
    if ($event_list->have_posts()):
        while ($event_list->have_posts()):
            $event_list->the_post();
            $eventDate = get_post_meta(get_the_ID(), 'eventer_all_dates', true);
            $eventDate = array_filter($eventDate, function ($date) {
                $prevDate = date_i18n('Y-m-d H:i:s',(strtotime ( '-1 day' , strtotime ( date_i18n('Y-m-d H:i:s')) ) ));
                return $date >= $prevDate;
            });

            $eventStartDate = get_post_meta(get_the_ID(), 'eventer_event_start_dt', true);
            $eventEndDate = get_post_meta(get_the_ID(), 'eventer_event_end_dt', true);
            $startDate = date_i18n('Y-m-d', strtotime($eventStartDate));
            $endDate = date_i18n('Y-m-d', strtotime($eventEndDate));
            if (!empty($eventDate) || ($startDate != $endDate)) {
                $eventDate = array_slice($eventDate, 0, 5);
                if ($startDate != $endDate) {
                    $eventDate = [];
                    for ($counter = 0; $counter <= 4; $counter++) {
                        $eventDate[] = date_i18n('Y-m-d', strtotime("+$counter days"));
                    }
                }
                $eventNewDate = [];
                foreach ($eventDate as $eDate) {
                    $eventNewDate[] = ['date' => $eDate, 'format' => date_i18n(get_option('date_format'), strtotime($eDate))];
                }
                $eventer_venue = get_the_terms(get_the_ID(), 'eventer-venue');
                $location_address = '';
                if (!is_wp_error($eventer_venue) && !empty($eventer_venue)) {
                    foreach ($eventer_venue as $venue) {
                        $location_address = get_term_meta($venue->term_id, 'venue_address', true);
                    }
                }
                $timeSlots = get_post_meta(get_the_ID(), 'eventer_time_slot', true);
                $timeSlot = [];
                if (!empty($timeSlots)) {
                    foreach ($timeSlots as $slot) {
                        $timeSlot[] = ['name' => $slot['title'], 'time' => date_i18n(get_option('time_format'), strtotime($slot['start'])), 'raw_time' => date_i18n('H:i', strtotime($slot['start']))];
                    }
                }
                remove_filter('the_title', 'wptexturize');
                $eventers[] = ['title' => get_the_title(), 'id' => get_the_ID(), 'event_date' => $eventNewDate, 'event_time' => date_i18n("H:i", strtotime($eventStartDate)) . ' - ' . date_i18n("H:i", strtotime($eventEndDate)), 'event_location' => $location_address, 'time_slots' => $timeSlot];
            }

        endwhile;
        $response = array("events" => $eventers);
    else:
        $message = "Sorry, there are no events to show here.";
        $response = array("error" => $message);
    endif;
    wp_reset_postdata();
    add_filter('the_title', 'wptexturize');
    return rest_ensure_response($response);
}

add_action('rest_api_init', 'eventer_attendees');
function eventer_attendees($request)
{
    register_rest_route(
        'imi',
        '/v2/attendees/',
        array(
            'methods' => 'POST',
            'callback' => 'eventer_get_attendees',
            'permission_callback' => 'eventer_rest_allow_app_or_admin'
        )
    );
}

function eventer_get_attendees($request = null)
{
    $parameters = $request->get_body_params();
    $event = (isset($parameters['event'])) ? absint($parameters['event']) : 0;
    $date = (isset($parameters['date'])) ? sanitize_text_field(wp_unslash($parameters['date'])) : '';
    $time = (isset($parameters['time']) && !empty($parameters['time'])) ? date_i18n('H:i:00', strtotime(sanitize_text_field(wp_unslash($parameters['time'])))) : '';
    $woocommerce_events = eventer_get_settings('eventer_enable_woocommerce_ticketing');
    if ($woocommerce_events == 'on' && !empty($event) && !empty($date)) {
        $eventTime = !empty($time) ? $time : get_post_meta($event, 'eventer_event_start_dt', true);
        $eventTime = date_i18n('H:i:s', strtotime($eventTime));
        $date = date_i18n('Y-m-d ' . $eventTime, strtotime($date));
        $attendees = getRegistrants($event, $date);
        $setAttendees = [];
        if(!empty($attendees)){
            foreach($attendees as $attendee){
                $status = $attendee->status ? 1 : 0;
                $setAttendees[] = ['name' => $attendee->name, 'show' => "xxxxx".substr($attendee->id, -3), 'id' => $attendee->id, 'checkin' => "$status", 'status' => "$status"];
            }
        }
        return rest_ensure_response(['attendees' => $setAttendees]);
    } elseif (!empty($event) && !empty($date)) {
        global $wpdb;
        $woocommerce_switch = eventer_get_settings('eventer_enable_woocommerce_ticketing');
        if ($woocommerce_switch != 'on') {
            $attendees = [];
            $table_name = $wpdb->prefix . "eventer_registrant";
            $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE `eventer` = %d AND `eventer_date` = %s", $event, $date));

            if ($wpdb->last_error) {
                echo 'wpdb error: ' . $wpdb->last_error;
                return rest_ensure_response(['error' => $wpdb->last_error]);
            }
            if (!empty($result)) {
                foreach ($result as $res) {
                    $userSystem = eventer_decode_array_payload($res->user_system);
                    if (!empty($time) && $userSystem['time_slot'] != $time) {
                        continue;
                    }
                    $checkedIn = getDefaultMeta($res->id, 'checked_in');
                    $checked = !empty($checkedIn) ? 1 : 0;
                    $attendees[] = ['name' => $res->username, 'id' => $res->id, 'show' => "xxxxx" . substr($res->id, -3), 'checkin' => $checked, 'status' => "$checked"];
                }
            }
        }

        return rest_ensure_response(['attendees' => $attendees]);
    } else {
        return rest_ensure_response(['error' => 'Event not found']);
    }
}

add_action('rest_api_init', 'eventer_checkin');
function eventer_checkin($request)
{
    register_rest_route(
        'imi',
        '/v2/checkin/',
        array(
            'methods' => 'POST',
            'callback' => 'eventer_process_checkin',
            'permission_callback' => 'eventer_rest_allow_app_or_admin'
        )
    );
}

function eventer_process_checkin($request = null)
{
    $parameters = $request->get_body_params();
    $event = (isset($parameters['event'])) ? absint($parameters['event']) : 0;
    $code = (isset($parameters['ticket'])) ? sanitize_text_field(wp_unslash($parameters['ticket'])) : '';
    $date = (isset($parameters['date'])) ? sanitize_text_field(wp_unslash($parameters['date'])) : '';
    $qr = (isset($parameters['qr'])) ? absint($parameters['qr']) : 0;
    
    if ($code != '') {
        $codes = explode("-", $code);
        $code = $codes[0];
    }

    if (empty($event)) {
        return rest_ensure_response(['error' => "Sorry, there are no events to show here."]);
    }
    if (date_i18n('Y-m-d', strtotime($date)) < date_i18n('Y-m-d')) {
        return rest_ensure_response(['error' => "Please select a future date."]);
    }
    if (empty($code)) {
        return rest_ensure_response(['error' => "No QR code found!"]);
    }

    $woocommerce_events = eventer_get_settings('eventer_enable_woocommerce_ticketing');
    if ($woocommerce_events == 'on') {
        $tickets = $qr == 1 ? getTicket($code) : getRegistrants($code);
        if (!empty($tickets)) {
            $bookingId = $tickets->reg_id;
            if ($qr == 1) {
                if ($tickets->ticket_status != 10) {
                    updateTicket($code, ['ticket_status' => 10], ['%d']);
                    $message = "Successfully check-in";
                } elseif ($tickets->ticket_status == 10) {
                    return rest_ensure_response(['error' => "This ticket has already been checked in"]);
                }
            } else {
                foreach ($tickets as $ticket) {
                    $bookingId = $ticket->id;
                    if ($ticket->checkin != 10) {
                        updateTicket($ticket->code, ['ticket_status' => 10], ['%d']);
                        $message = "Successfully check-in";
                    } elseif ($ticket->checkin == 10) {
                        return rest_ensure_response(['error' => "This ticket has already been checked in"]);
                    }
                }
            }

            $email = $status = $amount = "";
            if (!empty($bookingId)) {
                $bookingInfo = getRegistration($bookingId);
                $status = $bookingInfo->reg_status;
                $amount = $bookingInfo->reg_amount;
                $email = getRegistrationMeta($bookingId, 'user_email');
                $username = getRegistrationMeta($bookingId, 'user_name');
            }
            $eventers = array('id' => $code, 'title' => get_the_title($event), 'date' => date_i18n("Y-m-d", strtotime($date)), 'name' => $username, 'email' => $email, "status" => $status, "amount" => $amount, "services" => []);
        } else {
            $eventers = array('ID' => "No such ticket found", 'Title' => "", 'Date' => "", 'name' => "", 'email' => "", "status" => "", "amount" => "");
            return rest_ensure_response(['error' => "Not a valid code"]);
        }

        $response = array("scan" => $eventers, "msg" => $message);
        return rest_ensure_response($response);
    }

    $registrant = eventer_get_registrant_details("id", $code);
    $eventers = array('ID' => $code, 'Title' => "", 'Date' => "", 'name' => "", 'email' => "", "status" => "", "amount" => "");
    if ($registrant) {
        $registrant_email = $registrant->email;
        $ticket_id = $registrant->id;
        $amount = $registrant->amount;
        $username = $registrant->username;
        $status = $registrant->status;
        $event_date = $registrant->eventer_date;
        $event_id = $registrant->eventer;
        $user = eventer_decode_array_payload($registrant->user_system);
        $tickets = (isset($user['tickets'])) ? $user['tickets'] : '';
        $services = (isset($user['services'])) ? $user['services'] : '';
        $serviceOpted = [];
        if (!empty($services)) {
            foreach ($services as $service) {
                $serviceOpted[] = ['label' => $service['name'], 'value' => $service['value']];
            }
        }
        $woo = "";
        if (!empty($tickets)) {
            foreach ($tickets as $ticket) {
                $event_woo = $ticket['event'];
                $date_woo = $ticket['date'];
                if ($event_woo == $event && date_i18n("Y-m-d", strtotime($date)) == date_i18n("Y-m-d", $date_woo)) {
                    $woo = "1";
                    break;
                }
            }
        }
        if ($woo == "1") {
            $eventers = array('id' => $ticket_id, 'title' => get_the_title($event), 'date' => date_i18n("Y-m-d", strtotime($date)), 'name' => $username, 'email' => $registrant_email, "status" => $status, "amount" => $amount, "services" => $serviceOpted);
        } elseif ($event_date == date_i18n('Y-m-d', strtotime($date)) && $event_id == $event) {
            $eventers = array('id' => $ticket_id, 'title' => get_the_title($event), 'date' => date_i18n("Y-m-d", strtotime($date)), 'name' => $username, 'email' => $registrant_email, "status" => $status, "amount" => $amount, "services" => $serviceOpted);
        } else {
            $eventers = array('ID' => "", 'Title' => "", 'Date' => "", 'name' => "", 'email' => "", "status" => "", "amount" => "");
            return rest_ensure_response(['error' => "Sorry, ticket does not match with the selected event"]);
        }
    } else {
        $eventers = array('ID' => "No such ticket found", 'Title' => "", 'Date' => "", 'name' => "", 'email' => "", "status" => "", "amount" => "");
        return rest_ensure_response(['error' => "Sorry, no details found!"]);
    }
    $response = array("scan" => $eventers, "msg" => $message);
    $checkedIn = eventer_process_checkin_after($code);
    $status = $checkedIn['status'];
    $message = $checkedIn['msg'];
    if ($status === 1) {
        return rest_ensure_response($response);
    } else {
        return rest_ensure_response(['error' => $message]);
    }
}

function eventer_process_checkin_after($registrant = null)
{
    $woocommerce_events = eventer_get_settings('eventer_enable_woocommerce_ticketing');
    $registrants = eventer_get_registrant_details('id', $registrant);
    $status = 0;
    $msg = "";
    if ($woocommerce_events == 'on') {

    } else {
        $user_system = eventer_decode_array_payload($registrants->user_system);
        if (isset($user_system['checkin']) && $user_system['checkin'] == '1') {
            $msg = "This ticket has already been checked in";
            $status = -3;
        } else {
            addDefaultMeta(['reg_id' => $registrant, 'meta' => 'checked_in', 'slot' => $user_system['time_slot'] ?? "", 'value' => date_i18n('Y-m-d H:i:s')]);
            $user_system['checkin'] = "1";
            $user_system['checkin_date'] = date_i18n('Y-m-d H:i:s');
            eventer_update_registrant_details(array('user_system' => serialize($user_system)), $registrant, array("%s", "%s"));
            $msg = "Successfully checked-in.";
            $status = 1;
        }
    }
    return ['status' => $status, 'msg' => $msg];
}
