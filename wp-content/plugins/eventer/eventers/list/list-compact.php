<li class="eventer-event-item eventer-event-list-item eventer-featured-<?php echo esc_attr($params['featured_class']); ?> new_eventer_status_<?php echo esc_attr($params['new_status']); ?>">
    <a href="<?php echo $params['details']; ?>" target="<?php echo esc_attr($params['target']); ?>" class="eventer-event-item-link">
        <?php
        if ($params['multi'] == '1') {
            ?>
            <span class="eventer-event-date">
                <span class="eventer-event-multiday  eventer-event-multiday-border"><?php echo date_i18n('d M', strtotime($params['start'])); ?></span>
                <span class="eventer-event-multiday "><?php echo date_i18n('d M', strtotime($params['end'])); ?></span>
                <span class="eventer-event-year"> <?php echo date_i18n('Y', strtotime($params['end'])); ?></span>
            </span>
        <?php } else { ?>
            <span class="eventer-event-date" style="border-top-color:<?php echo esc_attr($params['color']); ?>">
                <span class="eventer-event-day "><?php echo date_i18n('d', strtotime($params['date'])); ?></span>
                <span class="eventer-event-month"><?php echo date_i18n('M', strtotime($params['date'])); ?></span>
                <span class="eventer-event-year"> <?php echo date_i18n('Y', strtotime($params['date'])); ?></span>
            </span>
        <?php } ?>
        <span class="eventer-event-details">
            <span class="eventer-event-details-side">
                <?php
                if (has_post_thumbnail($params['eventer'])) {
                    echo '<span class="eventer-event-image" style="background-image:url(' . get_the_post_thumbnail_url($params['eventer'], 'medium') . ');"></span>';
                }
                ?>
				<span class="eventer-cl-details-cont">
					<span class="eventer-event-time">
						<i class="eventer-icon-clock"></i> <?php echo esc_attr($params['show_time']); ?>
					</span>
					<div>
						<?php echo eventer_display_status_badge( $params['eventer'] ); ?>
						<?php echo eventer_check_event_is_virtual( $params['eventer'] ); ?>
						<?php echo $params['featured_span']; ?>
						<?php echo $params['status']; ?>
					</div>
					<span class="eventer-event-title"><?php echo $params['event']; ?></span>
					<?php if (!empty($params['address'])) { ?>
						<span class="eventer-event-venue"><i class="eventer-icon-location-pin" style="color:<?php echo esc_attr($params['color']); ?>"></i> <?php echo eventer_get_event_venue( $params['address'], $params['event_id'] ); ?></span>
					<?php } ?>
				</span>
            </span>
        </span><i class="eventer-icon-arrow-right"></i></a>
</li>