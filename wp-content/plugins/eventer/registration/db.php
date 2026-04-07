<?php	
class RegistrationDB	
{	
  public function __construct()	
  {	
    add_action('admin_init', array($this, 'createRegistration'));	
    add_action('admin_init', array($this, 'createTickets'));	
    add_action('admin_init', array($this, 'createTicketsMeta'));	
    add_action('admin_init', array($this, 'createRegistrationMeta'));	
  }	
  public function createRegistration()	
  {	
    global $wpdb;	
    $table_name = $wpdb->prefix . "eventer_registrations";	
    $plugin_data = get_plugin_data(__FILE__);	
    $plugin_version = $plugin_data['Version'];	
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {	
      $charset_collate = $wpdb->get_charset_collate();	
      $sql = "CREATE TABLE $table_name (	
      id bigint NOT NULL AUTO_INCREMENT,	
      booking_id varchar(20) NOT NULL,	
      user_id bigint NOT NULL,	
      order_id bigint NOT NULL,	
      reg_date datetime NOT NULL,	
      transaction_id varchar(20) NOT NULL,	
      paymentmode varchar(20) NOT NULL,	
	  reg_status varchar(20) NOT NULL,	
	  reg_amount decimal(10,2) NOT NULL,	
      PRIMARY KEY (id),	
      INDEX (`order_id`, `booking_id`, `user_id`, `transaction_id`, `paymentmode`, `reg_status`)	
      ) $charset_collate;";	
      require_once ABSPATH . 'wp-admin/includes/upgrade.php';	
      dbDelta($sql);	
    }	
  }	
  public function getHighestKey()	
  {	
    global $wpdb;	
    $tableName = $wpdb->prefix . "eventer_registrant";	
    if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") == $tableName) {	
      return $wpdb->get_col("SELECT max(id) FROM $tableName");	
    }	
    return false;	
  }	
  public function createTickets()	
  {	
    global $wpdb;	
    $table_name = $wpdb->prefix . "eventer_registration_tickets";	
    $regTableName = $wpdb->prefix . "eventer_registrations";	
    $plugin_data = get_plugin_data(__FILE__);	
    $plugin_version = $plugin_data['Version'];	
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {	
      $charset_collate = $wpdb->get_charset_collate();	
      $sql = "CREATE TABLE $table_name (	
      id bigint NOT NULL AUTO_INCREMENT,	
      reg_id bigint NOT NULL,	
      qr_code varchar(30) NOT NULL,	
      ticket_name varchar(255) NOT NULL,	
      user_name varchar(255) NOT NULL,	
      type varchar(20) NOT NULL,	
      ticket_date datetime NOT NULL,	
      venue varchar(255) NOT NULL,	
      event_id bigint NOT NULL,	
      price float(2) NOT NULL,	
      ticket_status int(1) NOT NULL,	
      PRIMARY KEY (id),	
      INDEX (`qr_code`, `reg_id`),	
      FOREIGN KEY  (reg_id) REFERENCES $regTableName(id)	
      ) $charset_collate;";	
      require_once ABSPATH . 'wp-admin/includes/upgrade.php';	
      dbDelta($sql);	
      $key = $this->getHighestKey();	
      if (!empty($key[0])) {	
        $key = $key[0] + 1;	
        $wpdb->query("ALTER TABLE $table_name AUTO_INCREMENT = $key;");	
      }	
    }	
  }	
  public function createTicketsMeta()	
  {	
    global $wpdb;	
    $table_name = $wpdb->prefix . "eventer_ticket_meta";	
    $ticketTableName = $wpdb->prefix . "eventer_registration_tickets";	
    $plugin_data = get_plugin_data(__FILE__);	
    $plugin_version = $plugin_data['Version'];	
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {	
      $charset_collate = $wpdb->get_charset_collate();	
      $sql = "CREATE TABLE $table_name (	
      id bigint NOT NULL AUTO_INCREMENT,	
      ticket_id bigint NOT NULL,	
      meta_key varchar(50) NOT NULL,	
      meta_value longtext NOT NULL,	
      PRIMARY KEY (id),	
      KEY ticket_id (ticket_id),	
      KEY meta_key (meta_key),	
      FOREIGN KEY  (ticket_id) REFERENCES $ticketTableName(id)	
      ) $charset_collate;";	
      require_once ABSPATH . 'wp-admin/includes/upgrade.php';	
      dbDelta($sql);	
    }	
  }	
  public function createRegistrationMeta()	
  {	
    global $wpdb;	
    $table_name = $wpdb->prefix . "eventer_registration_meta";	
    $regTableName = $wpdb->prefix . "eventer_registrations";	
    $plugin_data = get_plugin_data(__FILE__);	
    $plugin_version = $plugin_data['Version'];	
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {	
      $charset_collate = $wpdb->get_charset_collate();	
      $sql = "CREATE TABLE $table_name (	
      id bigint NOT NULL AUTO_INCREMENT,	
      reg_id bigint NOT NULL,	
      meta_key varchar(255) NOT NULL,	
      meta_value longtext NOT NULL,	
      PRIMARY KEY (id),	
      KEY reg_id (reg_id),	
      KEY meta_key (meta_key),	
      FOREIGN KEY  (reg_id) REFERENCES $regTableName(id)	
      ) $charset_collate;";	
      require_once ABSPATH . 'wp-admin/includes/upgrade.php';	
      dbDelta($sql);	
    }	
  }	
}	
new RegistrationDB();