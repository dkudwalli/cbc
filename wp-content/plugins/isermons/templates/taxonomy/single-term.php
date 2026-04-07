<!--
This template is to use for showing sermons taxonomy content.
**
This template can be overridden by copying it to yourtheme/templates/taxonomy/single-term.php.
-->
<div class="isermons-single-header">
    <a target="_blank" href="<?php echo esc_url(site_url('feed?post_type=imi_isermons&'.$params['taxonomy'].'='.$params['term_slug'])); ?>" class="pull-right isermons-feed-link">
        <label><?php esc_html_e('Subscribe to RSS Feed', 'isermons'); ?></label> <i class="isermons-icon-feed"></i>
    </a>

    <a href="<?php echo wp_get_referer(); ?>" class="isermons-btn isermons-btn-light">
        <i class="isermons-icon-arrow-left"></i> <?php esc_html_e('Back To Archive', 'isermons'); ?>
    </a>
</div>
<div class="isermons-single-content">
    <div class="isermons-row equah">
        <?php
		$image_url = array('');
        $mid_class = "6";
        if($params['image']!='')
        {
            $mid_class = "3";
            $image = wp_get_attachment_image_src($params['image_id'], $params['image']);
            // wp_get_attachment_image_src returns false on failure (PHP 8 notice fix)
            $image_url = ( $image && is_array($image) ) ? $image[0] : '';
        ?>
        <div class="isermons-col1by3 equah-item">
            <div class="isermons-media">
                <?php if($image_url)
                {
                  $isermons_default_color = isermons_get_settings('isermons_default_color');
                  $isermons_color = ($isermons_default_color)?$isermons_default_color:'#007F7B';
                  $current_term = get_queried_object();
                  $posts = get_objects_in_term($current_term->term_id, $current_term->taxonomy);
                  if($posts){
                    $post_id = $posts[0];
                    $post_permalink = get_permalink($post_id);
                  }
                  if($post_permalink){
                    echo '<div class="isermons-media">
                      <a href="'.esc_url($post_permalink).'" class="isermons-media-box equah-item1">
                        <img src="'.esc_url($image_url).'" alt="Series1">
                        <div class="isermons-default-placeholder" style="background-color: '.$isermons_color.'"></div>
                      </a>
                    </div>';
                  } else{
                    echo '<img src="'.esc_url($image_url).'" alt="Series">';
                  }
                } else {
                    echo '<div class="isermons-media-box"><div class="isermons-default-placeholder"></div></div>';
                } ?>
            </div>
        </div>
        <?php
        }
        ?>
        <div class="isermons-col1by<?php echo esc_attr($mid_class); ?> equah-item">
            <h2><?php echo wp_kses($params['term_name'], isermons_allowed_html()); ?></h2>
            <?php
            if(isset($params['max_date']))
            {
                echo '<div class="isermons-meta-data">'.date_i18n(get_option('date_format'), strtotime($params['min_date'])).' - '.date_i18n(get_option('date_format'), strtotime($params['max_date'])).'</div>';
            }
            else
            {
                echo '<div class="isermons-meta-data">'.date_i18n(get_option('date_format'), strtotime($params['min_date'])).'</div>';
            }
            ?>

            <?php
            if(!empty($params['preachers']))
            {
                echo '<div class="isermons-single-speakers">
                        <div class="isermons-meta-data">'.esc_html__('Speakers', 'isermons').'</div>
                        <ul>';
                foreach($params['preachers'] as $key=>$value)
                {
                    echo '<li class=" isermons-tip-top-right isermons-tip-rounded" tooltip-label="'.esc_attr($value['name']).'"><img src="'.esc_url($value['image']).'" alt=""></li>';
                }
                echo '</ul>
                    </div>';
            }
            ?>

        </div>
        <div class="isermons-col1by3 equah-item">
            <p><?php echo wp_kses($params['desc'], isermons_allowed_html()); ?></p>
        </div>
    </div>
</div>
<div class="isermons-spacer-30"></div>
<div class="isermons-inline-title">
    <span>
        <?php
        echo '<strong>' . wp_kses($params['term_name'], isermons_allowed_html()) . '</strong>';
        printf(__(' Includes The Following <strong>%d</strong> Messages', 'isermons'), $params['sermons']);
        ?>
    </span>
</div>

<?php
	// JS for the sermon listings counter
	wp_enqueue_script('isermons-term-counter', ISERMONS__PLUGIN_URL . 'js/term-counter.js', array('jquery'), '', true);
	$term_orderby = isermons_get_settings('isermons_tax_orderby') != ''?isermons_get_settings('isermons_tax_orderby'):'date';
	$term_order = isermons_get_settings('isermons_tax_order') != ''?isermons_get_settings('isermons_tax_order'):'DESC';
	$watch = isermons_get_settings('isermons_term_sermons_watch') != ''?isermons_get_settings('isermons_term_sermons_watch'):'Watch Sermon';
	$listen = isermons_get_settings('isermons_term_sermons_listen') != ''?isermons_get_settings('isermons_term_sermons_listen'):'Listen Sermon';
	$details = isermons_get_settings('isermons_term_sermons_details') != ''?isermons_get_settings('isermons_term_sermons_details'):'View Sermon';
	$col = isermons_get_settings('isermons_related_term_col') != ''?isermons_get_settings('isermons_related_term_col'):'4';
	$number = isermons_get_settings('isermons_term_sermons_page') != ''?isermons_get_settings('isermons_term_sermons_page'):'10';
?>
<?php echo do_shortcode('[isermons-list orderby="'.$term_orderby.'" order="'.$term_order.'" layout="minimal" relation="categories" search="" filters="" filters_operator="AND" watch="'.$watch.'" listen="'.$listen.'" details="'.$details.'" hover="enable" per_page="'.$number.'" pagination="yes" meta_data="preacher,books,date,series,video,audio,download" '.esc_attr($params['taxonomy']).'="'.esc_attr($params['term_id']).'" image="isermons-200-200"]'); ?>

<div class="isermons-inline-title"><span><?php esc_html_e('Related', 'isermons'); ?></span></div>
<?php echo do_shortcode('[isermons-terms count="4" layout="style2" columns="'.$col.'" taxonomy="'.esc_attr($params['taxonomy']).'" filters_order="id" single="1"]'); ?>
<?php isermons_append_template_with_arguments('templates/sermons/retagger', 'script', []); ?>