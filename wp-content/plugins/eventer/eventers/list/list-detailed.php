<li class="eventer-event-item eventer-detailed-row eventer-cfloat eventer-featured-<?php echo esc_attr($params['featured_class']); ?> new_eventer_status_<?php echo esc_attr($params['new_status']); ?>">
	<div class="eventer-dater" style="background-color: <?php echo esc_attr($params['color']); ?>">
		<?php
		if ($params['allday'] != '') {
			$date_day = date_i18n('l', strtotime($params['date']));
			$show_time = $params['allday'];
		} else {
			$date_day = date_i18n('l', strtotime($params['date']));
			$show_time = date_i18n(get_option('time_format'), strtotime($params['start']));
		}
		?>
		<?php
        if ($params['multi'] == '1') {
            ?>
                <span class="eventer-event-day "><?php echo date_i18n('D', strtotime($params['start'])); ?> - <?php echo date_i18n('D', strtotime($params['end'])); ?></span>
                <span class="eventer-event-multiday  eventer-event-multiday-border"><?php echo date_i18n('d M', strtotime($params['start'])); ?></span>
                <span class="eventer-event-multiday "><?php echo date_i18n('d M', strtotime($params['end'])); ?></span>
        <?php } else { ?>
                <span class="eventer-event-day "><?php echo esc_attr($date_day); ?></span>
                <span class="eventer-event-date"><strong><?php echo date_i18n('d', strtotime($params['date'])); ?></strong><span><?php echo esc_attr(date_i18n('M', strtotime($params['date']))); ?></span></span>
        <?php } ?>
		<span class="eventer-event-time"><?php echo esc_attr($show_time); ?></span>
	</div>
	<div class="eventer-detailed-col eventer-detailed-title">
		<div class="eventer-event-title">
			<?php echo eventer_display_status_badge( $params['eventer'] ); ?>
			<?php echo eventer_check_event_is_virtual( $params['eventer'] ); ?>
			<?php echo $params['featured_span']; ?>
			<?php echo $params['status']; ?>
			<a href="<?php echo $params['details']; ?>" target="<?php echo esc_attr($params['target']); ?>"><?php echo $params['event']; ?></a>
		</div>
	</div>
	<?php if (!empty($params['address']) && $params['address'] != '') { ?>
		<div class="eventer-detailed-col">
			<label><?php esc_html_e('Venue', 'eventer'); ?></label>
			<?php echo eventer_get_event_venue( $params['address'], $params['eventer'] ); ?>
		</div>
	<?php }
	if (isset($params['organizer']) && $params['organizer'] != '') { ?>
		<div class="eventer-detailed-col">
			<label><?php esc_html_e('Organiser', 'eventer'); ?></label>
			<?php echo $params['organizer']; ?>
		</div>
	<?php } ?>
	<?php
	if (!empty($params['tickets'])) {
		?>
		<div class="eventer-detailed-col eventer-col-actions">
			<div class="eventer-fe-dd eventer-fe-dd-right eventer-quick-ticket-info">
				<a href="#"><i class="eventer-icon-info"></i> <?php esc_html_e('Ticket Info', 'eventer'); ?></a>
				<div class="eventer-fe-dropdown eventer-cfloat">
					<div class="eventer-fe-dropdown-in eventer-cfloat">
						<ul class="eventer-tickets-info eventer-cfloat">
							<?php
								$woocommerce_ticketing = eventer_get_settings('eventer_enable_woocommerce_ticketing');
								$eventer_currency = ($woocommerce_ticketing != 'on' || !function_exists('get_woocommerce_currency_symbol')) ? eventer_get_settings('eventer_paypal_currency') : get_option('woocommerce_currency');
								foreach ($params['tickets'] as $ticket) {
									$remaining = ($ticket['tickets'] > 0) ? $ticket['tickets'] . ' ' . esc_html__('remaining', 'eventer') : esc_html__('All booked', 'eventer');
									
									if ( isset( $params['eventer'] ) ) {
						                $event_id = $params['eventer'];

						                if ( metadata_exists('post', $event_id, 'eventer_event_available_tickets' ) ) {
						                    $aviable_tickets = get_post_meta($event_id, 'eventer_event_available_tickets', true);
						                    $remaining = $aviable_tickets == 'yes' ? '' : $remaining; 
						                }
						            }
									?>
								<li>
									<span class="eventer-ticket-type-price"><?php echo eventer_get_currency_symbol($eventer_currency, $ticket['price']); ?></span>
									<span class="eventer-ticket-type-name"><?php echo $ticket['name']; ?> <i class="eventer-ticket-remaining"><?php echo $remaining; ?></i></span>
								</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
			<!--<a href="#" class="eventer-btn">Buy Tickets</a>-->
		</div>
	<?php } ?>
</li>