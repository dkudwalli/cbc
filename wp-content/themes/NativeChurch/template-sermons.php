<?php
/*
  Template Name: sermons
 */
get_header();
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module();
$options = get_option('imic_options');
?>

<div class="container">
    <div class="row main-content-row">
        <div class="<?php echo esc_attr($pageOptions['class']); ?>" id="content-col">
            <?php

            while (have_posts()) : the_post();
                if ($post->post_content != "") :
                    echo '<div class="page-content">';
                    the_content();
                    echo '</div>';
                    echo '<div class="spacer-20"></div>';
                endif;
            endwhile;
             if (isset($options['switch_sermon_filters']) && $options['switch_sermon_filters'] == 1) { ?>
                <div class="search-filters">
                    <?php echo do_shortcode('[imic-searchandfilter]'); ?>
                </div>
            <?php } ?>
            <?php
            query_posts(array(
                'post_type' => 'sermons',
                'paged' => get_query_var('paged')
            ));
            if (have_posts()) : ?>
                <div class="sermon-archive">
                    <!-- Sermons Listing -->
                    <?php
                    while (have_posts()) : the_post();
                        if ('' != get_the_post_thumbnail()) {
                            $class = "col-md-8";
                        } else {
                            $class = "col-md-12";
                        }
                        $custom = get_post_custom(get_the_ID());
                        ?>
                        <article class="post sermon">
                            <header class="post-title">
                                <?php
                                echo '<div class="row">
      					<div class="col-md-9 col-sm-9">
            				<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                                echo '<span class="meta-data"><i class="fa fa-calendar"></i>' . esc_html__('Posted on ', 'framework') . get_the_time(get_option('date_format'));
                                echo get_the_term_list(get_the_ID(), 'sermons-speakers', ' | ' . esc_html__('Pastor: ', 'framework'), ', ', '');
                                echo '</span>
                                         </div>';
                                echo '<div class="col-md-3 col-sm-3 sermon-actions">';
                                if (!empty($custom['imic_sermons_url'][0])) {
                                    echo '<a href="' . get_permalink() . '#playvideo" data-placement="top" data-bs-toggle="tooltip" data-original-title="' . esc_html__('Video', 'framework') . '" rel="tooltip"><i class="fa fa-video-camera"></i></a>';
                                }

                                $attach_full_audio = imic_sermon_attach_full_audio(get_the_ID());
                                if (!empty($attach_full_audio)) {
                                    echo '<a href="' . get_permalink() . '#play-audio" data-placement="top" data-bs-toggle="tooltip" data-original-title="' . esc_html__('Audio', 'framework') . '" rel="tooltip"><i class="fa fa-headphones"></i></a>';
                                }
                                echo '<a href="' . get_permalink() . '#read" data-placement="top" data-bs-toggle="tooltip" data-original-title="' . esc_html__('Read online', 'framework') . '" rel="tooltip"><i class="fa fa-file-text-o"></i></a>';
                                $attach_pdf = imic_sermon_attach_full_pdf(get_the_ID());
                                if (!empty($attach_pdf)) {

                                    echo '<a href="' . $attach_pdf . '" download data-placement="top" data-bs-toggle="tooltip" data-original-title="' . esc_html__('Download PDF', 'framework') . '" rel="tooltip"><i class="fa fa-book"></i></a>';
                                }
                                echo '</div>
                 	</div>';
                                ?>
                            </header>
                            <div class="post-content">
                                <div class="row">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="col-md-4">
                                            <a href="<?php the_permalink() ?>" class="media-box">
                                                <?php
                                                the_post_thumbnail('600x400', array('class' => "img-thumbnail"));
                                                ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="<?php echo esc_attr($class); ?>">
                                        <div class="page-content">
                                            <?php echo imic_excerpt(100); ?>
                                        </div>
                                        <p><a href="<?php the_permalink() ?>" class="btn btn-primary"><?php _e('Continue reading ', 'framework'); ?> <i class="fa fa-long-arrow-right"></i></a></p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endwhile;
                if (function_exists("pagination")) {
                    pagination();
                } ?>
                </div>
            </div>
        <?php endif;
		if (!empty($pageOptions['sidebar'])) { ?>
            <!-- Start Sidebar -->
            <div class="col-md-3 sidebar" id="sidebar-col">
                <?php dynamic_sidebar($pageOptions['sidebar']); ?>
            </div>
            <!-- End Sidebar -->
        <?php } ?>
    </div>
</div>
<?php get_footer(); ?>