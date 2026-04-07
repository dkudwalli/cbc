<?php
$the_categories = wp_kses_post($instance['categories']);
$post_title = wp_kses_post($instance['title']);
$numberPosts = (!empty($instance['number_of_posts'])) ? $instance['number_of_posts'] : 4;
$excerpt_length = wp_kses_post($instance['excerpt_length']);
$read_more_text = wp_kses_post($instance['read_more_text']);
$allpostsbtn = wp_kses_post($instance['allpostsbtn']);
$allpostsurl = sow_esc_url($instance['allpostsurl']);
?>
<?php query_posts(array('post_type' => 'sermons', 'sermons-category' => '' . $the_categories . '', 'posts_per_page' => $numberPosts, 'paged' => get_query_var('paged')));
if (have_posts()) :
	if (!empty($instance['title'])) { ?>
		<div class="sidebar-widget-title">
			<?php if (!empty($instance['allpostsurl'])) { ?><a href="<?php echo esc_url($allpostsurl); ?>" class="btn btn-primary float-end push-btn"><?php echo esc_attr($allpostsbtn); ?></a><?php } ?>
			<h3 class="widgettitle"><?php echo esc_attr($post_title); ?></h3>
		</div>
	<?php } ?>
	<?php if($instance['filters']) : ?>
		<div class="search-filters">
			<?php echo do_shortcode('[imic-searchandfilter]'); ?>
		</div>
	<?php endif; ?>
	<div class="sermon-archive">
		<?php while (have_posts()) : the_post();
			$custom = get_post_custom(get_the_ID());
			$attach_full_audio = imic_sermon_attach_full_audio(get_the_ID());
			$attach_pdf = imic_sermon_attach_full_pdf(get_the_ID()); ?>
			<article class="post sermon">
				<header class="post-title">
					<div class="row">
						<div class="col-md-9 col-sm-9">
							<?php
                            echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
							if ($instance['show_post_meta']) {
								echo '<span class="meta-data"><i class="fa fa-calendar"></i>' . __('Posted on ', 'framework') . get_the_time(get_option('date_format'));
								$pastor = esc_html__('Pastor', 'framework');
								echo get_the_term_list(get_the_ID(), 'sermons-speakers', ' | ' . $pastor . ': ', ', ', '');
								echo '</span>';
							}
							echo '</div>';
							echo '<div class="col-md-3 col-sm-3 sermon-actions">';
							if (!empty($custom['imic_sermons_url'][0])) {
								echo '<a href="' . get_permalink() . '#playvideo" data-placement="top" data-bs-toggle="tooltip" data-original-title="' . __('Video', 'framework') . '" rel="tooltip"><i class="fa fa-video-camera"></i></a>';
							}

							$attach_full_audio = imic_sermon_attach_full_audio(get_the_ID());
							if (!empty($attach_full_audio)) {
								echo '<a href="' . get_permalink() . '#play-audio" data-placement="top" data-bs-toggle="tooltip" data-original-title="' . __('Audio', 'framework') . '" rel="tooltip"><i class="fa fa-headphones"></i></a>';
							}
							echo '<a href="' . get_permalink() . '#read" data-placement="top" data-bs-toggle="tooltip" data-original-title="' . __('Read online', 'framework') . '" rel="tooltip"><i class="fa fa-file-text-o"></i></a>';
							$attach_pdf = imic_sermon_attach_full_pdf(get_the_ID());
							if (!empty($attach_pdf)) {

								echo '<a href="' . $attach_pdf . '" download data-placement="top" data-bs-toggle="tooltip" data-original-title="' . __('Download PDF', 'framework') . '" rel="tooltip"><i class="fa fa-book"></i></a>';
							}
							echo '</div>
</div>';
							echo '</header>
	  <div class="post-content">
		  <div class="row">';
							if ('' != get_the_post_thumbnail()) {
								$class = "col-md-8 col-sm-8";
							} else {
								$class = "col-md-12 col-sm-12";
							}
							if (has_post_thumbnail()) : ?>
								<div class="col-md-4">
									<a href="<?php the_permalink() ?>" class="media-box">
										<?php
										the_post_thumbnail('600x400', array('class' => "img-thumbnail"));
										?>
									</a>
								</div>
							<?php endif;
							echo '<div class="' . $class . '">';

							if ($excerpt_length != "") {
								echo '<div class="page-content">';
								echo imic_excerpt($excerpt_length);
								echo '</div>';
							}
							if ($read_more_text != "") {
								echo '<p><a href="' . get_permalink() . '" class="btn btn-primary">' . $read_more_text . ' <i class="fa fa-long-arrow-right"></i></a></p>';
							}
							echo '</div></div></div>';
							echo '</article>';
							?>
						<?php endwhile;
						if($instance['pagination']) : 
							if (function_exists("pagination")) {
								pagination();
							}
						endif;
						wp_reset_query(); ?>
					</div>
				<?php endif; ?>