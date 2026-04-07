<h2><?php
    ?></h2>
<?php
$order_received_URL = eventer_woo_get_return_url(wc_get_order($params['booking']['order_id']));
$order_received_URL = add_query_arg('backorder', '1', $order_received_URL);
?>
<div class="wrap">

  <div id="icon-options-general" class="icon32"></div>
  <h1><?php esc_attr_e('Booking Details', 'eventer'); ?></h1>

  <div id="poststuff">

    <div id="post-body" class="metabox-holder columns-2">

      <!-- main content -->
      <div id="post-body-content">

        <div class="meta-box-sortables ui-sortable">

          <div class="postbox">
            <div class="edit-post-header" style="display: flex; align-items: center; justify-content: space-between">
              <div class="edit-post-header__toolbar" style="text-align: left; display: inline-block; width: 50%">
                <h2 style="font-size: 20px;"><?php esc_attr_e('Order Details', 'eventer'); ?></h2>
              </div>
              <div class="edit-post-header__settings" style="padding: 15px; text-align: right">
                <a href="<?= add_query_arg(['booking_id' => $booking['id'], 'repos' => '14'], $order_received_URL) ?>" class="button button-primary"><?php esc_attr_e('Create Tickets', 'eventer'); ?></a>
                <a href="<?= add_query_arg(['booking_id' => $booking['id'], 'repos' => '15'], $order_received_URL) ?>" class="button button-primary"><?php esc_attr_e('Send Tickets Email', 'eventer'); ?></a>
                <small style="display: block; margin-top: 15px;"><?php esc_attr_e('Please be patient, the page will redirect back here after the action.', 'eventer'); ?></small>
              </div>
            </div>
            <hr>
            <table class="form-table eventer-form-table">
              <tbody>
                <tr class="">
                  <td valign="top" style="width:40%"><label><?php esc_attr_e('User Name', 'eventer'); ?></label></td>
                  <td><input maxlength="" type="text" disabled value="<?php echo getRegistrationMeta($booking['id'], 'user_name') ?? ""; ?>" size="30" style="width:97%"><span class="field-description"></span></td>
                  <td></td>
                </tr>
                <tr class="">
                  <td valign="top" style="width:40%"><label><?php esc_attr_e('User Email', 'eventer'); ?></label></td>
                  <td><input maxlength="" type="text" disabled value="<?php echo getRegistrationMeta($booking['id'], 'user_email') ?? ""; ?>" size="30" style="width:97%"><span class="field-description"></span></td>
                  <td></td>
                </tr>
                <tr class="">
                  <td valign="top" style="width:40%"><label><?php esc_attr_e('Event Slot', 'eventer'); ?></label></td>
                  <td><input maxlength="" type="text" disabled value="<?php echo getRegistrationMeta($booking['id'], 'slot_title') ?? ""; ?>" size="30" style="width:97%"><span class="field-description"></span></td>
                  <td></td>
                </tr>
                <tr class="">
                  <td valign="top" style="width:40%"><label><?php esc_attr_e('Woocommerce Order ID', 'eventer'); ?></label></td>
                  <td><input maxlength="" type="text" disabled value="<?php echo $params['booking']['order_id'] ?? ""; ?>" size="30" style="width:97%"><span class="field-description"><?php esc_attr_e('Woocommerce order details can be checked ', 'eventer'); ?><a href="<?php echo admin_url( 'post.php?post='.$params['booking']['order_id'].'&action=edit', '' ); ?>"><?php esc_attr_e('here', 'eventer'); ?></a></span></td>
                  <td></td>
                </tr>
                <tr class="">
                  <td valign="top" style="width:40%"><label><?php esc_attr_e('Registration Date', 'eventer'); ?></label></td>
                  <td><input maxlength="" type="text" disabled value="<?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($params['booking']['reg_date'])) ?? ""; ?>" size="30" style="width:97%"><span class="field-description"></span></td>
                  <td></td>
                </tr>
                <tr class="">
                  <td valign="top" style="width:40%"><label><?php esc_attr_e('Transaction ID', 'eventer'); ?></label></td>
                  <td><input maxlength="" type="text" disabled value="<?php echo $params['booking']['transaction_id'] ?? ""; ?>" size="30" style="width:97%"><span class="field-description"></span></td>
                  <td></td>
                </tr>
                <tr class="">
                  <td valign="top" style="width:40%"><label><?php esc_attr_e('Payment mode', 'eventer'); ?></label></td>
                  <td><input maxlength="" type="text" disabled value="<?php echo $params['booking']['paymentmode'] ?? ""; ?>" size="30" style="width:97%"><span class="field-description"></span></td>
                  <td></td>
                </tr>
                <tr class="">
                  <td valign="top" style="width:40%"><label><?php esc_attr_e('Status', 'eventer'); ?></label></td>
                  <td><input maxlength="" type="text" disabled value="<?php echo $params['booking']['reg_status'] ?? ""; ?>" size="30" style="width:97%"><span class="field-description"><?php esc_attr_e('Woocommerce order status can be updated ', 'eventer'); ?><a href="<?php echo admin_url( 'post.php?post='.$params['booking']['order_id'].'&action=edit', '' ); ?>"><?php esc_attr_e('here', 'eventer'); ?></a></span></td>
                  <td></td>
                </tr>
                <tr class="">
                  <td valign="top" style="width:40%"><label><?php esc_attr_e('Amount', 'eventer'); ?></label></td>
                  <td><input maxlength="" type="text" disabled value="<?php echo $params['booking']['reg_amount'] ?? ""; ?>" size="30" style="width:97%"><span class="field-description"></span></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
            <hr>
            <!-- .inside -->

          </div>
          <!-- .postbox -->

        </div>

        <div class="meta-box-sortables ui-sortable">

          <div class="postbox">
            <h2 style="font-size: 20px;">Details</h2>
            <hr>
            <?php
            foreach ($tickets as $ticket) {
              if ($ticket->type == 'ticket') { ?>
                <span style="border: 1px solid green; border-radius: 4px; padding: 8px;margin-left: 10px;"><?php esc_attr_e('Ticket', 'eventer'); ?></span>
              <?php } else { ?>
                <span style="border: 1px solid red; border-radius: 4px; padding: 8px;margin-left: 10px;"><?php esc_attr_e('Service', 'eventer'); ?></span>
              <?php } ?>
              <table class="form-table eventer-form-table">
                <tbody>
                  <?php if ($ticket->type == 'ticket') { ?>
                    <tr class="">
                      <td valign="top" style="width:40%"><label><?php esc_attr_e('QR ID', 'eventer'); ?></label></td>
                      <td><input maxlength="" disabled type="text" value="<?= $ticket->id ?? ""; ?>" size="30" style="width:97%"><span class="field-description"></span></td>
                      <td></td>
                    </tr>
                  <?php }
                    if ($ticket->type == 'ticket') { ?>
                    <tr class="">
                      <td valign="top" style="width:40%"><label><?php esc_attr_e('Event End Date &amp; Time', 'eventer'); ?></label></td>
                      <td><input maxlength="" type="text" value="<?= date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($ticket->ticket_date)) ?? "" ?>" size="30" style="width:97%"><span class="field-description"></span></td>
                      <td></td>
                    </tr>
                  <?php }
                    if ($ticket->type == 'ticket') { ?>
                    <tr class="">
                      <td valign="top" style="width:40%"><label><?php esc_attr_e('User Name', 'eventer'); ?></label></td>
                      <td><input maxlength="" type="text" value="<?= $ticket->user_name ?? "" ?>" size="30" style="width:97%"><span class="field-description"></span></td>
                      <td></td>
                    </tr>
                  <?php } ?>
                  <tr class="">
                    <td valign="top" style="width:40%"><label><?php esc_attr_e('Ticket Name', 'eventer'); ?></label></td>
                    <td><input maxlength="" type="text" value="<?= $ticket->ticket_name ?? "" ?>" size="30" style="width:97%"><span class="field-description"></span></td>
                    <td></td>
                  </tr>
                  <tr class="">
                    <td valign="top" style="width:40%"><label><?php esc_attr_e('Event Name', 'eventer'); ?></label></td>
                    <td><input maxlength="" type="text" value="<?= get_the_title($ticket->event_id) ?? "" ?>" size="30" style="width:97%"><span class="field-description"></span></td>
                    <td></td>
                  </tr>
                  <?php if ($ticket->type == 'ticket') { ?>
                    <tr class="">
                      <td valign="top" style="width:40%"><label><?php esc_attr_e('Venue', 'eventer'); ?></label></td>
                      <td><input maxlength="" type="text" value="<?= $ticket->venue ?? "" ?>" size="30" style="width:97%"><span class="field-description"></span></td>
                      <td></td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td>
                      
                    </td>
                  </tr>
                </tfoot>
              </table>
            <?php } ?>
          </div>
          <!-- .postbox -->

        </div>
        <!-- .meta-box-sortables .ui-sortable -->

      </div>
      <!-- post-body-content -->

      <!-- sidebar -->
      <div id="postbox-container-1" class="postbox-container">

        <div class="meta-box-sortables">

          <?php
			// Get the URL for downloading PDF tickets
			$pdf_download_url = get_pdf_download_url($booking['id']);
			?>
			<div class="postbox">
				<div class="inside" style="padding-top: 10px">
					<a href="<?= $pdf_download_url ?>" class="button button-primary">
						<?php esc_attr_e('Download PDF Tickets', 'eventer'); ?>
					</a>
					<br><br>
					<?php esc_attr_e('Click the button to download all PDF tickets for this booking.', 'eventer'); ?>
					<br><br>
					<?php esc_attr_e('If you see error "No PDF tickets found for this booking." then click on "Create Tickets" button at left and try downloading again.', 'eventer'); ?>
				</div>
			</div>

          <!-- .postbox -->

        </div>
        <!-- .meta-box-sortables -->

      </div>
      <!-- #postbox-container-1 .postbox-container -->

    </div>
    <!-- #post-body .metabox-holder .columns-2 -->

    <br class="clear">
  </div>
  <!-- #poststuff -->

</div> <!-- .wrap -->