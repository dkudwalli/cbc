<li class="eventer-event-item eventer-native-row eventer-cfloat eventer-featured-<?php echo esc_attr($params['featured_class']); ?> new_eventer_status_<?php echo esc_attr($params['new_status']); ?>">
	
	<div class="eventer-native-col">
		<div class="eventer-dater">
			<span class="eventer-event-day"><?php echo esc_attr(date_i18n('d', strtotime($params['date']))); ?></span>
			<span class="eventer-event-month"><?php echo esc_attr(date_i18n('M', strtotime($params['date']))); ?></span>
		</div>
	</div>
	<div class="eventer-native-col">
		<div class="eventer-event-title">
			<?php echo eventer_display_status_badge( $params['eventer'] ); ?>
			<?php echo eventer_check_event_is_virtual( $params['eventer'] ); ?>
			<?php echo $params['featured_span']; ?>
			<?php echo $params['status']; ?>
			<a href="<?php echo $params['details']; ?>" target="<?php echo esc_attr($params['target']); ?>" class="eventer-event-item-link"><?php echo $params['event']; ?></a>
		</div>
		<?php
		$date_show = $params['show_date'];
		?>
		<div class="eventer-classic-meta"><?php echo esc_attr($date_show); ?> | <strong><?php echo $params['show_time']; ?></strong></div>
		<?php
		if (!empty($params['tickets'])) {
			?>
			<div class="eventer-meta-ticket">
				<?php
					$woocommerce_ticketing = eventer_get_settings('eventer_enable_woocommerce_ticketing');
					$eventer_currency = ($woocommerce_ticketing != 'on' || !function_exists('get_woocommerce_currency_symbol')) ? eventer_get_settings('eventer_paypal_currency') : get_option('woocommerce_currency');
					foreach ($params['tickets'] as $ticket) {
						$remaining = ($ticket['tickets'] > 0) ? $ticket['tickets'] . ' ' . esc_html__('remaining', 'eventer') : esc_html__('All booked', 'eventer');
						?>
					<span><strong><?php echo eventer_get_currency_symbol($eventer_currency, $ticket['price']); ?></strong><span><?php echo $ticket['name']; ?></span><em><?php echo $remaining; ?></em></span>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
	
    <div class="eventer-native-col">
        <?php if (!empty($params['address']) && $params['virtual'] != 'mark_virtual') { ?>
            <a href="https://www.google.com/maps/dir//<?php echo $params['address']; ?>" target="_blank" title="<?php esc_html_e('Get Directions', 'eventer'); ?>" class="eventer-plain-links"><i class="eventer-icon-map"></i></a>
        <?php }
		if ($params['registration'] == '1') { ?>
        	<a href="<?php echo $params['regcustom']; ?>" target="<?php echo esc_attr($params['regcustomtarget']); ?>" class="eventer-btn eventer-btn-plain"><?php esc_html_e('Buy Tickets', 'eventer'); ?></a>
		<?php } ?>
    </div>
	
</li>