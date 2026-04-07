<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$theme = imi_get_theme_info();
$theme_name = IMI_Admin::theme( 'name' );

if(!empty(get_option('nativechurch_auth_code'))) {
	$showRegisterForm = ' style="display: none;"';
}
?>

<div class="wrap about-wrap imi-admin-wrap">

	<?php imi_get_admin_tabs(); ?>
	
	<?php

	$imi_theme = wp_get_theme();
	$mem_limit = ini_get('memory_limit');
	$mem_limit_byte = wp_convert_hr_to_bytes($mem_limit);
	$upload_max_filesize = ini_get('upload_max_filesize');
	$upload_max_filesize_byte = wp_convert_hr_to_bytes($upload_max_filesize);
	$post_max_size = ini_get('post_max_size');
	$post_max_size_byte = wp_convert_hr_to_bytes($post_max_size);
	$mem_limit_byte_boolean = ($mem_limit_byte < 268435456);
	$upload_max_filesize_byte_boolean = ($upload_max_filesize_byte < 67108864);
	$post_max_size_byte_boolean = ($post_max_size_byte < 67108864);
	$execution_time = ini_get('max_execution_time');
	$execution_time_boolean = ($execution_time < 300);
	$input_vars = ini_get('max_input_vars');
	$input_vars_boolean = ($input_vars < 2000);
	$input_time = ini_get('max_input_time');
	$input_time_boolean = ($input_time < 1000);
	$theme_option_address = admin_url("themes.php?page=_options");

	$imi_theme = wp_get_theme();
	$theme_option_address = admin_url("themes.php?page=_options");
	?>

	<div id="imi-dashboard" class="wrap about-wrap">

		<div class="welcome-content imi-clearfix">
			<div class="imi-row">
				<div class="imi-col-sm-12">
					<div class="imi-box theme-activate">
						<div class="imi-box-head">
							<?php esc_html_e('Theme Activation','imithemes'); ?>
						</div>
						<div class="imi-box-content">
							<p>
								<?php printf(esc_html__('Thank you for choosing %s! Please register it to get access to the demo import functions and theme options. The instructions below must be followed exactly to successfully register your purchase.', 'imithemes'), $theme_name); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="welcome-content imi-clearfix extra">
			<div id="wSystemStatus" class="imi-row">
				<div class="imi-col-sm-12">
					<div class="imi-box">
						<div class="imi-box-head">
							<?php esc_html_e('Quick System Status','imithemes'); ?>
						</div>
						<div class="imi-box-content">
							<?php esc_html_e('When you install a demo it provides pages, images, theme options, posts, slider, widgets and etc. IMPORTANT: Please check below status to see if your server meets all essential requirements for a successful import.','imithemes') ?>
							<div class="imi-system-info">
								<span> <?php esc_html_e('WP Memory Limit','imithemes'); ?> </span>
								<?php
								$wp_memory_limit = WP_MEMORY_LIMIT;
								$wp_memory_limit_value = preg_replace("/[^0-9]/", '', $wp_memory_limit);
								if( $wp_memory_limit_value < 256 ){ ?>
									<i class="imi-icon imi-icon-red dashicons dashicons-no-alt"></i> <span class="imi-current"> <?php echo esc_html__('Currently:','imithemes').' '.$wp_memory_limit ?> </span>
									<span class="imi-min"> <?php esc_html_e('(min:256M)','imithemes') ?> </span>
									<label class="hero button" for="wp-memory-limit"> <?php esc_html_e('How to fix it','imithemes') ?> </label>
									<aside class="lightbox">
										<input type="checkbox" class="state" id="wp-memory-limit" />
										<article class="content">
											<header class="header">
												<label class="button" for="wp-memory-limit"><i class="dashicons dashicons-no-alt"></i></label>
											</header>
											<main class="main">
												<p class="red"> <?php esc_html_e( 'We recommend setting memory to at least 256MB. Please define memory limit in wp-config.php file. you can read below link for more information:' , 'imithemes' ) ?></p>
												<a href="https://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank"> <?php esc_html_e( 'Increasing Wp Memory Limit' , 'imithemes' ) ?> </a>
											</main>
										</article>
										<label class="backdrop" for="wp-memory-limit"></label>
									</aside>
								<?php } else { ?>
									<i class="imi-icon imi-icon-green dashicons dashicons-yes"></i> <span class="imi-current"> <?php echo esc_html__('Currently:','imithemes').' '.$wp_memory_limit; ?> </span>
								<?php } ?>
							</div>
							<div class="imi-system-info">
								<span> <?php esc_html_e('Upload Max. Filesize','imithemes') ?> </span>
								<?php
								if($upload_max_filesize_byte_boolean){ ?>
									<i class="imi-icon imi-icon-red dashicons dashicons-no-alt"></i> <span class="imi-current"> <?php echo esc_html__('Currently:','imithemes').' '.$upload_max_filesize; ?> </span>
									<span class="imi-min"> <?php esc_html_e('(min:64M)','imithemes') ?> </span>
									<label class="hero button" for="php-upload-size"> <?php esc_html_e('How to fix it','imithemes') ?> </label>
									<aside class="lightbox">
										<input type="checkbox" class="state" id="php-upload-size" />
										<article class="content">
											<header class="header">
												<label class="button" for="php-upload-size"><i class="dashicons dashicons-no-alt"></i></label>
											</header>
											<main class="main">
												<p class="red"> <?php esc_html_e( 'We recommend setting Upload Max. Filesize to at least 64MB. you can read below link for more information:' , 'imithemes' ) ?></p>
												<a href="https://premium.wpmudev.org/blog/increase-memory-limit/" target="_blank"> <?php esc_html_e( 'Increasing Upload Max. Filesize' , 'imithemes' ) ?></a><br>
											</main>
										</article>
										<label class="backdrop" for="php-upload-size"></label>
									</aside>
								<?php } else { ?>
									<i class="imi-icon imi-icon-green dashicons dashicons-yes"></i> <span class="imi-current"> <?php echo esc_html__('Currently:','imithemes').' '.$upload_max_filesize; ?> </span>
								<?php } ?>
							</div>
							<div class="imi-system-info">
								<span> <?php esc_html_e('Max. Post Size','imithemes') ?> </span>
								<?php
								if($post_max_size_byte_boolean){ ?>
									<i class="imi-icon imi-icon-red dashicons dashicons-no-alt"></i> <span class="imi-current"> <?php echo esc_html__('Currently:','imithemes').' '.$post_max_size; ?> </span>
									<span class="imi-min"> <?php esc_html_e('(min:64M)','imithemes') ?> </span>
									<label class="hero button" for="php-post-upload-size"> <?php esc_html_e('How to fix it','imithemes') ?> </label>
									<aside class="lightbox">
										<input type="checkbox" class="state" id="php-post-upload-size" />
										<article class="content">
											<header class="header">
												<label class="button" for="php-post-upload-size"><i class="dashicons dashicons-no-alt"></i></label>
											</header>
											<main class="main">
												<p class="red"> <?php esc_html_e( 'We recommend setting Max. Post Size to at least 64MB. you can read below link for more information:' , 'imithemes' ) ?> </p>
												<a href="https://premium.wpmudev.org/blog/increase-memory-limit/" target="_blank">Increasing Max. Post Size</a><br>
											</main>
										</article>
										<label class="backdrop" for="php-post-upload-size"></label>
									</aside>
								<?php }else{ ?>
									<i class="imi-icon imi-icon-green dashicons dashicons-yes"></i> <span class="imi-current"> <?php echo esc_html__('Currently:','imithemes').' '.$post_max_size; ?> </span>
								<?php } ?>
							</div>
							<div class="imi-system-info">
								<span> <?php esc_html_e('Max. Execution Time','imithemes'); ?> </span>
								<?php
								if($execution_time_boolean){ ?>
									<i class="imi-icon imi-icon-red dashicons dashicons-no-alt"></i>
									<span class="imi-current"> <?php echo esc_html('Currently:','imithemes').' '.$execution_time; ?> </span>
									<span class="imi-min"> <?php esc_html_e('(min:300)','imithemes') ?> </span>
									<label class="hero button" for="execution-time"> <?php esc_html_e('How to fix it','imithemes') ?> </label>
									<aside class="lightbox">
										<input type="checkbox" class="state" id="execution-time" />
										<article class="content">
											<header class="header">
												<label class="button" for="execution-time"><i class="dashicons dashicons-no-alt"></i></label>
											</header>
											<main class="main">
												<p class="red"> <?php esc_html_e( 'We recommend setting max execution time to at least 300. you can read below link for more information:' , 'imithemes' ) ?> </p>
												<a href="http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded" target="_blank"> <?php esc_html_e( 'Increasing Max. Execution Time' , 'imithemes' ) ?> </a>
											</main>
										</article>
										<label class="backdrop" for="execution-time"></label>
									</aside>
								<?php } else { ?>
									<i class="imi-icon imi-icon-green dashicons dashicons-yes"></i> <span class="imi-current"> <?php echo esc_html__('Currently:','imithemes').' '.$execution_time; ?> </span>
								<?php } ?>
							</div>
							<div class="imi-system-info">
								<span> <?php esc_html_e('PHP Max. Input Vars','imithemes') ?> </span>
								<?php
								if($input_vars_boolean){ ?>
									<i class="imi-icon imi-icon-red dashicons dashicons-no-alt"></i>
									<span class="imi-current"> <?php echo esc_html__('Currently:','imithemes').' '.$input_vars; ?> </span>
									<span class="imi-min"> <?php esc_html_e('(min:2000)','imithemes') ?> </span>
									<label class="hero button" for="input-variables"><?php esc_html_e('How to fix it','imithemes') ?> </label>
									<aside class="lightbox">
										<input type="checkbox" class="state" id="input-variables" />
										<article class="content">
											<header class="header">
												<label class="button" for="input-variables"><i class="dashicons dashicons-no-alt"></i></label>
											</header>
											<main class="main">
												<p class="red"> <?php esc_html_e( 'We recommend setting max input vars to at least 2000. Please follow below steps:' , 'imithemes' ) ?></p>
												<p>There are several ways to do it. First one to check would be to login to your server's cPanel and look there for PHP settings. Often there's an option to edit PHP settings "per host" or "per domain" and you may find it there.
												<br>
												If there's no such option:
												<br>
												- create a file named "php.ini"<br>
												- put following line inside
												<br>
												<code class="red">max_input_vars = 3000;</code>
												<br>
												- save the file and upload it to your server to the root (main) folder of your domain
												<br>
												On some servers it's not possible to use "php.ini" file that way so if above doesn't work, there's another way to check:
												<br>
												- edit the ".htaccess" file of your site<br>
												- add following lines at the very top of it (do not remove anything that's already there)
												<br>
												<code class="red">php_value max_input_vars 3000</code>
												<br>
												- save the file.
												<br>
												If that doesn't work either or breaks the site, edit the file again to remove the line and get in touch with your host asking them if they could increase that value for you.</p>
											</main>
										</article>
										<label class="backdrop" for="input-variables"></label>
									</aside>
								<?php } else { ?>
									<i class="imi-icon imi-icon-green dashicons dashicons-yes"></i> <span class="imi-current"> <?php echo esc_html__('Currently:','imithemes').' '.$input_vars; ?> </span>
								<?php } ?>
							</div>
							<div class="imi-system-info">
								<span> <?php esc_html_e('PHP Max. Input Time','imithemes') ?> </span>
								<?php
								if($input_time_boolean){ ?>
									<i class="imi-icon imi-icon-red dashicons dashicons-no-alt"></i> <span class="imi-current"> <?php echo esc_html__('Currently:','imithemes').' '.$input_time; ?> </span>
									<span class="imi-min"> <?php esc_html_e('(min:1000)','imithemes') ?></span>
									<label class="hero button" for="php-input-time"> <?php esc_html_e('How to fix it','imithemes') ?></label>
									<aside class="lightbox">
										<input type="checkbox" class="state" id="php-input-time" />
										<article class="content">
											<header class="header">
												<label class="button" for="php-input-time"><i class="dashicons dashicons-no-alt"></i></label>
											</header>
											<main class="main">
												<p class="red"> <?php esc_html_e('It may not work with some shared hosts in which case you would have to ask your hosting service provider for support.' , 'imithemes' ) ?> </p>
												<strong> <?php esc_html_e('1- Create or Edit an existing PHP.INI file' , 'imithemes' ) ?> </strong><br>
												<?php esc_html_e('In most cases if you are on a shared host, you will not see a php.ini file in your directory. If you do not see one, then create a file called php.ini and upload it in the root folder. In that file add the following code:' , 'imithemes' ) ?><br>
												<code class="red"> <?php esc_html_e('max_input_time' , 'imithemes' ) ?> = 1000 </code><br><br>
												<strong> <?php esc_html_e('2- htaccess Method' , 'imithemes' ) ?></strong><br>
												<?php esc_html_e('Some people have tried using the htaccess method where by modifying the .htaccess file in the root directory, you can increase the Max. Input Time in WordPress. Open or create the .htaccess file in the root folder and add the following code:' , 'imithemes' ) ?><br>
												<code class="red"> <?php esc_html_e('php_value max_input_time' , 'imithemes' ) ?> 1000</code><br>
											</main>
										</article>
										<label class="backdrop" for="php-input-time"></label>
									</aside>
								<?php } else { ?>
									<i class="imi-icon imi-icon-green dashicons dashicons-yes"></i> <span class="imi-current"> <?php echo esc_html__('Currently:','imithemes').' '.$input_time; ?> </span>
								<?php }	?>
							</div>
							<div class="imi-button">
								<a href="<?php echo esc_url( self_admin_url( 'admin.php?page=imi-admin-system-status' ) ); ?>"><?php esc_html_e('CHECK COMPLETE REPORT','imithemes'); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="imi-row">
				<div class="imi-col-sm-12">
					<div class="imi-box change-log">
						<div class="imi-box-head">
							<?php esc_html_e('Changelog (Updates)','imithemes'); ?>
						</div>
						<div class="imi-box-content">
							<?php include_once get_template_directory() . '/Change_log.php'; ?>
							<pre><?php echo '' . $change_log; ?></pre>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> <!-- end wrap -->