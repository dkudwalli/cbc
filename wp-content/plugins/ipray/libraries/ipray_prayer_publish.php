<?php
require_once 'ipray_function.php';
//Call function while publishing prayer
function nativechurch_confirm_prayer_publishing($post)
{
	$post_type = get_post_type($post->ID);
	$mail_status = get_post_meta($post->ID, 'nativechurch_published_prayer_confirmation', true);
	if($post_type!='prayer'||$mail_status==1) return;
	$prayer_email = get_post_meta($post->ID, 'ipray_owner_email', true);
	$share_option = get_post_meta($post->ID, 'ipray_desired_share_option', true); 
	$name = get_post_meta($post->ID, 'ipray_owner_name', true);
	$name = ($share_option == 0)?$name:__('Anonymous','ipray');
	$prayer_data['name'] = $name;
	$prayer_data['message'] = $post->post_content;
	$prayer_data['reply_mail'] = get_post_meta($post->ID, 'ipray_owner_email', true);
	$subscribers = subscriberMail_list();
	$prayer_data['unsubscribe_true'] = 1;
	if (!array_key_exists($_REQUEST['email'], $subscribers)) 
	{
		$subscribers[$prayer_email] = date('Y-m-d G:i:s');
		$prayer_data['unsubscribe_true'] = 0;
	}
	if(!empty($subscribers))
	{
		foreach($subscribers  as $key => $data)
		{
			$prayer_data['mail_to'] = $key;
			$sendurl = '';
			$prayer_data['time'] = strtotime($data);
			sendToMail('add_new_prayer',$prayer_data,$sendurl);
		}
		update_post_meta($ID, 'nativechurch_published_prayer_confirmation', 1);
	}
}
add_action( 'pending_to_publish', 'nativechurch_confirm_prayer_publishing', 10, 2 );