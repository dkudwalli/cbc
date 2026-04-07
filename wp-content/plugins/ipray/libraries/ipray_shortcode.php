<?php 
class ipray_shortcode{
	#all short code wtih handler
	private static $shortcode_handler = array(
		'iPray'         => 'iPray',
	 );	 
	#init function for shortcode
	public function __construct()
	{
		$shortcodes = self::shortcode_manager();
		foreach($shortcodes as $shortcode => $handler)
		{
			add_shortcode($shortcode,$handler);
		}
		add_filter('the_content', array('ipray_shortcode','content_filter'));
	}
	#content filter
	public static function content_filter($content = null )
    {
		$shortcode_handler = ipray_shortcode::shortcode_manager();
		$block = '';
		foreach($shortcode_handler as $shortcode => $handler)
		{
			$block.=$shortcode.'|';
		}
		$response = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content);
        // closing tag
        $response = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $response);
        return $response ;
    }
	
	/*
	 event short manager
	*/
	public static function shortcode_manager()
	{
		return self::$shortcode_handler;
	}
}

$shortcode = new ipray_shortcode();
/*
 ipray list shorcode handler
*/
function iPray($atts=array(), $content = null)
{
	   #get setting shorcode for future
	    extract(shortcode_atts(array(
        "per_page" => null,
           ), $atts));
	$per_page = (isset($per_page) && $per_page !=null && !empty($per_page))?$per_page:0;   
	#get pagae options
	#default
    $default_success_msg          = __('Prayer has been added successfully.','ipray');
    $default_error_msg            = __('Oops!! Something went wrong! Please try again!','ipray');
	$default_data_not_found_msg   = __('No prayers found','ipray');
	$default_loading_msg          = __('Loading...','ipray');
	$default_sending_msg          = __('Sending...','ipray');
	$default_pagination           = 2;
	$default_prayer_to_show       = 10;
	$default_prayer_subscribe     = 1;
	
	$prayer_pagination  =  get_option('prayer_pagination');
	$prayer_pagination  =  ($prayer_pagination!='' && !empty($prayer_pagination))?$prayer_pagination:$default_pagination;
	$prayer_to_show     =  get_option('prayer_to_show');
	$prayer_to_show     =  ($prayer_to_show!='' && !empty($prayer_to_show))?$prayer_to_show:$default_prayer_to_show;
	$prayer_subscribe   =  get_option('prayer_subscribe');
	$prayer_subscribe   =  ($prayer_subscribe!='' && !empty($prayer_subscribe))?$prayer_subscribe:$default_prayer_subscribe;
	$prayer_instruction =  get_option('prayer_instruction');
	$prayer_instruction =  ($prayer_instruction!='' && !empty($prayer_instruction))?$prayer_instruction:'';
	$prayer_success_msg =  get_option('prayer_success_msg');
	$prayer_success_msg =  ($prayer_success_msg!='' && !empty($prayer_success_msg))?$prayer_success_msg:$default_success_msg;
	$prayer_anonymous   =  get_option('prayer_anonymous');
	$prayer_anonymous   =  ($prayer_anonymous!='' && !empty($prayer_anonymous))?$prayer_anonymous:'';
	$prayer_error_msg   =  get_option('prayer_error_msg');
	$prayer_error_msg   =  ($prayer_error_msg!='' && !empty($prayer_error_msg))?$prayer_error_msg:$default_error_msg;
   #options
   $setting_options  = array(
						   'per_page'             =>($per_page !== 0)?$per_page:$prayer_to_show,
						   'prayer_pagination'    =>$prayer_pagination,
						   'prayer_subscribe'     =>$prayer_subscribe,
						   'prayer_instruction'   =>stripslashes($prayer_instruction),
						   'prayer_success_msg'   =>stripslashes($prayer_success_msg),
						   'prayer_anonymous'     =>$prayer_anonymous,
						   'prayer_error_msg'     =>$prayer_error_msg,
						   'data_not_found_msg'   =>$default_data_not_found_msg,
						   'default_loading_msg'  =>$default_loading_msg,
						   'default_sending_msg'  =>$default_sending_msg,
						   );
	extract($setting_options);
    #hidden fields
    $hidden_fields = array(
	     'action'        => 'ipray-list',
		 'per_page'      => $per_page,
		 'start'         => 0,
		'uid'						=> (isset($_GET['uid'])&&is_numeric($_GET['uid']))?$_GET['uid']:'',
		'uemail'						=> (isset($_GET['uemail'])&&!filter_var($_GET['uemail'], FILTER_VALIDATE_EMAIL) === false)?$_GET['uemail']:''
	);
	require_once(IPRAY__PLUGIN_DIR.IPRAY__PUBLIC_PATH.DIRSEP.'/templates/ipray_list.php');
}