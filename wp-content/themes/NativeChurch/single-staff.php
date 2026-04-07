<?php get_header(); 
global $framework_allowed_tags;
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module();
$options = get_option('imic_options'); ?>
<div class="container">
    <div class="row main-content-row">
        <div class="<?php echo esc_attr($pageOptions['class']); ?>" id="content-col"> 
            <header class="post-title">
            <?php
            echo'<h2>'. get_the_title() .'</h2>';
            echo'<span class="meta-data">';
            echo'<div class="spacer-10"></div>';
            echo get_the_term_list(get_the_ID(), 'staff-category',esc_html__('Categories','framework').': ', ', ', '');
            echo'</span>';
            ?>
            </header>
            <article class="post-content single-staff-page">
            <?php if (has_post_thumbnail()): ?>
            <div class="featured-image">
            <?php
            the_post_thumbnail('full');
            ?>
            </div>
            <?php
            endif;
            while (have_posts()):the_post();
			echo '<div class="page-content">';
            the_content();
			echo '</div>';
            endwhile;
            $job_title = get_post_meta(get_the_ID(), 'imic_staff_job_title', true);
            $job = '';
            if (!empty($job_title)) {
            $job = '<div class="meta-data">' .esc_html__('Position','framework').': '. $job_title . '</div>';
            }
            $output = '';
            $output .= $job;
            $output .= imic_social_staff_icon();
            echo wp_kses($output, $framework_allowed_tags);
            ?>	
            </article>
        </div>
        <?php if(!empty($pageOptions['sidebar'])){ ?>
        <!-- Start Sidebar -->
        <div class="col-md-3 sidebar" id="sidebar-col">
            <?php dynamic_sidebar($pageOptions['sidebar']); ?>
        </div>
        <!-- End Sidebar -->
        <?php } ?>
    </div>
</div>
<?php get_footer(); ?>