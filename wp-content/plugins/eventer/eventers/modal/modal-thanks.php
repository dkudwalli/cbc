<?php
$view = isset($eventer_thanks_view) && is_array($eventer_thanks_view) ? $eventer_thanks_view : array();
$event_title = isset($view['event_title']) ? $view['event_title'] : '';
$venue = isset($view['venue']) ? $view['venue'] : '';
$event_time = isset($view['time']) ? $view['time'] : '';
$event_date = isset($view['date']) ? $view['date'] : '';
$order_number = isset($view['order_number']) ? $view['order_number'] : '';
$organizer = isset($view['organizer']) && is_array($view['organizer']) ? $view['organizer'] : array();
$booked_tickets = isset($view['booked_tickets']) ? $view['booked_tickets'] : array();
$registrant_email = isset($view['registrant_email']) ? $view['registrant_email'] : '';
$show_ticket_delivery = !empty($view['show_ticket_delivery']);
$pending_message = isset($view['pending_message']) ? $view['pending_message'] : '';
?>
<div class="eventer eventer-event-single eventer-modal-static" id="eventer-ticket-confirmation" data-eid="<?php echo esc_attr(isset($view['event_id']) ? $view['event_id'] : get_the_ID()); ?>">
    <div class="eventer-modal-body">
        <div class="eventer-row equah">
            <div class="eventer-ticket-confirmation-left eventer-col4 eventer-col10-xs equah-item">
                <div>
                    <div>
                        <div class="equah-item">
                            <span><?php esc_html_e('Thank', 'eventer'); ?> <em><?php esc_html_e('you', 'eventer'); ?></em> <?php esc_html_e('Kindly', 'eventer'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="eventer-ticket-confirmation-right eventer-col6 eventer-col10-xs equah-item">
                <div class="eventer-toggle-area">
                    <div class="eventer-ticket-confirmation-info">
                        <label><?php esc_html_e('Event', 'eventer'); ?></label>
                        <h3><?php echo wp_kses_post($event_title); ?></h3>
                        <div class="eventer-row">
                            <?php if ($venue !== '') : ?>
                                <div class="eventer-col5">
                                    <label><?php esc_html_e('Venue Location', 'eventer'); ?></label>
                                    <p><?php echo esc_html($venue); ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="eventer-col5">
                                <label><?php esc_html_e('Date', 'eventer'); ?> &amp; <?php esc_html_e('Time', 'eventer'); ?></label>
                                <p><?php echo esc_html($event_time); ?><br><?php echo esc_html($event_date); ?></p>
                            </div>
                        </div>
                        <div class="eventer-spacer-30"></div>
                        <div class="eventer-row">
                            <div class="eventer-col5">
                                <label><?php esc_html_e('Order', 'eventer'); ?> #</label>
                                <p><?php echo esc_html($order_number); ?></p>
                            </div>
                            <?php if (!empty($organizer)) : ?>
                                <div class="eventer-col5">
                                    <label><?php esc_html_e('Organizer', 'eventer'); ?></label>
                                    <p><?php echo esc_html(isset($organizer['name']) ? $organizer['name'] : ''); ?></p>
                                    <p><?php echo esc_html(isset($organizer['phone']) ? $organizer['phone'] : ''); ?></p>
                                    <p><?php echo esc_html(isset($organizer['email']) ? $organizer['email'] : ''); ?></p>
                                    <p><?php echo esc_html(isset($organizer['website']) ? $organizer['website'] : ''); ?></p>
                                    <?php if (!empty($organizer['events_url'])) : ?>
                                        <a href="<?php echo esc_url($organizer['events_url']); ?>"><?php esc_html_e("Organizer's other events", 'eventer'); ?></a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="eventer-spacer-30"></div>
                        <?php if (!empty($booked_tickets)) : ?>
                            <label class="eventer-label-bi-tickets"><?php esc_html_e('Booked Tickets', 'eventer'); ?></label>
                            <?php foreach ($booked_tickets as $ticket) : ?>
                                <?php if (empty($ticket['name'])) { continue; } ?>
                                <p><?php echo esc_html($ticket['name']); ?> x <strong><?php echo esc_html($ticket['number']); ?></strong></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <div class="eventer-ticket-confirmation-footer">
                            <?php if ($show_ticket_delivery && !empty($booked_tickets)) : ?>
                                <label><?php esc_html_e('Tickets sent to', 'eventer'); ?>:</label>
                                <p><?php echo esc_html($registrant_email); ?></p>
                            <?php elseif (!empty($booked_tickets)) : ?>
                                <p><?php echo esc_html($pending_message); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
