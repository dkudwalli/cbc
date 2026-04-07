<!--
This template is to use for showing sermons in minimal list view.
**
This template can be overridden by copying it to yourtheme/templates/sermons/list-minimal.php.
-->
<div class="isermons isermons-sermons-list isermons-sermons-list-minimal isermons-listings-view isermons-list">
	<ul>
		<?php
            if($params['query']->have_posts()):while($params['query']->have_posts()):$params['query']->the_post();
			$videoAva = get_post_meta(get_the_ID(), 'isermons_video_url', true);
			$audioAva = get_post_meta(get_the_ID(), 'isermons_audio_file', true);
			if($videoAva != ''){
				$btntext = $params['watch'];
				$btnclass = 'isermons-btn-play';
				$mediaclass = 'isermons-hover-video';
			} elseif($audioAva != '') {
				$btntext = $params['listen'];
				$btnclass = 'isermons-btn-listen';
				$mediaclass = 'isermons-hover-audio';
			} elseif($videoAva == '' && $audioAva == '') {
				$btntext = $params['details'];
				$btnclass = 'isermons-btn-view';
				$mediaclass = 'isermons-hover-view';
			}
            $redirect_link = ($params['redirect']=='yes')?'href="'.get_permalink().'"':'';
			$sermon_series_image = wp_get_object_terms(get_the_ID(), 'imi_isermons-series');
			$sermon_series_id = (!is_wp_error($sermon_series_image) && !empty($sermon_series_image))?$sermon_series_image[0]:'';
			$sermon_series_image = (!empty($sermon_series_id))?get_term_meta($sermon_series_id->term_id, 'imi_isermons-series_image', true):'';
			$series_image = (!empty($sermon_series_image))?wp_get_attachment_image_src($sermon_series_image, 'isermons-200-200'):array('');
			$series_image = $series_image[0];
        ?>
		<li class="isermons-list-item isermons-sermons-list-item">
			<div>
				<div class="isermons-media <?php echo esc_attr($mediaclass); ?>">
					<a <?php echo wp_kses($redirect_link, isermons_allowed_html()); ?> class="isermons-media-box">
						<?php if(has_post_thumbnail()){
									the_post_thumbnail($params['image']);
							}
							elseif(!empty($series_image))
							{
								echo '<img src="'.esc_url($series_image).'">';
							}
							else
							{
									?>
							 <div class="isermons-default-placeholder isermons-default-placeholder-rel isermons-default-placeholder-min"></div>
						<?php } ?>
					</a>
				</div>
			</div>
			<div>
				<div class="isermons-list-item-in">
					<?php
                    do_action('isermons_add_video_section', '', get_the_ID(), $params['meta_data']);
                    do_action('isermons_add_audio_section', '', get_the_ID(), $params['meta_data']);
                    ?>
				</div>
				<h4 class="series-title"><a <?php echo wp_kses($redirect_link, isermons_allowed_html()); ?>><?php the_title(); ?></a></h4>
				<?php
                do_action('isermons_meta_data_display', '', get_the_ID(), $params['meta_data']);
                ?>
			</div>
			<div class="isermons-sermon-actions-wrapper">
                <?php
                do_action('isermons_add_meta_and_media_actions', '', get_the_ID(), $params['meta_data']);
                ?>
			</div>
            <div>
				<a <?php echo wp_kses($redirect_link, isermons_allowed_html()); ?> class="isermons-btn isermons-btn-primary <?php echo esc_attr($btnclass); ?>">
					<?php echo wp_kses($btntext, isermons_allowed_html());; ?>
				</a>
			</div>
		</li>
        <?php
        endwhile; endif;
        ?>
    </ul>
</div>
<?php isermons_append_template_with_arguments('templates/sermons/retagger', 'script', []); ?>