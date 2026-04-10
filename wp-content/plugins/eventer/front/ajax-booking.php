<?php
defined('ABSPATH') or die('No script kiddies please!');

if (!function_exists('eventer_handle_registrant_tickets_ajax')) {
    function eventer_handle_registrant_tickets_ajax()
    {
        if (!check_ajax_referer('eventer_create_nonce_for_registrant', 'booking_nonce', false)) {
            wp_send_json_error(array('message' => esc_html__('Invalid booking request.', 'eventer')), 403);
        }

        $booking_request = RegistrationService::sanitize_booking_request();
        $reg_email = $booking_request['reg_email'];
        $eventer_id = $booking_request['eventer_id'];
        $tickets = $booking_request['tickets'];
        $eventer_date = $booking_request['eventer_date'];
        $eventer_time = $booking_request['eventer_time'];
        $amount = $booking_request['amount'];
        $formdata = $booking_request['formdata'];
        $services = $booking_request['services'];
        $registrants = $booking_request['registrants'];
        $reg_name = $booking_request['reg_name'];
        $eventer_time_slot = $booking_request['eventer_time_slot'];
        $book_type = $booking_request['book_type'];
        $user_filled_data = $booking_request['user_filled_data'];

        if ($eventer_id <= 0 || get_post_type($eventer_id) !== 'eventer' || $eventer_date === '') {
            wp_send_json_error(array('message' => esc_html__('Invalid event registration request.', 'eventer')), 400);
        }

        $ticket_datetime = trim($eventer_date . ' ' . $eventer_time);
        $duplicate_ticket = RegistrationService::find_restricted_ticket_conflict($eventer_id, $reg_email, $tickets, $ticket_datetime);
        if ($duplicate_ticket !== '') {
            wp_send_json(array('reg_invalid' => '1', 'ticket_name' => $duplicate_ticket));
        }

        if ($book_type == 'woo') {
            eventer_handle_woo_booking_submission($booking_request);
        }

        $secret = '';
        $stripe_response = array();
        $verified_amount_received = 0;
        $transaction_id = '';
        $stripe_status_success = '2';
        if ($book_type == 'stripe') {
            $stripe_result = PaymentService::create_stripe_booking_result($eventer_id, $amount, $booking_request['card_credentials']);
            if (is_wp_error($stripe_result)) {
                wp_send_json_error(array('message' => $stripe_result->get_error_message()), 400);
            }
            $stripe_status_success = $stripe_result['success_flag'];
            $secret = $stripe_result['secret'];
            $transaction_id = $stripe_result['transaction_id'];
            $stripe_response = $stripe_result['stripe_response'];
            $verified_amount_received = $stripe_result['verified_amount_received'];
        }

        if (empty($registrants) && !empty($tickets)) {
            foreach ($tickets as $ticket) {
                $registrants[$ticket['name']] = array(array('name' => $reg_name, 'email' => $reg_email));
            }
        }

        RegistrationService::reserve_ticket_inventory($eventer_id, $eventer_date, $eventer_time, $tickets);

        $current_date = date_i18n('Y-m-d H:i:s');
        $tickets_ser = !empty($tickets) ? serialize($tickets) : '';
        $reg_details = serialize($formdata);
        $payment_mode = isset($user_filled_data['chosen-payment-option']) ? sanitize_text_field($user_filled_data['chosen-payment-option']) : sanitize_text_field($book_type);
        $lastid = RegistrationService::insert_legacy_registration(
            array(
                'eventer' => $eventer_id,
                'eventer_date' => $eventer_date,
                'username' => $reg_name,
                'email' => $reg_email,
                'user_details' => $reg_details,
                'tickets' => $tickets_ser,
                'ctime' => $current_date,
                'status' => 'Pending',
                'amount' => $amount,
                'user_system' => RegistrationService::build_user_system_payload($services, $registrants, $eventer_time, $eventer_time_slot),
                'user_id' => get_current_user_id(),
                'paymentmode' => $payment_mode,
            )
        );

        $autocomplete_orders = eventer_get_settings('eventer_order_autocomplete');
        $registration_id = eventer_encode_security_registration($lastid, 6, 8);
        if ($amount <= 0 || $autocomplete_orders == '1') {
            $registration_id = eventer_encode_security_registration($lastid, 9, 8);
        }
        $confirm_token = ($lastid > 0) ? eventer_create_registrant_action_nonce('eventer-confirm-payment-stripe', $lastid) : '';

        if ($lastid && $book_type == 'stripe' && $stripe_status_success == '1') {
            eventer_update_registrant_details(
                array(
                    'transaction_id' => $transaction_id,
                    'status' => 'Success',
                    'paypal_details' => serialize($stripe_response),
                    'paymentmode' => 'Stripe',
                    'amount' => ($verified_amount_received / 100),
                ),
                $lastid,
                array('%s', '%s', '%s', '%f')
            );
            $registration_id = eventer_encode_security_registration($lastid, 9, 8);
        }

        eventer_pass_email_registration($lastid, '1');
        wp_send_json(
            array(
                'reg' => $registration_id,
                'woo' => '',
                'stripe_error' => '',
                'stripe_msg' => '',
                'secret' => $secret,
                'reg_id' => $lastid,
                'confirm_token' => $confirm_token,
                'reg_invalid' => '0',
            )
        );
    }
}

if (!function_exists('eventer_handle_confirm_payment_stripe_ajax')) {
    function eventer_handle_confirm_payment_stripe_ajax()
    {
        $secret = isset($_REQUEST['secret']) ? sanitize_text_field(wp_unslash($_REQUEST['secret'])) : '';
        $lastid = isset($_REQUEST['reg_id']) ? absint($_REQUEST['reg_id']) : 0;
        $confirm_token = isset($_REQUEST['confirm_token']) ? sanitize_text_field(wp_unslash($_REQUEST['confirm_token'])) : '';
        if ($lastid <= 0 || $secret === '' || !eventer_verify_registrant_action_nonce('eventer-confirm-payment-stripe', $lastid, $confirm_token)) {
            wp_send_json_error(array('message' => esc_html__('Invalid Stripe confirmation request.', 'eventer')), 403);
        }

        $confirmation = PaymentService::confirm_pending_registration($lastid, $secret);
        if (is_wp_error($confirmation)) {
            wp_send_json_error(array('message' => $confirmation->get_error_message()), 400);
        }

        $intent = $confirmation['intent'];
        if ($intent->status == 'requires_action' && $intent->next_action->type == 'use_stripe_sdk') {
            wp_send_json(
                array(
                    'requires_action' => true,
                    'payment_intent_client_secret' => $intent->client_secret,
                )
            );
        } elseif ($intent->status == 'succeeded') {
            wp_send_json(
                array(
                    'reg' => $confirmation['registration_code'],
                    'woo' => '',
                    'stripe_error' => '',
                    'stripe_msg' => '',
                    'secret' => '',
                    'success' => true,
                )
            );
        }

        wp_send_json_error(array('message' => 'Invalid PaymentIntent status'), 500);
    }
}

if (!function_exists('eventer_handle_switch_dashboard_tab_ajax')) {
    function eventer_handle_switch_dashboard_tab_ajax()
    {
        eventer_verify_public_ajax_request('eventer_switch_dashboard_tab');

        $tab = isset($_REQUEST['tab']) ? sanitize_key(wp_unslash($_REQUEST['tab'])) : '';
        $shortcode = isset($_REQUEST['shortcode']) ? sanitize_key(wp_unslash($_REQUEST['shortcode'])) : '';
        $order = isset($_REQUEST['order']) ? absint($_REQUEST['order']) : 0;
        $allowed_tabs = array('eventer_add_new', 'eventer_submissions', 'eventer_bookings', 'eventer_login');
        if ($tab != '' && $tab != 'undefined') {
            if (!in_array($tab, $allowed_tabs, true)) {
                wp_die();
            }
            if ($tab == 'eventer_add_new') {
                $form_options = get_option('eventer_forms_data');
                $form_options = empty($form_options) ? array() : $form_options;
                $current_form_details = isset($form_options[$shortcode]) ? $form_options[$shortcode] : '';
                $add_new = '[eventer_add_new';
                if (!empty($current_form_details)) {
                    $form_status = $current_form_details['status'];
                    $form_sections = $current_form_details['number'];
                    $add_new .= ' status="' . $form_status . '"';
                    $add_new .= ' sections="' . $form_sections . '"';
                    $add_new .= ' id="' . $shortcode . '"';
                    $add_new .= ' load="1"';
                }
                $add_new .= ' ]';
                echo do_shortcode($add_new);
            } else {
                if ($tab != 'eventer_submissions') {
                    echo do_shortcode('[' . $tab . ']');
                } else {
                    echo '<div id="eventer-dashboard-content-area" class="eventer-fe-content-col eventer-fe-content-part eventer-dashboard-main">';
                    echo do_shortcode('[' . $tab . ']');
                    echo '</div>';
                    echo do_shortcode('[eventer_dash_terms]');
                }
            }
        } elseif ($order > 0) {
            $field = (get_post_type($order) == 'shop_order' && eventer_get_settings('eventer_enable_woocommerce_ticketing') == 'on') ? 'eventer' : 'id';
            $new_tickets = apply_filters('eventer_preapare_data_for_tickets', $field, $order, array());
            $new_tickets['data-regpos'] = 15;
            $default = array('data-eid' => '', 'data-regpos' => 15);
            $new_tickets['default'] = $default;
            do_action('eventer_ticket_raw_design', '', $new_tickets);
        }

        wp_die();
    }
}

if (!function_exists('eventer_handle_validate_coupon_ajax')) {
    function eventer_handle_validate_coupon_ajax()
    {
        eventer_verify_public_ajax_request('eventer_validate_coupon');

        $coupon = isset($_REQUEST['coupon']) ? sanitize_text_field(wp_unslash($_REQUEST['coupon'])) : '';
        $amount = isset($_REQUEST['amount']) ? floatval(wp_unslash($_REQUEST['amount'])) : 0;
        $validate = $msg = $discounted = '';
        if ($coupon || $amount > 0) {
            global $wpdb;
            $eventer_coupon_table = $wpdb->prefix . 'eventer_coupons';
            $coupon_row = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM $eventer_coupon_table WHERE coupon_code = %s", $coupon),
                ARRAY_A
            );
            if ($coupon_row) {
                $coupon_discount = isset($coupon_row['discounted']) ? $coupon_row['discounted'] : '';
                $coupon_validity = isset($coupon_row['valid_till']) ? $coupon_row['valid_till'] : '';
                $coupon_status = isset($coupon_row['coupon_status']) ? $coupon_row['coupon_status'] : '';
                if (strtotime($coupon_validity) > date_i18n('U') && $coupon_status != '1') {
                    $validate = '1';
                    $msg = esc_html__('Coupon validated successfully.', 'eventer');
                    $discounted = $coupon_discount;
                    if (is_numeric($coupon_discount)) {
                        $amount = intval($amount) - intval($coupon_discount);
                    } elseif (strpos($coupon_discount, '%') !== false) {
                        $discounted_amount = ($coupon_discount / 100) * $amount;
                        $amount = $amount - $discounted_amount;
                    }
                } elseif (strtotime($coupon_validity) < date_i18n('U')) {
                    $validate = '0';
                    $msg = esc_html__('Coupon validity period expired', 'eventer');
                } elseif ($coupon_status == '1') {
                    $validate = '0';
                    $msg = esc_html__('Coupon is disabled', 'eventer');
                }
            } else {
                $validate = '0';
                $msg = esc_html__('Coupon doesn\'t exist', 'eventer');
            }
        } elseif ($coupon == '') {
            $validate = '0';
            $msg = esc_html__('Coupon can not be empty', 'eventer');
        } elseif ($amount <= 0) {
            $validate = '0';
            $msg = esc_html('Total amount is not valid', 'eventer');
        }
        wp_send_json(array('validate' => $validate, 'msg' => $msg, 'discount' => $discounted, 'amount' => $amount));
    }
}

if (!function_exists('eventer_handle_woo_booking_submission')) {
    function eventer_handle_woo_booking_submission(array $booking_request)
    {
        global $woocommerce;

        $tickets = $booking_request['tickets'];
        $services = $booking_request['services'];
        $registrants = $booking_request['registrants'];
        $eventer_id = $booking_request['eventer_id'];
        $eventer_date = $booking_request['eventer_date'];
        $eventer_time = $booking_request['eventer_time'];
        $eventer_time_slot = $booking_request['eventer_time_slot'];
        $cart_status = $booking_request['cart_status'];

        if ($cart_status != '') {
            foreach ($woocommerce->cart->get_cart() as $key => $item) {
                if ($cart_status == $item['product_id']) {
                    $woocommerce->cart->remove_cart_item($key);
                }
            }
        }

        $eventer_url = eventer_generate_endpoint_url('edate', $eventer_date, get_permalink($eventer_id));
        $eventer_start_time = get_post_meta($eventer_id, 'eventer_event_start_dt', true);
        $eventer_allday = get_post_meta($eventer_id, 'eventer_event_all_day', true);
        $event_start_time_str = strtotime($eventer_start_time);
        $eventer_st_time = ($eventer_time != '00:00:00' && $eventer_time != '') ? date_i18n('H:i', strtotime($eventer_time)) : date_i18n('H:i', $event_start_time_str);
        $woo_checkout_name = $woo_checkout_email = '';

        foreach ($tickets as $woo_ticket) {
            if ($woo_ticket['number'] == '') {
                continue;
            }

            $pid = $woo_ticket['pid'];
            $primary = $woo_ticket['primary'];
            $translated_id = (function_exists('icl_object_id')) ? icl_object_id($pid, 'product', false, ICL_LANGUAGE_CODE) : $pid;
            $product_id = $pid;
            $number = $woo_ticket['number'];
            $this_ticket_name = $woo_ticket['name'];
            if ($translated_id == '') {
                $product_id = icl_makes_duplicates($product_id);
            }

            $event_custom_price = $woo_ticket['price'];
            if (!has_term('eventer', 'product_cat', $product_id) || get_post_type($product_id) != 'product') {
                continue;
            }

            $this_ticket_registrants = isset($registrants[$this_ticket_name]) ? $registrants[$this_ticket_name] : array();
            if ($woo_checkout_name == '') {
                foreach ($this_ticket_registrants as $woo_checkout_data) {
                    $woo_checkout_name = $woo_checkout_data['name'];
                    $woo_checkout_email = $woo_checkout_data['email'];
                    setcookie('woo_checkout_user_name', $woo_checkout_name, (time() + 3600), '/');
                    setcookie('woo_checkout_user_email', $woo_checkout_email, (time() + 3600), '/');
                    break;
                }
            }

            $cart_item_data = array(
                'wceventer_name' => apply_filters('eventer_raw_event_title', '', $eventer_id),
                'wceventer_id' => $eventer_id,
                'wceventer_date' => strtotime($eventer_date . ' ' . $eventer_st_time),
                'wceventer_time' => date_i18n(get_option('time_format'), strtotime($eventer_st_time)),
                'wceventer_url' => $eventer_url,
                'eventer_custom_price' => $event_custom_price,
                'eventer_registrants' => $this_ticket_registrants,
                'wceventer_product' => 'ticket',
                '_eventer_custom_title' => $this_ticket_name,
                'wceventer_allday' => $eventer_allday,
                'wceventer_slot' => $eventer_time,
                'wceventer_slot_title' => $eventer_time_slot,
                'eventer_all_data' => $tickets,
                'ticket_id' => $primary,
                'registrants' => $registrants,
            );

            WC()->cart->add_to_cart($product_id, $number, '', array(), $cart_item_data);
        }

        if (!empty($services)) {
            foreach ($services as $woo_service) {
                $service_name = $woo_service['name'];
                $service_type = $woo_service['value'];
                if ($service_type == '') {
                    continue;
                }

                $service_pid = $woo_service['pid'];
                $service_cost = $woo_service['cost'];
                if (!has_term('eventer_services', 'product_cat', intval($service_pid)) || get_post_type($service_pid) != 'product') {
                    continue;
                }

                foreach ($woocommerce->cart->get_cart() as $key => $item) {
                    if ($eventer_id == $item['wceventer_id'] && $service_pid == $item['product_id']) {
                        $woocommerce->cart->remove_cart_item($key);
                    }
                }

                $cart_item_data = array(
                    'wceventer_name' => apply_filters('eventer_raw_event_title', '', $eventer_id),
                    'wceventer_id' => $eventer_id,
                    'wceventer_date' => strtotime($eventer_date . ' ' . $eventer_st_time),
                    'wceventer_time' => date_i18n(get_option('time_format'), strtotime($eventer_st_time)),
                    'wceventer_services' => $service_type,
                    'eventer_custom_price' => $service_cost,
                    'wceventer_url' => $eventer_url,
                    'wceventer_product' => 'service',
                );
                WC()->cart->add_to_cart($service_pid, 1, '', array(), $cart_item_data);
            }
        }

        ob_start();
        echo '<div class="widget_shopping_cart_content">';
        woocommerce_mini_cart();
        echo '</div>';
        $output = ob_get_clean();
        echo wp_json_encode(array('reg' => '', 'woo' => $output, 'stripe_error' => '1', 'stripe_msg' => '', 'reg_invalid' => '0'));
        wp_die();
    }
}
