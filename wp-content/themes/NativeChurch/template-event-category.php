<?php
/*
Template Name: Event Category
 */
get_header();
$site_lang = substr(get_locale(), 0, 2);
$imic_event_category_page_url = get_permalink();
$pageOptions = imic_page_design(); //page design options
imic_sidebar_position_module();
?>
<div class="container">
<div class="row main-content-row">
<div class="<?php echo esc_attr($pageOptions['class']); ?> posts-archive" id="content-col">
<?php
while (have_posts()): the_post();
    echo '<div class="page-content">';
    the_content();
    echo '</div>';
endwhile;
wp_reset_postdata();
$event_add = array();
$rec = 1;
$no_event = 0;
$today = date_i18n('Y-m-d');
$event_cat = get_query_var('event_cat');
$event_cat = imic_get_term_category(get_the_ID(), 'imic_advanced_event_list_taxonomy');
$event_cat = !empty($event_cat) ? $event_cat : '';
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$saved_future_events = get_option('nativechurch_saved_future_events_' . $site_lang);
if ($saved_future_events) {
    $saved_events_raw = $saved_future_events;
} else {
    $saved_events_raw = imic_recur_events('future', 'nos', '', '', 'save');
}
$event_add = $saved_events_raw;
if ($event_cat) {
    $events_objects = nativechurch_get_term_objects(explode(',', $event_cat));
    $event_add = array_intersect($saved_events_raw, $events_objects);
}
$now = date_i18n('U');
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$count = 1;
$saiji = 1;
$perPage = get_option('posts_per_page');
$paginate = 1;
if ($paged > 1) {
    $paginate = ($paged - 1) * $perPage;
    $paginate = $paginate + 1;}
$google_events = nativechurch_fetch_google_events();
if (empty($event_cat)) {
    $new_events = $google_events + $event_add;
} else {
    $new_events = $event_add;
}
$TotalEvents = count($new_events);
if ($TotalEvents % $perPage == 0) {
    $TotalPages = $TotalEvents / $perPage;
} else {
    $TotalPages = $TotalEvents / $perPage;
    $TotalPages = $TotalPages + 1;
}
if (!empty($new_events)) {
    ksort($new_events);
    foreach ($new_events as $key => $value) {
        if (preg_match('/^[0-9]+$/', $value)) {
            $google_flag = 1;
        } else {
            $google_flag = 2;
        }
        if ($google_flag == 1) {
            $frequency = get_post_meta(get_the_ID(), 'imic_event_frequency', true);
            $frequency_count = get_post_meta(get_the_ID(), 'imic_event_frequency_count', true);
            switch ($frequency) {
                case 1:
                    $recur = 'Every Day';
                    break;
                case 2:
                    $recur = 'Every Second Day';
                    break;
                case 3:
                    $recur = 'Every Third Day';
                    break;
                case 4:
                    $recur = 'Every Fourth Day';
                    break;
                case 5:
                    $recur = 'Every Fifth Day';
                    break;
                case 6:
                    $recur = 'Every Sixth Day';
                    break;
                case 7:
                    $recur = 'Every Week';
                    break;
                case 30:
                    $recur = 'Every Month';
                    break;
            }
            $icon = '';
            setup_postdata(get_post($value));
            $terms = wp_get_post_terms($value, 'event-category');
            $output = '';
            foreach ($terms as $terms_data) {
                $term_link = imic_query_arg_event_cat($terms_data->slug, $imic_event_category_page_url);
                $output .= '<a href="'
                . $term_link . '">'
                . $terms_data->name
                    . "</a>  ";
            }}
        if ($count == $paginate && $saiji <= $perPage) {$paginate++;
            $saiji++;
            if ($google_flag == 1) {
                $frequency = get_post_meta($value, 'imic_event_frequency', true);
                if ('' != get_the_post_thumbnail($value)) {
                    $class = "col-md-8 col-sm-8";
                } else {
                    $class = "col-md-12 col-sm-12";
                }
            }
            echo '<article class="post taxonomy-event">
<div class="row">';
            if ($google_flag == 1) {
                $date_converted = date_i18n('Y-m-d', $key);
                $custom_event_url = imic_query_arg($date_converted, $value);
                if ('' != get_the_post_thumbnail($value)):
                    echo '<div class="col-md-4 col-sm-4">
			<a href="' . $custom_event_url . '">';
                    echo get_the_post_thumbnail($value, '600x400', array('class' => "img-thumbnail"));
                    echo '</a></div>';
                endif;
                $event_title = get_the_title($value);
            }
            if ($google_flag == 2) {
                $class = "col-md-12 col-sm-12";
                $google_data = (explode('!', $value));
                $event_title = $google_data[0];
                $custom_event_url = $google_data[1];
            }
            echo '<div class="' . $class . '">';
            echo '<h3><a href="' . $custom_event_url . '">' . $event_title . '</a>' . imicRecurrenceIcon($value) . '</h3>';
            echo '<span class="post-meta meta-data">
<span><i class="fa fa-calendar"></i>' . date_i18n(get_option('date_format'), $key) . '</span>';
            if ($google_flag == 1) {
                echo '<span><i class="fa fa-archive"></i>' . $output . '</span>';
            }
            echo '</span>';
            if ($google_flag == 1) {
                echo '<div class="page-content">';
                echo imic_excerpt_id($value,50);
                echo '</div>';
            }
            echo '<p><a href="' . $custom_event_url . '" class="btn btn-primary">' . esc_html__('Continue reading', 'framework') . '<i class="fa fa-long-arrow-right"></i></a></p>';
            echo '</div></div>';
            echo '</article>';
        }
        $count++;}
    wp_reset_postdata();
    $TotalPages = floor($TotalPages);
    if ($TotalPages > 1) {
        pagination($TotalPages, $perPage);}
} else {
    echo '<article class="post">';
    ?>
<h3><?php _e('There are no future events to show.', 'framework');?></h3>
<?php
echo '</article>';
}
?>
</div>
<?php if (!empty($pageOptions['sidebar'])) {?>
<!-- Start Sidebar -->
<div class="col-md-3 sidebar" id="sidebar-col">
    <?php dynamic_sidebar($pageOptions['sidebar']);?>
</div>
<!-- End Sidebar -->
<?php }?>
</div>
</div>
<?php get_footer();?>