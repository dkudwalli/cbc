<?php
if (!function_exists('wceventer_enqueue_scripts')) {
  function wceventer_enqueue_scripts()
  {
    $theme_info = wp_get_theme();
    wp_enqueue_script('eventer-woocommerce-scripts', EVENTER__PLUGIN_URL . 'WC/wc_scripts.js', array(), $theme_info->get('Version'), false);
  }
  add_action('wp_enqueue_scripts', 'wceventer_enqueue_scripts');
}
function eventer_add_product_to_cart()
{
  global $woocommerce;
  $product_id = (isset($_REQUEST['product'])) ? $_REQUEST['product'] : '';
  if (!has_term('eventer', 'product_cat', $product_id)) return;
  if (get_post_type($product_id) != 'product') wp_die();
  $tickets = (isset($_REQUEST['tickets'])) ? $_REQUEST['tickets'] : '';
	$ticketName = (isset($_REQUEST['ticketname'])) ? $_REQUEST['ticketname'] : '';
  $eventer_id = (isset($_REQUEST['ticket_id'])) ? $_REQUEST['ticket_id'] : '';
  $event_date = (isset($_REQUEST['event_date'])) ? $_REQUEST['event_date'] : '';
  $event_date_multi = (isset($_REQUEST['event_multi'])) ? $_REQUEST['event_multi'] : '';
  $event_time = (isset($_REQUEST['event_time'])) ? $_REQUEST['event_time'] : '';
  $event_time_slot = (isset($_REQUEST['event_slot'])) ? $_REQUEST['event_slot'] : '';
  $event_time = ($event_time_slot != '' && $event_time_slot != '00:00:00') ? date_i18n(get_option('time_format'), strtotime($event_time_slot)) : $event_time;
  $event_time_slot_title = (isset($_REQUEST['event_slot_title'])) ? $_REQUEST['event_slot_title'] : '';
  $event_allday = (isset($_REQUEST['event_allday'])) ? $_REQUEST['event_allday'] : '';
  $event_url = (isset($_REQUEST['event_url'])) ? $_REQUEST['event_url'] : '';
  $ticket_price = (isset($_REQUEST['ticket_price'])) ? $_REQUEST['ticket_price'] : '';
  $cart_item_data = array('wceventer_name' => apply_filters('eventer_raw_event_title', '', $eventer_id), 'wceventer_id' => $eventer_id, 'wceventer_date' => $event_date, 'wceventer_time' => $event_time, 'wceventer_url' => $event_url, 'eventer_custom_price' => $ticket_price, 'eventer_ticket_name' => $ticketName, 'wceventer_product' => 'ticket', 'wceventer_allday' => $event_allday, 'wceventer_slot' => $event_time_slot, 'wceventer_slot_title' => $event_time_slot_title, 'wceventer_multi' => $event_date_multi);
  foreach ($woocommerce->cart->get_cart() as $key => $item) {
    $item_id = $item['wceventer_id'];
    $cart_product_id = $item['product_id']; // the product ID
    if ($eventer_id == $item_id && $product_id == $cart_product_id) {
      $woocommerce->cart->remove_cart_item($key);
    }
  }
  //$cart_item_data = array('price' => $_REQUEST['product_with_services_cost']);
  WC()->cart->add_to_cart($product_id, $tickets, '', array(), $cart_item_data);
  wp_die();
}
add_action('wp_ajax_eventer_add_product_to_cart', 'eventer_add_product_to_cart');
add_action('wp_ajax_nopriv_eventer_add_product_to_cart', 'eventer_add_product_to_cart');

add_action('woocommerce_before_calculate_totals', 'eventer_add_custom_price');

function eventer_add_custom_price($cart)
{
  foreach ($cart->cart_contents as $key => $value) {
    if (isset($value['_eventer_custom_title']) && $value['_eventer_custom_title'] != '') {
      $value['data']->set_name($value['_eventer_custom_title']);
    }
    if (!isset($value['eventer_custom_price'])) continue;
    $value['data']->set_price((!isset($value['eventer_custom_price'])) ? $value['price'] : $value['eventer_custom_price']);
  }
}

function eventer_custom_pre_get_posts_query($q)
{
  $tax_query = (array) $q->get('tax_query');
  $tax_query[] = array(
    'taxonomy' => 'product_cat',
    'field' => 'slug',
    'terms' => array('eventer', 'eventer_services'), // Don't display products in the eventer category on the shop page.
    'operator' => 'NOT IN'
  );
  if (in_array($q->get('post_type'), array('product'))) {
    $q->set('tax_query', $tax_query);
  }
}
//add_action( 'woocommerce_product_query', 'eventer_custom_pre_get_posts_query' );
//add_action( 'pre_get_posts' ,'eventer_custom_pre_get_posts_query' ); 


function eventer_add_custom_ticket_variation($item_data, $cart_item)
{
  if (!isset($cart_item['wceventer_name'])) {
    return $item_data;
  }
  $show_order_meta_date = (isset($cart_item['wceventer_date'])) ? date_i18n(get_option('date_format'), $cart_item['wceventer_date']) : '';
  $show_order_meta_allday = (isset($cart_item['wceventer_allday'])) ? $cart_item['wceventer_allday'] : '';
  $time = ($show_order_meta_allday != '') ? esc_html__('All day', 'eventer') : $cart_item['wceventer_time'];
  $multi_date = (isset($cart_item['wceventer_multi'])) ? $cart_item['wceventer_multi'] : '';
  $time_slot = (isset($cart_item['wceventer_slot'])) ? $cart_item['wceventer_slot'] : '';
  $time = ($time_slot != '' && $time_slot != '00:00:00') ? date_i18n(get_option('time_format'), strtotime($cart_item['wceventer_slot'])) : $time;
  $item_data[] = array(
    'key'     => esc_html__('Event', 'eventer'),
    'value'   => wc_clean($cart_item['wceventer_name']),
    'display' => '',
  );
  if ($multi_date) {
    $date_all = explode('-', $multi_date);
    $date_start = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $date_all[0]);
    $date_end = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $date_all[1]);
  }
  $cart_date_show = ($multi_date != '') ? $date_start . '-' . $date_end : $show_order_meta_date . ' ' . $time;
  $item_data[] = array(
    'key'     => esc_html__('Event Date', 'eventer'),
    'value'   => $cart_date_show,
    'display' => '',
  );
  if (isset($cart_item['wceventer_services'])) {
    $item_data[] = array(
      'key'     => esc_html__('Services', 'eventer'),
      'value'   => wc_clean($cart_item['wceventer_services']),
      'display' => '',
    );
  }
  return $item_data;
}
add_filter('woocommerce_get_item_data', 'eventer_add_custom_ticket_variation', 10, 2);
add_filter('woocommerce_cart_item_name', 'eventer_add_ticket_custom_option_from_session_into_cart', 1, 3);
add_filter('woocommerce_order_item_name', 'eventer_add_ticket_custom_option_from_session_into_cart', 999, 3);
if (!function_exists('eventer_add_ticket_custom_option_from_session_into_cart')) {
  function eventer_add_ticket_custom_option_from_session_into_cart($product_name, $values, $cart_item_key)
  {
    if ((!has_term('eventer', 'product_cat', $values['product_id']) && !has_term('eventer_services', 'product_cat', $values['product_id'])) || is_page(array('checkout'))) return $product_name;
    if (isset($values['_eventer_custom_title']) && $values['_eventer_custom_title'] != '') {
      return $values['_eventer_custom_title'];
    } else {
      return get_the_title($values['product_id']);
    }
  }
}
add_action('woocommerce_checkout_order_created', 'eventer_new_order_send_emails', 99, 1);
add_action('woocommerce_order_status_processing', 'eventer_processing_order_send_emails', 99, 1 );
add_action('woocommerce_order_status_completed', 'eventer_payment_confirmation_send_emails', 99, 1 );
function eventer_new_order_send_emails( $order ) {
  eventer_normalize_pre_send_emails( $order->get_id(), 'before_payment' );
}

function eventer_processing_order_send_emails( $order_id ) {
  eventer_normalize_pre_send_emails( $order_id, 'before_payment' );
}

function eventer_payment_confirmation_send_emails( $order_id ) {
  eventer_normalize_pre_send_emails( $order_id, 'after_payment' );
}

function eventer_normalize_pre_send_emails( $order_id, $payment ) {

  $registration_process = new RegistrationProcess();
  $registration_process->eventerWooRegister( $order_id );

  $reg_id = get_post_meta( $order_id, 'eventer_order_recorded', true );
  $reg_object = getRegistration( $reg_id );
  $tickets = getRegistrationTickets( $reg_id );

  $content = eventer_get_woo_email_content( $order_id, $reg_object, $tickets, $payment );
  eventer_send_emails( $reg_object, $content, $payment );
}

function eventer_send_emails( $reg_object, $content, $payment ) {
  $reg_user_email = getRegistrationMeta( $reg_object->id, 'user_email' );

  $sender = eventer_get_settings( 'email_from_address' ) ? eventer_get_settings( 'email_from_address' ) : get_option('admin_email');

  $sender_name = eventer_get_settings( 'email_from_name' ) ? eventer_get_settings( 'email_from_name' ) : get_bloginfo('name');

  $headers[] = 'From: ' . $sender_name . ' <' . $sender . '>';
  $headers[] = "MIME-Version: 1.0" . "\r\n";
  $headers[] = "Content-Type: text/html; charset=" . get_bloginfo('charset') . "" . "\r\n";

  $content = wpautop($content);
  $subject = esc_html__('Ticket Pre Payment Email', 'eventer');
  $registration_content_switch = eventer_get_settings('pre_registration_content_switch');
  if ($payment == 'after_payment') {
    $subject = esc_html__('Payment Confirmation email', 'eventer');
    $registration_content_switch = eventer_get_settings('payment_confirmation_content_switch');
  }

  if( $registration_content_switch == '0' ) return;

  $email_status = send_eventer_custom_email($sender, $subject, $content, $headers);
  if ($email_status) {
    return 1;
  } else {
    return 0;
  }
}

function eventer_get_woo_email_content( $order_id, $reg_object, $tickets, $type ) {
  
  $email_content = eventer_get_settings('pre_registration_content');

  if( $type == 'after_payment' ){
    $email_content = eventer_get_settings('payment_confirmation_content');
  }

  if( empty( $email_content ) ) return;

  $order = wc_get_order($order_id);
  $eventer_id = $event_date = '';
  $services = [];

  foreach ($order->get_items() as $item_key => $item ) {
    $eventer_id = wc_get_order_item_meta($item->get_id(), '_wceventer_id', true);
    $eventer_date = wc_get_order_item_meta($item->get_id(), '_wceventer_date', true);
    $item_data = $item->get_data();
    $product_name = $item_data['name'];
    $quantity = $item_data['quantity'];
    $services[$product_name] = array('name' => $product_name, 'quantity' => $quantity);
  }

  $event_services = [];
  $services_list = '';
  if ($services) {
    foreach ($services as $service) {
      $event_services['{' . $service['name'] . '}'] = $service['quantity'];
      if ($service['name'] == $service['quantity'] ) {
        $services_list .= '<p>' . $service['name'] . '</p>';
      } else {
        $services_list .= '<p>' . $service['name'] . ': ' . $service['quantity'] . '</p>';
      }
    }
  }

  $eventer_organizer = get_the_terms($eventer_id, 'eventer-organizer');
  $eventer_venue = get_the_terms($eventer_id, 'eventer-venue');
  if (!is_wp_error($eventer_venue) && !empty($eventer_venue)) {
    foreach ($eventer_venue as $venue) {
      $location_address = get_term_meta($venue->term_id, 'venue_address', true);
      $elocation = ($location_address != '') ? $location_address : $venue->name;
    }
  }
  if (!is_wp_error($eventer_organizer) && !empty($eventer_organizer)) {
    foreach ($eventer_organizer as $organizer) {
      $organizer_name = $organizer->name;
      $organizer_email = get_term_meta($organizer->term_id, 'organizer_email', true);
      $organizer_phone = get_term_meta($organizer->term_id, 'organizer_phone', true);
      $organizer_website = get_term_meta($organizer->term_id, 'organizer_website', true);
    }
  }
  $event_start_date = get_post_meta($eventer_id, 'eventer_event_start_dt', true);
  $event_end_date = get_post_meta($eventer_id, 'eventer_event_end_dt', true);
  $start_date_string = strtotime($event_start_date);
  $end_date_string = strtotime($event_end_date);
  $eventer_date_formatted = date_i18n(get_option('date_format'), $start_date_string);
  if (date_i18n('Y-m-d', $start_date_string) != date_i18n('Y-m-d', $end_date_string)) {
    $eventer_date_formatted = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $start_date_string) . ' - ' . date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $end_date_string);
  }
  $eventer_time_formatted = date_i18n(get_option('time_format'), $start_date_string);
  $payment_status = $reg_object->reg_status;
  $user_fields_val = $registrant_tickets_name = $registrant_tickets_vals = array();
  $user_info = '';
  $vars = [];

  $tickets_name_numbers = '';
  $tickets_array = [];
  if ( ! empty( $tickets ) ) {
    $ticket_start = 1;

    foreach( $tickets as $index => $ticket ) {
      $tickets_array[$ticket->ticket_name] = $ticket->ticket_name . ' X ' . $services[$ticket->ticket_name]['quantity'] . '<br/>';
    
      $registrant_tickets_name['{ticket' . $ticket_start . '}'] = $ticket->ticket_name;
      $registrant_tickets_vals['{ticket_nos' . $ticket_start . '}'] = $services[$ticket->ticket_name]['quantity'];
      $vars['{registrant_name' . $ticket_start.'}'] = getTicketMeta($ticket->id, 'name');
      $vars['{registrant_email' . $ticket_start.'}'] = getTicketMeta($ticket->id, 'email');
      $ticket_start++;
    }
    $tickets_name_numbers = join( ' ', $tickets_array );
  }

    $reg_user_name = getRegistrationMeta( $reg_object->id, 'user_name' );
    $reg_user_email = getRegistrationMeta( $reg_object->id, 'user_email' );
    $registrant_time_slot = getRegistrationMeta( $reg_object->id, 'slot_title' );
    $registrant_id = $reg_object->id;
    $registrant_email = $reg_user_email;

    $user_fields_val['{Name}'] = $reg_user_name;
    $user_fields_val['{email}'] = $reg_user_email;
    $user_info .= '<p>Name: ' . esc_attr($reg_user_name) . '</p>';
    $user_info .= '<p>email: ' . esc_attr($reg_user_email) . '</p>';

  $paymentmode = $reg_object->paymentmode;

  $organizer = wp_get_object_terms($eventer_id, 'eventer-organizer');
  $organizer_email = $completed_url_tkt = $pending_url_tkt = $failed_url_tkt = '';
  
  $message_dynamic = $email_content;

  // Get the payment method
  $payment_method = $order->get_payment_method();
  // Check the payment method
  if ($payment_method == 'offline_payment') {
     remove_shortcode('eventer_free');
  } else {
    remove_shortcode('eventer_offline');
    remove_shortcode('eventer_free');
  }

  $content = do_shortcode($message_dynamic);
  $registration_content_new = preg_replace('#\[[^\]]+\]#', '', $content);

  //Generate unique number using registration ID
  //We here using $start =8 and $end = 9, so that user can see tickets when receiving this email
  $registration_unique_number = eventer_encode_security_registration($registrant_id, 8, 9);
  if ($payment_status == "Completed" || $payment_status == "completed") {
    $completed_url_tkt = eventer_generate_endpoint_url('edate', $eventer_date, get_permalink($eventer_id));
    $completed_url_tkt = add_query_arg(array('reg' => $registration_unique_number), $completed_url_tkt);
  }
  if ($payment_status == "Pending" || $payment_status == "pending") {
    $pending_url_tkt = 'pending';
  } elseif ($payment_status == "Failed" || $payment_status == "failed") {
    $failed_url_tkt = 'failed';
  }

  $amount = $order->get_total();
  $eventer_currency = eventer_get_currency_symbol(eventer_get_settings('eventer_paypal_currency'));
  //We are here changing provided codes to the dynamic data of registrants
  
    $vars['{services}']         = $services_list;
    $vars['{venue}']            = $elocation;
    $vars['{organizer_name}']   = $organizer_name;
    $vars['{organizer_email}']  = $organizer_email;
    $vars['{organizer_phone}']  = $organizer_phone;
    $vars['{organizer_website}']= $organizer_website;
    $vars['{reg_id}']           = esc_attr($registrant_id);
    $vars['{tx_id}']            = $order->get_transaction_id();
    $vars['{pmt_st}']           = esc_attr($payment_status);
    $vars['{reg_email}']        = $registrant_email;
    $vars['{amt_pd}']           = esc_attr($eventer_currency . $amount);
    $vars['{evt_date}']         = esc_attr($eventer_date_formatted);
    $vars['{evt_time}']         = esc_attr($eventer_time_formatted);
    $vars['{evt_title}']        = apply_filters('eventer_raw_event_title', '', $eventer_id);
    $vars['{evt_url}']          = esc_url(eventer_generate_endpoint_url('edate', $eventer_date, get_permalink($eventer_id)));
    $vars['{event_additional_info}'] = wpautop(get_post_meta($eventer_id, 'eventer_event_email_additional_info', true));
    $vars['{tkt}']              = $tickets_name_numbers;
    $vars['{time_slot_title}']  = $registrant_time_slot;
    $vars['{user_details}']     = $user_info;
    $vars['{completed}']        = $completed_url_tkt;
    $vars['{pending}']          = $pending_url_tkt;
    $vars['{failed}']           = $failed_url_tkt;
  $new_vars = array_merge($vars, $user_fields_val, $registrant_tickets_name, $registrant_tickets_vals);
  $message = strtr($registration_content_new, $new_vars);
  $start = '{';
  $end = '}';
  $pattern = sprintf(
    '/%s(.+?)%s/ims',
    preg_quote($start, '/'),
    preg_quote($end, '/')
  );

  $match_found = '';
  if (preg_match_all($pattern, $message, $matches)) {
    $match_found = $matches[0];
  }
  if ($match_found) {
    foreach ($match_found as $match) {
      $message = str_replace($match, '', $message);
    }
  }
  $content = $message;
  return $content;
}

add_action('woocommerce_new_order_item', 'eventer_add_values_to_order_item_meta', 1, 3);
if (!function_exists('eventer_add_values_to_order_item_meta')) {
  function eventer_add_values_to_order_item_meta($item_id, $item, $order_id)
  {
    $order_item = new WC_Order_Item_Product($item_id);
    $product_id = $order_item->get_product_id();
    if (!has_term('eventer', 'product_cat', $product_id) && !has_term('eventer_services', 'product_cat', $product_id)) return;
    global $woocommerce, $wpdb;
    $eventer_name = (isset($item->legacy_values['wceventer_name'])) ? $item->legacy_values['wceventer_name'] : '';
    $wceventer_id = (isset($item->legacy_values['wceventer_id'])) ? $item->legacy_values['wceventer_id'] : '';
    $wceventer_date = (isset($item->legacy_values['wceventer_date'])) ? $item->legacy_values['wceventer_date'] : '';
    $wceventer_date_multi = (isset($item->legacy_values['wceventer_multi'])) ? $item->legacy_values['wceventer_multi'] : '';
    $wceventer_time = (isset($item->legacy_values['wceventer_time'])) ? $item->legacy_values['wceventer_time'] : '';
    $wceventer_time_slot = (isset($item->legacy_values['wceventer_slot'])) ? $item->legacy_values['wceventer_slot'] : '';
    $wceventer_time_slot_title = (isset($item->legacy_values['wceventer_slot_title'])) ? $item->legacy_values['wceventer_slot_title'] : '';
    $wceventer_ticket_id = (isset($item->legacy_values['ticket_id'])) ? $item->legacy_values['ticket_id'] : '';
    $wceventer_allday = (isset($item->legacy_values['wceventer_allday'])) ? $item->legacy_values['wceventer_allday'] : '';
    $wceventer_url = (isset($item->legacy_values['wceventer_url'])) ? $item->legacy_values['wceventer_url'] : '';
    $wceventer_ticket_name = (isset($item->legacy_values['eventer_ticket_name'])) ? $item->legacy_values['eventer_ticket_name'] : '';
    $wceventer_registrants = (isset($item->legacy_values['eventer_registrants'])) ? $item->legacy_values['eventer_registrants'] : '';
    $wceventer_services = (isset($item->legacy_values['wceventer_services'])) ? $item->legacy_values['wceventer_services'] : '';
    $wceventer_product = (isset($item->legacy_values['wceventer_product'])) ? $item->legacy_values['wceventer_product'] : '';
    $wceventer_product_title = (isset($item->legacy_values['_eventer_custom_title'])) ? $item->legacy_values['_eventer_custom_title'] : '';

    $wc_registrants = (isset($item->legacy_values['registrants'])) ? $item->legacy_values['registrants'] : '';

    if (!empty($eventer_name)) {
      wc_add_order_item_meta($item_id, 'Event name', $eventer_name);
    }
	  if (!empty($wceventer_ticket_name)) {
      wc_add_order_item_meta($item_id, '_eventer_custom_title', $wceventer_ticket_name);
    }
    if (!empty($wceventer_id)) {
      wc_add_order_item_meta($item_id, '_wceventer_id', $wceventer_id);
    }
    if (!empty($wceventer_date)) {
      $set_time = ($wceventer_allday != '') ? '' : $wceventer_time;
      $show_order_meta_date = date_i18n(get_option('date_format'), $wceventer_date);
      $save_order_meta_date = date_i18n('Y-m-d', $wceventer_date);
      wc_add_order_item_meta($item_id, 'Event Date', $show_order_meta_date . ' ' . $set_time);
      wc_add_order_item_meta($item_id, '_wceventer_date', $save_order_meta_date);
    }
    wc_add_order_item_meta($item_id, '_eventer_multi_date', $wceventer_date_multi);
    if (!empty($wceventer_url)) {
      wc_add_order_item_meta($item_id, 'Event URL', $wceventer_url);
    }
    if (!empty($wceventer_time_slot)) {
      wc_add_order_item_meta($item_id, '_wceventer_slot', $wceventer_time_slot);
      wc_add_order_item_meta($item_id, 'wceventer_slot_title', $wceventer_time_slot_title);
      wc_add_order_item_meta($item_id, '_ticket_id', $wceventer_ticket_id);
    }
    if (!empty($wceventer_allday)) {
      wc_add_order_item_meta($item_id, '_eventer_allday', $wceventer_allday);
    }
    if (!empty($wceventer_registrants)) {
      wc_add_order_item_meta($item_id, '_eventer_registrants', $wceventer_registrants);
    }
    if (!empty($wceventer_services)) {
      wc_add_order_item_meta($item_id, 'Services', $wceventer_services);
    }
    if (!empty($wceventer_product)) {
      wc_add_order_item_meta($item_id, '_eventer_product', $wceventer_product);
    }
    if (!empty($wceventer_product_title)) {
      wc_add_order_item_meta($item_id, '_eventer_custom_title', $wceventer_product_title);
    }

    if (!empty($wc_registrants)) {
      wc_add_order_item_meta( $item_id, '_wc_event_registrants', serialize( $wc_registrants ) );
    }
  }
}

add_action('woocommerce_before_cart_item_quantity_zero', 'eventer_remove_user_ticket_data_options_from_cart', 1, 1);


//add_filter('woocommerce_order_item_display_meta_key', 'change_order_item_meta_title', 20, 3);
/**
 * Changing a meta title
 * @param  string        $key  The meta key
 * @param  WC_Meta_Data  $meta The meta object
 * @param  WC_Order_Item $item The order item object
 * @return string        The title
 */
function change_order_item_meta_title($key, $meta, $item)
{

  // By using $meta-key we are sure we have the correct one.
  if ('Event Date' === $meta->key) {
    $key = 'SOMETHING';
  }

  return $key;
}
add_filter('woocommerce_order_item_display_meta_value', 'change_order_item_meta_value', 20, 3);
/**
 * Changing a meta value
 * @param  string        $value  The meta value
 * @param  WC_Meta_Data  $meta   The meta object
 * @param  WC_Order_Item $item   The order item object
 * @return string        The title
 */
function change_order_item_meta_value($value, $meta, $item)
{

  // By using $meta-key we are sure we have the correct one.
  if ('Event Date' === $meta->key) {
    $item_id = $item->get_id();
    $multi_date = wc_get_order_item_meta($item_id, '_eventer_multi_date', true);
    if ($multi_date != '') {
      $date_all = explode('-', $multi_date);
      $date_start = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $date_all[0]);
      $date_end = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $date_all[1]);
      $value = $date_start . '-' . $date_end;
    }
  }

  return $value;
}

if (!function_exists('eventer_remove_user_ticket_data_options_from_cart')) {
  function eventer_remove_user_ticket_data_options_from_cart($cart_item_key)
  {
    global $woocommerce;
    // Get cart
    $cart = $woocommerce->cart->get_cart();
    // For each item in cart, if item is upsell of deleted product, delete it
    foreach ($cart as $key => $values) {
      //if ( $values['_wceventer_id'] == $cart_item_key || $values['_wceventer_date'] == $cart_item_key)
      //unset( $woocommerce->cart->cart_contents[ $key ] );
    }
  }
}
add_action('woocommerce_checkout_order_processed', 'eventer_update_tickets', 10, 1);
//add_action('woocommerce_thankyou', 'eventer_update_tickets', 10, 1);
function eventer_update_tickets($order_id)
{
  if (!$order_id) return;
  $ticket_orders = (is_array(get_option('eventer_ticket_orders'))) ? get_option('eventer_ticket_orders') : array();
  if (in_array($order_id, $ticket_orders)) {
    return;
  }
  $order = wc_get_order($order_id);
  $update_new_val = $new_already_booked = $cart_items = array();
  foreach ($order->get_items() as $item_key => $item_values) :
    $new_already_booked = $update_new_val = array();
    $item_data = $item_values->get_data();
    $item_id = $item_values->get_id();
    $product_name = $item_data['name'];
    $product_id = $item_data['product_id'];
    if (!has_term('eventer', 'product_cat', $product_id) && !has_term('eventer_services', 'product_cat', $product_id)) continue;
    $quantity = $item_data['quantity'];
    $order_event_url = wc_get_order_item_meta($item_id, 'Event URL', true);
    $eventer_id = wc_get_order_item_meta($item_id, '_wceventer_id', true);
    $original_event = eventer_wpml_original_post_id($eventer_id);
    $eventer_date = wc_get_order_item_meta($item_id, '_wceventer_date', true);
    $eventer_time_slot = wc_get_order_item_meta($item_id, '_wceventer_slot', true);
    $eventer_time = wc_get_order_item_meta($item_id, 'Event Date', true);
    $eventer_time = $eventer_time_slot;
    $send_ticket_data = array('id' => intval($product_id) + intval($original_event), 'number' => $quantity);
    if (get_post_meta($eventer_id, 'eventer_common_ticket_count', true) != '') {
      $booked_tickets = eventer_update_date_wise_bookings_table($eventer_id, $eventer_date . ' ' . $eventer_time, array(), 2);
      if ($booked_tickets) {
        foreach ($booked_tickets as $get_ticket) {
          $all_tickets = (isset($get_ticket['pid'])) ? $get_ticket['pid'] : '';
          if ($all_tickets != '' && $all_tickets != $product_id) {
            $send_ticket_data_new = array('id' => intval($all_tickets) + intval($original_event), 'number' => $quantity);
            eventer_update_date_wise_bookings_table($eventer_id, $eventer_date . ' ' . $eventer_time, array($send_ticket_data_new), 3, 1);
          }
        }
      }
    }
    eventer_update_date_wise_bookings_table($eventer_id, $eventer_date . ' ' . $eventer_time, array($send_ticket_data), 3, 1);
  endforeach;
  $new_order_vals = array_unique(array_merge($ticket_orders, array($order_id)));
  update_option('eventer_ticket_orders', $new_order_vals);
}

function eventer_remove_editing_quantity($product_quantity, $cart_item_key)
{
  $cart_item = WC()->cart->cart_contents[$cart_item_key];
  $product_id = $cart_item['product_id'];
  if (!has_term('eventer', 'product_cat', $product_id) && !has_term('eventer_services', 'product_cat', $product_id)) return $product_quantity;
  $quantity = $cart_item['quantity'];
  return $quantity;
}
add_filter('woocommerce_cart_item_quantity', 'eventer_remove_editing_quantity', 10, 3);

function eventer_remove_permalink_thumb($image, $cart_item, $cart_item_key)
{
  $product_id = $cart_item['product_id'];
  $event_id = (isset($cart_item['wceventer_id'])) ? $cart_item['wceventer_id'] : '';
  if (!has_term('eventer', 'product_cat', $product_id) && !has_term('eventer_services', 'product_cat', $product_id)) return $image;
  return get_the_post_thumbnail($event_id);
}
add_filter('woocommerce_cart_item_thumbnail', 'eventer_remove_permalink_thumb', 10, 3);
function eventer_alter_ticket_thumbnail($product_get_permalink_cart_item, $cart_item, $cart_item_key)
{
  $product_id = $cart_item['product_id'];
  $event_url = (isset($cart_item['wceventer_url'])) ? $cart_item['wceventer_url'] : '';
  if (!has_term('eventer', 'product_cat', $product_id) && !has_term('eventer_services', 'product_cat', $product_id)) return $product_get_permalink_cart_item;
  return $event_url;
}
add_filter('woocommerce_cart_item_permalink', 'eventer_alter_ticket_thumbnail', 10, 3);
function eventer_change_item_key($cart_item_data, $product_id)
{
  if (!has_term('eventer', 'product_cat', $product_id) && has_term('eventer_services', 'product_cat', $product_id)) return $cart_item_data;
  $unique_cart_item_key = md5(microtime() . rand());
  $cart_item_data['unique_key'] = $unique_cart_item_key;
  return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'eventer_change_item_key', 10, 2);
add_filter('woocommerce_order_item_name', 'eventer_remove_hyperlink_from_order', 10, 2);
function eventer_remove_hyperlink_from_order($item_name, $item)
{
  if (!has_term('eventer', 'product_cat', $item['product_id']) && !has_term('eventer_services', 'product_cat', $item['product_id'])) return $item_name;
  $item_name = get_the_title($item['product_id']);
  return $item_name;
}
function eventer_add_meta_on_success($array)
{
  $order = $array['order'];
  if ($order->get_status() == "completed") {
    wp_add_inline_script('eventer-init', 'jQuery(".eventer-show-download-tickets-form").show();');
    $order_id = $order->get_id();
    $has_eventer = 0;

    foreach ($order->get_items() as $item_key => $item_values) :
      $item_data = $item_values->get_data();
      $product_id = $item_data['product_id'];
      if (!has_term('eventer', 'product_cat', $product_id)) continue;
      $has_eventer = 1;
    endforeach;
    if ($has_eventer == 0) return $array;

	$args = array($order_id);
	if (!wp_next_scheduled('eventer_woocommerce_generate_ticket', $args)) {
		wp_schedule_single_event(time() + 5, 'eventer_woocommerce_generate_ticket', $args);
	}
  }
  return $array;
}
add_filter('woocommerce_email_order_items_args', 'eventer_add_meta_on_success', 12, 1);

add_action('eventer_woocommerce_generate_ticket', 'eventer_woo_tickets_attachment', 10, 1);
function eventer_woo_tickets_attachment($order_id)
{
  $registrants = eventer_get_registrant_details('eventer', $order_id);
  echo apply_filters('eventer_status_changed_completed', $registrants);
}

function eventer_change_product_meta_key($display_key)
{
  if ($display_key == "Event Ticket") {
    $display_key = esc_html__('Event Ticket', 'eventer');
  } elseif ($display_key == "Event name") {
    $display_key = esc_html__('Event name', 'eventer');
  } elseif ($display_key == "Event Date") {
    $display_key = esc_html__('Event Date', 'eventer');
  } elseif ($display_key == "Event URL") {
    $display_key = esc_html__('Event URL', 'eventer');
  } elseif ($display_key == "Services") {
    $display_key = esc_html__('Services', 'eventer');
  }
  return $display_key;
};
add_filter('woocommerce_order_item_display_meta_key', 'eventer_change_product_meta_key', 10, 1);

//add_action('eventer_dashboard_bookings_tickets', 'eventer_create_booking_woocommerce', 20, 1);
//add_action('woocommerce_thankyou', 'eventer_show_thanks_page_download_button', 1);

function eventer_show_thanks_page_download_button($order_id)
{
  $order = wc_get_order($order_id);
  $download = '';
  foreach ($order->get_items() as $item_key => $item_values) :
    $item_id = $item_values->get_id();
    $eventer_product_type = wc_get_order_item_meta($item_id, '_eventer_product', true);
    if ($eventer_product_type == '') continue;
    $download = 1;
  endforeach;
  if ($download == 1) {
    $back_order_tickets = (isset($_REQUEST['backorder'])) ? wp_get_referer() : '';
    $registrant_uname = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
    $registrant_email = $order->get_billing_email();
    $default = array();
    $newTickets = $new_tickets = apply_filters('eventer_preapare_data_for_tickets', 'eventer', $order_id, array());
    $registrants = eventer_get_registrant_details('eventer', $order_id);
    $user_system = unserialize($registrants->user_system);
    $tickets = isset($user_system['tickets']) ? $user_system['tickets'] : [];
    if ($tickets) {
      $count = 0;
      foreach ($tickets as $index => $ticket) {
        $new_tickets['data-regpos'] = 14;
        $new_tickets['data-backorder'] = $back_order_tickets;
        $new_tickets['default']['data-eid'] = '';
        $new_tickets['default']['data-regpos'] = 14;
        $new_tickets['default']['data-registrant'] = $registrants->id;
        $ticket_info = $newTickets['individual'][0];
        $count++;
        $ticket_info['data-eventid'] = $ticket['event'];
        $ticket_info['data-eventname'] = get_the_title($ticket['event']);
        $ticket_info['data-ticket'] = $ticket['ticket'];
        $quantity = $ticket['quantity'];
        $qr_id = $ticket['id'];
        $ticket_info['data-qrcode'] = $registrants->id . '-' . $qr_id;
        $new_tickets['individual'] = [$ticket_info];
        for ($counter = 0; $counter < $quantity; $counter++) {
          do_action('eventer_ticket_raw_design', '', $new_tickets);
        }
      }
    }

    echo '<form action="' . esc_url(admin_url('admin-ajax.php')) . '" method="post" class="eventer-show-download-tickets-form" style="display:none;">';
    echo '<input type="hidden" name="action" value="eventer_woo_download_tickets">';
    echo '<input type="hidden" class="eventer-woo-tickets" name="tickets" value="">';
    echo '<input type="hidden" name="captcha" value="' . wp_create_nonce('eventer-tickets-download') . '">';
    echo '<input type="submit" value="' . esc_html__('Download Tickets', 'eventer') . '" class="button"></form><br/>';
  }
}
//add_action('woocommerce_checkout_order_processed', 'eventer_create_booking_woocommerce', 999);

function eventer_create_booking_woocommerce($order_id)
{
  return;
  if (empty(get_post_meta($order_id, 'eventer_order_recorded', true))) {
  	$order = wc_get_order($order_id);
    $registrant_uname = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
    $registrant_email = $order->get_billing_email();
    $event_ids = $registrants = $all_tickets = $all_services = $reg_details = $ticket_wise_registrants = array();
    foreach ($order->get_items() as $item_key => $item_values) :
      $item_data = $item_values->get_data();
      $item_id = $item_values->get_id();
      $product_id = $item_data['product_id'];
      $eventer_product_type = wc_get_order_item_meta($item_id, '_eventer_product', true);
      if ($eventer_product_type == '') continue;
      $event_date = wc_get_order_item_meta($item_id, '_wceventer_date', true);
      $event_time_slot = wc_get_order_item_meta($item_id, '_wceventer_slot', true);
      $event_time_slot_title = wc_get_order_item_meta($item_id, 'wceventer_slot_title', true);
      $event_id = wc_get_order_item_meta($item_id, '_wceventer_id', true);
      $event_allday = wc_get_order_item_meta($item_id, '_eventer_allday', true);
      $event_ids[$event_id . '-' . mt_rand()] = $event_date;
      $event_time = get_post_meta($event_id, 'eventer_event_start_dt', true);
      $event_time = date_i18n(get_option('time_format'), strtotime($event_time));
      $event_time = ($event_allday != '') ? esc_html__('All day', 'eventer') : $event_time;
      $event_registrants = wc_get_order_item_meta($item_id, '_eventer_registrants', true);
      $registrants[$event_id . '-' . mt_rand()] = $event_registrants;
      $event_date = strtotime($event_date);
      $product_name = $item_data['name'];
      $quantity = $item_data['quantity'];
      $all_tickets[] = array('name' => $product_name, 'quantity' => $quantity, 'number' => $quantity);
      $ticket_wise_registrants[] = array('event' => $event_id, 'date' => $event_date, 'type' => $eventer_product_type, 'ticket' => $product_name, 'quantity' => $quantity, 'registrants' => $event_registrants, 'time_slot' => $event_time_slot, 'slot_title' => $event_time_slot_title, 'id' => intval($product_id) + intval($event_id));
      if ($eventer_product_type != 'ticket') {
        $all_services[] = array('name' => $product_name, 'quantity' => $quantity);
      }
    endforeach;
    $current_date = date_i18n('Y-m-d G:i');
    $transID = $order->get_transaction_id();
    $payment_method = $order->get_payment_method();
    $ip = eventer_client_ip();
    $status = $order->get_status();
    $amount = $order->get_total();
    $user_reg_id = get_current_user_id();
    $user_system_data = serialize(array('ip' => $ip, 'services' => $all_services, 'tickets' => $ticket_wise_registrants, 'registrants' => $registrants, 'events' => $event_ids, 'time_slot' => $event_time_slot, 'slot_title' => $event_time_slot_title));
    $eventer_date = $event_date;
    global $wpdb;
    $table_name = $wpdb->prefix . "eventer_registrant";
    $wpdb->query(
      $wpdb->prepare(
        "INSERT INTO $table_name
          ( eventer, transaction_id , username, email, paymentmode, user_details, tickets, ctime, status, amount, user_system, user_id)
          VALUES ( %d, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s, %d )",
        array($order_id, $transID, $registrant_uname, $registrant_email, $payment_method, serialize($reg_details), serialize($all_tickets), $current_date, $status, $amount, $user_system_data, $user_reg_id)
      )
    );
    update_post_meta($order_id, 'eventer_order_recorded', $wpdb->insert_id);
	$args = array($order_id);
	if (!wp_next_scheduled('eventer_woocommerce_ticket_restore_auto', $args)) {
		wp_schedule_single_event(time() + 5, 'eventer_woocommerce_ticket_restore_auto', $args);
	}
  }
}

if (eventer_get_settings('eventer_woo_layout') != 'off') {
  add_action('woocommerce_widget_shopping_cart_buttons', function () {
    // Removing Buttons
    remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10);
    remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);

    // Adding customized Buttons
    add_action('woocommerce_widget_shopping_cart_buttons', 'eventer_custom_widget_shopping_cart_button_view_cart', 10);
    add_action('woocommerce_widget_shopping_cart_buttons', 'eventer_custom_widget_shopping_cart_button_view_cart', 20);
  }, 1);
}


// Custom cart button
function eventer_custom_widget_shopping_cart_button_view_cart()
{
  echo '';
}
add_action('woocommerce_order_status_changed', 'eventer_update_booking_status');
function eventer_update_booking_status($order_id)
{
  $order = wc_get_order($order_id);
  $status = $order->get_status();
  $registrant_id = get_post_meta($order_id, 'eventer_order_recorded', true);
  eventer_update_registrant_details(array('status' => $status), $registrant_id, array("%s", "%s"));
  //}
}
add_action('eventer_woocommerce_ticket_restore_auto', 'eventer_update_tickets_woocommerce', 10, 1);

function eventer_update_tickets_woocommerce($order_id)
{
  $order = wc_get_order($order_id);
  $status = $order->get_status();
  if ($status != 'completed') {
	$args = array($order_id, 'pendings', 'failed');
	if (!wp_next_scheduled('eventer_woocommerce_order_status_changed', $args)) {
		wp_schedule_single_event(time(), 'eventer_woocommerce_order_status_changed', $args);
	}
  }
}
//add_action('eventer_woocommerce_order_status_changed', 'eventer_status_changed_restore', 10, 3);
add_action('woocommerce_order_status_changed', 'eventer_status_changed_restore', 10, 3);
function eventer_status_changed_restore($order_id, $old_status, $new_status)
{
  if ($new_status == 'completed') {
    echo apply_filters('eventer_registrationv2_status_update', $order_id, 'wc_functions');
  }
  return;
  $registrants = eventer_get_registrant_details('eventer', $order_id);
  $user_system = unserialize($registrants->user_system);
  $tickets = (isset($user_system['tickets'])) ? $user_system['tickets'] : array();
  $tickets_restore = (isset($user_system['restore'])) ? $user_system['restore'] : '';
  if ($old_status == 'pending' && $tickets_restore == '') {
    $user_system['restore'] = 0;
    eventer_update_registrant_details(array('user_system' => serialize($user_system)), $registrants->id, array("%s", "%s"));
    return;
  }
  if (!empty($tickets) && $tickets_restore == 1 && $new_status == 'completed') {
    foreach ($tickets as $ticket) {
      if (!isset($ticket['id'])) break;
      $ticket_date = date_i18n('Y-m-d', $ticket['date']);
      $ticket_time = (isset($ticket['time_slot']) && $ticket['time_slot'] != '') ? $ticket['time_slot'] : '00:00:00';
      eventer_update_date_wise_bookings_table($ticket['event'], $ticket_date . ' ' . $ticket_time, array(array('id' => $ticket['id'], 'number' => $ticket['quantity'])), 3, 1);
    }
    $user_system['restore'] = 0;
    eventer_update_registrant_details(array('user_system' => serialize($user_system)), $registrants->id, array("%s", "%s"));
  } elseif ($tickets_restore != 1 && $new_status != 'completed') {
    foreach ($tickets as $ticket) {
      if (!isset($ticket['id'])) break;
      $ticket_date = date_i18n('Y-m-d', $ticket['date']);
      $ticket_time = (isset($ticket['time_slot']) && $ticket['time_slot'] != '') ? $ticket['time_slot'] : '00:00:00';
      eventer_update_date_wise_bookings_table($ticket['event'], $ticket_date . ' ' . $ticket_time, array(array('id' => $ticket['id'], 'number' => $ticket['quantity'])), 3, 2);
    }
    $user_system['restore'] = 1;
    eventer_update_registrant_details(array('user_system' => serialize($user_system)), $registrants->id, array("%s", "%s"));
  }
}

add_filter('woocommerce_checkout_get_value', 'eventer_modify_checkout_fields', 10, 2);
function eventer_modify_checkout_fields($value, $input)
{
  $token = (!empty($_GET['token'])) ? $_GET['token'] : '';
  $checkout_fields = array(
    'billing_first_name'    => ((isset($_COOKIE['woo_checkout_user_name'])) ? $_COOKIE['woo_checkout_user_name'] : ''),
    'billing_email'         => ((isset($_COOKIE['woo_checkout_user_email'])) ? $_COOKIE['woo_checkout_user_email'] : ''),
  );
  foreach ($checkout_fields as $key_field => $field_value) {
    if ($input == $key_field && !empty($field_value)) {
      $value = $field_value;
    }
  }
  return $value;
}

// Add support for the shortcode query filter
add_filter('woocommerce_shortcode_products_query', 'eventer_modify_shortcode_products_query', 10, 3);

function eventer_modify_shortcode_products_query($query_args, $atts, $loop) {
    // Define the terms to be removed
    $terms_to_remove = array('eventer', 'eventer_services');

    // Initialize an array to store the term IDs
    $term_ids_to_remove = array();

    // Loop through each term name and get the corresponding term ID
    foreach ($terms_to_remove as $term_name) {
        $term = get_term_by('name', $term_name, 'product_cat');
        if ($term) {
            $term_ids_to_remove[] = $term->term_id;
        }
    }

    if (!empty($term_ids_to_remove)) {
        // Check if tax_query already exists in query_args
        $existing_tax_query = isset($query_args['tax_query']) ? $query_args['tax_query'] : array();

        // Add a tax query to exclude products in the specified terms
        $additional_tax_query = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $term_ids_to_remove,
                'operator' => 'NOT IN',
            ),
        );

        // Merge the existing tax query with the additional tax query
        $query_args['tax_query'] = array_merge($existing_tax_query, $additional_tax_query);
    }

    return $query_args;
}

// Add support for the pre_get_posts hook
add_action('pre_get_posts', 'eventer_hide_eventer_category_products' );

function eventer_hide_eventer_category_products($q) {

    // Check if this is the main query and if it's for products
    if ( $q->is_main_query() ) {

        // Check if it's a product archive or taxonomy page
        if ( is_post_type_archive('product')
            || is_tax('product_cat') || in_array( $q->get('post_type'), array('product') )
        ) {
            // Define the category names
            $category_names = array('eventer', 'eventer_services');

            // Initialize an array to store the term IDs
            $category_ids = array();

            // Loop through each category name and get the corresponding term ID
            foreach ($category_names as $category_name) {
                $category = get_term_by('name', $category_name, 'product_cat');
                if ($category) {
                    $category_ids[] = $category->term_id;
                }
            }

            if (!empty($category_ids)) {
                // Check if tax_query already exists in $q
                $tax_query = $q->get('tax_query');

                if (empty($tax_query)) {
                    $tax_query = array(
                        'relation' => 'AND',
                    );
                }

                // Check if the tax query for the product category already exists
                $existing_tax_query = false;
                foreach ((array) $tax_query as $query ) {
                    if ( empty( $query ) || ! is_array($query ) ) {
                        continue;
                    }
                    
                    if ($query['taxonomy'] === 'product_cat' && $query['field'] === 'term_id') {
                        $existing_tax_query = true;
                        $query['terms'] = array_merge($query['terms'], $category_ids);
                        break;
                    }
                }

                // If tax_query doesn't exist, or if the product category tax query doesn't exist, add it
                if (!$existing_tax_query) {
                    $tax_query[] = array(
                        'taxonomy' => 'product_cat',
                        'field' => 'term_id',
                        'terms' => $category_ids,
                        'operator' => 'NOT IN',
                    );
                }

                $q->set('tax_query', $tax_query );
            }
        }
    }
}