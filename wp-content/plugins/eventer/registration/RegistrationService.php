<?php

class RegistrationService
{
    public static function sanitize_booking_request(array $request = array())
    {
        if (empty($request)) {
            $request = wp_unslash($_REQUEST);
        }

        $post = wp_unslash($_POST);
        $eventer_time = isset($post['reg_event_time']) ? eventer_sanitize_time_input($post['reg_event_time']) : '00:00:00';
        if ($eventer_time === '') {
            $eventer_time = '00:00:00';
        }

        return array(
            'reg_email' => isset($post['reg_mail']) ? sanitize_email($post['reg_mail']) : '',
            'reg_name' => isset($post['reg_name']) ? sanitize_text_field($post['reg_name']) : '',
            'eventer_id' => isset($post['eventer_id']) ? absint($post['eventer_id']) : 0,
            'eventer_date' => isset($post['reg_event_date']) ? eventer_sanitize_date_input($post['reg_event_date']) : '',
            'eventer_time' => $eventer_time,
            'eventer_time_slot' => isset($post['reg_event_slot']) ? sanitize_text_field($post['reg_event_slot']) : '',
            'tickets' => isset($request['tickets']) ? eventer_sanitize_ticket_rows($request['tickets']) : array(),
            'formdata' => isset($post['reg_data']) ? eventer_sanitize_form_rows($post['reg_data']) : array(),
            'services' => isset($post['services']) ? eventer_sanitize_service_rows($post['services']) : array(),
            'registrants' => isset($post['registrants']) ? eventer_sanitize_registrant_rows($post['registrants']) : array(),
            'amount' => isset($post['amount']) ? eventer_sanitize_decimal_value($post['amount']) : 0.0,
            'cart_status' => isset($post['cart_status']) ? absint($post['cart_status']) : 0,
            'book_type' => isset($request['book_type']) ? sanitize_text_field($request['book_type']) : 'eventer',
            'card_credentials' => (isset($request['card_cred']) && is_array($request['card_cred'])) ? $request['card_cred'] : array(),
            'user_filled_data' => array_column(isset($post['reg_data']) ? eventer_sanitize_form_rows($post['reg_data']) : array(), 'value', 'name'),
        );
    }

    public static function find_restricted_ticket_conflict($eventer_id, $email, array $tickets, $ticket_datetime)
    {
        $eventer_id = absint($eventer_id);
        $email = sanitize_email($email);
        if ($eventer_id <= 0 || $email === '' || empty($tickets) || $ticket_datetime === '') {
            return '';
        }

        global $wpdb;
        $registrant_table = $wpdb->prefix . 'eventer_registrant';
        $ticket_table = $wpdb->prefix . 'eventer_tickets';
        $registrations = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT tickets FROM $registrant_table WHERE eventer = %d AND email = %s",
                $eventer_id,
                $email
            ),
            ARRAY_A
        );

        if (empty($registrations)) {
            return '';
        }

        foreach ($registrations as $registration) {
            $tickets_booked = eventer_decode_array_payload($registration['tickets']);
            if (empty($tickets_booked)) {
                continue;
            }

            foreach ($tickets_booked as $booked_ticket) {
                $ticket_name = isset($booked_ticket['name']) ? sanitize_text_field($booked_ticket['name']) : '';
                if ($ticket_name === '') {
                    continue;
                }

                $saved_ticket = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT ticket_id FROM $ticket_table WHERE event = %d AND date = %s AND name = %s AND restricts = %d LIMIT 1",
                        $eventer_id,
                        $ticket_datetime,
                        $ticket_name,
                        1
                    )
                );

                if (!empty($saved_ticket)) {
                    return $ticket_name;
                }
            }
        }

        return '';
    }

    public static function reserve_ticket_inventory($eventer_id, $eventer_date, $eventer_time, array $tickets)
    {
        if ($eventer_id <= 0 || $eventer_date === '' || empty($tickets)) {
            return;
        }

        $all_dynamics = array();
        $total_booked = 0;
        foreach ($tickets as $ticket) {
            $ticket_id = isset($ticket['id']) ? absint($ticket['id']) : 0;
            $ticket_number = isset($ticket['number']) ? absint($ticket['number']) : 0;
            if ($ticket_number <= 0) {
                continue;
            }

            $all_dynamics[$ticket_id] = $ticket_number;
            $total_booked += $ticket_number;
        }

        if (empty($all_dynamics)) {
            return;
        }

        $date_time = $eventer_date . ' ' . $eventer_time;
        if (get_post_meta($eventer_id, 'eventer_common_ticket_count', true) != '') {
            $all_tickets = eventer_update_date_wise_bookings_table($eventer_id, $date_time, array(), 2, 1, true);
            if ($all_tickets) {
                foreach ($all_tickets as $ticket) {
                    $dynamic_id = isset($ticket['dynamic']) ? $ticket['dynamic'] : '';
                    eventer_update_date_wise_bookings_table(
                        $eventer_id,
                        $date_time,
                        array(array('id' => $dynamic_id, 'number' => $total_booked)),
                        1,
                        1,
                        true
                    );
                }
            }
            return;
        }

        eventer_update_date_wise_bookings_table($eventer_id, $date_time, $tickets, 1, 1, true);
    }

    public static function insert_legacy_registration(array $booking_data)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'eventer_registrant';

        $inserted = $wpdb->insert(
            $table_name,
            array(
                'eventer' => absint($booking_data['eventer']),
                'eventer_date' => $booking_data['eventer_date'],
                'username' => $booking_data['username'],
                'email' => $booking_data['email'],
                'user_details' => $booking_data['user_details'],
                'tickets' => $booking_data['tickets'],
                'ctime' => $booking_data['ctime'],
                'status' => $booking_data['status'],
                'amount' => $booking_data['amount'],
                'user_system' => $booking_data['user_system'],
                'user_id' => absint($booking_data['user_id']),
                'paymentmode' => $booking_data['paymentmode'],
            ),
            array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%d', '%s')
        );

        if (!$inserted) {
            return 0;
        }

        return (int) $wpdb->insert_id;
    }

    public static function build_user_system_payload(array $services, array $registrants, $eventer_time, $eventer_time_slot)
    {
        return serialize(
            array(
                'ip' => eventer_client_ip(),
                'services' => $services,
                'email_pre' => '1',
                'registrants' => $registrants,
                'time_slot' => $eventer_time,
                'slot_title' => $eventer_time_slot,
            )
        );
    }

    public static function prepare_ticket_markup_data($field, $registrant, $atts = array())
    {
        $reg = eventer_get_registrant_details($field, $registrant);
        $all_data = array();
        $individual = array();
        $default_payment_reg = array();
        $all_events = array();
        $default_all = '';
        $eventer_organizer = array();

        if (!$reg) {
            return $all_data;
        }

        $usersystem = eventer_decode_array_payload($reg->user_system);
        $time_slot = isset($usersystem['time_slot']) ? $usersystem['time_slot'] : '';
        $user_registrants_list = (!empty($usersystem) && isset($usersystem['registrants'])) ? $usersystem['registrants'] : array();
        $woo_user_registrants_list = (!empty($usersystem) && isset($usersystem['tickets'])) ? $usersystem['tickets'] : array();
        $user_registrants_list = ($field == 'id') ? $user_registrants_list : $woo_user_registrants_list;
        $usertickets = ($field == 'id') ? eventer_decode_array_payload($reg->tickets) : $user_registrants_list;
        $normal_registration = !empty($usertickets) ? $usertickets : array();

        if (!empty($normal_registration)) {
            foreach ($normal_registration as $normal) {
                if ((isset($normal['number']) && $normal['number'] > 0) || (isset($normal['quantity']) && $normal['quantity'] > 0)) {
                    $quantity = ($field == 'id') ? $normal['number'] : $normal['quantity'];
                    $ticket = ($field == 'id') ? $normal['name'] : $normal['ticket'];
                    $default_payment_reg[$ticket] = $quantity;
                    $default_all .= $ticket . ' X ' . $quantity;
                }
            }
        }

        if (!empty($user_registrants_list)) {
            foreach ($user_registrants_list as $key => $value) {
                if ((isset($value['type']) && $value['type'] != 'ticket') || ($key == 'main' && $key != 0)) {
                    continue;
                }

                $valindex = ($field == 'id') ? $key : $value['ticket'];
                $set_quantity = isset($value['quantity']) ? $value['quantity'] : '';
                $get_quantity = array_key_exists($valindex, $default_payment_reg) ? $default_payment_reg[$valindex] : $set_quantity;
                $valvalue = ($field == 'id') ? $value : $value['registrants'];
                $event_id = ($field == 'id' && isset($atts['event'])) ? $atts['event'] : $value['event'];
                $event_id = empty($event_id) ? $reg->eventer : $event_id;
                $event_time = get_post_meta($event_id, 'eventer_event_start_dt', true);
                $event_end_time = get_post_meta($event_id, 'eventer_event_end_dt', true);
                $days_diff = eventer_dateDiff($event_time, $event_end_time);
                $allday = get_post_meta($event_id, 'eventer_event_all_day', true);
                $eventer_organizer = eventer_get_terms_front('eventer-organizer', $event_id, array('organizer_email'));
                $eventer_venue = get_the_terms($event_id, 'eventer-venue');
                $eventer_location = '';

                if (!is_wp_error($eventer_venue) && !empty($eventer_venue)) {
                    foreach ($eventer_venue as $venue) {
                        $location_address = get_term_meta($venue->term_id, 'venue_address', true);
                        $eventer_location = ($location_address != '') ? $location_address : $venue->name;
                    }
                }

                $default_reg = array(array('name' => $reg->username, 'email' => $reg->email, 'quantity' => $get_quantity));
                $valvalue = (!empty($valvalue) && $valindex != 'main') ? array_merge($valvalue, $default_reg) : $default_reg;

                if (!empty($valvalue) && $valindex != 'main') {
                    foreach ($valvalue as $val) {
                        $new_valindex = $valindex;
                        $quantity = isset($val['quantity']) ? '1' : '';
                        if ($quantity == '' && isset($val['email']) && $val['email'] == $reg->email) {
                            continue;
                        }
                        if ($quantity != '') {
                            if (in_array($event_id, $all_events, true)) {
                                continue;
                            }
                            $all_events[] = $event_id;
                            $quantity = '';
                            $new_valindex = $default_all;
                        }

                        $time = ($time_slot != '00:00:00' && $time_slot != '') ? $time_slot : date_i18n(get_option('time_format'), strtotime($event_time)) . ' - ' . date_i18n(get_option('time_format'), strtotime($event_end_time));
                        $time = (($time_slot != '00:00:00' && $time_slot != '' && $allday !== 'on')) ? $time : esc_html__('All day', 'eventer');

                        if ($days_diff > 0) {
                            $dynamic_start_date = date_i18n('Y-m-d', $value['date']);
                            $set_end_date = date('Y-m-d', strtotime($dynamic_start_date . ' + ' . $days_diff . ' days'));
                            $date = ($field == 'id') ? $reg->eventer_date : date_i18n(get_option('date_format'), $value['date']) . ' - ' . date_i18n(get_option('date_format'), strtotime($set_end_date));
                        } else {
                            $date = ($field == 'id') ? $reg->eventer_date : date_i18n(get_option('date_format'), $value['date']);
                        }

                        $individual[] = array(
                            'data-ticket' => $new_valindex,
                            'data-elocation' => $eventer_location,
                            'data-datetime' => $time . ' ' . $date,
                            'data-eventid' => $event_id,
                            'data-eventname' => get_the_title($event_id),
                            'data-email' => isset($val['email']) ? $val['email'] : '',
                            'data-name' => isset($val['name']) ? $val['name'] : '',
                            'data-qrcode' => $reg->id,
                            'data-img' => '',
                        );
                    }
                }
            }
        }

        $all_data = array(
            'data-nonce' => eventer_create_registrant_action_nonce('eventer-qrcode-nonce', $reg->id),
            'default' => array(
                'data-uname' => $reg->username,
                'data-uemail' => $reg->email,
                'data-registrant' => $reg->id,
            ),
            'data-mainreg' => $reg->email,
            'data-registrant' => $reg->id,
            'data-eid' => '',
            'data-organizer' => (isset($eventer_organizer['metas'])) ? $eventer_organizer['metas']['organizer_email'] : '',
            'individual' => $individual,
        );

        return $all_data;
    }
}
