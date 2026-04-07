<?php get_header();
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module();
$options = get_option('imic_options'); ?>
<div class="container">
    <div class="row main-content-row">
        <div class="<?php echo esc_attr($pageOptions['class']); ?>" id="content-col">
            <?php while (have_posts()) : the_post(); ?>
                <header class="single-post-header clearfix">
                    <div class="float-end post-comments-count">
                        <?php comments_popup_link('<i class="fa fa-comment"></i>' . esc_html__('No comments yet', 'framework'), '<i class="fa fa-comment"></i>1', '<i class="fa fa-comment"></i>%', 'comments-link', ''); ?>
                    </div>
                    <h2 class="post-title"><?php the_title() ?></h2>
                </header>
                <article class="post-content">
                    <span class="post-meta meta-data">
                        <span class="post-date-meta"><i class="fa fa-calendar"></i>
                            <?php
                            _e('Posted on ', 'framework');
                            echo get_the_time(get_option('date_format'));
                            $cats = get_the_category();
                            ?>
                        </span>
                        <span class="post-author-meta"><i class="fa fa-user"></i><?php esc_html_e(' Posted By: ', 'framework'); ?>
                            <?php echo get_the_author_meta('display_name'); ?></span><span class="post-category-meta"><i class="fa fa-archive"></i>
                            <?php
                            _e('Categories: ', 'framework');
                            the_category(', '); ?>
                            </span></span>
                    <?php
                    if (has_post_thumbnail()) :
                        echo '<div class="featured-image">';
                        the_post_thumbnail('full');
                        echo '</div>';
                    endif;
                    echo '<div class="page-content margin-20">';
                    the_content();
                    wp_link_pages(array(
                        'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__('Pages:', 'framework') . '</span>',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                        'pagelink'    => '<span class="screen-reader-text"></span>%',
                        'separator'   => '<span class="screen-reader-text">/ </span>',
                    ));
                    echo '</div><div class="clearfix"></div>';
                    if (has_tag()) {
                        echo '<div class="post-meta">';
                        echo '<i class="fa fa-tags"></i>';
                        the_tags('', ', ');
                        echo '</div>';
                    }
                    if (isset($options['switch_sharing']) && $options['switch_sharing'] == 1) {
                        if($options['share_post_types']['1'] == '1'){
							imic_share_buttons();
						}
                    } ?>
                </article>
				
            <?php endwhile;
            comments_template('', true); ?>
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