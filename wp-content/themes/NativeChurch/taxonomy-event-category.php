<?php
get_header();
$this_term = get_query_var('term');
$class = (is_active_sidebar('event-sidebar')) ? 9 : 12;
imic_sidebar_position_module();
echo '<div class="container">
      	<div class="row main-content-row">
	  		<div class="col-md-' . $class . '" id="content-col">
	  			<div id="ajax_events">
	  				<div class="listing events-listing">
						<header class="listing-header">
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<h3>' . esc_html__('All events', 'framework') . '</h3>
		  						</div>
		  						<div class="listing-header-sub col-md-6 col-sm-6">';
									$currentEventTime = date_i18n('Y-m');
									$prev_month = date_i18n('Y-m', strtotime('-1 month', strtotime($currentEventTime)));
									$next_month = date_i18n('Y-m', strtotime('+1 month', strtotime($currentEventTime)));
								echo '</div>
							</div>
						</header>
						<section class="listing-cont">
						  <ul>';
							$today = date_i18n('Y-m');
							$curr_month = date_i18n('Y-m-t', strtotime('-1 month', strtotime($currentEventTime)));
							$currentTime = date_i18n(get_option('time_format'));
							$sp = imic_recur_events('future', '', $this_term, '');
							ksort($sp);
							foreach ($sp as $key => $value) {

								$satime = get_post_meta($value, 'imic_event_start_tm', true);
								$satime = strtotime($satime);
								$date_converted = date_i18n('Y-m-d', $key);
								$custom_event_url =  imic_query_arg($date_converted, $value);
								echo '<li class="item event-item">	
											  <div class="event-date"> <span class="date">' . date_i18n('d', $key) . '</span> <span class="month">' . imic_global_month_name($key) . '</span> </div>
											  <div class="event-detail">
																  <h4><a href="' . $custom_event_url . '">' . get_the_title($value) . '</a>' . imicRecurrenceIcon($value) . '</h4>';
								$stime = '';
								if ($satime != '') {
									$stime = ' | ' . date_i18n(get_option('time_format'), $satime);
								}
								echo '<span class="event-dayntime meta-data">' . date_i18n('l', $key) . $stime . '</span> </div>
											  <div class="to-event-url">
												<div><a href="' . $custom_event_url . '" class="btn btn-default btn-sm">' . esc_html__('Details', 'framework') . '</a></div>
											  </div>
											</li>';
							}
						echo '</ul>
					</section>
				</div>
			</div>
		</div>';
		if (is_active_sidebar('event-sidebar')) { ?>
		<!-- Start Sidebar -->
		<div class="col-md-3 sidebar" id="sidebar-col">
			<?php dynamic_sidebar('event-sidebar'); ?>
		</div><?php } ?>
		<!-- End Sidebar -->
	</div>
</div>
<?php get_footer(); ?>