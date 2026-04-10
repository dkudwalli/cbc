<?php
defined('ABSPATH') or die('No script kiddies please!');

if (!function_exists('eventer_handle_generate_ticket_qrcode_ajax')) {
    function eventer_handle_generate_ticket_qrcode_ajax()
    {
        TicketAssetService::handle_legacy_generation_request();
    }
}

if (!function_exists('eventer_handle_dynamic_ticket_area_ajax')) {
    function eventer_handle_dynamic_ticket_area_ajax()
    {
        eventer_verify_public_ajax_request('eventer_dynamic_ticket_area');

        $event = isset($_REQUEST['event']) ? absint($_REQUEST['event']) : 0;
        $raw_date = isset($_REQUEST['date']) ? sanitize_text_field(wp_unslash($_REQUEST['date'])) : '';
        $raw_time = isset($_REQUEST['time']) ? sanitize_text_field(wp_unslash($_REQUEST['time'])) : '00:00:00';
        $date_timestamp = strtotime($raw_date);
        if (!$event || !$date_timestamp) {
            wp_send_json(array());
        }

        $date = date_i18n('Y-m-d', $date_timestamp);
        $time = strtotime($raw_time) ? date_i18n('H:i:s', strtotime($raw_time)) : '00:00:00';
        wp_send_json(
            array(
                'tickets_modal' => do_shortcode('[eventer_ajax_tickets id="' . $event . '" date="' . $date . '" time="' . $time . '" ajax="1"]'),
                'tickets' => do_shortcode('[eventer_ajax_tickets_meta id="' . $event . '" date="' . $date . '" time="' . $time . '" ajax="1"]'),
                'metas' => do_shortcode('[eventer_metas id="' . $event . '" date="' . $date . '" time="' . $time . '"]'),
                'date_show' => date_i18n(get_option('date_format'), $date_timestamp),
                'date' => $date,
                'time' => $time,
                'formatted' => date_i18n(get_option('date_format'), $date_timestamp),
                'event_url' => eventer_generate_endpoint_url('edate', $date, get_permalink($event)),
            )
        );
    }
}

if (!function_exists('eventer_handle_woo_download_tickets_ajax')) {
    function eventer_handle_woo_download_tickets_ajax()
    {
        $nonce = isset($_REQUEST['captcha']) ? sanitize_text_field(wp_unslash($_REQUEST['captcha'])) : '';
        if (!wp_verify_nonce($nonce, 'eventer-tickets-download')) {
            wp_die('Security check failed');
        }

        $tickets = isset($_REQUEST['tickets']) ? explode(',', sanitize_text_field(wp_unslash($_REQUEST['tickets']))) : array();
        TicketAssetService::stream_ticket_archive($tickets);
    }
}
