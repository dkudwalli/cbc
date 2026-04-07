<?php
$selected_date = date_i18n('Y-m-d', $params['event_cdate']);
$difference = '';
$mandatory_timeslot = get_post_meta($event_id, 'eventer_time_slots_mandatory', true);
if ($registration_switch == "1")
{
    $eventer_formatted_date = date_i18n(get_option('date_format') , $event_cdate);
    $eventer_formatted_date_fm = date_i18n("Y-m-d", $event_cdate);
    $eventer_start_time = get_post_meta($event_id, 'eventer_event_start_dt', true);
    $event_start_time_str = strtotime($eventer_start_time);
    $eventer_st_time = date_i18n("H:i", $event_start_time_str);
    $eventer_formatted_proper_time = date_i18n('Y-m-d ' . $eventer_st_time, $event_cdate);
    $original_event = eventer_wpml_original_post_id($event_id);
    $tickets = get_post_meta($original_event, 'eventer_tickets', true);
    $tickets_translated = get_post_meta(get_the_ID() , 'eventer_tickets', true);
    $aviable_tickets = get_post_meta($event_id, 'eventer_event_available_tickets', true);
    $remaining_for_reg = '';
    $booking_url = '';
    $booking_url_target = get_post_meta($event_id, 'eventer_event_registration_target', true);
    $registration_url = get_post_meta($event_id, 'eventer_event_custom_registration_url', true);
    //if (filter_var($registration_url, FILTER_VALIDATE_URL)) {
    $booking_url = $registration_url;
    //}
    $booking_calendar = eventer_get_settings('eventer_booking_calendar');
    $booking_calendar_type = eventer_get_settings('eventer_booking_calendar_type');
?>
	
	<?php
    ob_start();
    // Parse the URL to find the date after /edate/ or as a query parameter
    $current_url = $_SERVER['REQUEST_URI'];
    $date_after_edate = null;

    if (preg_match('/\/edate\/([\d-]+)/', $current_url, $matches)) {
        $date_after_edate = $matches[1];
    } elseif (isset($_GET['edate'])) {
        $date_after_edate = $_GET['edate'];
    }

    if ($booking_calendar_type == 'on' && count($all_dates) > 1 && $days_diff <= 0 && get_post_type($event_id) == 'eventer' && $booking_calendar == 'on'):
?>
        <select id="eventer-future-bookings" name="eventer-future-bookings" data-eventer="<?php echo esc_attr($event_id); ?>">
            <option value="" selected><?php esc_html_e('Select', 'eventer'); ?></option>
            <?php
            $all_dates = array_filter($all_dates, function ($date) {
                $start = date_i18n('Y-m-d G:i', strtotime(date_i18n("Y-m-d", time()) . " - 730 day"));
                $end = date_i18n('Y-m-d G:i', strtotime(date_i18n("Y-m-d", time()) . " + 730 day"));
                return (strtotime($date) >= strtotime($start) && strtotime($date) <= strtotime($end));
            });

            usort($all_dates, 'eventer_compare_dates');
            foreach ($all_dates as $date) {
                $date = date_i18n('Y-m-d', strtotime($date));
                $date_show = date_i18n(get_option('date_format'), strtotime($date));
                $selected = '';

                if ($date_after_edate && $date_after_edate == $date) {
                    $selected = 'selected="selected"';
                } elseif ($selected_date == $date && $params['ajax'] == 1) {
                    $selected = 'selected="selected"';
                }

                if (strtotime($date) >= strtotime(date('Y-m-d'))) {
                    echo '<option value="' . esc_attr($date) . '" ' . $selected . '>' . esc_attr($date_show) . '</option>';
                }
            }
            ?>
        </select>
    <?php
    endif;
    $select_date_html = ob_get_clean();
?>

  <div class="eventer eventer-event-single eventer-ticket-details-wrap">

	<?php
    $ticket_remaining_modal = $remaining_for_reg = '';
    $show_tickets_info = (!empty($booked_tickets)) ? $booked_tickets : $tickets;
    if ( ! empty( $show_tickets_info ) ) {
    	echo '<div class="eventer-ticket-details" data-date="' . esc_attr(date_i18n('Y-m-d', $event_cdate)) . '" data-time="' . esc_attr($time_slot) . '" data-slottitle="' . $time_slot_title . '">
			<h3>' . esc_html__('Tickets details', 'eventer') . '</h3>';
        if ( $booking_calendar_type == 'on' ) {
            if (count($all_dates) > 1 && $days_diff <= 0 && get_post_type($event_id) == 'eventer' && $booking_calendar == 'on') {
                echo '<label>' . esc_html__('Select Booking Date', 'eventer') . '</label>';
                echo $select_date_html;
            }
        } else {
            echo (count($all_dates) > 1 && $days_diff <= 0 && get_post_type($event_id) == 'eventer' && $booking_calendar == 'on') ? '<label>' . esc_html__('Select Booking Date', 'eventer') . '</label><input type="input" id="eventer-future-bookings" data-time="asdasdf" class="datepicker" style="display:none;"/>' : '';
        }

        if ( ! empty( $time_slot_values ) ) {
            echo '<label>' . esc_html__('Select Booking Slot', 'eventer') . '</label>' . $time_slot_values;
        }

        echo '<ul class="eventer-tickets-info"><form class="eventer-loader-form" style="display:none;"><div class="eventer-loader-wrap"><div class="eventer-loader"></div></div></form>';
	        $counting = 0;
	        foreach ( $show_tickets_info as $ticket ) {
	            $ticket_name = (isset($ticket['name'])) ? $ticket['name'] : '';
	            $ticket_locale_name = (isset($ticket['cust_val1'])) ? json_decode($ticket['cust_val1'], true) : [];
	            $ticket_name = ($ticket_locale_name && isset($ticket_locale_name[EVENTER__LANGUAGE_CODE]) && $ticket_locale_name[EVENTER__LANGUAGE_CODE] != '') ? $ticket_locale_name[EVENTER__LANGUAGE_CODE] : $ticket_name;
	            $ticket_pid = (isset($ticket['pid'])) ? $ticket['pid'] : '';
	            $ticket_existing = (get_post_type($ticket_pid) == 'product' && get_post_status($ticket_pid) == 'publish') ? '' : esc_html__('Ticket missing', 'eventer');
	            $ticket_existing = ($woo_ticketing == 'on') ? $ticket_existing : '';
	            if (isset($tickets_translated[$counting]) && isset($tickets_translated[$counting]['pid']) && $tickets_translated[$counting]['pid'] == $ticket_pid && $ticket_name != $tickets_translated[$counting]['name']) {
	                $ticket_name = $tickets_translated[$counting]['name'];
	            }

	            if ($ticket_name == '') continue;
	            $ticket_number = (isset($ticket['tickets'])) ? $ticket['tickets'] : '';
	            $ticket_price = (isset($ticket['price'])) ? number_format($ticket['price'], 2) : '';
	            $ticket_price_without_format = (isset($ticket['price'])) ? $ticket['price'] : '';
	            $ticket_restrict = (isset($ticket['restricts'])) ? $ticket['restricts'] : '';
	            $ticket_enabled = (isset($ticket['enabled'])) ? $ticket['enabled'] : '';
	            $ticket_currency = $eventer_currency;
	            if (is_numeric($ticket_price_without_format) && $ticket_price != '') {
	                $ticket_price = ($currency_position != 'postfix') ? $ticket_currency . $ticket_price : $ticket_price . $ticket_currency;
	                $discounted_price = '';
	            } elseif (strpos($ticket_price, "-") !== false && $ticket_price != '') {
	                $new_ticket_price = explode('-', $ticket_price);
	                $calculate_discounted_price = $new_ticket_price[0] - $new_ticket_price[1];
	                $discounted_price = $ticket_currency . $calculate_discounted_price;
	                $show_price = ($currency_position != 'postfix') ? $ticket_currency . $new_ticket_price[0] : $new_ticket_price[0] . $ticket_currency;
	                $ticket_price = '<del class="eventer-price-currency">' . $show_price . '</del>';
	            } else {
	                $ticket_price = $ticket_price;
	                $discounted_price = '';
	                $ticket_currency = '';
	            }

	            $remaining_tickets = ($ticket_number <= 0) ? '<i class="eventer-ticket-remaining eventer-ticket-full">' . esc_html__('All Booked', 'eventer') . '</i>' : '<i class="eventer-ticket-remaining">' . $ticket_number . ' ' . esc_html__('remaining', 'eventer') . '</i>';
	            $ticket_enabled_date = (strtotime($ticket_enabled) <= date_i18n('U')) ? '' : '<i class="eventer-ticket-remaining eventer-ticket-full">' . esc_html__('Ticket sale opens on', 'eventer') . ' ' . date_i18n(get_option('date_format') , strtotime($ticket_enabled)) . '</i>';
	            $difference = 1000;
	            $booking_closes = get_post_meta($event_id, 'eventer_disable_booking_before', true);
	            if ( $booking_closes != '' ) {
	                $close_date = date('Y-m-d', strtotime($eventer_formatted_date_fm . ' - ' . $booking_closes . ' days'));
	                $difference = eventer_dateDiff(date_i18n('Y-m-d') , $close_date);
	                if ($difference <= 4 && $difference > 0) {
	                    $ticket_enabled_date = '<i class="eventer-ticket-remaining eventer-ticket-full">' . $difference . esc_html__(' days left for booking', 'eventer') . '</i>';
	                } elseif ($difference <= 0) {
	                    $ticket_enabled_date = '<i class="eventer-ticket-remaining eventer-ticket-full">' . esc_html__('Booking closed', 'eventer') . '</i>';
	                }
	            }

	            if ($ticket_number > 0) {
	                $remaining_for_reg = 1;
	            }

	            if ( $aviable_tickets === 'yes' ) {
	            	$remaining_tickets = '';
	            }

	            if ($ticket_existing == '') {
	                echo '<li data-restrict="' . esc_attr($ticket_restrict) . '">
						<span class="eventer-ticket-type-price">' . $ticket_price . ' ' . $discounted_price . '</span>
						<span class="eventer-ticket-type-name">' . $ticket_name . ' ' . $remaining_tickets . $ticket_enabled_date . '</span>
					</li>';
	            } else {
	                echo '<li title="' . esc_html__('It seems like the tickets you have added for this event no more exists.', 'eventer') . '">
					<span class="eventer-ticket-type-name">' . $ticket_existing . '</span>
					</li>';
	            }

	            $counting++;
	        }

        echo '</ul></div>';
	}

    $formated_dates = [];
    foreach ($all_dates as $date) {
        $formated_dates[] = date_i18n(get_option('date_format') , strtotime($date));
    }

    if ( $remaining_for_reg == 1 && date_i18n('U') < strtotime($eventer_formatted_proper_time) && in_array($eventer_formatted_date, $formated_dates) && $booking_url == '' && get_post_type($event_id) == 'eventer' && $difference > 0) { ?>
		<a class="eventer-btn eventer-btn-primary et_smooth_scroll_disabled" rel="emodal:open" href="#eventer-ticket-form-<?php echo $dynamic_val; ?>"><?php esc_html_e('Book tickets', 'eventer'); ?></a>
	<?php
	} elseif (date_i18n('U') > strtotime($eventer_formatted_proper_time) && in_array($eventer_formatted_date, $formated_dates) && $booking_url == '' && get_post_type(get_the_ID()) == 'eventer') { ?>
		<a href="javascript:void(0)" class="eventer-btn eventer-btn-primary"><?php esc_html_e('Sorry, Event Passed', 'eventer'); ?></a>
	<?php
    } elseif ( empty($show_tickets_info) && $booking_url == '' && get_post_type($event_id) == 'eventer' && $difference > 0) { ?>
		<a class="eventer-btn eventer-btn-primary et_smooth_scroll_disabled" rel="emodal:open" href="#eventer-ticket-form-<?php echo $dynamic_val; ?>"><?php esc_html_e('Book tickets', 'eventer'); ?></a>
	<?php
    }

    if ( $booking_url != '' ) { ?>
		<a href="<?php echo esc_url($booking_url); ?>" target="<?php echo esc_attr($booking_url_target); ?>" class="eventer-btn eventer-btn-primary"><?php esc_html_e('Register', 'eventer'); ?></a>
	<?php
	}

	eventer_append_template_with_arguments('eventers/sections/event', 'organizer', $params);
?>
</div>
<?php
}

if (get_metadata('post', $event_id, 'eventer_time_slots_mandatory', true)):
    $mandatory_timeslot = get_post_meta($event_id, 'eventer_time_slots_mandatory', true);

    if ($mandatory_timeslot == 'on'):
?>
	<script type="text/javascript">
		jQuery(document).ready(function( $ ){
			function checkTimeSlot(event) {
				var $time = $("select.eventer-time-slot");
				if ( ! $time.length ) {
					return false;
				}

				var url = window.location.href;

				if ($time.val() === "00:00:00" && url.indexOf('checkout_show') === -1) {
					var url = $(event.currentTarget).attr("href");
					event.currentTarget.href = "#";
					event.stopPropagation();
					event.preventDefault();
					$time.addClass("eventer-required-field");
				}
			}

			$('.eventer.eventer-event-single.eventer-ticket-details-wrap a.eventer-btn.eventer-btn-primary').on('click', function( e ) {
				checkTimeSlot(e);
			});
		});
	</script>
<?php
    else: ?>

	<?php
    endif;
else: ?>
	<script type="text/javascript">
		jQuery(document).ready(function( $ ){
			function checkTimeSlot(event) {
				var $time = $("select.eventer-time-slot");
				if ( ! $time.length ) {
					return false;
				}

				var url = window.location.href;

				if ($time.val() === "00:00:00" && single.mandatory_timeslot === "on" && url.indexOf('checkout_show') === -1) {
					var url = $(event.currentTarget).attr("href");
					event.currentTarget.href = "#";
					event.stopPropagation();
					event.preventDefault();
					$time.addClass("eventer-required-field");
				}
			}

			$('.eventer.eventer-event-single.eventer-ticket-details-wrap a.eventer-btn.eventer-btn-primary').on('click', function( e ) {
				checkTimeSlot(e);
			});
		});
	</script>
<?php
endif; ?>
