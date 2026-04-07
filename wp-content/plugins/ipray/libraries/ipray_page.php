<?php // Don't load directly
if ( ! defined('ABSPATH') ) { die(); } 
class ipray_page {
public  static function ipray_setting() { ?>
<div class="wrap" id="theme-options-wrap">
<h2><?php _e('iPray Settings','ipray') ?></h2>
<form method="post" id="ipray_option" name="ipray_option" action="">
	<h2><?php _e('Usage','ipray'); ?></h2>
    <p><?php _e('Copy/Paste below shortcode in any of your page to show Prayers Wall. Change per_page attribute for the number of prayers to show per page.','ipray'); ?></p>
    <code>[iPray per_page="2"]</code>
    <p></p>
	<h2><?php _e('Configuration','ipray'); ?></h2>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php  _e('Show prayers subscribe form','ipray') ?></th>
        <td>
           <?php $prayer_subscribe = get_option('prayer_subscribe'); ?>
           <input type="checkbox" value="1" name="prayer_subscribe" <?php if($prayer_subscribe == 1) { ?> checked="checked" <?php } ?>/>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php  _e('Allow anonymous prayer submissions','ipray') ?></th>
        <td>
        <?php $prayer_anonymous = get_option('prayer_anonymous'); ?>
           <input type="checkbox" value="1" name="prayer_anonymous" <?php if($prayer_anonymous == 1) { ?> checked="checked" <?php } ?>/>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php  _e('Show pagination','ipray') ?></th>
        <td>
        <?php $prayer_pagination = get_option('prayer_pagination'); ?>
           <input type="radio" id="prayer_pagination_top" value="0" name="prayer_pagination" <?php if($prayer_pagination == 0) { ?> checked="checked" <?php } ?>/>
           <label for="prayer_pagination_top"><?php _e('Top','ipray') ?></label>
           <input type="radio" value="1" id="prayer_pagination_bottom" name="prayer_pagination" <?php if($prayer_pagination == 1) { ?> checked="checked" <?php } ?>/>
            <label for="prayer_pagination_bottom"><?php _e('Bottom','ipray') ?></label>
           <input type="radio" value="2" id="prayer_pagination_both" name="prayer_pagination" <?php if($prayer_pagination == 2) { ?> checked="checked" <?php } ?>/>
           <label for="prayer_pagination_both"><?php _e('Both','ipray') ?></label>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php  _e('Requires Admin Approval','ipray') ?></th>
        <td>
         <?php $prayer_modification = get_option('prayer_modification'); ?>
           <input type="checkbox" value="1" name="prayer_modification" <?php if($prayer_modification == 1) { ?> checked="checked" <?php } ?>/>
           <small><?php _e('Check if you need to approve prayers before publishing them on website live.','ipray'); ?></small>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Unsubscribe Prayer','ipray') ?></th>
        <td>
         <?php $unsubscribe_prayer = get_option('unsubscribe_prayer'); ?>
           <input type="checkbox" value="1" name="unsubscribe_prayer" <?php if($unsubscribe_prayer == 1) { ?> checked="checked" <?php } ?>/>
           <small><?php _e('Check if you need to add a link of unsubscribe prayer in email.','ipray'); ?></small>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Message for prayer request form submission','ipray') ?></th>
        <td>
           <input type="text" maxlength="300" name="prayer_success_msg" value="<?php echo esc_attr(stripslashes(get_option('prayer_success_msg'))); ?>" />
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Number of prayers to show per page','ipray') ?></th>
        <td>
           <input type="text" maxlength="2" name="prayer_to_show" value="<?php echo esc_attr( get_option('prayer_to_show') ); ?>" />
           <small><?php _e('This can be overridden by the shortcode attribute per_page','ipray'); ?></small>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Prayer Page URL','ipray') ?></th>
        <td>
           <input type="text" maxlength="300" name="prayer_url" value="<?php echo esc_attr( get_option('prayer_url') ); ?>" />
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Include Bootstrap CSS','ipray') ?></th>
        <td>
        <?php $prayer_bootstrap_css = get_option('prayer_bootstrap_css'); ?>
           <input type="radio" id="prayer_bootstrap_css_yes" value="1" name="prayer_bootstrap_css" <?php if($prayer_bootstrap_css == 1) { ?> checked <?php } ?>/>
           <label for="prayer_bootstrap_css_yes"><?php _e('Yes','ipray') ?></label>
           <input type="radio" value="0" id="prayer_bootstrap_css_no" name="prayer_bootstrap_css" <?php if($prayer_bootstrap_css == 0) { ?> checked <?php } ?>/>
           <label for="prayer_bootstrap_css_no"><?php  _e('No','ipray') ?></label>
           <small><?php _e('Select No if your theme already loads Twitter Bootstrap CSS File.','ipray'); ?></small>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Include Bootstrap JS','ipray') ?></th>
        <td>
        <?php $prayer_bootstrap_js = get_option('prayer_bootstrap_js'); ?>
           <input type="radio" id="prayer_bootstrap_js_yes" value="1" name="prayer_bootstrap_js" <?php if($prayer_bootstrap_js == 1) { ?> checked <?php } ?>/>
           <label for="prayer_bootstrap_js_yes"><?php  _e('Yes','ipray') ?></label>
           <input type="radio" value="0" id="prayer_bootstrap_js_no" name="prayer_bootstrap_js" <?php if($prayer_bootstrap_js == 0) { ?> checked <?php } ?>/>
           <label for="prayer_bootstrap_js_no"><?php  _e('No','ipray') ?></label>
           <small><?php  _e('Select No if your theme already loads Twitter Bootstrap JS File.','ipray'); ?></small>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Instructions for the prayer submission form','ipray') ?></th>
        <td>
            <textarea rows="6" cols="40" maxlength="1000" style="padding:0px;"  name="prayer_instruction">
			 <?php echo esc_attr(stripslashes(get_option('prayer_instruction'))); ?>
            </textarea>
            <input type="hidden" name="prayer_submit_option_page" value="1" />
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Prayer added email content','ipray') ?></th>
        <td>
            <textarea rows="6" cols="40" maxlength="1000" style="padding:0px;"  name="prayer_added_content">
			 <?php echo esc_attr(stripslashes(get_option('prayer_added_content'))); ?>
            </textarea>
            <small><?php _e('You can format email content using some HTML Tags, please use [msg] for prayer message and [prayer_url] for prayer page URL.','ipray'); ?></small>
            <input type="hidden" name="prayer_submit_option_page" value="1" />
        </td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
</div>
<?php 
}
}
?>