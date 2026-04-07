<li class="eventer-event-item eventer-event-list-item <?php echo esc_attr($params['featured_class']); ?> new_eventer_status_<?php echo esc_attr($params['new_status']); ?>"<?php if (!empty($params['color'])) { echo ' style="border-left-color:' . esc_attr($params['color']) . '"'; } ?>>
    <a href="<?php echo $params['details']; ?>" target="<?php echo esc_attr($params['target']); ?>" class="eventer-event-item-link equah">
        <?php
        if ($params['multi'] == '1') {
            ?>
            <span class="eventer-event-date">
                <span>
                    <span class="eventer-cell">
                        <span class="eventer-dater">
                            <span class="eventer-event-month"><?php echo date_i18n('d M', strtotime($params['start'])); ?>-<?php echo date_i18n('d M', strtotime($params['end'])); ?></span>
                            <span class="eventer-event-time"> <?php echo esc_attr($params['show_time']); ?></span>
                        </span>
                    </span>
                </span>
            </span>
        <?php } else { ?>
            <span class="eventer-event-date">
                <span>
                    <span class="eventer-cell">
                        <span class="eventer-event-day pull-left"><?php echo date_i18n('d', strtotime($params['date'])); ?></span>
                        <span class="eventer-dater">
                            <span class="eventer-event-month"><?php echo date_i18n('M', strtotime($params['date'])); ?></span>
                            <span class="eventer-event-year"> <?php echo date_i18n('Y', strtotime($params['date'])); ?></span>
                            <span class="eventer-event-time"> <?php echo esc_attr($params['show_time']); ?></span>
                        </span>
                    </span>
                </span>
            </span>
        <?php } ?>
        <span class="eventer-event-details">
			<?php echo eventer_display_status_badge( $params['eventer'] ); ?>
			<?php echo eventer_check_event_is_virtual( $params['eventer'] ); ?>
			<?php echo $params['featured_span']; ?>
			<?php echo $params['status']; ?>
            <span class="eventer-event-title"><?php echo $params['event']; ?> </span>
            <?php if (!empty($params['address'])) { ?>
                <span class="eventer-event-venue"> <?php echo eventer_get_event_venue( $params['address'], $params['event_id'] ); ?></span>
            <?php } ?>
        </span>
    </a>
</li>