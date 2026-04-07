<li class="eventer-event-item eventer-p2-event-list-item eventer-cfloat eventer-featured-<?php echo esc_attr($params['featured_class']); ?> new_eventer_status_<?php echo esc_attr($params['new_status']); ?>">
  <div class="eventer-p2-event-image">
    <a href="<?php echo $params['details']; ?>" target="<?php echo esc_attr($params['target']); ?>" class="eventer-media-hover">
      <?php echo get_the_post_thumbnail($params['eventer'], 'eventer-thumb-600x400'); ?>
    </a>
    <div class="eventer-quick-share closed">
      <a href="#"><i class="eventer-icon-share"></i></a>
      <ul>
        <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($params['raw_url']); ?>"><i class="eventer-icon-social-facebook"></i></a></li>
        <li><a href="https://twitter.com/intent/tweet?source=<?php echo esc_url($params['raw_url']); ?>&text=Event: <?php echo $params['event_title']; ?>:<?php echo esc_url($params['raw_url']); ?>"><i class="eventer-icon-social-twitter"></i></a></li>
        <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url($params['raw_url']); ?>&title=<?php echo $params['event_title']; ?>&source=<?php echo esc_url($params['raw_url']); ?>"><i class="eventer-icon-social-linkedin"></i></a></li>
        <li><a href="mailto:?subject=<?php echo $params['event_title']; ?>&body=<?php echo $params['excerpt']; ?>:<?php echo esc_url($params['raw_url']); ?>"><i class="eventer-icon-envelope-letter"></i></a></li>
      </ul>
    </div>
  </div>
  <div class="eventer-p2-list-content">
	<?php echo eventer_display_status_badge( $params['eventer'] ); ?>
	<?php echo eventer_check_event_is_virtual( $params['eventer'] ); ?>
	<?php echo $params['featured_span']; ?>
	<?php echo $params['status']; ?>
    <h4 class="eventer-event-title">
      <a href="<?php echo $params['details']; ?>" target="<?php echo esc_attr($params['target']); ?>" class="eventer-event-item-link"><?php echo $params['event']; ?></a>
    </h4>
    <?php
    $date_show = $params['show_date'];
    ?>
    <div class="eventer-classic-meta"><i class="eventer-icon-calendar"></i> <?php echo esc_attr($date_show); ?> <span class="eventer-meta-sub"><?php echo $params['show_time']; ?></span></div>
    <?php if (!empty($params['address'])) { ?>
      <div class="eventer-classic-meta"><i class="eventer-icon-location-pin"></i> <?php echo eventer_get_event_venue( $params['address'], $params['eventer'] ); ?></div>
    <?php } ?>
	<?php if($params['excerpt']){?>
    <div class="eventer-classic-content">
      <?php echo esc_attr($params['excerpt']); ?>
    </div>
	<?php } ?>
    <div class="eventer-classic-ticket-info">
       <?php if ($params['registration'] == '1') { ?>
      	<a href="<?php echo esc_attr($params['regcustom']); ?>" target="<?php echo esc_attr($params['regcustomtarget']); ?>" class="eventer-btn"><?php esc_html_e('Buy Tickets', 'eventer'); ?></a>
	  <?php } ?>
      <?php
      if (!empty($params['tickets'])) {
        ?>

        <div class="eventer-fe-dd eventer-quick-ticket-info">
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

      <?php } ?>
    </div>
  </div>
</li>