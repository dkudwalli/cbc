<?php
if (!class_exists("WP_List_Table")) {
    require_once ABSPATH . "wp-admin/includes/class-wp-list-table.php";
}

class RegistrationTable extends WP_List_Table
{
    /** Class constructor */
    public function __construct()
    {
        parent::__construct([
            "singular" => __("Booking", "eventer"), // Singular name of the listed records
            "plural" => __("Bookings", "eventer"),  // Plural name of the listed records
            "ajax" => false,                        // Should this table support ajax?
        ]);
    }

    /**
     * Extra table navigation for filters
     *
     * @param string $which
     */
    function extra_tablenav($which)
    {
        if ($which == "top") {
            $specific_event = isset($_REQUEST["eventer"]) ? absint($_REQUEST["eventer"]) : 0;
            $start_date = isset($_REQUEST["start_date"]) ? sanitize_text_field(wp_unslash($_REQUEST["start_date"])) : "";
            $end_date = isset($_REQUEST["end_date"]) ? sanitize_text_field(wp_unslash($_REQUEST["end_date"])) : "";
			$event_date = isset($_REQUEST["event_date"]) ? sanitize_text_field(wp_unslash($_REQUEST["event_date"])) : "";

            ?>
            <form method="get" class="form-eventer_bookings">
                <div class="alignleft actions">
                    <label class="screen-reader-text" for="cat"><?php esc_html_e("Filter by event", "eventer"); ?></label>
                    <select name="eventer" class="postform">
                        <option value=""><?php esc_html_e("All Events", "eventer"); ?></option>
                        <?php
                        $all_registered_events = [];
                        $event_arg = [
                            "post_type" => "eventer",
                            "posts_per_page" => -1,
                            "orderby" => "name",
                            "order" => "ASC",
                        ];
                        $event_list = new WP_Query($event_arg);
                        if ($event_list->have_posts()):
                            while ($event_list->have_posts()):
                                $event_list->the_post();
                                $all_registered_events[get_the_ID()] = get_the_title();
                            endwhile;
                        endif;
                        wp_reset_postdata();

                        foreach ($all_registered_events as $key => $value) {
                            $selected = $specific_event === absint($key) ? "selected" : "";
                            echo "<option " . $selected . ' value="' . absint($key) . '">' . esc_html($value) . "</option>";
                        }
                        ?>
                    </select>
                	<input type="text" class="eventer-bookings-date" name="event_date" value="<?php echo esc_attr($event_date); ?>" placeholder="<?php esc_html_e("Event Date", "eventer"); ?>">
                    <input type="text" class="eventer-bookings-date" name="start_date" value="<?php echo esc_attr($start_date); ?>" placeholder="<?php esc_html_e("Bookings Start Date", "eventer"); ?>">
					<input type="text" class="eventer-bookings-date" name="end_date" value="<?php echo esc_attr($end_date); ?>" placeholder="<?php esc_html_e("Bookings End Date", "eventer"); ?>">
					<input type="hidden" name="filter_eventer_bookings" value="1">
					<input type="submit" class="button button-primary" id="filter_eventer_bookings" value="Filter">
					<input type="button" class="button" id="reset_eventer_filters" value="Reset">
					<?php
					// Include other query parameters to maintain context
					foreach ($_GET as $key => $value) {
                        if (!in_array($key, ['eventer', 'start_date', 'end_date', 'event_date', 'filter_eventer_bookings'], true)) {
                            echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
                        }
                    }
                    ?>
                </div>
            </form>
            <?php
        }
    }

    /**
     * Retrieve customer’s data from the database
     *
     * @param int $per_page
     * @param int $page_number
     * @return mixed
     */
    public static function get_customers($per_page = 10, $page_number = 1)
    {
        $offset = ($page_number - 1) * $per_page;
        $result = getRegistration(null, $per_page, $offset);
        return $result;
    }

    /**
     * Retrieve filtered customer’s data by event ID
     *
     * @param int $event_id
     * @return array
     */
    public static function get_filter_customers($event_id)
    {
        $ticket = getRegistrationTickets($event_id, 10, 0, "event_id");

        if (!$ticket) {
            return [];
        }

        $array = [];
        foreach ($ticket as $key => $data) {
            $result = getRegistration($data->reg_id, 10, 1, "id");
            if (!$result) {
                continue;
            }

            $array[] = get_object_vars($result);
        }

        return $array;
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "eventer_registration_tickets";
        $registration_table = $wpdb->prefix . "eventer_registrations";
        $specific_event = isset($_REQUEST["eventer"]) ? absint($_REQUEST["eventer"]) : 0;
        $start_date = isset($_REQUEST["start_date"]) ? sanitize_text_field(wp_unslash($_REQUEST["start_date"])) : "";
        $end_date = isset($_REQUEST["end_date"]) ? sanitize_text_field(wp_unslash($_REQUEST["end_date"])) : "";

        $where = [];
        if ($specific_event > 0) {
            $where[] = $wpdb->prepare("t.event_id = %d", $specific_event);
        }
        if (!empty($start_date)) {
            $where[] = $wpdb->prepare("r.reg_date >= %s", date("Y-m-d", strtotime($start_date)));
        }
        if (!empty($end_date)) {
            $where[] = $wpdb->prepare("r.reg_date <= %s", date("Y-m-d", strtotime($end_date)));
        }

        $where_sql = '';
        if (!empty($where)) {
            $where_sql = 'WHERE ' . implode(' AND ', $where);
        }

        $query = "
            SELECT COUNT(*)
            FROM $table_name AS t 
            INNER JOIN $registration_table AS r 
            ON t.reg_id = r.id 
            $where_sql
        ";

        return $wpdb->get_var($query);
    }

    /**
     * Retrieve bookings from the database with pagination
     *
     * @param int $per_page
     * @param int $page_number
     * @return array
     */
    public static function getBookings($per_page = 20, $page_number = 1)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "eventer_registration_tickets";
        $registration_table = $wpdb->prefix . "eventer_registrations";
        $specific_event = isset($_REQUEST["eventer"]) ? absint($_REQUEST["eventer"]) : 0;
        $start_date = isset($_REQUEST["start_date"]) ? sanitize_text_field(wp_unslash($_REQUEST["start_date"])) : "";
        $end_date = isset($_REQUEST["end_date"]) ? sanitize_text_field(wp_unslash($_REQUEST["end_date"])) : "";

        $offset = ($page_number - 1) * $per_page;

        $where = [];
        if ($specific_event > 0) {
            $where[] = $wpdb->prepare("t.event_id = %d", $specific_event);
        }
        if (!empty($start_date)) {
            $where[] = $wpdb->prepare("r.reg_date >= %s", date("Y-m-d", strtotime($start_date)));
        }
        if (!empty($end_date)) {
            $where[] = $wpdb->prepare("r.reg_date <= %s", date("Y-m-d", strtotime($end_date)));
        }

        $where_sql = '';
        if (!empty($where)) {
            $where_sql = 'WHERE ' . implode(' AND ', $where);
        }

        $query = "
            SELECT t.*, r.reg_date 
            FROM $table_name AS t 
            INNER JOIN $registration_table AS r 
            ON t.reg_id = r.id 
            $where_sql
            ORDER BY r.reg_date DESC
            LIMIT $offset, $per_page
        ";

        $export_query = $wpdb->get_results($query, ARRAY_A);

        if (!$export_query) {
            return [];
        } else {
            $array_data = [];
            foreach ($export_query as $key => $data) {
                if (in_array($data["reg_id"], array_column($array_data, "id"))) {
                    continue;
                }

                $result = getRegistration($data["reg_id"], 10, 1, "id");
                if (!$result) {
                    continue;
                }

                $array_data[] = get_object_vars($result);
            }
        }

        return $array_data;
    }

    /** Text displayed when no customer data is available */
    public function no_items()
    {
        _e("No bookings available.", "eventer");
    }

    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     * @return string
     */
    function column_name($item)
    {
        // Create a nonce
        $delete_nonce = wp_create_nonce("sp_delete_customer");

        $title = "<strong>" . $item["id"] . "</strong>";

        $actions = [
            "delete" => sprintf(
                '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>',
                esc_attr($_REQUEST["page"]),
                "delete",
                absint($item["id"]),
                $delete_nonce
            ),
        ];

        return $title . $this->row_actions($actions);
    }

    /**
     * Render a column when no column specific method exists.
     *
     * @param array $item
     * @param string $column_name
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case "order_id":
                return '<a href="' .
                    esc_url(
                        get_admin_url() .
                            "admin.php?page=booking_details&booking_id=" .
                            $item["id"]
                    ) .
                    '">' .
                    $item["order_id"] .
                    "</a>";
            case "date":
                return $item["reg_date"];
            case "mode":
                return $item["paymentmode"];
            case "status":
                return $item["reg_status"];
            case "amount":
                return $item["reg_amount"];
            case 'event_name':
                return $this->eventer_get_registrant_data($item['id'], 'event_name');
            case 'event_date':
                return $this->eventer_get_registrant_data($item['id'], 'event_date');
            case 'reg_name':
                return $this->eventer_get_registrant_data($item['id'], 'name');
            case 'reg_email':
                return $this->eventer_get_registrant_data($item['id'], $column_name);
            case 'check_in':
                return $this->eventer_get_registrant_data($item['id'], 'check_in');
            default:
                return '';
        }
    }

    /**
     * Get registrant data
     *
     * @param int $bookingId
     * @param string $field
     * @return string
     */
    function eventer_get_registrant_data($bookingId, $field)
    {
        $bookingDetails = getRegistration($bookingId);
        $tickets = getRegistrationTickets($bookingId);
        if (empty($tickets)) {
            return '';
        }

        $return = [];
        foreach ($tickets as $ticket)
        {
            if (empty($ticket))
            {
                continue;
            }

            $reg_details = getTicketMeta($ticket->id, 'reg_details');

            $check_in_status = ($ticket->ticket_status == '10') ? 'Yes' : 'No';

            if ($field == 'name')
            {
                $reg_name = $ticket->user_name;
            }
            else if ($field == 'reg_email')
            {
                $reg_name = getTicketMeta($ticket->id, 'email');
            }
            else if ($field == 'check_in')
            {
                $reg_name = $check_in_status;
            }
            else if ($field == 'event_name')
            {
                $return[] = isset($ticket->event_id) ? get_the_title($ticket->event_id) : '';
                break;
            }
            else if ($field == 'event_date')
            {
               $return[] = $ticket->ticket_date;
                break;
            }

            if ($reg_details)
            {
                $reg_details = eventer_decode_array_payload($reg_details);

                if (isset($reg_details[$field]))
                {
                    $reg_name = $reg_details[$field];
                }
            }

            $return[] = $reg_name;
        }

        return implode('<br />', $return);
    }
    
    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     * @return string
     */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />',
            $item["id"]
        );
    }

    /**
     * Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        $columns = [
            "cb" => '<input type="checkbox" />',
            "order_id" => __("Order", "eventer"),
            "date" => __("Booking Date", "eventer"),
            "event_name" => __("Event", "eventer"),
            "event_date" => __("Event Date", "eventer"),
            "mode" => __("Payment Mode", "eventer"),
            "status" => __("Payment Status", "eventer"),
            "amount" => __("Total Amount", "eventer"),
            'reg_name' => __('Reg Name', 'eventer'),
            'reg_email' => __('Reg Email', 'eventer'),
            'check_in' => __('Check-In', 'eventer')
        ];

        return $columns;
    }

    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        $sortable_columns = [
            "date" => ["Date", true],
        ];

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions()
    {
        $actions = [
            "bulk-delete" => "Delete",
        ];

        return $actions;
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page = $this->get_items_per_page('customers_per_page', 20);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        // Set pagination args
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ]);

        // Retrieve the items for the current page
        $this->items = self::getBookings($per_page, $current_page);
    }

    /**
     * Process bulk actions
     */
    public function process_bulk_action()
    {
        // Detect when a bulk action is being triggered...
        if ("delete" === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST["_wpnonce"]);

            if (!wp_verify_nonce($nonce, "sp_delete_customer")) {
                die("Go get a life script kiddies");
            } else {
                self::delete_customer(absint($_GET["customer"]));

                wp_redirect(esc_url(add_query_arg()));
                exit();
            }
        }

        if ("bulk-delete" === $this->current_action()) {
            $delete_ids = esc_sql($_POST["bulk-delete"]);
            if (!is_array($delete_ids)) {
                $delete_ids = [$delete_ids];
            }

            foreach ((array) $delete_ids as $id) {
                self::delete_customer($id);
            }

            wp_redirect(esc_url(add_query_arg()));
            exit();
        }
    }

    /**
     * Delete customer record from the database
     *
     * @param int $id
     */
    public function delete_customer($id)
    {
        global $wpdb;

        // Delete the record from the table
        $table_name = $wpdb->prefix . "eventer_registrations";

        $registration = getRegistration($id);
        $registration_meta = getRegistrationMeta($id, "reg_id");

        $meta_table_name = $wpdb->prefix . "eventer_registration_meta";
        $wpdb->delete($meta_table_name, ["reg_id" => $id]);

        $tickets_table_name = $wpdb->prefix . "eventer_registration_tickets";
        $ticket_ids = $wpdb->get_col(
            "SELECT id FROM $tickets_table_name WHERE reg_id = $id"
        );

        if (!empty($ticket_ids)) {
            $ticket_meta_table_name = $wpdb->prefix . "eventer_ticket_meta";
            foreach ($ticket_ids as $ticket) {
                $wpdb->delete($ticket_meta_table_name, [
                    "ticket_id" => $ticket,
                ]);
            }

            $wpdb->delete($tickets_table_name, ["reg_id" => $id]);
        }

        // Delete record from eventer_registrations
        $registrations_table_name = $wpdb->prefix . "eventer_registrations";
        $wpdb->delete($registrations_table_name, ["id" => $id]);
    }
}
?>
