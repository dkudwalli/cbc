<li class="eventer-modern-row eventer-cfloat eventer-featured-<?php echo esc_attr($params['featured_class']); ?> new_eventer_status_<?php echo esc_attr($params['new_status']); ?>">
	<div class="eventer-modern-col">
		<?php if (!has_post_thumbnail( $params['eventer'] ) ){
			$classer = 'eventer-modern-noimg';
		}?>
		<div class="eventer-dater <?php echo esc_attr($classer); ?>" style="background-image: url(<?php if (has_post_thumbnail( $params['eventer'] ) ){ echo get_the_post_thumbnail_url($params['eventer'], 'eventer-thumb-170x170'); } ?>); background-color: <?php echo esc_attr($params['color']); ?>">
			<span class="eventer-event-date"><strong><?php echo esc_attr(date_i18n('d', strtotime($params['date']))); ?></strong><span><?php echo esc_attr(date_i18n('M, Y', strtotime($params['date']))); ?></span></span>
		</div>
	</div>
	<div class="eventer-modern-col">
		<div class="eventer-event-title">
			<?php echo eventer_display_status_badge( $params['eventer'] ); ?>
			<?php echo eventer_check_event_is_virtual( $params['eventer'] ); ?>
			<?php echo $params['featured_span']; ?>
			<?php echo $params['status']; ?>
			<a href="<?php echo $params['details']; ?>" target="<?php echo esc_attr($params['target']); ?>" class="eventer-event-item-link"><?php echo $params['event']; ?></a>
		</div>
		<?php if (!empty($params['address'])) { ?>
		<div class="eventer-classic-meta"><?php echo eventer_get_event_venue( $params['address'], $params['eventer'] ); ?></div>
		<?php } ?>
	</div>
	<?php if ($params['registration'] == '1') { ?>
	<div class="eventer-modern-col">
		<a href="<?php echo $params['regcustom']; ?>" target="<?php echo esc_attr($params['regcustomtarget']); ?>" class="eventer-btn eventer-btn-plain"><?php esc_html_e('Buy Tickets', 'eventer'); ?></a>
	</div>
	<?php } ?>
</li>