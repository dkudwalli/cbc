<?php
defined('ABSPATH') or die('No script kiddies please!');

if (!function_exists('eventer_render_booking_details_page')) {
    function eventer_render_booking_details_page($registrant)
    {
        $get_details = eventer_get_registrant_details('id', $registrant);
        if (!$get_details) {
            echo 'No details found here';
            return;
        }

        $user_details = eventer_decode_array_payload($get_details->user_details);
        if (is_array($user_details)) {
            $user_details = array_column($user_details, 'value', 'name');
        }
        $settings = eventer_decode_array_payload($get_details->user_system);
        $payment = eventer_decode_array_payload($get_details->paypal_details);
        $payment_mode = $get_details->paymentmode;
        $payment_mode_string = 'Offline';
        if ($payment_mode == '1') {
            $payment_mode_string = 'PayPal';
        } elseif ($payment_mode == '2') {
            $payment_mode_string = 'Stripe';
        }
        $payment_status = $get_details->status;
        $payment_amount = $get_details->amount;
        $tickets_booked = eventer_decode_array_payload($get_details->tickets);
        $new_booked = array();
        if ($tickets_booked) {
            foreach ($tickets_booked as $ticket) {
                if (isset($ticket['number']) && $ticket['number'] > 0) {
                    $new_booked[$ticket['name']] = $ticket['number'];
                }
            }
        }
        $tickets_booked = $new_booked;
        $username = $get_details->username;
        $email = $get_details->email;

        echo '<h2>Booking Details</h2>
        <p>Below are the details of registration ID: ' . $get_details->id . ' and the username is ' . $get_details->username . '</p>
        <div class="tab">
            <button class="tablinks" onclick="openCity(event, \'London\')" id="defaultOpen">Details</button>
            <button class="tablinks" onclick="openCity(event, \'Paris\')">Tickets Booked</button>
            <button class="tablinks" onclick="openCity(event, \'Tokyo\')">Payment Details</button>
        </div>
        <div id="London" class="tabcontent">
            <span onclick="this.parentElement.style.display=\'none\'" class="topright">&times</span>
            <form class="eventer-update-user-details"><fieldset>';
        if ($user_details) {
            foreach ($user_details as $key => $value) {
                if (strpos($key, 'quantity') !== false || strpos($key, 'chosen') !== false) {
                    continue;
                }
                echo $key . ':<br><input type="text" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '"><br>';
            }
        }
        echo '<br/><input type="submit" value="Submit" class=""></fieldset></form></div>';

        echo '<div id="Paris" class="tabcontent">
            <span onclick="this.parentElement.style.display=\'none\'" class="topright">&times</span>
            <form class="eventer-update-user-settings"><fieldset>';
        if (!empty($settings['registrants'])) {
            foreach ($settings['registrants'] as $ticket => $regs) {
                if (array_key_exists($ticket, $tickets_booked) && $tickets_booked[$ticket] <= 0) {
                    continue;
                }
                echo '<div class="eventer-tickets-area"><h4>' . esc_html($ticket) . ':<br></h4>';
                foreach ($regs as $reg) {
                    $default_class = $disabled = $default_msg = '';
                    $name = isset($reg['name']) ? $reg['name'] : '';
                    $value = isset($reg['email']) ? $reg['email'] : '';
                    if ($name == $username && $value == $email) {
                        $default_class = 'default-field';
                        $disabled = 'disabled';
                        $default_msg = 'These are the default values of registraion and you can modify them from Details section.';
                    }
                    echo '<span class="tickets-specific">';
                    echo '<input ' . $disabled . ' type="text" class="reg-name ' . esc_attr($default_class) . '" value="' . esc_attr($name) . '">';
                    echo '<input ' . $disabled . ' type="text" class="reg-email ' . esc_attr($default_class) . '" value="' . esc_attr($value) . '"></span>';
                    echo '<p>' . esc_html($default_msg) . '</p><br>';
                }
                echo '</div>';
            }
        }
        echo '<br/><input type="submit" value="Submit" class=""></fieldset></form></div>';

        echo '<div id="Tokyo" class="tabcontent">
            <span onclick="this.parentElement.style.display=\'none\'" class="topright">&times</span>
            <div><label><strong>Payment Mode: </strong></label><label style="background-color:red;">' . esc_html($payment_mode_string) . '</label></div>
            <div><label><strong>Payment Status: </strong></label><label style="background-color:red;">' . esc_html($payment_status) . '</label></div>
            <div><label><strong>Payment Amount: </strong></label><label style="background-color:red;">' . esc_html($payment_amount) . '</label></div>';
        if ($payment) {
            foreach ($payment as $key => $value) {
                if (is_array($value)) {
                    continue;
                }
                echo '<div><label><strong>' . esc_html($key) . ': </strong></label><label style="background-color:red;">' . esc_html($value) . '</label></div>';
            }
        }
        echo '</div>
        <script>
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        document.getElementById("defaultOpen").click();
        jQuery(document).ready(function($){
            jQuery(document).on("submit", ".eventer-update-user-details", function(e){
                e.preventDefault();
                var form_data = jQuery(this).serializeArray();
                var reg = ' . absint($registrant) . ';
                var username = $("input[name=Name]").val();
                var email = $("input[name=email]").val();
                $.ajax({
                    url:"' . esc_url(admin_url('admin-ajax.php')) . '",
                    type:"post",
                    dataType:"json",
                    data:{
                        action:"eventer_booking_user_details_update",
                        nonce:"' . esc_js(wp_create_nonce('eventer_admin_nonce')) . '",
                        reg:reg,
                        details:form_data,
                        username:username,
                        email:email
                    }
                });
            });
            jQuery(document).on("submit", ".eventer-update-user-settings", function(e){
                e.preventDefault();
                var tickets = {};
                $(".eventer-tickets-area").each(function(){
                    var ticket_reg = [];
                    var ticket_name = $(this).find("h4").text();
                    $(this).find(".tickets-specific").each(function(){
                        ticket_reg.push({name:$(this).find(".reg-name").val(), email:$(this).find(".reg-email").val()});
                    });
                    tickets[ticket_name] = ticket_reg;
                });
                $.ajax({
                    url:"' . esc_url(admin_url('admin-ajax.php')) . '",
                    type:"post",
                    dataType:"json",
                    data:{
                        action:"eventer_booking_user_settings_update",
                        nonce:"' . esc_js(wp_create_nonce('eventer_admin_nonce')) . '",
                        reg:' . absint($registrant) . ',
                        details:tickets,
                        settings:' . wp_json_encode($settings) . '
                    }
                });
            });
        });
        </script>';
    }
}

if (!function_exists('eventer_handle_booking_user_details_update')) {
    function eventer_handle_booking_user_details_update()
    {
        eventer_verify_admin_ajax_request();
        $registrant_id = isset($_REQUEST['reg']) ? absint($_REQUEST['reg']) : 0;
        $user_details = (isset($_REQUEST['details']) && is_array($_REQUEST['details'])) ? map_deep(wp_unslash($_REQUEST['details']), 'sanitize_text_field') : array();
        $username = isset($_REQUEST['username']) ? sanitize_text_field(wp_unslash($_REQUEST['username'])) : '';
        $email = isset($_REQUEST['email']) ? sanitize_email(wp_unslash($_REQUEST['email'])) : '';
        eventer_update_registrant_details(array('user_details' => serialize($user_details), 'username' => $username, 'email' => $email), $registrant_id, array('%s', '%s', '%s'));
        wp_send_json_success();
    }
}

if (!function_exists('eventer_handle_export_bookings_csv_ajax')) {
    function eventer_handle_export_bookings_csv_ajax()
    {
        eventer_verify_admin_ajax_request();
        global $wpdb;
        $booking_status = isset($_REQUEST['status']) ? sanitize_text_field(wp_unslash($_REQUEST['status'])) : '';
        $specific_event = isset($_REQUEST['eventer']) ? absint($_REQUEST['eventer']) : 0;
        $where = '';
        $query_args = array();
        if ($booking_status != '' && $specific_event != 0) {
            $where = 'WHERE status = %s AND eventer = %d';
            $query_args = array($booking_status, $specific_event);
        } elseif ($booking_status != '') {
            $where = 'WHERE status = %s';
            $query_args = array($booking_status);
        } elseif ($specific_event != 0) {
            $where = 'WHERE eventer = %d';
            $query_args = array($specific_event);
        }

        $table_name = $wpdb->prefix . 'eventer_registrant';
        $sql = "SELECT * FROM $table_name $where";
        $export_query = empty($query_args) ? $wpdb->get_results($sql, ARRAY_A) : $wpdb->get_results($wpdb->prepare($sql, $query_args), ARRAY_A);
        if (!empty($wpdb->last_error)) {
            die('The following error was found: ' . $wpdb->print_error());
        }

        $output_filename = 'eventer_booking_csv_' . date_i18n('Y-m-d_H-i-s') . '.csv';
        $output_handle = @fopen('php://output', 'w');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename=' . $output_filename);
        header('Expires: 0');
        header('Pragma: public');

        fputcsv($output_handle, array('Registrant ID', 'Eventer ID', 'Registration Time', 'Eventer Date', 'Transaction ID', 'Registrant User Name', 'Registrant Email', 'Payment Mode', 'Payment Status', 'Amount', 'User Details', 'Tickets', 'User ID', 'Payment Details', 'User Details'));
        foreach ($export_query as $result) {
            $row = array();
            foreach ($result as $key => $value) {
                $show_data = $value;
                if (in_array($key, array('user_details', 'user_system', 'tickets', 'paypal_details'), true)) {
                    $decoded_value = eventer_decode_array_payload($value);
                    $show_data = !empty($decoded_value) ? $decoded_value : $value;
                }
                if (is_array($show_data) && $key == 'user_details') {
                    $user_details = '';
                    foreach ($show_data as $data) {
                        $field_val = isset($data['value']) ? $data['value'] : '';
                        $field_name = isset($data['name']) ? $data['name'] : '';
                        $user_details .= ($field_val != '') ? '{' . $field_name . ': ' . $field_val . '}' : '';
                    }
                    $show_data = $user_details;
                } elseif (is_array($show_data) && $key == 'user_system') {
                    $user_tickets = '';
                    foreach ($show_data as $meta_key => $meta_value) {
                        if ($meta_key == 'tickets') {
                            foreach ($meta_value as $ticket) {
                                foreach ($ticket as $ticket_key => $ticket_value) {
                                    $ticket_value = ($ticket_key == 'date') ? date_i18n('Y-m-d', $ticket_value) : $ticket_value;
                                    $ticket_value = ($ticket_key == 'event') ? get_the_title($ticket_value) : $ticket_value;
                                    $user_tickets .= '{' . $ticket_key . ': ' . $ticket_value . '}';
                                }
                            }
                        }
                        if ($meta_key == 'services') {
                            foreach ($meta_value as $service) {
                                $user_tickets .= '{' . $service['name'] . '=>' . $service['value'] . '}';
                            }
                        }
                    }
                    $show_data = $user_tickets;
                } elseif (is_array($show_data) && $key == 'tickets') {
                    $user_tickets = '';
                    foreach ($show_data as $data) {
                        $tickets_count = (isset($data['number']) && $data['number'] != '') ? $data['number'] : 0;
                        $tickets_name = (isset($data['name']) && $data['name'] != '') ? $data['name'] : '';
                        $user_tickets .= ($tickets_count != 0) ? '{' . $tickets_name . ': ' . $tickets_count . '}' : '';
                    }
                    $show_data = $user_tickets;
                } elseif (is_array($show_data) && $key == 'paypal_details') {
                    $payment_details = '';
                    foreach ($show_data as $payment_key => $payment_value) {
                        if (is_array($payment_value)) {
                            continue;
                        }
                        $payment_details .= '{' . $payment_key . ': ' . $payment_value . '}';
                    }
                    $show_data = $payment_details;
                }
                $row[$key] = $show_data;
            }
            fputcsv($output_handle, $row);
        }
        fclose($output_handle);
        die();
    }
}

if (!function_exists('eventer_handle_booking_user_settings_update')) {
    function eventer_handle_booking_user_settings_update()
    {
        eventer_verify_admin_ajax_request();
        $registrant_id = isset($_REQUEST['reg']) ? absint($_REQUEST['reg']) : 0;
        $user_details = (isset($_REQUEST['details']) && is_array($_REQUEST['details'])) ? map_deep(wp_unslash($_REQUEST['details']), 'sanitize_text_field') : array();
        $settings = (isset($_REQUEST['settings']) && is_array($_REQUEST['settings'])) ? map_deep(wp_unslash($_REQUEST['settings']), 'sanitize_text_field') : array();
        $settings['registrants'] = $user_details;
        eventer_update_registrant_details(array('user_system' => serialize($settings)), $registrant_id, array('%s', '%s'));
        wp_send_json_success();
    }
}

if (!function_exists('eventer_handle_get_booked_tickets_ajax')) {
    function eventer_handle_get_booked_tickets_ajax()
    {
        eventer_verify_admin_ajax_request();
        $eventer_id = isset($_REQUEST['eventer_id']) ? absint($_REQUEST['eventer_id']) : 0;
        $booked_date = (isset($_REQUEST['booked_date']) && $_REQUEST['booked_date']) ? sanitize_text_field(wp_unslash($_REQUEST['booked_date'])) : '';
        $booked_time = (isset($_REQUEST['booked_time']) && $_REQUEST['booked_time']) ? sanitize_text_field(wp_unslash($_REQUEST['booked_time'])) : '';
        $original_event = eventer_wpml_original_post_id($eventer_id);
        $default_featured = get_post_meta($original_event, 'eventer_event_featured', true);
        $updated_tickets_new = eventer_update_date_wise_bookings_table($eventer_id, $booked_date . ' ' . $booked_time, array());
        $updated_tickets_new = eventer_update_date_wise_bookings_table($eventer_id, $booked_date . ' ' . $booked_time, array(), 2);
        $featured_events = get_option('eventer_all_featured_events');
        $featured_events = !empty($featured_events) ? $featured_events : array();
        $saved_data = isset($featured_events[$booked_date]) ? $featured_events[$booked_date] : array();
        $saved_data = array_unique($saved_data);
        if (!empty($updated_tickets_new) && isset($updated_tickets_new[0]['featured']) && $updated_tickets_new[0]['featured'] == 1) {
            $saved_data[] = $eventer_id;
        } else {
            $saved_data = array_diff($saved_data, array($eventer_id));
        }
        $featured_events[$booked_date] = $saved_data;
        update_option('eventer_all_featured_events', $featured_events);

        $title = array();
        if ($updated_tickets_new) {
            foreach ($updated_tickets_new as $ticket) {
                $locale_title = isset($ticket['cust_val1']) ? $ticket['cust_val1'] : array();
                $locale_title = json_decode($locale_title, true);
                $title[$ticket['ticket_id']] = isset($locale_title[EVENTER__LANGUAGE_CODE]) ? $locale_title[EVENTER__LANGUAGE_CODE] : '';
            }
        }

        echo wp_json_encode(array('tickets' => $updated_tickets_new, 'title' => $title, 'featured' => $default_featured));
        die();
    }
}

if (!function_exists('eventer_handle_update_booked_tickets_ajax')) {
    function eventer_handle_update_booked_tickets_ajax()
    {
        eventer_verify_admin_ajax_request();
        $eventer_id = isset($_REQUEST['eventer_id']) ? absint($_REQUEST['eventer_id']) : 0;
        $booked_date = isset($_REQUEST['booked_date']) ? sanitize_text_field(wp_unslash($_REQUEST['booked_date'])) : '';
        $eventer_position = isset($_REQUEST['position']) ? sanitize_text_field(wp_unslash($_REQUEST['position'])) : '';
        if ($eventer_position == 'reset') {
            global $wpdb;
            $table_name_tickets = $wpdb->prefix . 'eventer_tickets';
            $wpdb->delete($table_name_tickets, array('event' => $eventer_id), array('%d'));
            wp_die();
        }

        $time_slot = (isset($_REQUEST['time']) && $_REQUEST['time'] != 'undefined' && $_REQUEST['time'] != '') ? sanitize_text_field(wp_unslash($_REQUEST['time'])) : '00:00:00';
        $booked_date_full = $booked_date . ' ' . $time_slot;
        $tickets_new = isset($_REQUEST['updated_detail']) ? $_REQUEST['updated_detail'] : array();
        $woo_payment = eventer_get_settings('eventer_enable_woocommerce_ticketing');
        $setup_tickets_new = ($woo_payment == 'on') ? array() : $tickets_new;
        if (!empty($tickets_new) && empty($setup_tickets_new)) {
            foreach ($tickets_new as $ticket_get) {
                if (isset($ticket_get['pid']) && $ticket_get['pid'] == '' && isset($ticket_get['name']) && $ticket_get['name'] != '') {
                    $product_id = wp_insert_post(array('post_type' => 'product', 'post_title' => $ticket_get['name'], 'post_status' => 'publish'));
                    if (function_exists('icl_object_id') && class_exists('SitePress') && function_exists('wpml_add_translatable_content')) {
                        wpml_add_translatable_content('post_product', $product_id, EVENTER__LANGUAGE_CODE);
                    }
                    $ticket_get['id'] = intval($product_id) + intval($eventer_id);
                    $ticket_get['pid'] = $product_id;
                    wp_set_object_terms($product_id, 'eventer', 'product_cat');
                    update_post_meta($product_id, '_regular_price', (float) $ticket_get['price']);
                    update_post_meta($product_id, '_price', (float) $ticket_get['price']);
                    update_post_meta($product_id, '_virtual', 'yes');
                    eventer_update_date_wise_bookings_table($eventer_id, $booked_date . ' ' . $time_slot, array($ticket_get), 2);
                }
                $setup_tickets_new[] = $ticket_get;
            }
        }
        eventer_update_date_wise_bookings_table($eventer_id, $booked_date_full, $setup_tickets_new, 1, 3);
        wp_die();
    }
}

if (!function_exists('eventer_handle_get_term_details_ajax')) {
    function eventer_handle_get_term_details_ajax()
    {
        eventer_verify_admin_ajax_request();
        $term_id = isset($_REQUEST['term_id']) ? absint($_REQUEST['term_id']) : 0;
        $taxonomy = isset($_REQUEST['taxonomy']) ? sanitize_text_field(wp_unslash($_REQUEST['taxonomy'])) : '';
        if ($taxonomy == 'list:eventer-venue') {
            $location = get_term_meta($term_id, 'venue_address', true);
            echo '<div id="misc-publishing-actions" class="eventer-admin-term-metas-show"><div class="">' . esc_html__('Location', 'eventer') . ': <span id="post-status-display">' . esc_attr($location) . '</span></div>';
        } else {
            $organizer_email = get_term_meta($term_id, 'organizer_email', true);
            $organizer_phone = get_term_meta($term_id, 'organizer_phone', true);
            $organizer_website = get_term_meta($term_id, 'organizer_website', true);
            echo '<div id="misc-publishing-actions" class="eventer-admin-term-metas-show"><div class="">' . esc_html__('Email', 'eventer') . ': <span id="post-status-display">' . esc_attr($organizer_email) . '</span></div><div class="">' . esc_html__('Phone', 'eventer') . ': <span id="post-status-display">' . esc_attr($organizer_phone) . '</span></div><div class="">' . esc_html__('Website', 'eventer') . ': <span id="post-status-display">' . esc_url($organizer_website) . '</span></div></div>';
        }
        wp_die();
    }
}

if (!function_exists('eventer_handle_export_registrants_ajax')) {
    function eventer_handle_export_registrants_ajax()
    {
        eventer_verify_admin_ajax_request();
        global $wpdb;
        $booking_date = isset($_REQUEST['date']) ? sanitize_text_field(wp_unslash($_REQUEST['date'])) : '';
        $booking_status = isset($_REQUEST['status']) ? sanitize_text_field(wp_unslash($_REQUEST['status'])) : '';
        $specific_event = isset($_REQUEST['eventer']) ? absint($_REQUEST['eventer']) : 0;
        $woocommerce_events = eventer_get_settings('eventer_enable_woocommerce_ticketing');
        $where = '';
        $query_args = array();
        if ($woocommerce_events != 'on') {
            $where_clauses = array();
            if ($specific_event) {
                $where_clauses[] = 'eventer = %d';
                $query_args[] = $specific_event;
            }
            if ($booking_date != '') {
                $where_clauses[] = 'eventer_date = %s';
                $query_args[] = $booking_date;
            }
            if ($booking_status != '') {
                $where_clauses[] = 'status = %s';
                $query_args[] = $booking_status;
            }
            if (!empty($where_clauses)) {
                $where = 'WHERE ' . implode(' AND ', $where_clauses);
            }
        } elseif ($booking_status != '') {
            $where = 'WHERE status = %s';
            $query_args[] = $booking_status;
        }

        $table_name = $wpdb->prefix . 'eventer_registrant';
        $sql = "SELECT * FROM $table_name $where";
        $export_query = empty($query_args) ? $wpdb->get_results($sql, ARRAY_A) : $wpdb->get_results($wpdb->prepare($sql, $query_args), ARRAY_A);
        if (!empty($wpdb->last_error)) {
            die('The following error was found: ' . $wpdb->print_error());
        }

        $output_filename = 'eventer-registrant-csv_' . date_i18n('Y-m-d_H-i-s') . '.csv';
        $output_handle = @fopen('php://output', 'w');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename=' . $output_filename);
        header('Expires: 0');
        header('Pragma: public');

        $lead_array = array();
        if ($woocommerce_events != 'on') {
            foreach ($export_query as $result) {
                $user_data = eventer_decode_array_payload($result['user_details']);
                $user_system = eventer_decode_array_payload($result['user_system']);
                $registrants = isset($user_system['registrants']) ? $user_system['registrants'] : array();
                $time_slot = isset($user_system['slot_title']) ? $user_system['slot_title'] : '';
                $services = isset($user_system['services']) ? $user_system['services'] : array();
                $checkin_info = isset($user_system['checkin']) && $user_system['checkin'] == '1' ? 'Yes-' . $user_system['checkin_date'] : '';
                $service = '';
                foreach ($services as $serve) {
                    $service .= '{' . $serve['name'] . ' ' . $serve['value'] . ' - ' . $serve['cost'] . '} ';
                }
                $tickets_booked = eventer_decode_array_payload($result['tickets']);
                $ticket_counts = array();
                foreach ($tickets_booked as $ticket) {
                    if (isset($ticket['number']) && $ticket['number'] > 0) {
                        $ticket_counts[$ticket['name']] = $ticket['number'];
                    }
                }
                if (!empty($registrants)) {
                    foreach ($registrants as $ticket_name => $values) {
                        if (!array_key_exists($ticket_name, $ticket_counts)) {
                            continue;
                        }
                        foreach ($values as $reg) {
                            $row = array(
                                'status' => $result['status'],
                                'title' => get_the_title($result['eventer']),
                                'id' => $result['id'],
                                'event_date' => $result['eventer_date'],
                                'event_slot' => $time_slot,
                                'registration_date' => $result['ctime'],
                                'username' => $result['username'],
                                'email' => $result['email'],
                                'amount' => $result['amount'],
                                'count' => $ticket_counts[$ticket_name],
                                'QR_ID' => $result['id'],
                                'check_in' => $checkin_info,
                                'services' => $service,
                                'registrant_name' => $reg['name'],
                                'registrant_email' => $reg['email'],
                                'registrant_ticket' => $ticket_name,
                            );
                            foreach ($user_data as $data) {
                                if (strpos('quantity_tkt', $data['name']) !== false) {
                                    continue;
                                }
                                $row[$data['name']] = $data['value'];
                            }
                            foreach ($reg as $field_key => $value) {
                                if ($field_key == 'name' || $field_key == 'email' || $field_key == 'QR_ID') {
                                    continue;
                                }
                                $row[$field_key] = $value;
                            }
                            $lead_array[] = $row;
                        }
                    }
                } else {
                    $lead_array[] = array(
                        'status' => $result['status'],
                        'title' => get_the_title($result['eventer']),
                        'id' => $result['id'],
                        'event_date' => $result['eventer_date'],
                        'event_slot' => $time_slot,
                        'registration_date' => $result['ctime'],
                        'username' => $result['username'],
                        'email' => $result['email'],
                        'amount' => $result['amount'],
                        'registrant_ticket' => '',
                        'registrant_name' => '',
                        'registrant_email' => '',
                    );
                }
            }
            if (!empty($lead_array)) {
                fputcsv($output_handle, array_keys($lead_array[0]));
                foreach ($lead_array as $row) {
                    fputcsv($output_handle, $row);
                }
            }
        } else {
            fputcsv($output_handle, array('status', 'order id', 'Event name', 'Event Date', 'Booking Date', 'Mode', 'Name', 'Email', 'Phone', 'Product Type', 'Ticket name', 'Ticket Quantity', 'Registrants'));
            foreach ($export_query as $result) {
                $woo_order = $result['eventer'];
                $order = wc_get_order($woo_order);
                if (!$order) {
                    continue;
                }
                $user_system = eventer_decode_array_payload($result['user_system']);
                $registrants = isset($user_system['tickets']) ? $user_system['tickets'] : array();
                foreach ($order->get_items() as $item_values) {
                    $item_data = $item_values->get_data();
                    $item_id = $item_values->get_id();
                    $product_name = $item_data['name'];
                    $eventer_id = wc_get_order_item_meta($item_id, '_wceventer_id', true);
                    $eventer_date = wc_get_order_item_meta($item_id, '_wceventer_date', true);
                    if ($specific_event && $eventer_id != $specific_event) {
                        continue;
                    }
                    $ticket_showing = '';
                    foreach ($registrants as $regs) {
                        if ($regs['ticket'] != $product_name) {
                            continue;
                        }
                        $ticket_showing .= get_the_title($regs['event']) . '-' . $regs['ticket'] . '(';
                        foreach ((array) $regs['registrants'] as $set_registrant) {
                            $ticket_showing .= '[' . $set_registrant['name'] . '=>' . $set_registrant['email'] . ']';
                        }
                        $ticket_showing .= ')';
                    }
                    fputcsv($output_handle, array(
                        $order->get_status(),
                        $woo_order,
                        get_the_title($eventer_id),
                        date_i18n(get_option('date_format'), strtotime($eventer_date)),
                        date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($result['ctime'])),
                        $order->get_payment_method_title(),
                        $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                        $order->get_billing_email(),
                        $order->get_billing_phone(),
                        wc_get_order_item_meta($item_id, '_eventer_product', true),
                        $product_name,
                        $item_data['quantity'],
                        $ticket_showing,
                    ));
                }
            }
        }

        fclose($output_handle);
        die();
    }
}

if (!function_exists('eventer_handle_coupon_refresh_ajax')) {
    function eventer_handle_coupon_refresh_ajax()
    {
        eventer_verify_admin_ajax_request();
        global $wpdb;
        $coupons = isset($_REQUEST['coupons']) ? $_REQUEST['coupons'] : array();
        $eventer_coupon_table = $wpdb->prefix . 'eventer_coupons';
        if ($coupons) {
            foreach ($coupons as $coupon) {
                $coupon_id = isset($coupon['id']) ? absint($coupon['id']) : 0;
                $coupon_title = isset($coupon['title']) ? sanitize_text_field(wp_unslash($coupon['title'])) : '';
                $coupon_code = isset($coupon['code']) ? sanitize_text_field(wp_unslash($coupon['code'])) : '';
                $coupon_amount = isset($coupon['amount']) ? sanitize_text_field(wp_unslash($coupon['amount'])) : '';
                $coupon_validity = isset($coupon['validity']) ? sanitize_text_field(wp_unslash($coupon['validity'])) : '';
                $coupon_status = isset($coupon['status']) ? absint($coupon['status']) : 0;
                $coupon_remove = isset($coupon['remove']) ? absint($coupon['remove']) : 0;
                if ($coupon_id == 0 && $coupon_title != '' && $coupon_amount != '') {
                    $wpdb->insert($eventer_coupon_table, array('coupon_name' => $coupon_title, 'coupon_code' => $coupon_code, 'discounted' => $coupon_amount, 'valid_till' => $coupon_validity, 'coupon_status' => $coupon_status), array('%s', '%s', '%s', '%s', '%d'));
                } elseif ($coupon_remove == 1 && $coupon_id != 0) {
                    $wpdb->delete($eventer_coupon_table, array('id' => $coupon_id), array('%d'));
                } elseif ($coupon_id != 0 && $coupon_title != '' && $coupon_amount != '') {
                    $wpdb->update($eventer_coupon_table, array('coupon_name' => $coupon_title, 'coupon_code' => $coupon_code, 'discounted' => $coupon_amount, 'valid_till' => $coupon_validity, 'coupon_status' => $coupon_status), array('id' => $coupon_id), array('%s', '%s', '%s', '%s', '%d'), array('%d'));
                }
            }
        }
        echo wp_json_encode($wpdb->get_results("SELECT * FROM $eventer_coupon_table"));
        wp_die();
    }
}

if (!function_exists('eventer_handle_delete_bookings_ajax')) {
    function eventer_handle_delete_bookings_ajax()
    {
        eventer_verify_admin_ajax_request();
        global $wpdb;
        $table_name = $wpdb->prefix . 'eventer_registrant';
        $bookings = isset($_REQUEST['bookings']) ? array_filter(array_map('absint', (array) $_REQUEST['bookings'])) : array();
        foreach ($bookings as $id) {
            $wpdb->delete($table_name, array('id' => $id), array('%d'));
        }
        echo 'success';
        wp_die();
    }
}
