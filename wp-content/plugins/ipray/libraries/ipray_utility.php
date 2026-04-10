<?php
if (!defined('ABSPATH')) {
	require_once dirname(__DIR__, 4) . '/wp-load.php';
}
require_once __DIR__ . '/ipray_function.php';
class ipray_utility
{
	public $dbobj;
	public $start;
	public function __construct() {
       global $wpdb;
	   $this->dbObj = $wpdb;
    }
	public function getResponse($data = array())
	{
		switch($this->action)
		{
			case 'ipray-list':
			  return $this->prayerList($data);
			break;
			case 'prayer_submit':
			  return $this->addPrayer();
			break;
			case 'newsletter_subscribe':
			  return $this->prayerSubscribe();
			break;
			case 'iprayed':
			  return $this->iPrayed();
			break;
			default :
			  return $this->prayerList($data);
			break;
		}	
	}
	/* add new prayer*/
	public function addPrayer()
	{
		$post_status = '';
		$result = array('submit' => 0);
		$modification = get_option('prayer_modification');
		/* check email */
		if(!isset($_REQUEST['email']) && empty($_REQUEST['email']) )
		{
			return $result;
		}
		if($modification != NULL && $modification == 1)
		{
			 $post_status = 'pending';
		}
		else 
		{
			$post_status = 'publish';
		}
		/* post prayer data */
		$user_ID = get_current_user_id();
		$new_post = array(
		'post_title' => sanitize_text_field($_REQUEST['name']),
		'post_content' => sanitize_text_field($_REQUEST['prayer']),
		'post_status' => $post_status,
		//'post_date' => date('Y-m-d H:i:s'),
		'post_author' => $user_ID,
		'post_type' => 'prayer',
      );
      $post_id = wp_insert_post($new_post);
	   #insert post meta
	  if ($post_id) {
				add_post_meta($post_id, 'ipray_owner_name', sanitize_text_field($_REQUEST['name']));
				add_post_meta($post_id, 'ipray_owner_email', sanitize_text_field($_REQUEST['email']));
				add_post_meta($post_id, 'ipray_owner_phone', sanitize_text_field($_REQUEST['phone']));
				add_post_meta($post_id, 'ipray_prayer_notifyme', isset($_REQUEST['notifyme'])?1:0);
				add_post_meta($post_id, 'ipray_desired_share_option', sanitize_text_field($_REQUEST['desired_share_option']));
				/* send alert new prayer add */
				if($_REQUEST['desired_share_option'] != 2&&$post_status=='publish')
				{
					$share_option = $_REQUEST['desired_share_option'];
					$name = ($share_option == 0)?$_REQUEST['name']:__('Anonymous','ipray');
					$prayer_data['name'] = $name;
					$prayer_data['message'] = $_REQUEST['prayer'];
					$prayer_data['reply_mail'] = $_REQUEST['email'];
					/* fetch all subscribers */
					$email_alerts = $this->subscriberMail();
					$prayer_data['unsubscribe_true'] = 1;
					if (!array_key_exists($_REQUEST['email'], $email_alerts)) 
					{
						$email_alerts[$_REQUEST['email']] = date('Y-m-d G:i:s');
						$prayer_data['unsubscribe_true'] = 0;
					}
					if(!empty($email_alerts))
					{
						foreach($email_alerts  as $key=>$value)
						{
							$prayer_data['mail_to'] = $key;
							$prayer_data['time'] = strtotime($value);
							$sendurl                = iprayPageUrl($_REQUEST['requesturi']);
							sendToMail('add_new_prayer',$prayer_data,$sendurl);
						}
						update_post_meta($post_id, 'nativechurch_published_prayer_confirmation', 1);
					}
				}
				$result['submit'] = 1;
				return $result;
       }
	   return $result;
	}
	/* list all prayers */
	public function prayerList($option)
	{
		global $wp_query; 
		$data = array();
        query_posts( 
		       array(
			       'post_type'          =>'prayer',
				   'meta_query'         => array(
				                                array(
												    'relation' => 'AND',
				                                            array(
															   'key' => 'ipray_desired_share_option',
															    'value' => 2,
																'compare' => '!='
																)
														)
				                                 ),
				   'orderby'            => 'date',
				   'order'              => 'DESC',
				   'offset'             =>$_REQUEST['start'],
				   'posts_per_page'     =>$_REQUEST['per_page']
				   )
		);		
		if(isset($option['count']))
		{
            return $wp_query->found_posts;
			//return $GLOBALS['wp_query']->request;
		}
		$success_newsletter_unsubscription = '';
		if($_REQUEST['uemail'])
		{
			$unsubscribed = ipray_unsubscribeMail(urldecode($_REQUEST['uemail']), $_REQUEST['uid']);
			$success_newsletter_unsubscription = ($unsubscribed)?'<div class="alert alert-success fade in">'.esc_html__('Successfully unsubscribed for new prayer notifications.', 'ipray').'</div>':'';
		}
		if (have_posts())
		  {
			$i=0;
            while (have_posts()){
				the_post();
				$share_option = get_post_meta(get_the_ID(),'ipray_desired_share_option',true);
					if($i==0)
					{
						$data[$i]['unsubscribe'] = $success_newsletter_unsubscription;
					}
					else
					{
						$data[$i]['unsubscribe'] = '';
					}
					$data[$i]['ID'] = get_the_ID();
					$name = '';
					if($share_option == 1)
					{
						$name = __('Anonymous','ipray');
					}
					else
					{
						$name = get_post_meta(get_the_ID(),'ipray_owner_name',true);
					}
					$data[$i]['name'] = $name;
					$data[$i]['is_pray_allow'] = $this->isPrayeredAllow(get_the_ID());
					$data[$i]['prayer'] = get_the_content();
					$data[$i]['date_time'] = get_the_time('F d, Y',get_the_ID());
					$data[$i]['class'] = ($i%2==0)?'even':'odd';
					$prayer_count = $this->prayerCount(get_the_ID());
		      		$time_srting = ($prayer_count> 1)? __('times', 'ipray'): __('time', 'ipray');
					$data[$i]['prayer_count'] = $this->prayerCount(get_the_ID());
					$count_msg = sprintf(__('Prayed for %d %s','ipray'),$prayer_count,$time_srting);
					$data[$i]['prayer_count_msg'] = $count_msg;
					$i++;
			}
		  }
		  return $data;
	}
	/* subscribe prayer */
	public function prayerSubscribe()
	{
		 $table_name = $this->dbObj->prefix.'prayer_newsletter';
		 $result = array('submit' => 0);
		 if(!isset($_REQUEST['email']) && empty($_REQUEST['email']) )
		 {
			return $result;
		 }
			 	if($this->checkmail(trim($_REQUEST['email']))==1)
				{
					$result['submit'] = 2;
					$result['msg'] = __('This email address already exists!','ipray');
					return $result;
				}
				elseif($this->checkmail(trim($_REQUEST['email']))!=0)
				{
					$result['submit'] = 2;
					$result['msg'] = __('You have been successfully resubscribed!','ipray');
					return $result;
				}
		 
		  $email       = trim($_REQUEST['email']);
		  $browser     = $_SERVER['HTTP_USER_AGENT'];
		  $ip          = $_SERVER['REMOTE_ADDR'];
		  $created     = date('Y-m-d H:i:s');
						
	  $sql ="INSERT INTO $table_name (id,email, browser,ip,created) VALUES ('','$email','$browser','$ip','$created')" ;
      $response = $this->dbObj->query($sql);
	  if($response)
	  {
		$subject = __('New subscriber for Prayers.','ipray');
		$content = __('New user is subscribed to have notifications of new prayers, email address is: ', 'ipray') . PHP_EOL . PHP_EOL;
		$content = wordwrap($content, 70 );
		$content .= $email;
		$msg = '<div>';
		$msg .= $content;
		$headers = "From:".get_option('admin_email') . PHP_EOL;
			$headers .= "Reply-To:".get_option('admin_email'). PHP_EOL;
			$headers .= "MIME-Version: 1.0" . PHP_EOL;
			$headers .= "Content-Type: text/html; charset=\"iso-8859-1\"\n" . PHP_EOL;
			$headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL; 
			wp_mail(get_option('admin_email'),$subject, $msg, $headers);
		  $result['msg'] = __('You have been successfully subscribed!','ipray');
		  $result['submit'] = 1;
	  }
	  return $result;
	}
	/* is prayed allowed  */
	public function isPrayeredAllow($prayer_id)
	{
        $table_name = $this->dbObj->prefix.'prayer_prayed';
		if (!isset($_SESSION))
		{ 
		   session_start();
		}
		
		$session_id = session_id();
		$prayer_ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
		
        $sql = $this->dbObj->prepare(
            "SELECT id FROM $table_name WHERE prayer_id = %d AND prayer_session = %s AND prayer_ip = %s",
            absint($prayer_id),
            $session_id,
            $prayer_ip
        );
        $response = $this->dbObj->get_row($sql,OBJECT);
		if($response == null)
		{
		   return 1; 
		}
		return 0;
	}
	/* check mail is alreay exist or not */
	private function checkmail($email)
	{
		$table_name = $this->dbObj->prefix.'prayer_newsletter';
    $sql = $this->dbObj->prepare("SELECT * FROM $table_name WHERE email = %s", sanitize_email($email));
    $response = $this->dbObj->get_row($sql,OBJECT);
		$s = 0;
		if($response !== NULL)
		{
			$s = 1;
			if($response->status==2)
			{
				$data = array('status'=>1);
				$where = array('email'=>$email);
				$sb = $this->dbObj->update( $table_name, $data, $where);
				$s = 2;
			}
		}
		return $s;
	}
	
	/* fecth all active prayer subscriber user 
	   return all subscriber emails
	*/
	
	private function subscriberMail()
	{
		$data = array();
		$table_name = $this->dbObj->prefix.'prayer_newsletter';
        $sql = $this->dbObj->prepare("SELECT * FROM $table_name WHERE status = %d", 1);
        $all_mail = $this->dbObj->get_results($sql,OBJECT);
		if($all_mail)
		{
			foreach ($all_mail as $data_mail ) 
			{
				$data[$data_mail->email] = $data_mail->created;
			}
		}
		return $data;
	}

	/* prayer count */
	private function prayerCount($prayer_id)
	{
		$table_name = $this->dbObj->prefix.'prayer_prayed';
        $sql = $this->dbObj->prepare("SELECT COUNT(prayer_id) as total_prayer FROM $table_name WHERE prayer_id = %d", absint($prayer_id));
        $response = $this->dbObj->get_row($sql,OBJECT);
		return $response->total_prayer; 
	}	
	/* any person prayer for The prayer owner */
	public function iPrayed()
	{
		$data = array(); 
		$data['prayer_count'] = 0;
		if(!isset($_REQUEST['prayer_id']))
		{
			return $data;
		}
		$prayer_id = absint($_REQUEST['prayer_id']);
		if(!$prayer_id)
		{
			return $data;
		}
		if($this->isPrayeredAllow($prayer_id))
		{
			if (!isset($_SESSION))
			{ 
			   session_start();
			}
		  $table_name      = $this->dbObj->prefix.'prayer_prayed';
		  $prayer_browser  = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';
		  $prayer_ip       = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
		  $prayed_created  = date('Y-m-d H:i:s');
		  $prayer_session  = session_id();
		  
		   $sql = $this->dbObj->prepare(
            "INSERT INTO $table_name (prayer_id, prayer_session, prayer_browser, prayer_ip, prayed_created) VALUES (%d, %s, %s, %s, %s)",
            $prayer_id,
            $prayer_session,
            $prayer_browser,
            $prayer_ip,
            $prayed_created
           );
		   $this->dbObj->query($sql);
		   /* iprayed submit */
		   $data['prayer_count'] = $this->prayerCount($prayer_id);
		   $time_srting = ($data['prayer_count']>1)?'times':'time';
		   $data['prayer_count_msg'] = sprintf(__('Prayed for %d %s','ipray'),$data['prayer_count'],$time_srting);
				/* inform someone prayer */
				$notifyme = get_post_meta($prayer_id,'ipray_prayer_notifyme',true);
				$prayer_owner_email = get_post_meta($prayer_id,'ipray_owner_email',true);
				if(($notifyme != NULL && !empty($notifyme)) && ($notifyme == 'on' || $notifyme == '1'))
				{
					$content_post = get_post($prayer_id);
					$content = $content_post->post_content;
					$prayer_data['mail_to'] = $prayer_owner_email;
					$prayer_data['message'] = $content;
					$sendurl                = iprayPageUrl(isset($_REQUEST['requesturi']) ? sanitize_text_field(wp_unslash($_REQUEST['requesturi'])) : '');
					sendToMail('prayed_to_someone',$prayer_data,$sendurl );
				}	
		}
        return $data;
	}
	public function isAjax()
	{
		/* AJAX check  */
		 if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
		 {
			 return true;
		 }
		 return false;
	}
	/* clear all memories */
	public function __destruct() {
        unset($this->dbObj);
    }
}
if (!function_exists('ipray_verify_public_ajax_request')) {
	function ipray_verify_public_ajax_request()
	{
		check_ajax_referer('ipray_public_ajax', 'nonce');
	}
}

if (!function_exists('ipray_handle_utility_request')) {
	function ipray_handle_utility_request()
	{
		ipray_verify_public_ajax_request();

		$ipray_utility = new ipray_utility();
		$action = isset($_REQUEST['action']) ? sanitize_text_field(wp_unslash($_REQUEST['action'])) : '';
		if ($action === '') {
			wp_send_json(array('submit' => 0), 400);
		}

		$ipray_utility->action = $action;

		if ($action === 'ipray-list') {
			$data = array();
			$res_count = $ipray_utility->getResponse(array('count' => true));
			$ipray_data = $ipray_utility->getResponse();
			$data['res_count'] = $res_count;
			$data['display_results'] = $ipray_data;
			$data['setting']['prayer_text'] = __('I prayed for this', 'ipray');
			$data['setting']['recieve_text'] = __('Posted:', 'ipray');
			$data['per_page'] = isset($_REQUEST['per_page']) ? sanitize_text_field(wp_unslash($_REQUEST['per_page'])) : '';
			wp_send_json($data);
		}

		if ($action === 'prayer_submit' || $action === 'newsletter_subscribe' || $action === 'iprayed') {
			wp_send_json($ipray_utility->getResponse());
		}

		wp_send_json(array('submit' => 0), 400);
	}
}

if (isset($_SERVER['SCRIPT_FILENAME']) && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
	ipray_handle_utility_request();
}
