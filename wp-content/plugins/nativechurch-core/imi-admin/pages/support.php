<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wrap about-wrap imi-admin-wrap">

	<?php imi_get_admin_tabs('support'); ?>

	<div id="imi-dashboard" class="wrap about-wrap">
		<div class="welcome-content imi-clearfix extra">
			<div class="imi-row">
				<div class="imi-col-sm-12">
					<a href="https://imithemes.com/checkout/?edd_action=add_to_cart&amp;download_id=567&amp;edd_options[price_id]=1&amp;discount=DOCREF10" target="_blank" rel="noopener"><img class="imi-theme-installation-service" src="<?php echo NATIVECHURCH_CORE__PLUGIN_URL . 'imi-admin/images/Professional-Theme-Installation-Service.png'; ?>"></a>
				</div>
			</div>
			<div class="imi-row">
				<div class="imi-col-sm-6">
					<div class="imi-box doc">
						<div class="imi-box-head">
							<?php esc_html_e('Online Documentation','imithemes'); ?>
						</div>
						<div class="imi-box-content">
							<p>
							<?php esc_html_e('Our online documentation covers every basic aspect of theme from installation to how to manage your website content. For more questions visit our forum or open a ticket.' , 'imithemes'); ?>
							</p>
							<div class="imi-button">
								<a href="https://support.imithemes.com/native-theme-manuals/" target="_blank"><?php esc_html_e('DOCUMENTATION','imithemes'); ?></a>
							</div>
						</div>
					</div>
				</div>
				<div class="imi-col-sm-6">
					<div class="imi-box support">
						<div class="imi-box-head">
							<?php esc_html_e('Help Center','imithemes'); ?>
						</div>
						<div class="imi-box-content">
							<p>
							<?php esc_html_e('If you have any questions that documentation doesn\'t cover or any issue please open a support ticket at the help center and we will reply as soon as possible.' , 'imithemes'); ?>
							</p>
							<div class="imi-button">
								<a href="https://support.imithemes.com/help/" target="_blank"><?php esc_html_e('OPEN A TICKET','imithemes'); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="imi-row">
				<div class="imi-col-sm-6">
					<div class="imi-box support forum">
						<div class="imi-box-head">
							<?php esc_html_e('Knowledgebase','imithemes'); ?>
						</div>
						<div class="imi-box-content">
							<p>
							<?php esc_html_e('Our knowledgebase is the best place to get answers for the most common questions and problems.' , 'imithemes'); ?>
							</p>
							<div class="imi-button">
								<a href="https://support.imithemes.com/knowledge-base/" target="_blank"><?php esc_html_e('VISIT KB','imithemes'); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div> <!-- end wrap -->