<?php
wp_enqueue_style('imic_owl1');
wp_enqueue_style('imic_owl2');
wp_enqueue_script('imic_owl_carousel');
wp_enqueue_script('imic_owl_carousel_init');
$post_title = wp_kses_post($instance['title']);
$numberPosts = (!empty($instance['number_of_posts']))? $instance['number_of_posts'] : 4 ;
$allpostsbtn = wp_kses_post($instance['allpostsbtn']);
$allpostsurl = sow_esc_url($instance['allpostsurl']);
$autoplay = $instance['autoplay'] == 'yes' ? '5000' : '';
$navigation = $instance['navigation'] == 'yes' ? 'yes' : 'no';
$pagination = $instance['pagination'] == 'yes' ? 'yes' : 'no';
?>
<?php
if(!empty($instance['title'])){ ?>
<div class="sidebar-widget-title">
<?php if(!empty($instance['allpostsurl'])){ ?><a href="<?php echo esc_url($allpostsurl); ?>" class="btn btn-primary float-end push-btn"><?php echo esc_attr($allpostsbtn); ?></a><?php } ?>
<h3 class="widgettitle"><?php echo esc_attr($post_title); ?></h3>
</div>
<?php } ?>
<div class="carousel-container">
<ul class="partner-logos owl-carousel owl-theme" data-columns="<?php echo esc_attr($numberPosts); ?>" data-autoplay="<?php echo esc_attr($autoplay); ?>" data-pagination="<?php echo esc_attr($pagination); ?>" data-arrows="<?php echo esc_attr($navigation); ?>" data-single-item="no" data-items-desktop="<?php echo esc_attr($numberPosts); ?>" data-items-desktop-small="3" data-items-tablet="2" data-items-mobile="1">
<?php foreach( $instance['images'] as $i => $image ) : ?>
<?php
if( !empty($image['icon_image']) ) {
	$src = wp_get_attachment_image_src($image['icon_image'], $image['icon_size']);
if( !empty($src) ) {
	$attr = array(
		'src' => $src[0],
		'width' => $src[1],
		'height' => $src[2],
	);
}
		?><li class="item"><?php if(!empty( $image['more_url'] )){ ?><a href="<?php echo sow_esc_url( $image['more_url'] ); ?>" <?php echo ''.( $image['new_window'] ? 'target="_blank"' : '' ); ?>><?php } ?><img <?php foreach($attr as $n => $v) echo ''.$n.'="' . esc_attr($v) . '" ' ?> alt="image"><?php if(!empty( $image['more_url'] )){ ?></a><?php } ?>
        <?php if(!empty($image['icon_title'])) : ?>
            <h5>
                <?php echo wp_kses_post( $image['icon_title'] ) ?>
            </h5>
        <?php endif; ?>
        </li><?php
	
} ?>
<?php endforeach; ?>
</ul>
</div>