<?php 
class ipray {
	#'filename'=>'decription of file'
	private $admin_libraries  =  array(
	                                     'global'=>'This is a ipray register menu, meta box file', 
										 'function' =>'For all ipray common native functions',
										 'page'=>'This is a page register file', 
									);
	#ipray hooks definer	
	#'hook name'=>'function name'							
	private $hooks  = array( 
							  'init'       =>array('ipray_global', 'ipray_register'),
							  'admin_menu' =>array('ipray_global', 'ipray_register_page'),
							  'save_post'  => 'ipray_meta_data_save',
							  'admin_init' =>array('ipray_global', 'register_ipray_page_fields'),
						);
	/*
	include all required files
	*/				          
	public function init()
	{
		foreach($this->admin_libraries as $file=>$description)
		 {
			require_once(IPRAY__PLUGIN_DIR.IPRAY__LIB_PATH.DIRSEP.IPRAY__CLSPRE.$file.EXT);
		 }
	}
	/* required hook add */
	public function hook_init()
	{
		 foreach($this->hooks as $key=>$action)
		 {
				 add_action($key, $action);
		 }
		 add_action('admin_menu', array('ipray_global', 'ipray_add_meta_box'));
	}
	/**
	 * all option for activation hook here
	 * @static
	*/
	public static function plugin_ipray_activation() {		
		add_option('ipray_plugin_version', IPRAY__VERSION);
		add_option('prayer_subscribe', 1);
		add_option('prayer_anonymous', 1);
		add_option('prayer_modification', 0);
		add_option('unsubscribe_prayer', 1);
		add_option('prayer_success_msg', __('Prayer added successfully.','ipray'));
		add_option('prayer_to_show', '10');
		add_option('prayer_pagination', '2');
		add_option('prayer_instruction', __('Form instructions comes here','ipray'));
		add_option('prayer_added_content', '');
		add_option('prayer_bootstrap_css', 0);
		add_option('prayer_bootstrap_js', 0);
		add_option('prayer_url', '');
		
		/* initialize db*/
		self::ipray_setup_database();
	}	
	/**
	 * Removes all connection options
	 * @static
	 */	 
	public static function plugin_ipray_deactivation() {
			delete_option('ipray_plugin_version');
			delete_option('prayer_subscribe');
			delete_option('prayer_anonymous');
			delete_option('prayer_modification');
			delete_option('unsubscribe_prayer');
			delete_option('prayer_success_msg');
			delete_option('prayer_to_show');
			delete_option('prayer_pagination');
			delete_option('prayer_instruction');
			delete_option('prayer_added_content');
			delete_option('prayer_bootstrap_css');
		  delete_option('prayer_bootstrap_js');
			delete_option('prayer_url');
	}
	
	/**
	 * create tables
	 * @static
	 */	 
public static function ipray_setup_database() {
	  global $wpdb;
	  /* tables */
	  $ipray_tables = array('prayer_newsletter','prayer_prayed'); 
	  $charset_collate = $wpdb->get_charset_collate();
	  foreach($ipray_tables as $table)
	  {
			$table_name = $wpdb->prefix.$table;
			if ($wpdb->query('SHOW TABLES LIKE "' . $table_name . '"')) 
			{
			   $wpdb->query('TRUNCATE TABLE ' . $table_name);
			}
			else
			{
				if($table == 'prayer_newsletter')
				{
					$sql  = "CREATE TABLE $table_name (
					  id int(20) NOT NULL AUTO_INCREMENT,
					  email varchar(100) NOT NULL,
					  browser varchar(300) NOT NULL,
					  ip varchar(30) NOT NULL COMMENT 'prayer user ip address',
					  status int(3) NOT NULL default 1 COMMENT '1 - enable, 0 - disabled',
					  created datetime NOT NULL,
					  PRIMARY KEY (id)
					)$charset_collate;";
				}
				else if($table == 'prayer_prayed')
				{
					$sql  = "CREATE TABLE $table_name (
					  id int(20) NOT NULL AUTO_INCREMENT,
					  prayer_id int(20) NOT NULL COMMENT 'prayer main id',
                      prayer_session varchar(50) NOT NULL COMMENT 'use for making unique prayer',
					  prayer_browser varchar(300) NOT NULL COMMENT 'prayer user browser name',
					  prayer_ip varchar(30) NOT NULL COMMENT 'prayer user ip address',
					  prayed_created datetime NOT NULL COMMENT 'date of prayer create',
					  PRIMARY KEY (id)
					)$charset_collate;";
				}
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta($sql);
			}			
	  }  
	}
}
#create instance and call function
$init=new ipray();
$init->init();
$init->hook_init();
#remove unused variable from memory
unset($init);