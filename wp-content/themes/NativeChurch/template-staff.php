<?php
/*
  Template Name: Staff
 */
get_header();
global $framework_allowed_tags;
$variable_post_id =  get_the_ID();
$number = get_post_meta(get_the_ID(), 'imic_staff_to_show_on', true);
$column = get_post_meta(get_the_ID(), 'imic_staff_grid_column', true);
$excerpt_length = get_post_meta(get_the_ID(), 'imic_staff_excerpt_length', true);
if ($excerpt_length == '') {
    $excerpt_length = 30;
}
$order = get_post_meta(get_the_ID(), 'imic_staff_select_orderby', true);
$staff_category = imic_get_term_category(get_the_ID(), 'imic_advanced_staff_taxonomy', 'staff-category');
$pageOptions = imic_page_design(); //page design options 
imic_sidebar_position_module();
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
            endwhile; ?>
            <div class="row">
                <?php $sort_order = "ASC";
				if ($order == "ID") {
                    $sort_order = "DESC";
                } else {
                    $sort_order = get_post_meta(get_the_ID(), 'imic_staff_select_order', true);
                }
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                query_posts(array(
                    'post_type' => 'staff',
                    'posts_per_page' => $number,
                    'orderby' => $order,
                    'staff-category' => $staff_category,
                    'order' => $sort_order,
                    'paged' => $paged
                ));
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        $custom = get_post_custom(get_the_ID());
                        echo '<div class="col-md-'.$column.' col-sm-4">
                            <div class="grid-item staff-item"> 
                                <div class="grid-item-inner">';
                        if (has_post_thumbnail()) :
                            echo '<div class="media-box"><a href="' . get_permalink(get_the_ID()) . '">';
                            echo get_the_post_thumbnail(get_the_ID(), '600x400');
                            echo '</a></div>';
                        endif;
                        $job_title = get_post_meta(get_the_ID(), 'imic_staff_job_title', true);
                        $job = '';
                        if (!empty($job_title)) {
                            $job = '<div class="meta-data">' . $job_title . '</div>';
                        }
                        echo '<div class="grid-content">
                                   <h3> <a href="' . get_permalink(get_the_ID()) . '">' . get_the_title() . '</a></h3>';
                        echo wp_kses($job, $framework_allowed_tags);
                        $staff_icons = get_post_meta(get_the_ID(), 'imic_social_icon_list', false);
                        echo imic_social_staff_icon();
                        $description = imic_excerpt($excerpt_length);
                        if ($excerpt_length != 0) {
                            echo '<div class="page-content">';
                            if (!empty($description)) {
                                echo '' . $description;
                            }
                            echo '</div>';
                        }
                        if ($excerpt_length != 0) {
                            $staff_read_more_text = (isset($imic_options['staff_read_more_text'])) ? $imic_options['staff_read_more_text'] : '';
                            if (isset($imic_options['switch_staff_read_more']) && $imic_options['switch_staff_read_more'] == 1 && $imic_options['staff_read_more'] == '0') {
                                echo '<p><a href="' . get_permalink() . '" class="btn btn-default">' . $staff_read_more_text . '</a></p>';
                            } elseif (isset($imic_options['switch_staff_read_more']) && $imic_options['switch_staff_read_more'] == 1 && $imic_options['staff_read_more'] == '1') {
                                echo '<p><a href="' . get_permalink() . '">' . $staff_read_more_text . '</a></p>';
                            }
                        }
                        echo '</div></div>
                            </div>
                        </div>';
                    endwhile;
                endif;
                echo '<div class="clear"></div>';
                if (function_exists("pagination")) {
                    pagination();
                }
                wp_reset_query();
                ?>
            </div>
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