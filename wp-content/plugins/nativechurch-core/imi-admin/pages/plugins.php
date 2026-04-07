<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wrap about-wrap imi-admin-wrap">

	<?php imi_get_admin_tabs('plugins'); ?>

	<div class="imi-plugins imi-theme-browser-wrap">
		<div class="theme-browser rendered">
			<div class="themes">

			<?php

			$tgmpa_list_table	= new TGMPA_List_Table;
			$plugins			= TGM_Plugin_Activation::$instance->plugins;

			foreach( $plugins as $plugin ) :

				$plugin_status				= '';
				$plugin['type']				= isset( $plugin['type'] ) ? $plugin['type'] : 'recommended';
				$plugin['sanitized_plugin']	= $plugin['name'];

				$plugin_action = $tgmpa_list_table->actions_plugin( $plugin );

				if ( is_plugin_active( $plugin['file_path'] ) ) {
					$plugin_status = 'active';
				}

				?>

				<div class="theme <?php echo esc_attr( $plugin_status ); ?>">

					<?php if ( $plugin['type'] == 'Required' ) : ?>
						<div class="plugin-requirement plugin-required"><?php esc_html_e( 'REQUIRED', 'imithemes' ); ?></div>
					<?php else: ?>
						<div class="plugin-requirement"><?php esc_html_e( 'OPTIONAL', 'imithemes' ); ?></div>
					<?php endif; ?>

					<div class="theme-screenshot">
						<img src="<?php echo esc_url( $plugin['image_src'] ); ?>" alt="Screen">
					</div>

					<h3 class="theme-name"><?php echo esc_html( $plugin['name'] ); ?></h3>
					<div class="theme-actions"><?php echo '' . $plugin_action; ?></div>

				</div>

			<?php endforeach; ?>

			</div>
		</div>
	</div>

</div> <!-- end wrap -->