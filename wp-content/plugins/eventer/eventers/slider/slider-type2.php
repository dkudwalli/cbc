<li class="eventer-event-slide eventer-featured-<?php echo esc_attr($params['featured_class']); ?> new_eventer_status_<?php echo esc_attr($params['new_status']); ?>" style="background-image: url(<?php echo get_the_post_thumbnail_url($params['eventer'], 'full'); ?>)">
	<div class="eventer-slider-content">
		<div class="eventer-event-date-meta">
			<div class="eventer-event-date">
				<strong><?php echo date_i18n('d', strtotime($params['date'])); ?></strong>
				<span><?php echo date_i18n('M', strtotime($params['date'])); ?></span>
			</div>
			<div class="eventer-event-day"><?php echo date_i18n('l', strtotime($params['date'])); ?></div>
			<div class="eventer-event-time"><?php echo $params['show_time']; ?></div>
		</div>
		<?php if (!empty($params['address'])) { ?>
			<div class="eventer-status-badge"><?php echo eventer_get_event_venue( $params['address'], $params['eventer'] ); ?></div>
		<?php } ?>
		<?php echo eventer_display_status_badge( $params['eventer'] ); ?>
		<?php echo eventer_check_event_is_virtual( $params['eventer'] ); ?>
		<?php echo $params['featured_span']; ?>
		<?php echo $params['status']; ?>
		<div class="eventer-event-title">
			<a href="<?php echo $params['details']; ?>" target="<?php echo esc_attr($params['target']); ?>"><?php echo $params['event']; ?></a>
		</div>
		<?php if ($params['organizer'] != '') { ?>
			<div class="eventer-event-organizer"><em><?php esc_html_e('Organized by', 'eventer'); ?></em> <a href="<?php echo esc_url($params['organizer_link']); ?>"><strong><?php echo $params['organizer']; ?></strong></a></div>
		<?php } ?>
		<div class="eventer-event-excerpt">
			<p><?php echo esc_attr($params['excerpt']); ?></p>
		</div>
		<a href="<?php echo $params['details']; ?>" target="<?php echo esc_attr($params['target']); ?>" class="eventer-btn eventer-btn-primary"><?php esc_html_e('Read More', 'eventer'); ?></a>
	</div>
</li>