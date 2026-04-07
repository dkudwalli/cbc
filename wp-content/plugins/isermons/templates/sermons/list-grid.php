<!--
This template is to use for showing sermons in grid view.
**
This template can be overridden by copying it to yourtheme/templates/sermons/list-grid.php.
-->
<div class="isermons isermons-sermons-grid isermons-grid isermons-listings-view isermons-grid-col<?php echo wp_kses($params['column'], isermons_allowed_html()); ?>">
			<ul class="equah equah1">
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
                ?>
				<li class="isermons-grid-item isermons-sermons-grid-item">
					<div class="isermons-media equah-item1 <?php echo esc_attr($mediaclass); ?>">
						<a href="<?php the_permalink(); ?>" class="isermons-media-box">
							<?php the_post_thumbnail($params['image'],['class' => 'isermons-term-image']); ?>
							<?php if(!has_post_thumbnail()){ ?>
								 <div class="isermons-default-placeholder"></div>
							<?php } ?>
						</a>
					</div>
					<div class="isermons-grid-item-in equah-item">
						<?php
                            do_action('isermons_add_video_section', '', get_the_ID(), $params['meta_data']);
                            do_action('isermons_add_audio_section', '', get_the_ID(), $params['meta_data']);
                        ?>
						
						<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
						<?php
                        do_action('isermons_meta_data_display', '', get_the_ID(), $params['meta_data']);
                        do_action('isermons_add_meta_and_media_actions', '', get_the_ID(), $params['meta_data']);
                        ?>
					</div>
				</li>
                <?php
                endwhile; endif;
                ?>
            </ul>
</div>
<?php isermons_append_template_with_arguments('templates/sermons/retagger', 'script', []); ?>