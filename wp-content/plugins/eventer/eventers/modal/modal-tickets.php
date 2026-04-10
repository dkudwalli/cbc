<?php
$view = isset($eventer_ticket_view) && is_array($eventer_ticket_view) ? $eventer_ticket_view : array();
$tickets = isset($view['tickets']) ? $view['tickets'] : array();
?>
<div class="eventer eventer-event-single eventer-modal-static" id="eventer-ticket-show-now">
    <div class="eventer-modal-body">
        <div class="eventer-ticket-final-tickets">
            <div class="eventer-ticket-printable">
                <div class="eventer-ticket-printable-top">
                    <div class="eventer-on-ticket-qr" data-qr-content="<?php echo esc_attr(isset($view['ticket_code']) ? $view['ticket_code'] : ''); ?>" data-qr-size="200"></div>
                    <label class="eventer-ticket-reg-code"><?php echo esc_html(isset($view['ticket_code']) ? $view['ticket_code'] : ''); ?></label>
                </div>
                <div class="eventer-ticket-printable-bottom">
                    <label><?php esc_html_e('Attendee', 'eventer'); ?></label>
                    <h3><?php echo esc_html(isset($view['attendee']) ? $view['attendee'] : ''); ?></h3>
                    <div class="eventer-spacer-10"></div>
                    <label><?php esc_html_e('Event', 'eventer'); ?></label>
                    <p><?php echo wp_kses_post(isset($view['event_title']) ? $view['event_title'] : ''); ?></p>
                    <div class="eventer-spacer-10"></div>
                    <label><?php esc_html_e('Ticket', 'eventer'); ?></label>
                    <?php foreach ($tickets as $ticket) : ?>
                        <?php if (empty($ticket['name']) || empty($ticket['number'])) { continue; } ?>
                        <p class="eventer-tickets-booked-info"><?php echo esc_html($ticket['name']); ?> x <strong><?php echo esc_html($ticket['number']); ?></strong></p>
                    <?php endforeach; ?>
                    <div class="eventer-row">
                        <?php if (!empty($view['venue'])) : ?>
                            <div class="eventer-col5 eventer-pt-venue">
                                <div class="eventer-spacer-10"></div>
                                <label><?php esc_html_e('Venue Location', 'eventer'); ?></label>
                                <p><?php echo esc_html($view['venue']); ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="eventer-col5 eventer-pt-datetime">
                            <div class="eventer-spacer-10"></div>
                            <label><?php esc_html_e('Date', 'eventer'); ?> &amp; <?php esc_html_e('Time', 'eventer'); ?></label>
                            <p><?php echo esc_html(isset($view['time']) ? $view['time'] : ''); ?><br><?php echo esc_html(isset($view['date']) ? $view['date'] : ''); ?></p>
                        </div>
                    </div>
                    <?php if (!empty($view['event_notes'])) : ?>
                        <div class="eventer-row">
                            <div class="eventer-col10 eventer-pt-instructions">
                                <div class="eventer-spacer-10"></div>
                                <label><?php esc_html_e('Instructions', 'eventer'); ?></label>
                                <p><?php echo wp_kses($view['event_notes'], 'post'); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="eventer-row eventer-ticket-c-info">
                        <div class="eventer-col10 eventer-pt-cominfo">
                            <?php if (!empty($view['company_logo_html'])) : ?>
                                <p class="eventer-ticket-c-logo"><?php echo $view['company_logo_html']; ?></p>
                            <?php endif; ?>
                            <?php if (!empty($view['company_name'])) : ?>
                                <p class="eventer-ticket-c-address"><?php echo esc_html($view['company_name']); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($view['company_address'])) : ?>
                                <p class="eventer-ticket-c-address"><?php echo wp_kses_post($view['company_address']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a class="eventer-print-ticket" href="javascript:void(0)"><?php esc_html_e('PRINT', 'eventer'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
