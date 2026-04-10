<?php

class TicketAssetService
{
    public static function handle_legacy_generation_request()
    {
        $registrant_id = isset($_REQUEST['reg']) ? absint($_REQUEST['reg']) : 0;
        $nonce = isset($_REQUEST['nonce']) ? sanitize_text_field(wp_unslash($_REQUEST['nonce'])) : '';
        if (!eventer_verify_registrant_action_nonce('eventer-qrcode-nonce', $registrant_id, $nonce)) {
            wp_send_json_error(array('message' => esc_html__('Invalid ticket generation request.', 'eventer')), 403);
        }

        $event_id = isset($_REQUEST['eid']) ? absint($_REQUEST['eid']) : 0;
        $registrant = eventer_get_registrant_details('id', $registrant_id);
        if (!$registrant) {
            wp_send_json_error(array('message' => esc_html__('Registration not found.', 'eventer')), 404);
        }

        $original_event = eventer_wpml_original_post_id($event_id);
        if ($original_event <= 0 || eventer_wpml_original_post_id($registrant->eventer) !== $original_event) {
            wp_send_json_error(array('message' => esc_html__('Registration does not match the requested event.', 'eventer')), 403);
        }

        $context = array(
            'registrant' => $registrant,
            'registrant_id' => $registrant_id,
            'event_id' => $original_event,
            'qrdata' => isset($_REQUEST['qrdata']) ? eventer_sanitize_ticket_image_rows(wp_unslash($_REQUEST['qrdata'])) : array(),
            'source' => isset($_REQUEST['source']) ? sanitize_text_field(wp_unslash($_REQUEST['source'])) : '',
            'reg_pos' => isset($_REQUEST['regpos']) ? absint($_REQUEST['regpos']) : 0,
            'main_reg' => isset($_REQUEST['mainreg']) ? sanitize_email(wp_unslash($_REQUEST['mainreg'])) : '',
            'organizer_email' => isset($_REQUEST['organizer']) ? sanitize_email(wp_unslash($_REQUEST['organizer'])) : get_option('admin_email'),
            'backorder' => isset($_REQUEST['backorder']) ? esc_url_raw(wp_unslash($_REQUEST['backorder'])) : '',
            'update_user_system' => true,
            'schedule_registration_mail' => true,
            'woocommerce_order_id' => (get_post_type($registrant_id) === 'shop_order') ? $registrant_id : 0,
        );

        self::generate_ticket_assets($context);
    }

    public static function handle_registration_v2_generation_request()
    {
        $registrant_id = isset($_REQUEST['reg']) ? absint($_REQUEST['reg']) : 0;
        $nonce = isset($_REQUEST['nonce']) ? sanitize_text_field(wp_unslash($_REQUEST['nonce'])) : '';
        if (!eventer_verify_registrant_action_nonce('eventer-qrcode-nonce', $registrant_id, $nonce)) {
            wp_send_json_error(array('message' => esc_html__('Invalid ticket generation request.', 'eventer')), 403);
        }

        $booking = getRegistration($registrant_id);
        $tickets_for_booking = getRegistrationTickets($registrant_id, 500, 0);
        $event_id = isset($_REQUEST['eid']) ? absint($_REQUEST['eid']) : 0;
        if (!$booking || empty($tickets_for_booking)) {
            wp_send_json_error(array('message' => esc_html__('Registration not found.', 'eventer')), 404);
        }

        $has_matching_event = false;
        foreach ($tickets_for_booking as $ticket_item) {
            if (isset($ticket_item->event_id) && absint($ticket_item->event_id) === $event_id) {
                $has_matching_event = true;
                break;
            }
        }

        if ($event_id <= 0 || !$has_matching_event) {
            wp_send_json_error(array('message' => esc_html__('Registration does not match the requested event.', 'eventer')), 403);
        }

        $context = array(
            'registrant' => $booking,
            'registrant_id' => $registrant_id,
            'event_id' => $event_id,
            'qrdata' => isset($_REQUEST['qrdata']) ? eventer_sanitize_ticket_image_rows(wp_unslash($_REQUEST['qrdata'])) : array(),
            'source' => isset($_REQUEST['source']) ? sanitize_text_field(wp_unslash($_REQUEST['source'])) : '',
            'reg_pos' => isset($_REQUEST['regpos']) ? absint($_REQUEST['regpos']) : 0,
            'main_reg' => isset($_REQUEST['mainreg']) ? sanitize_email(wp_unslash($_REQUEST['mainreg'])) : '',
            'organizer_email' => get_option('admin_email'),
            'backorder' => isset($_REQUEST['backorder']) ? esc_url_raw(wp_unslash($_REQUEST['backorder'])) : '',
            'update_user_system' => false,
            'schedule_registration_mail' => false,
            'woocommerce_order_id' => absint($booking->order_id),
            'registration_v2' => true,
        );

        self::generate_ticket_assets($context);
    }

    public static function generate_ticket_assets(array $context)
    {
        $registrant = $context['registrant'];
        $registrant_id = absint($context['registrant_id']);
        $event_id = absint($context['event_id']);
        $qrdata = isset($context['qrdata']) ? $context['qrdata'] : array();
        if (empty($qrdata)) {
            wp_send_json_error(array('message' => esc_html__('No ticket payload was provided.', 'eventer')), 400);
        }

        $event_title = apply_filters('eventer_raw_event_title', '', $event_id);
        $event_post = get_post($event_id);
        if (!$event_post) {
            wp_send_json_error(array('message' => esc_html__('Event not found.', 'eventer')), 404);
        }

        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once ABSPATH . '/wp-admin/includes/file.php';
            WP_Filesystem();
        }

        $upload = wp_upload_dir();
        $upload_blog_url = $upload['baseurl'];
        $upload_dir = $upload['basedir'] . '/eventer';
        if (!$wp_filesystem->is_dir($upload_dir)) {
            wp_mkdir_p($upload_dir);
        }

        $attachment_content_switch = true;
        if (metadata_exists('post', $event_id, 'eventer_event_ticket_email')) {
            $ticket_email = get_post_meta($event_id, 'eventer_event_ticket_email', true);
            if ($ticket_email == 'on') {
                $attachment_content_switch = false;
            }
        }

        $tickets_created = array();
        $ticket_send = array();
        $send_tickets = array();
        $file_names = array();
        $event_slug = $event_post->post_name;
        $eventer_venue = get_the_terms($event_id, 'eventer-venue');

        foreach ($qrdata as $data) {
            $random_name = date_i18n('Y-m-d-H-i-s');
            $image_validate = eventer_check_base64_image($data['src']);
            $ticket_name_clean = eventer_clean_string($data['ticket']);
            $qrcode_name = eventer_clean_string(!empty($data['email']) ? $data['email'] : $data['name']);
            if (!$image_validate || $image_validate < 110) {
                continue;
            }

            $filename_first = $registrant_id . '-' . $event_slug . '-' . $qrcode_name . '-' . $ticket_name_clean . '-' . $event_id . '-' . $random_name . '.png';
            $filename_pdf = $registrant_id . '-' . $event_slug . '-' . $qrcode_name . '-' . $ticket_name_clean . '-' . $event_id . '-' . $random_name . '.pdf';
            $filename = $upload_dir . '/' . $filename_first;
            $filename_pdf_full = $upload_dir . '/' . $filename_pdf;
            $data['filename'] = $filename_pdf_full;
            $data['attendee_name'] = isset($data['name']) ? $data['name'] : '';

            if (!is_wp_error($eventer_venue) && !empty($eventer_venue)) {
                foreach ($eventer_venue as $venue) {
                    $location_address = get_term_meta($venue->term_id, 'venue_address', true);
                    $data['location'] = ($location_address != '') ? $location_address : $venue->name;
                    break;
                }
            }

            generatePdfTicket($data, $event_id);
            $email = (!empty($data['email'])) ? $data['email'] : wp_rand(10, 1000000000000000000);
            $tickets_created[$email][] = $filename_first;
            $ticket_send[$email][] = $filename_first;
            $file_names[] = $filename_first;
            $send_tickets[] = $filename_first;

            if (!empty($context['registration_v2'])) {
                updateTicketMeta($data['code'], 'ticket_url', get_bloginfo('wpurl') . '/wp-content/uploads/eventer/' . $filename_first);
                updateTicketMeta($data['code'], 'ticket_path', $filename_first);
            }
        }

        if (empty($file_names)) {
            wp_send_json_error(array('message' => esc_html__('Unable to generate tickets.', 'eventer')), 500);
        }

        self::schedule_ticket_cleanup($upload_dir, $file_names);

        if (!empty($context['schedule_registration_mail']) && empty($context['source'])) {
            self::schedule_registration_ticket_emails(
                $ticket_send,
                $context['main_reg'],
                $registrant_id,
                $registrant,
                $event_id,
                $file_names,
                $context['organizer_email'],
                $context['reg_pos']
            );
        }

        if (!empty($context['registration_v2']) && empty($context['source']) && $attachment_content_switch) {
            foreach ($ticket_send as $email => $tickets) {
                $args = array($email, $tickets);
                if (!wp_next_scheduled('sendTicketsEmail', $args)) {
                    wp_schedule_single_event(time() + 1, 'sendTicketsEmail', $args);
                }
            }
        }

        if (!empty($context['update_user_system'])) {
            self::store_created_tickets_on_registrant($registrant, $tickets_created);
        }

        $event_url = self::get_generation_event_url($context, $registrant, $event_id, $send_tickets);
        wp_send_json(
            array(
                'tickets' => implode(',', $file_names) . ',',
                'event_url' => $event_url,
                'ticket_arr' => $send_tickets,
                'url' => $upload_blog_url . '/eventer',
                'allow' => wp_create_nonce('eventer-tickets-download'),
            )
        );
    }

    public static function stream_ticket_archive(array $tickets)
    {
        $archive_file_name = 'eventer_tickets.zip';
        $upload = wp_upload_dir();
        $file_path = trailingslashit($upload['basedir']) . 'eventer/';
        $zip = new ZipArchive();
        if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE) !== true) {
            wp_die("cannot open <$archive_file_name>\n");
        }

        foreach ($tickets as $file) {
            $file = basename(sanitize_file_name($file));
            if ($file === '' || !file_exists($file_path . $file)) {
                continue;
            }
            $zip->addFile($file_path . $file, $file);
        }
        $zip->close();

        header('Content-type: application/zip');
        header("Content-Disposition: attachment; filename=$archive_file_name");
        header('Pragma: no-cache');
        header('Expires: 0');
        if (ob_get_length()) {
            ob_end_clean();
        }
        readfile($archive_file_name);
        if (file_exists($archive_file_name)) {
            unlink($archive_file_name);
        }
        wp_die();
    }

    public static function build_thanks_modal_view(array $params)
    {
        $registrant = isset($params['registrant']) ? $params['registrant'] : 0;
        $woocommerce_ticketing = isset($params['woo_ticketing']) ? $params['woo_ticketing'] : '';
        $registrant_email = isset($params['registrant_email']) ? $params['registrant_email'] : '';
        $booked_registrant_tickets = isset($params['booked_registrant_tickets']) ? $params['booked_registrant_tickets'] : array();
        $reg_position = isset($params['reg_position']) ? absint($params['reg_position']) : 0;
        $mode = isset($params['mode']) ? $params['mode'] : '';
        $usersystem = isset($params['usersystem']) ? eventer_decode_array_payload($params['usersystem']) : array();
        $event_time_show = isset($params['event_time_show']) ? $params['event_time_show'] : '';
        $event_cdate = isset($params['event_cdate']) ? $params['event_cdate'] : 0;
        $allday = isset($params['allday']) ? $params['allday'] : '';
        $username = isset($params['username']) ? $params['username'] : '';
        $order_id = isset($params['order_id']) ? $params['order_id'] : $registrant;

        if (get_post_type($registrant) == 'shop_order' && $woocommerce_ticketing == 'on') {
            $order = wc_get_order($order_id);
            $registrant_uname = get_post_meta($registrant, '_shipping_first_name', true) . ' ' . get_post_meta($registrant, '_shipping_last_name', true);
            $registrant_uname = ($registrant_uname != '') ? $registrant_uname : get_post_meta($registrant, '_billing_first_name', true) . ' ' . get_post_meta($order_id, '_billing_last_name', true);
            $registrant_email = get_post_meta($registrant, '_billing_email', true);
            $booked_registrant_tickets = array();
            $order_status = $order ? $order->get_status() : '';
            if ($order) {
                foreach ($order->get_items() as $item_values) {
                    $item_data = $item_values->get_data();
                    $item_id = $item_values->get_id();
                    $event_id = wc_get_order_item_meta($item_id, '_wceventer_id', true);
                    if ($event_id != get_the_ID()) {
                        continue;
                    }
                    $booked_registrant_tickets[] = array('name' => $item_data['name'], 'number' => $item_data['quantity']);
                }
            }
            $order_num = 'we' . $registrant;
            $mode = ($order_status == 'completed') ? 'Free' : '';
        } else {
            $registrant_uname = $username ? $username : '';
            $order_num = sprintf('%06d', $registrant);
            $registrants = eventer_get_registrant_details('id', $registrant);
            if ($registrants && $registrants->eventer_date) {
                $event_cdate = strtotime($registrants->eventer_date);
            }
        }

        $time_slot_set = isset($usersystem['time_slot']) ? $usersystem['time_slot'] : '00:00:00';
        $event_time_show = ($time_slot_set == '00:00:00') ? $event_time_show : date_i18n(get_option('time_format'), strtotime($time_slot_set));
        if ($time_slot_set != '00:00:00' && $allday == 'on') {
            $event_time_show = esc_html__('All Day', 'eventer');
        }

        $eventer_venue = get_the_terms(get_the_ID(), 'eventer-venue');
        $venue = '';
        if (!is_wp_error($eventer_venue) && !empty($eventer_venue)) {
            foreach ($eventer_venue as $venue_term) {
                $location_address = get_term_meta($venue_term->term_id, 'venue_address', true);
                $venue = ($location_address != '') ? $location_address : $venue_term->name;
            }
        }

        $organizer_data = array();
        $eventer_organizer = get_the_terms(get_the_ID(), 'eventer-organizer');
        if (!is_wp_error($eventer_organizer) && !empty($eventer_organizer)) {
            $organizer = $eventer_organizer[0];
            $organizer_data = array(
                'name' => $organizer->name,
                'email' => get_term_meta($organizer->term_id, 'organizer_email', true),
                'phone' => get_term_meta($organizer->term_id, 'organizer_phone', true),
                'website' => get_term_meta($organizer->term_id, 'organizer_website', true),
                'events_url' => get_term_link($organizer->term_id, 'eventer-organizer'),
            );
        }

        return array(
            'event_id' => get_the_ID(),
            'event_title' => apply_filters('eventer_raw_event_title', '', get_the_ID()),
            'venue' => $venue,
            'date' => $event_cdate ? date_i18n(get_option('date_format'), $event_cdate) : '',
            'time' => $event_time_show ? $event_time_show : '',
            'order_number' => $order_num,
            'organizer' => $organizer_data,
            'booked_tickets' => $booked_registrant_tickets,
            'registrant_email' => $registrant_email,
            'show_ticket_delivery' => (($mode == 'Free' || $reg_position >= 15) && !empty($booked_registrant_tickets)),
            'pending_message' => esc_html__('An email with link to download ticket will be sent to you once we acknowledge successful payment.', 'eventer'),
        );
    }

    public static function build_ticket_modal_view(array $params)
    {
        $registrant = isset($params['registrant']) ? $params['registrant'] : 0;
        $woocommerce_ticketing = isset($params['woo_ticketing']) ? $params['woo_ticketing'] : '';
        $order_num = $registrant;
        $event_time_show = isset($params['event_time_show']) ? $params['event_time_show'] : '';
        $event_cdate = isset($params['event_cdate']) ? $params['event_cdate'] : 0;
        $ticket_id_db = isset($params['ticket_id_db']) ? $params['ticket_id_db'] : '';
        $booked_registrant_tickets = isset($params['booked_registrant_tickets']) ? $params['booked_registrant_tickets'] : array();
        $user_details = isset($params['user_details']) ? $params['user_details'] : array();
        $usersystem = isset($params['usersystem']) ? eventer_decode_array_payload($params['usersystem']) : array();

        if (get_post_type($registrant) == 'shop_order' && $woocommerce_ticketing == 'on') {
            $order = wc_get_order($registrant);
            $registrant_uname = $order ? $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() : '';
        } else {
            $registrant_uname = isset($params['username']) ? $params['username'] : '';
            $registrant_firstname = isset($user_details['Name']) ? $user_details['Name'] : '';
            $registrant_lastname = isset($user_details['LAST NAME']) ? $user_details['LAST NAME'] : '';
            $registrant_uname = trim($registrant_firstname . ' ' . $registrant_lastname) ?: $registrant_uname;
            if (isset($usersystem['slot_title']) && isset($usersystem['time_slot']) && $usersystem['time_slot'] != '00:00:00') {
                $slot_time = date_i18n(get_option('time_format'), strtotime($usersystem['time_slot']));
                $event_time_show = $slot_time . ' ' . $usersystem['slot_title'];
            }

            $registrants = eventer_get_registrant_details('id', $registrant);
            if ($registrants && $registrants->eventer_date) {
                $event_cdate = strtotime($registrants->eventer_date);
            }
        }

        $venue = '';
        $eventer_venue = get_the_terms(get_the_ID(), 'eventer-venue');
        if (!is_wp_error($eventer_venue) && !empty($eventer_venue)) {
            foreach ($eventer_venue as $venue_term) {
                $location_address = get_term_meta($venue_term->term_id, 'venue_address', true);
                $venue = ($location_address != '') ? $location_address : $venue_term->name;
            }
        }

        $company_name = eventer_get_settings('ticket_image_company_name');
        $company_address = eventer_get_settings('ticket_image_company_address');
        $company_logo = eventer_get_settings('ticket_image_company_logo');
        $event_notes = get_post_meta(get_the_ID(), 'eventer_event_ticket_image_notes', true);
        if (empty($event_notes)) {
            $event_notes = eventer_get_settings('event_ticket_image_notes');
        }

        return array(
            'ticket_code' => $ticket_id_db,
            'attendee' => $registrant_uname,
            'event_title' => apply_filters('eventer_raw_event_title', '', get_the_ID()),
            'tickets' => $booked_registrant_tickets,
            'venue' => $venue,
            'date' => $event_cdate ? date_i18n(get_option('date_format'), $event_cdate) : '',
            'time' => $event_time_show,
            'event_notes' => $event_notes,
            'company_name' => $company_name,
            'company_address' => $company_address,
            'company_logo_html' => $company_logo ? wp_get_attachment_image($company_logo, 'full') : '',
            'order_number' => $order_num,
        );
    }

    private static function schedule_ticket_cleanup($upload_dir, array $file_names)
    {
        $start = 18600;
        foreach ($file_names as $ticket) {
            $args = array($upload_dir . '/' . $ticket);
            if (!wp_next_scheduled('eventer_initiate_cron_remove_directory', $args)) {
                wp_schedule_single_event(time() + ($start + 5), 'eventer_initiate_cron_remove_directory', $args);
            }
        }
    }

    private static function schedule_registration_ticket_emails(array $ticket_send, $main_reg, $registrant_id, $registrant, $event_id, array $sub_tickets, $organizer_email, $reg_pos)
    {
        $start_time = 30;
        foreach ($ticket_send as $email => $tickets) {
            if ($reg_pos > 14 && $email && $email !== $main_reg) {
                $args = array($email, $registrant_id, $registrant, $event_id, $tickets, $organizer_email);
                if (!wp_next_scheduled('generate_ticket_for_registrants', $args)) {
                    wp_schedule_single_event(time() + $start_time, 'generate_ticket_for_registrants', $args);
                }
                $start_time += 5;
            }
        }

        if ($reg_pos > 14 && $main_reg !== '') {
            $args = array($main_reg, $registrant_id, $registrant, $event_id, $sub_tickets, $organizer_email);
            if (!wp_next_scheduled('generate_ticket_for_registrants', $args)) {
                wp_schedule_single_event(time(), 'generate_ticket_for_registrants', $args);
            }
        }
    }

    private static function store_created_tickets_on_registrant($registrant, array $tickets_created)
    {
        if (!$registrant) {
            return;
        }

        $user_system = eventer_decode_array_payload($registrant->user_system);
        if ($user_system) {
            $user_system['tickets_created'] = $tickets_created;
            eventer_update_registrant_details(array('user_system' => serialize($user_system)), $registrant->id, array('%s', '%s'));
        }
    }

    private static function get_generation_event_url(array $context, $registrant, $event_id, array $send_tickets)
    {
        $backorder = isset($context['backorder']) ? $context['backorder'] : '';
        if (!empty($context['registration_v2'])) {
            $order = !empty($context['woocommerce_order_id']) ? wc_get_order($context['woocommerce_order_id']) : false;
            $backorder = $backorder ? add_query_arg('allow', $context['registrant_id'], $backorder) : '';
            $event_url = '';
            if ($order) {
                $event_url = getTicketMeta(isset($context['qrdata'][0]['code']) ? $context['qrdata'][0]['code'] : 0, 'event_url');
            }
            return $backorder ? $backorder : $event_url;
        }

        if (!empty($context['woocommerce_order_id']) && eventer_get_settings('eventer_enable_woocommerce_ticketing') == 'on' && 1 == 2) {
            return '';
        }

        return ($backorder != '') ? add_query_arg('allow', $registrant->id, $backorder) : '';
    }
}
