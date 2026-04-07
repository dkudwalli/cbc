<?php
class Bookings
{

  // class instance
  static $instance;

  // customer WP_List_Table object
  public $customers_obj;

  // class constructor
  public function __construct()
  {
    add_filter('set-screen-option', [__CLASS__, 'set_screen'], 10, 3);
    add_action('admin_menu', [$this, 'plugin_menu']);
    add_action('admin_menu', [$this, 'bookingDetails']);
	add_action('wp_ajax_eventer_export_bookings', [ $this, 'eventer_export_bookings' ] );

  }

  public function eventer_export_bookings() {
    global $wpdb;
    $tickets_table = $wpdb->prefix . "eventer_registration_tickets";
    $registrations_table = $wpdb->prefix . "eventer_registrations";
    $specific_event = isset($_REQUEST['eventer']) ? $_REQUEST['eventer'] : '';
    $start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
    $end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
    $event_date = isset($_REQUEST['event_date']) ? $_REQUEST['event_date'] : '';
    $bookings_list = isset($_REQUEST['multipleslect']) ? array_map('intval', $_REQUEST['multipleslect']) : [];

    $where = [];
    if ($specific_event != '') {
        $where[] = $wpdb->prepare("t.event_id = %d", $specific_event);
    }
    if (!empty($start_date)) {
        $where[] = $wpdb->prepare("r.reg_date >= %s", date('Y-m-d', strtotime($start_date)));
    }
    if (!empty($end_date)) {
        $where[] = $wpdb->prepare("r.reg_date <= %s", date('Y-m-d', strtotime($end_date)));
    }
    if (!empty($bookings_list)) {
        $where[] = "t.reg_id IN (" . implode(',', $bookings_list) . ")";
    }
    if (!empty($event_date)) {
        $where[] = $wpdb->prepare("t.ticket_date = %s", date('Y-m-d', strtotime($event_date)));
    }

    $where_sql = '';
    if (!empty($where)) {
        $where_sql = 'WHERE ' . implode(' AND ', $where);
    }

    $query = "
        SELECT t.*, r.reg_date
        FROM $tickets_table AS t
        INNER JOIN $registrations_table AS r ON t.reg_id = r.id
        $where_sql
    ";

    $wpdb->show_errors();
    $export_query = $wpdb->get_results($query, ARRAY_A);

    if (!$export_query) {
        $Error = $wpdb->print_error();
        die("The following error was found: $Error");
    } else {
        $array_data = [];
        foreach ($export_query as $data) {
            $result = getRegistration($data['reg_id'], 10, 1, 'id');
            if (!$result) {
                continue;
            }

            $final_data = get_object_vars($result);
            $final_data['user_name'] = getTicketMeta($data['id'], 'name');
            $final_data['user_email'] = getTicketMeta($data['id'], 'email');
            $final_data['qr_code'] = $data['id'];
            $registrants = @unserialize(getTicketMeta($data['id'], 'reg_details'));

            if (is_array($registrants)) {
                foreach ($registrants as $field_key => $value) {
                    $position1 = stripos($field_key, 'Registrant name');
                    $position2 = stripos($field_key, 'Registrant email');
                    if ($position1 !== false || $position2 !== false) {
                        unset($registrants[$field_key]);
                    }
                }
            }

            $final_data['registrants'] = $registrants;
            $final_data['ticket_name'] = isset($data['ticket_name']) ? $data['ticket_name'] : '';
            $final_data['ticket_status'] = isset($data['ticket_status']) ? $data['ticket_status'] : '';
            $array_data[] = $final_data;
        }

        $csv_fields = array(
            'booking id',
            'Status',
            'User ID',
            'order id',
            'Booking Date',
            'Payment Mode',
            'Amount',
            'Event Title',
            'Event Date',
            'QR ID',
            'Checked-in',
            'Name',
            'Email'
        );

        $dynamic_fields = eventer_get_settings('individual_registrant_fields');
        $fieldName = 'name';
        $pattern = '/\b' . preg_quote($fieldName, '/') . '="(.*?)"/';
        $custom_field_keys = [];

        if (preg_match_all($pattern, $dynamic_fields, $matches)) {
            $custom_field_keys = $matches[1];
        }

        if (isset($array_data[0]['registrants']) && !empty($array_data[0]['registrants'])) {
            foreach ($array_data[0]['registrants'] as $column => $value) {
                if ($column == 'email' || $column == 'name') {
                    continue;
                }
                $csv_fields[] = $column;
            }
        }

        $csv_fields[] = 'Phone';
        $csv_fields[] = 'Ticket name';

        $output_filename = 'eventer_booking_csv_' . date_i18n("Y-m-d_H-i-s") . ".csv";
        $output_handle = @fopen('php://output', 'w');

        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename=' . $output_filename);
        header('Expires: 0');
        header('Pragma: public');

        fputcsv($output_handle, $csv_fields);

        foreach ($array_data as $Result) {
            $woo_order = $Result['order_id'];
            $order = wc_get_order($woo_order);

            if (!$order) {
                continue;
            }

            $already_printed = false;
            foreach ($order->get_items() as $item_key => $item_values) {
                if ($already_printed) {
                    continue;
                }

                $data_set = array();
                $item_data = $item_values->get_data();
                $item_id = $item_values->get_id();
                $product_name = $item_data['name'];
                $eventer_id = wc_get_order_item_meta($item_id, '_wceventer_id', true);
                $eventer_date = wc_get_order_item_meta($item_id, '_wceventer_date', true);

                $data_set['booking id'] = $Result['id'];
                $data_set['status'] = $Result['reg_status'];
                $data_set['User ID'] = $Result['user_id'];
                $data_set['order id'] = $woo_order;
                $data_set['Booking Date'] = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($Result['reg_date']));
                $data_set['Payment Mode'] = $Result['paymentmode'];
                $data_set['Amount'] = $Result['reg_amount'];
                $data_set['Event Title'] = get_the_title($eventer_id);
                $data_set['Event Date'] = date_i18n(get_option('date_format'), strtotime($eventer_date));
                $data_set['QR ID'] = $Result['qr_code'];

                if ($Result['ticket_status'] == '10') {
                    $check_in_status = 'Yes';
                } else {
                    $check_in_status = 'No';
                }

                $data_set['Checked-in'] = $check_in_status;
                $data_set['Name'] = !empty($Result['user_name']) ? $Result['user_name'] : $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
                $data_set['Email'] = !empty($Result['user_email']) ? $Result['user_email'] : $order->get_billing_email();

                foreach ($custom_field_keys as $index => $key) {
                    if (in_array($key, $csv_fields)) {
                        $data_set[$key] = isset($Result['registrants'][$key]) ? $Result['registrants'][$key] : 'null';
                    } else {
                        $data_set[$key] = isset($Result['registrants'][$key]) ? $Result['registrants'][$key] : '';
                    }
                }

                $data_set['Phone'] = $order->get_billing_phone();
                $data_set['Ticket name'] = !empty($Result['ticket_name']) ? $Result['ticket_name'] : $product_name;

                fputcsv($output_handle, $data_set);
                $already_printed = true;
            }
        }

        fclose($output_handle);
        die();
    }
}

  public static function set_screen($status, $option, $value)
  {
    return $value;
  }

  public function plugin_menu()
  {
    $hook = add_menu_page(
      'Woo Bookings',
      'Woo Bookings',
      'manage_options',
      'eventer_bookings',
      [$this, 'plugin_settings_page']
    );

    add_action("load-$hook", [$this, 'screen_option']);
  }

  public function bookingDetails()
  {

    $hook = add_menu_page(
      'Booking Details',
      'Booking Details',
      'manage_options',
      'booking_details',
      [$this, 'bookingDetailsPage']
    );

    add_action("load-$hook", [$this, 'screen_option']);
  }

  /**
   * Screen options
   */
  public function screen_option()
  {

    $option = 'per_page';
    $args = [
      'label' => 'Bookings',
      'default' => 20,
      'option' => 'customers_per_page'
    ];

    add_screen_option($option, $args);

    $this->customers_obj = new RegistrationTable();
  }

  /**
   * Plugin settings page
   */
  public function plugin_settings_page()
  {
	$reg_details = $this->getBookings();

	?>
	<div class="wrap">
	  <h2>Eventer Bookings</h2>
	  <?php if (!empty($reg_details)) : ?>
		<form class="woo-csv-export-form" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post">
		<input type="hidden" name="action" value="eventer_export_bookings">
		<input type="hidden" name="date" value="<?php echo (isset($_REQUEST['booking'])) ? $_REQUEST['booking'] : ''; ?>">
		<input type="hidden" name="eventer" value="<?php echo (isset($_REQUEST['eventer'])) ? $_REQUEST['eventer'] : ''; ?>">
		<input type="hidden" name="eventer_all" value="<?php echo (isset($_REQUEST['eventer_id'])) ? $_REQUEST['eventer_id'] : ''; ?>">
		<input type="submit" value="<?php esc_html_e('Download csv', 'eventer'); ?>" class="button eventer-download-bulk-downloading">
		</form>
	  <?php endif; ?>

	  <div id="poststuff">
		<div id="post-body" class="metabox-holder">
		  <div id="post-body-content">
			<div class="meta-box-sortables ui-sortable">
			  <form class="form-eventer_bookings" action="<?php echo esc_url(admin_url('admin.php?page=eventer_bookings')); ?>" method="post">
				<?php
					$this->customers_obj->prepare_items();
					$this->customers_obj->display(); ?>
			  </form>
			</div>
		  </div>
		</div>
		<br class="clear">
	  </div>
	</div>
<?php
  }

  public function getBookings($per_page = 20, $page_number = 1) {
    global $wpdb;
    $tickets_table = $wpdb->prefix . "eventer_registration_tickets";
    $registrations_table = $wpdb->prefix . "eventer_registrations";
    $specific_event = isset($_REQUEST['eventer']) ? $_REQUEST['eventer'] : '';
    $bookings = isset($_REQUEST['multipleslect']) ? $_REQUEST['multipleslect'] : [];
    $start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
    $end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
	$event_date = isset($_REQUEST['event_date']) ? $_REQUEST['event_date'] : '';
    $bookings_list = [];

    if ($bookings) {
        foreach ($bookings as $id) {
            if (!intval($id)) {
                continue;
            }
            $bookings_list[] = $id;
        }
    }

    $where = [];
    if ($specific_event != '') {
        $where[] = $wpdb->prepare("t.event_id = %d", $specific_event);
    }
    if (!empty($bookings_list)) {
        $where[] = "t.reg_id IN (" . implode(',', array_map('intval', $bookings_list)) . ")";
    }
    if (!empty($start_date)) {
        $where[] = $wpdb->prepare("r.reg_date >= %s", date('Y-m-d', strtotime($start_date)));
    }
    if (!empty($end_date)) {
        $where[] = $wpdb->prepare("r.reg_date <= %s", date('Y-m-d', strtotime($end_date)));
    }
    if (!empty($event_date)) {
        $where[] = $wpdb->prepare("t.ticket_date = %s", date('Y-m-d', strtotime($event_date)));
    }

    $where_sql = '';
    if (!empty($where)) {
        $where_sql = 'WHERE ' . implode(' AND ', $where);
    }

    $offset = ($page_number - 1) * $per_page;

    $query = "
        SELECT t.*, r.reg_date
        FROM $tickets_table AS t
        INNER JOIN $registrations_table AS r ON t.reg_id = r.id
        $where_sql
        ORDER BY r.reg_date DESC
        LIMIT $offset, $per_page
    ";

    $wpdb->show_errors();
    $results = $wpdb->get_results($query, ARRAY_A);

    if (!$results) {
        return [];
    } else {
        $array_data = [];
        foreach ($results as $key => $data) {
            $result = getRegistration($data['reg_id'], 10, 1, 'id');
            if (!$result) {
                continue;
            }
            $array_data[] = get_object_vars($result);
        }
    }

    return $array_data;
}


  /**
   * Plugin settings page
   */
  public function bookingDetailsPage()
  {
    $bookingId = isset($_REQUEST['booking_id']) ? $_REQUEST['booking_id'] : '';
    $bookingDetails = getRegistration($bookingId);
    $bookingTickets = getRegistrationTickets($bookingId);
    eventer_append_template_with_arguments('eventers/admin/booking', "details", ['booking' => (array) $bookingDetails, 'tickets' => $bookingTickets]);
  }

  /** Singleton instance */
  public static function get_instance()
  {
    if (!isset(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }
}
add_action('plugins_loaded', function () {
  Bookings::get_instance();
});
