<?php
if(is_front_page()){
    $page_on_front= get_option('page_on_front');
    if(!empty($page_on_front)){
  $home_id= get_option('page_on_front');
    }
}else{
	$home_id = $id;
}
$custom_home = get_post_custom($home_id);
$height = get_post_meta($home_id,'imic_pages_slider_height',true);
$height = ($height == '') ? '150' : $height;
$breadpad = $height - 60;
echo '<style type="text/css">' . "\n";
echo '.hero-slider{height:'.$height.'px;}';
echo '.hero-slider.flexslider ul.slides li{height:'.$height.'px;}';
echo '.body ol.breadcrumb{padding-top:'.$breadpad.'px;}';
echo "</style>" . "\n";
$imic_Choose_slider_display = get_post_meta($home_id, 'imic_pages_Choose_slider_display', true);
if ($imic_Choose_slider_display == 1) {
	$speed = (get_post_meta($home_id, 'imic_pages_slider_speed', true)!='')?get_post_meta($home_id, 'imic_pages_slider_speed', true):5000;
    $pagination = get_post_meta($home_id, 'imic_pages_slider_pagination', true);
    $auto_slide = get_post_meta($home_id, 'imic_pages_slider_auto_slide', true);
    $direction = get_post_meta($home_id, 'imic_pages_slider_direction_arrows', true);
    $effect = get_post_meta($home_id, 'imic_pages_slider_effects', true);
    if (count(get_post_meta($home_id, 'imic_pages_slider_image', false)) > 0) {
        ?><div style="position:relative;">
            <div class="hero-slider flexslider clearfix" data-autoplay=<?php echo esc_attr($auto_slide); ?> data-pagination=<?php echo esc_attr($pagination); ?> data-arrows=<?php echo esc_attr($direction); ?> data-style=<?php echo esc_attr($effect); ?> data-pause="yes" data-speed=<?php echo esc_attr($speed); ?>>
                <ul class="slides">
                    <?php
                    foreach ($custom_home['imic_pages_slider_image'] as $custom_home_image) {
                        $src = wp_get_attachment_image_src($custom_home_image, 'full');
                        $attachment_meta = imic_wp_get_attachment($custom_home_image);
                        $caption = $attachment_meta['caption'];
                        $slide_meta = '';
                        $url = $attachment_meta['url'];
                        if (!empty($url)) {
                            $slide_meta = '<a href="' . $url . '"></a>';
                        }
                        if (!empty($caption)) {
                            $slide_meta = '<span class="container">
                                                              <span class="slider-caption">
                                                                       <span>' . $caption . '</span>
                                                              </span>
                                               </span>';
                        }
                        if (!empty($caption) && ($url)) {
                            $slide_meta = '<a href="' . $url . '"><span class="container">
                                                              <span class="slider-caption">
                                                                       <span>' . $caption . '</span>
                                                              </span>
                                               </span></a>';
                        }
                        echo'<li class=" parallax" style="background-image:url(' . $src[0] . ');">' . $slide_meta . '</li>';
                    }
                    ?>
                </ul>
        </div>
        <div class="container">
              <ol class="breadcrumb" style="padding-top: 0;position: absolute;bottom: 0px;z-index: 22;">
                  <?php
                  if (function_exists('bcn_display_list')) {
                      bcn_display_list();
                  }
                  ?>
              </ol>
        </div>
        </div>
        <?php
    }
} elseif ($imic_Choose_slider_display == 2) {
    $rev_slider = get_post_meta($home_id, 'imic_pages_select_revolution_from_list', true);
	echo '<div class="slider-revolution-new">';
    if (has_shortcode($rev_slider, 'rev_slider')) {
        $rev_slider = preg_replace('/\\\\/', '', $rev_slider);
    } else {
        if (class_exists('RevSlider')) {
            $sld = new RevSlider();
            $sliders = $sld->getArrSliders();
            if (!empty($sliders)) {
                foreach ($sliders as $slider) {
                    if ($slider->id != $rev_slider) continue;
                    $rev_slider = $slider->getParam('shortcode', 'false');
                }
            }
        }
    }
    echo do_shortcode(stripslashes($rev_slider)); ?>
	<div class="container">
          <ol class="breadcrumb" style="padding-top: 0;position: absolute;bottom: 0px;z-index: 22;">
              <?php
              if (function_exists('bcn_display_list')) {
                  bcn_display_list();
              }
              ?>
          </ol>
    </div>
	</div>
    <?php 
} else {
	$rev_slider = get_post_meta($home_id, 'imic_pages_select_smart_from_list', true);
	echo '<div class="smart-slider-new">';
    if (defined('NEXTEND_SMARTSLIDER_3_URL_PATH')) {
    	echo do_shortcode('[smartslider3 slider="1"]');
    }
    ?>
	<div class="container">
          <ol class="breadcrumb" style="padding-top: 0;position: absolute;bottom: 0px;z-index: 22;">
              <?php
              if (function_exists('bcn_display_list')) {
                  bcn_display_list();
              }
              ?>
          </ol>
    </div>
	</div>
	<?php
}
?>