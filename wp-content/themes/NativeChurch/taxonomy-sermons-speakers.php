<?php get_header();
$pages_e = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'template-sermons.php'
));
$variable_post_id = (!empty($pages_e)) ? $pages_e[0]->ID : '';
$SermonPageURL = get_permalink($variable_post_id);
$pageOptions = imic_page_design($variable_post_id, 9); //page design options 
imic_sidebar_position_module();
$options = get_option('imic_options');
?>
<div class="container">
    <div class="row main-content-row">
        <div class="<?php echo esc_attr($pageOptions['class']); ?> sermon-archive" id="content-col">
            <!-- Sermons Listing -->
            <?php
            if (have_posts()) : ?>
                <?php if (isset($options['switch_sermon_filters']) && $options['switch_sermon_filters'] == 1) { ?>
                    <div class="search-filters">
                        <?php echo do_shortcode('[imic-searchandfilter categories="' . get_query_var('sermons-category') . '" tags="' . get_query_var('sermons-tag') . '" speakers="' . get_query_var('sermons-speakers') . '"]'); ?>
                    </div>
                <?php } ?>
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
                                echo '<a href="' . get_permalink() . '#playvideo" class="play-video-link" data-placement="top" data-bs-toggle="tooltip" data-original-title="' . esc_html__('Video', 'framework') . '" rel="tooltip"><i class="fa fa-video-camera"></i></a>';
                            }
                            $attach_full_audio = imic_sermon_attach_full_audio(get_the_ID());
                            if (!empty($attach_full_audio)) {
                                echo '<a href="' . get_permalink() . '#play-audio" class="play-audio-link" data-placement="top" data-bs-toggle="tooltip" data-original-title="' . esc_html__('Audio', 'framework') . '" rel="tooltip"><i class="fa fa-headphones"></i></a>';
                            }
                            echo '<a href="' . get_permalink() . '#read" class="read-online-link" data-placement="top" data-bs-toggle="tooltip" data-original-title="' . esc_html__('Read online', 'framework') . '" rel="tooltip"><i class="fa fa-file-text-o"></i></a>';
                            $attach_pdf = imic_sermon_attach_full_pdf(get_the_ID());
                            if (!empty($attach_pdf)) {
                                echo '<a href="' . $attach_pdf . '" download class="download-pdf-link" data-placement="top" data-bs-toggle="tooltip" data-original-title="' . esc_html__('Download PDF', 'framework') . '" rel="tooltip"><i class="fa fa-book"></i></a>';
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

                                    <?php
                                    echo '<div class="page-content">';
                                    echo imic_excerpt(100);
                                    echo '</div>';
                                    ?>
                                    <p><a href="<?php the_permalink() ?>" class="btn btn-primary"><?php _e('Continue reading ', 'framework'); ?> <i class="fa fa-long-arrow-right"></i></a></p>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php
            endwhile;
        else :
            echo '<article class="post">';
            if (current_user_can('edit_posts')) :
                ?>
                    <h3><?php _e('No posts to display', 'framework'); ?></h3>
                    <p><?php printf(__('Ready to publish your first post? <a href="%s">Get started here</a>.', 'framework'), admin_url('post-new.php?post_type=sermons')); ?></p>
                <?php else : ?>
                    <h3><?php _e('Nothing Found', 'framework'); ?></h3>
                    <p><?php printf(esc_html__('Apologies, but no results were found. Perhaps searching will help find a related post..', 'framework')); ?></p>
                    <?php
                    echo '</article>';
                endif;
            endif; // end have_posts() 
            pagination();
            ?>
        </div>
        <?php if (!empty($pageOptions['sidebar'])) { ?>
            <!-- Start Sidebar -->
            <div class="col-md-3 sidebar" id="sidebar-col">
                <?php dynamic_sidebar($pageOptions['sidebar']); ?>
            </div>
            <!-- End Sidebar -->
        <?php } ?>
    </div>
</div>
<?php get_footer(); ?>