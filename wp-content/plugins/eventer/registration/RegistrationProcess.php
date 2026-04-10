<?php
class RegistrationProcess {
  public function __construct() {
    add_action( 'woocommerce_checkout_order_processed', [ $this, 'eventerWooRegister' ], 999 );
    add_action( 'woocommerce_thankyou', [ $this, 'createTickets' ], 1 );
    add_action( 'wp_ajax_generateTicketsImage', [ $this, 'generateTicketsImage' ] );
    add_action( 'wp_ajax_nopriv_generateTicketsImage', [ $this, 'generateTicketsImage' ] );
    add_action( 'sendTicketsEmail', [ $this, 'sendTicketsEmail' ], 10, 2 );
    add_filter( 'eventer_registrationv2_status_update', [ $this, 'eventerRegistrationV2StatusUpdate' ], 999, 2 );
    add_action( 'send_tickets', [ $this, 'sendTicketsAgain' ], 10, 1 );
  }

  public function eventerWooRegister( $order_id ) {

    if ( empty( get_post_meta( $order_id, 'eventer_order_recorded', true ) ) ) {

      $order = wc_get_order( $order_id );
      $counter = 0;
      $elocation = '';
      $registrant_uname = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
      $registrant_email = $order->get_billing_email();
      foreach ( $order->get_items() as $item_key => $item_values ):
        $item_data = $item_values->get_data();
      $item_id = $item_values->get_id();
      $product_id = $item_data[ 'product_id' ];
      $eventer_product_type = wc_get_order_item_meta( $item_id, '_eventer_product', true );
      if ( $eventer_product_type == '' ) continue;
      $eventRawDate = wc_get_order_item_meta( $item_id, '_wceventer_date', true );
      $event_time_slot = wc_get_order_item_meta( $item_id, '_wceventer_slot', true );
      $event_time_slot_title = wc_get_order_item_meta( $item_id, 'wceventer_slot_title', true );
      $event_id = wc_get_order_item_meta( $item_id, '_wceventer_id', true );
      $eventer_venue = get_the_terms( $event_id, 'eventer-venue' );
      if ( !is_wp_error( $eventer_venue ) && !empty( $eventer_venue ) ) {
        foreach ( $eventer_venue as $venue ) {
          $location_address = get_term_meta( $venue->term_id, 'venue_address', true );
          $elocation = ( $location_address != '' ) ? $location_address : $venue->name;
        }
      }
		$attachment_content_switch = true;
		if ( metadata_exists( 'post', $event_id, 'eventer_event_ticket_email' ) ) {
		  $ticket_email = get_post_meta( $event_id, 'eventer_event_ticket_email', true );
		  if ( $ticket_email == 'on' ) {
			$attachment_content_switch = false;
		  }
		}
      $event_allday = wc_get_order_item_meta( $item_id, '_eventer_allday', true );
      $event_time = get_post_meta( $event_id, 'eventer_event_start_dt', true );
      $ticketPrimaryId = wc_get_order_item_meta( $item_id, '_ticket_id', true );
      $event_time = date_i18n( get_option( 'time_format' ), strtotime( $event_time ) );
      $event_registrants = wc_get_order_item_meta( $item_id, '_eventer_registrants', true );
      $wc_event_registrants = wc_get_order_item_meta( $item_id, '_wc_event_registrants', true );
      $eventTicketName = wc_get_order_item_meta( $item_id, '_eventer_custom_title', true );
      if ( $counter == 0 ) {
        $transID = $order->get_transaction_id();
        $payment_method = $order->get_payment_method();
        $status = $order->get_status();
        $amount = $order->get_total();
        $user_reg_id = get_current_user_id();
        $data = [ 'booking_id' => 1, 'user_id' => $user_reg_id, 'order_id' => $order_id, 'reg_date' => date_i18n( 'Y-m-d H:i:s' ), 'transaction_id' => $transID, 'paymentmode' => $payment_method, 'reg_status' => $status, 'reg_amount' => $amount ];
        $registrationId = addRegistration( $data );
        addRegistrationMeta( [ 'reg_id' => $registrationId, 'meta_key' => 'slot_title', 'meta_value' => $event_time_slot_title ] );
        addRegistrationMeta( [ 'reg_id' => $registrationId, 'meta_key' => 'user_name', 'meta_value' => $registrant_uname ] );
        addRegistrationMeta( [ 'reg_id' => $registrationId, 'meta_key' => 'user_email', 'meta_value' => $registrant_email ] );
      }

      // print_r( [ $event_registrants, $wc_event_registrants ] );exit();
      if ( $event_time_slot == '00:00:00' ) {
        $eventTicketDate = date( 'Y-m-d H:i:s', strtotime( $eventRawDate . ' ' . $event_time ) );
      } else {
        $eventTicketDate = date( 'Y-m-d H:i:s', strtotime( $eventRawDate . ' ' . $event_time_slot ) );
      }
      if ( $eventer_product_type == 'ticket' ) {
        $quantity = $item_data[ 'quantity' ];
      } else {
        $services = array_map( 'trim', explode( ',', wc_get_order_item_meta( $item_id, 'Services', true ) ) );
        $quantity = count( $services );
      }
      $order_event_url = wc_get_order_item_meta( $item_id, 'Event URL', true );
      for ( $count = 0; $count < $quantity; $count++ ) {
        if ( $eventer_product_type == 'service' && empty( trim( $services[ $count ], ' ' ) ) ) {
          continue;
        }
        $ticketName = $eventer_product_type == 'ticket' ? $eventTicketName : $services[ $count ];
        $data = [ 'reg_id' => $registrationId, 'qr_code' => 1, 'ticket_name' => $ticketName, 'user_name' => $registrant_uname, 'type' => $eventer_product_type, 'ticket_date' => $eventTicketDate, 'venue' => $elocation, 'event_id' => $event_id, 'price' => 1, 'ticket_status' => 'active' ];
        $ticketId = addTickets( $data );
        addTicketMeta( [ 'ticket_id' => $ticketId, 'meta_key' => 'product_id', 'meta_value' => $product_id ] );
        addTicketMeta( [ 'ticket_id' => $ticketId, 'meta_key' => 'primary_ticket_id', 'meta_value' => $ticketPrimaryId ] );
        addTicketMeta( [ 'ticket_id' => $ticketId, 'meta_key' => 'event_url', 'meta_value' => $order_event_url ] );
        $registratantDetails = isset( $event_registrants[ $count ] ) ? $event_registrants[ $count ] : [];

        if ( !empty( $registratantDetails ) ) {
          addTicketMeta( [
            'ticket_id' => $ticketId,
            'meta_key' => 'reg_details',
            'meta_value' => serialize( $registratantDetails )
          ] );

          foreach ( $registratantDetails as $key => $value ) {
            if ( $key == 'name' ) {
              updateTicket( $ticketId, [ 'user_name' => $value ], [ '%s' ] );
            }

            if ( $key == 'name' || $key == 'email' ) {
              addTicketMeta( [ 'ticket_id' => $ticketId, 'meta_key' => $key, 'meta_value' => $value ] );
            }
          }
        }
      }
      $counter++;
      endforeach;
      update_post_meta( $order_id, 'eventer_order_recorded', $registrationId );
    }
  }

  function generateTicketsImage( $order_id ) {
    TicketAssetService::handle_registration_v2_generation_request();
  }

  public function createTickets( $order_id ) {
    $bookingId = get_post_meta( $order_id, 'eventer_order_recorded', true );
    if ( !empty( $bookingId ) ) {
      $booking = getRegistration( $bookingId );
      $order = wc_get_order( $order_id );
      $registrant_uname = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
      $registrant_email = $order->get_billing_email();
      $tickets = getRegistrationTickets( $bookingId );

      $order = wc_get_order( $booking->order_id );
      $status = $order->get_status();
      $position = $status == 'completed' ? 15 : 14;
      $position = isset( $_REQUEST[ 'repos' ] ) ? $_REQUEST[ 'repos' ] : $position;
      $backorder = "";
      if ( isset( $_REQUEST[ 'booking_id' ] ) ) {
        $backorder = esc_url( get_admin_url() . 'admin.php?page=booking_details&booking_id=' . $bookingId );
      }
      if ( !empty( $tickets ) ) {
        foreach ( $tickets as $ticket ) {
          if ( $ticket->type == 'ticket' ) {
            $ticketName = getTicketMeta( $ticket->id, 'name' );
            $ticketEmail = getTicketMeta( $ticket->id, 'email' );
            $ticketName = !empty( $ticketName ) ? $ticketName : $registrant_uname;
            $ticketEmail = !empty( $ticketEmail ) ? $ticketEmail : $registrant_email;
            $ticketData = [ 'data-nonce' => eventer_create_registrant_action_nonce( 'eventer-qrcode-nonce', $bookingId ), 'default' => [ 'data-uname' => $registrant_uname, 'data-regv2' => 'v2', 'data-uemail' => $registrant_email, 'data-registrant' => $bookingId, 'data-eid' => '', 'data-regpos' => $position ], 'data-mainreg' => $registrant_email, 'data-registrant' => $bookingId, 'data-eid' => '', 'data-organizer' => '', 'individual' => [ 0 => [ 'data-ticket' => $ticket->ticket_name, 'data-elocation' => $ticket->venue, 'data-datetime' => date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $ticket->ticket_date ) ), 'data-eventid' => $ticket->event_id, 'data-eventname' => get_the_title( $ticket->event_id ), 'data-email' => $ticketEmail, 'data-name' => $ticketName, 'data-qrcode' => $ticket->id, 'data-regv2' => 'v2', 'data-img' => '' ] ], 'data-regpos' => $position, 'data-backorder' => $backorder ];
            do_action( 'eventer_ticket_raw_design', '', $ticketData );
          }
        }
      }
    }
  }

  function sendTicketsEmail( $email, $qrcode_name ) {
    $sender = eventer_get_settings( 'email_from_address' ) ? eventer_get_settings( 'email_from_address' ) : get_option( 'admin_email' );
    $sender_name = eventer_get_settings( 'email_from_name' ) ? eventer_get_settings( 'email_from_name' ) : get_bloginfo( 'name' );
    $headers[] = 'From: ' . $sender_name . ' <' . $sender . '>';
    $headers[] = "Content-type: text/html; charset=" . get_bloginfo( 'charset' ) . "" . "\r\n";
    $subject = esc_html__( 'Your tickets', 'eventer' );
    if ( empty( $qrcode_name ) ) {
      return;
    }

    global $wp_filesystem;
    if ( empty( $wp_filesystem ) ) {
      require_once ABSPATH . '/wp-admin/includes/file.php';
      WP_Filesystem();
    }
    $message = '';
    $upload = wp_upload_dir();
    $upload_dir_base = $upload[ 'basedir' ];
    $has_attachment = 0;
    $mail_attachment = array();
    $pdfAttachment = 'on'; //eventer_get_settings('eventer_pdf_ticket');
    foreach ( $qrcode_name as $ticket_print ) {

      if ( $pdfAttachment == 'on' ) {
        $ticket_print = str_replace( 'png', 'pdf', $ticket_print );
      }

      if ( !file_exists( $upload_dir_base . '/eventer/' . $ticket_print ) ) continue;
      $mail_attachment[] = $upload_dir_base . '/eventer/' . $ticket_print;
      $size = filesize( $upload_dir_base . '/eventer/' . $ticket_print );
      if ( $size > 120 ) {
        $has_attachment = 1;
      }
    }
    $attachment_content = apply_filters( 'the_content', eventer_get_settings( 'email_tickets_attachment' ) );
    $attachment_content_switch = eventer_get_settings( 'email_tickets_attachment_switch' );

    if ( $has_attachment == 0 || $attachment_content_switch == '0' ) {
      return;
    }
    if ( $attachment_content == '' ) {
      $message = esc_html__( 'Please find tickets in the attachment.', 'eventer' );
    } else {
      $message = $attachment_content;
    }
    $mail_status = send_eventer_custom_email( $email, $subject, $message, $headers, $mail_attachment );
  }

  public function eventerRegistrationV2StatusUpdate( $orderId, $source ) {
    $booking = getRegistration( $orderId, 10, 0, 'order_id' );
    $order = wc_get_order( $orderId );
    if ( $order ) {
      $status = $order->get_status();
      updateRegistration( $booking->id, [ 'reg_status' => $status ], [ '%s' ] );
    }

    do_action( 'send_tickets', $booking );
  }
	
  public function sendTicketsAgain( $booking ) {
    $tickets = getRegistrationTickets( $booking->id );
    $ticketsSend = $emptyTickets = [];
    $registrant_email = get_post_meta( $booking->order_id, '_billing_email', true );

    foreach ( $tickets as $ticket ) {
      if ( $ticket->type != 'ticket' ) continue;
      $event_id = $ticket->event_id;
      $attachment_content_switch = true;
      if ( metadata_exists( 'post', $event_id, 'eventer_event_ticket_email' ) ) {
        $ticket_email = get_post_meta( $event_id, 'eventer_event_ticket_email', true );
        if ( $ticket_email == 'on' ) {
          $attachment_content_switch = false;
        }
      }

      $ticketUrl = getTicketMeta( $ticket->id, 'ticket_url' );
      if ( !empty( $ticketUrl ) && @getimagesize( $ticketUrl ) && $attachment_content_switch ) {
        $ticketEmail = getTicketMeta( $ticket->id, 'email' );
        $ticketEmail = empty( $ticketEmail ) ? $registrant_email : $ticketEmail;
        $ticketsSend[ $ticketEmail ][] = getTicketMeta( $ticket->id, 'ticket_path' );
      } else {}
    }
    if ( empty( $emptyTickets ) && !empty( $ticketsSend ) ) {
      foreach ( $ticketsSend as $email => $ticket ) {
		$args = array( $email, $ticket );
		if (!wp_next_scheduled('sendTicketsEmail', $args)) {
			wp_schedule_single_event(time() + 1, 'sendTicketsEmail', $args);
		}
      }
    }
  }
	
}
$process = new RegistrationProcess();
