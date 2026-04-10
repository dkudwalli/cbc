<?php // Don't load directly
if ( ! defined('ABSPATH') ) { die(_("Don't load directly")); } 
?>
<div id="ipray-submit-container" class="ipray-forms-common">
				<div class="ipray-links">
                        <a class="btn btn-primary" data-close="<?php _e('Close Prayer Request Form','ipray') ?>" data-open="<?php _e('Share Your Request or Praise','ipray') ?>" id="ipray-share-button" href="javascript:;">
                           <?php _e('Share Your Request or Praise','ipray') ?>
                        </a>
                        <a class="btn btn-default" data-close="<?php _e('Close alerts Form','ipray') ?>" data-open="<?php _e('Get alerts when new prayers post','ipray') ?>" id="ipray-subscribe-button" href="javascript:;">
                           <?php _e('Get alerts when new prayers post','ipray') ?>
                        </a>
                    </div>
      			<div id="ipray-prayers-container" style="display: none;">
					<h3><?php _e('Add new prayer','ipray') ?></h3>			
					
      				<p class="ipray-instructions"> <?php echo $prayer_instruction ?></p>
				
      				<form id="ipray-prayer-submit-form" name="ipray-prayer-submit-form" method="post" action="">
                    	<div class="row">
                        	<div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4"><label for="name"><?php _e('Your Name:','ipray') ?></label></div>
                                    <div class="col-md-8"><input type="text" tabindex="1" id="name" value="" name="name" class="form-control"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"><label for="email"><?php _e('Your Email:','ipray') ?></label></div>
                                    <div class="col-md-8"><input type="text" tabindex="2" id="email" value="" name="email" class="form-control"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"><label for="phone"><?php _e('Your Phone:','ipray') ?></label></div>
                                    <div class="col-md-8"><input type="text" tabindex="3" id="phone" value="" name="phone" class="form-control"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"><label for="desired_share_option"><?php _e('Share it','ipray') ?></label></div>
                                    <div class="col-md-8">
                                        <select tabindex="4" id="desired_share_option" name="desired_share_option" class="form-control">
                                            <option value="0"><?php _e('Share this','ipray') ?></option>
                                            <?php if($prayer_subscribe == 1) { ?>
                                            <option value="1">
                                             <?php _e('Share this anonymously','ipray') ?>
                                            </option>
                                            <?php } ?>
                                            <option value="2">
                                            <?php _e('Do not share this','ipray') ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"><label for="prayer"><?php _e('Your prayer request:','ipray') ?></label></div>
                                    <div class="col-md-8"><textarea tabindex="5" id="prayer" cols="" rows="5" name="prayer" class="form-control"></textarea></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"> </div>
                                    <div class="col-md-8">
                                        <div id="ipray-submit-area">
                                            <?php if($prayer_subscribe == 1) { ?>
                                            <label class="checkbox">
                                                <input type="checkbox" tabindex="6" class="check" value="1" id="notifyme" name="notifyme">
                                                <?php _e('Email me when someone prays for me','ipray') ?>
                                            </label>
                                            <?php } ?>
                                            <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('ipray_public_ajax')); ?>" />
                                            <input type="hidden" name="requesturi" value="<?php echo esc_attr(base64_encode($_SERVER['REQUEST_URI'])); ?>" />
                                            <input type="hidden" name="action" value="prayer_submit" />
                                            <input type="submit" tabindex="9" class="submit btn btn-primary btn-block" value="<?php _e('Submit Request','ipray'); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
