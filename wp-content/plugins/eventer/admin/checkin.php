<?php
defined('ABSPATH') or die('No script kiddies please!');

if (!function_exists('eventer_handle_checkin_process_ticket_ajax')) {
    function eventer_handle_checkin_process_ticket_ajax()
    {
        eventer_verify_admin_ajax_request();
        $event_id = isset($_REQUEST['event']) ? absint($_REQUEST['event']) : 0;
        $event_date = isset($_REQUEST['date']) ? sanitize_text_field(wp_unslash($_REQUEST['date'])) : '';
        $ticket_id = isset($_REQUEST['ticket']) ? absint($_REQUEST['ticket']) : 0;
        $woocommerce_events = eventer_get_settings('eventer_enable_woocommerce_ticketing');
        $registrants = eventer_get_registrant_details('id', $ticket_id);
        if (!$registrants) {
            wp_send_json(array('msg' => esc_html__('It seems like the ticket is not matching with the selected event details.', 'eventer'), 'ticket' => ''));
        }

        $name = $registrants->username;
        $email = $registrants->email;
        $msg = '';
        $ticket_info = '';
        if ($woocommerce_events == 'on') {
            $tickets = getTicket($ticket_id);
            if (!empty($tickets)) {
                if ($tickets->ticket_status != 10) {
                    updateTicket($ticket_id, array('ticket_status' => 10), array('%d'));
                    $msg = 'Successfully checked-in';
                } else {
                    $msg = esc_html__('This ticket was already checked-in', 'eventer');
                }
                $ticket_info = '<div class="form-style-2"><div class="form-style-2-heading">' . esc_html__('Ticket Information', 'eventer') . '</div><label><span>Name </span>' . $tickets->user_name . '</label></div>';
            } else {
                $msg = esc_html__('It seems like the ticket is not matching with the selected event details.', 'eventer');
            }
        } else {
            if ($event_id == $registrants->eventer && $event_date == $registrants->eventer_date) {
                $user_system = eventer_decode_array_payload($registrants->user_system);
                if (isset($user_system['checkin']) && $user_system['checkin'] == '1') {
                    $msg = esc_html__('This ticket was already checked-in', 'eventer');
                } else {
                    $user_system['checkin'] = '1';
                    $user_system['checkin_date'] = date_i18n('Y-m-d H:i:s');
                    eventer_update_registrant_details(array('user_system' => serialize($user_system)), $ticket_id, array('%s', '%s'));
                    $msg = esc_html__('Successfully checked-in', 'eventer');
                }
                $ticket_info = '<div class="form-style-2"><div class="form-style-2-heading">' . esc_html__('Ticket Information', 'eventer') . '</div><label><span>Name </span>' . $name . '</label><label><span>Email </span>' . $email . '</label></div>';
            } else {
                $msg = esc_html__('It seems like the ticket is not matching with the selected event details.', 'eventer');
            }
        }

        wp_send_json(array('msg' => $msg, 'ticket' => $ticket_info));
    }
}
