<?php // Don't load directly
if ( ! defined('ABSPATH') ) { die(_("Don't load directly")); } 
?>
<div id="newsletter-container" class="ipray-forms-common" style="display: none;">	
	<h3><?php _e('Get new prayers updates','ipray') ?></h3>
    <div class="row">
    <form id="ipray-newsletter-form" class="col-md-6" name="ipray-newsletter-form" method="post" action="">
        <label for="email"><?php _e('Email:','ipray') ?></label>
         <input type="text" maxlength="70" name="email" class="form-control">
         <input type="hidden" name="action" value="newsletter_subscribe" />
        <input type="submit" value="<?php _e('Subscribe','ipray'); ?>" class="btn btn-primary">
    </form>
    </div>
</div>