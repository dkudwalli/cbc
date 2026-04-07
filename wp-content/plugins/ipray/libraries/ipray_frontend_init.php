<?php 
class ipray_frontend {
	#'filename'=>'decription of file'
    private $frontend_libraries  =  array(
                                     'function' =>'For all ipray common native functions', 
									 'shortcode'=>'For ipray shortcode',
									 'global'=>'This is a ipray register file',
								);
								
	#'hook name'=>'function name'							
    private $hooks  = array( 
						    'init' =>array('ipray_frontend','enqueue_init'),
							//'init'=>array('ipray_global', 'ipray_register'),
							//'init' =>array ('ipray_global', 'ipray_add_meta_box'),
							);
	/* enqeue all js and css in frontend */
	public static function enqueue_init()
	{
		 
		 wp_register_script('ipray_confliction',
			IPRAY__PLUGIN_URL .IPRAY__PUBLIC_PATH. '/js/jquery.confliction.js',
			array('jquery'), '', false
			);
		 wp_register_script('ipray_pagination',
			IPRAY__PLUGIN_URL .IPRAY__PUBLIC_PATH. '/js/jquery.simplePagination.js',
			array('jquery'),'',false
			);
		wp_register_script('ipray_form_validate',
			IPRAY__PLUGIN_URL .IPRAY__PUBLIC_PATH. '/js/jquery.validate.js',
			array('jquery'), '', false
			);
	   wp_register_script('bootstrap_min',
			IPRAY__PLUGIN_URL .IPRAY__PUBLIC_PATH. '/bootstrap/js/bootstrap.min.js',
			array('jquery'),'',false
			);
		 wp_register_script('ipray_global',
			IPRAY__PLUGIN_URL .IPRAY__PUBLIC_PATH. '/js/jquery.global.js',
			array('jquery'),'',false
			);
		wp_register_style('bootstrap_min',
			IPRAY__PLUGIN_URL .IPRAY__PUBLIC_PATH. '/bootstrap/css/bootstrap.min.css',
			array(),'',false
			);
		wp_register_style('bootstrap_theme_min',
			IPRAY__PLUGIN_URL .IPRAY__PUBLIC_PATH. '/bootstrap/css/bootstrap-theme.min.css',
			array(),'',false
			);
		 wp_register_style('ipray_global_css',
			IPRAY__PLUGIN_URL .IPRAY__PUBLIC_PATH. '/css/ipray.css',
			array(),'',false
			);
		$prayer_bootstrap_js = get_option('prayer_bootstrap_js'); 
		$prayer_bootstrap_css = get_option('prayer_bootstrap_css');
		if($prayer_bootstrap_css == 1)
		{
		  wp_enqueue_style('bootstrap_min');
		  wp_enqueue_style('bootstrap_theme_min');
		}
        wp_enqueue_style('ipray_global_css');
	    wp_enqueue_script('ipray_google_cdn');
		if($prayer_bootstrap_js == 1)
		{
		 wp_enqueue_script('bootstrap_min');
		}
		wp_enqueue_script('ipray_confliction');
		wp_enqueue_script('ipray_pagination');
		wp_enqueue_script('ipray_form_validate');
		wp_enqueue_script('ipray_global');
	}
	/* required hook add */
	public function hook_init()
	{
		 foreach($this->hooks as $key=>$action)
		 {
			    add_action($key, $action);
		 }
	}
	/*
	include all required files
	*/				           
	public function init()
	{
		foreach($this->frontend_libraries as $file=>$description)
		 {
			require_once(IPRAY__PLUGIN_DIR.IPRAY__LIB_PATH.DIRSEP.IPRAY__CLSPRE.$file.EXT);
		 }
	}
}
/* after plugin loaded */
function ipray_load_plugin_textdomain()
{
  load_plugin_textdomain('ipray', FALSE, basename(IPRAY__PLUGIN_DIR). '/languages/');
}
add_action( 'plugins_loaded', 'ipray_load_plugin_textdomain' );
#create instance and call function
$frontend=new ipray_frontend();
$frontend->init();
$frontend->hook_init();
#remove unused variable from memory
unset($frontend);