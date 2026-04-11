<!DOCTYPE html>
<!--// OPEN HTML //-->
<html <?php language_attributes(); ?> class="no-js">

<head>
  <?php
	$id = imi_page_id();
	$options = get_option('imic_options');
	$bodyClass = get_post_meta($id, 'imic_page_layout', true);
	if($bodyClass == ''){
		$bodyClass = (isset($options['site_layout']) && $options['site_layout'] == 'boxed') ? ' boxed' : '';
	}
  ?>
  <!--// SITE META //-->
  <meta charset="<?php bloginfo('charset'); ?>" />
  <!-- Mobile Specific Metas
================================================== -->
  <?php if (isset($options['switch-responsive']) && $options['switch-responsive'] == 1) { ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <?php } ?>
  <!--// PINGBACK & FAVICON //-->
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  <?php if (function_exists('wp_site_icon') && has_site_icon()) {
    echo '<link rel="shortcut icon" href="' . get_site_icon_url() . '" />';
  } else {
    if (isset($options['custom_favicon']) && $options['custom_favicon'] != "") { ?>
  <link rel="shortcut icon" href="<?php echo esc_url($options['custom_favicon']['url']); ?>" />
  <?php }
    }
    if (isset($options['iphone_icon']) && $options['iphone_icon'] != "") { ?>
  <link rel="apple-touch-icon-precomposed" href="<?php echo esc_url($options['iphone_icon']['url']); ?>">
  <?php
  }
  if (isset($options['iphone_icon_retina']) && $options['iphone_icon_retina'] != "") { ?>
  <link rel="apple-touch-icon-precomposed" sizes="114x114"
    href="<?php echo esc_url($options['iphone_icon_retina']['url']); ?>">
  <?php
  }
  if (isset($options['ipad_icon']) && $options['ipad_icon'] != "") { ?>
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo esc_url($options['ipad_icon']['url']); ?>">
  <?php
  }
  if (isset($options['ipad_icon_retina']) && $options['ipad_icon_retina'] != "") { ?>
  <link rel="apple-touch-icon-precomposed" sizes="144x144"
    href="<?php echo esc_url($options['ipad_icon_retina']['url']); ?>">
  <?php
  }
  ?>
  <?php
  $space_before_head = (isset($options['space-before-head'])) ? $options['space-before-head'] : '';
  $SpaceBeforeHead = $space_before_head;
  echo wp_kses($SpaceBeforeHead, $GLOBALS['allowedposttags']);
  ?>
  <?php //  WORDPRESS HEAD HOOK 
  wp_head(); ?>
</head>
<!--// CLOSE HEAD //-->

<body <?php body_class($bodyClass); ?>>
  <?php if (function_exists('wp_body_open')) {
    wp_body_open();
  }
	$header_layout = get_post_meta($id, 'imic_page_specific_header', true);
	if($header_layout == ''){
		$header_layout = (isset($options['header_layout'])) ? $options['header_layout'] : '';
	}
?>
  <div class="body header-style<?php echo esc_attr($header_layout); ?>">
    <?php
    $menu_locations = get_nav_menu_locations();
    get_template_part('template-parts/header/top', 'bar', ['options' => $options, 'menu' => $menu_locations]);
    get_template_part('template-parts/header/site', 'header', ['header_layout' => $header_layout, 'options' => $options, 'menu' => $menu_locations]);
    ?>
    <!-- Start Site Header -->

    <!-- End Site Header -->
    <?php
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    $flag = imic_cat_count_flag();
    $page_for_posts = get_option('page_for_posts');
    $show_on_front = get_option('show_on_front');
    if (is_home()) {
      $id = $page_for_posts;
    } elseif (is_404() || is_search()) {
      $id = '';
    } elseif (function_exists('is_shop') && is_shop()) {
      $id = get_option('woocommerce_shop_page_id');
    } elseif ($flag == 0) {
      $id = '';
    } else {
      $id = get_the_ID();
    }
    if ((!is_front_page()) || $show_on_front == 'posts' || (!is_page_template('template-home.php') && !is_page_template('template-h-second.php') && !is_page_template('template-h-third.php') && !is_page_template('template-home-pb.php'))) {
      if (is_404() || is_search() || $flag == 0) {
        $custom = array();
      } else {
        $custom = get_post_custom($id);
      }
      $page_header_show = get_post_meta($id, 'imic_page_header_show', true);
      $page_title_show = get_post_meta($id, 'imic_pages_title_show', true);
      $show_parallax_header = !($page_header_show === '0');
      $show_page_title = !($page_title_show === '0');
      $header_image = get_post_meta($id, 'imic_header_image', true);
      if (is_category() || !empty($term->term_id)) {
        global $cat;
        if (!empty($cat)) {
          $term_taxonomy = 'category';
          $t_id = $cat; // Get the ID of the term we're editing
        } else {
          $term_taxonomy = get_query_var('taxonomy');
          $t_id = $term->term_id; // Get the ID of the term we're editing
        }
        $header_image  = get_option($term_taxonomy . $t_id . "_image_term_id"); // Do the check
      }
      $default_header_image = (isset($options['header_image'])) ? $options['header_image']['url'] : '';
      if (!empty($header_image)) {
        if (is_category() || !empty($term->term_id)) {
          $src[0] = $header_image;
        } else {
          $src = wp_get_attachment_image_src($header_image, 'Full');
        }
      } else {
        $src[0] = $default_header_image;
        if (is_singular('post') && isset($options['header_image_post']) && !empty($options['header_image_post']['url'])) {
          $src[0] = $options['header_image_post']['url'];
        } elseif (is_singular('page') && isset($options['header_image_page']) && !empty($options['header_image_page']['url'])) {
          $src[0] = $options['header_image_page']['url'];
        } elseif (is_singular('event') && isset($options['header_image_event']) && !empty($options['header_image_event']['url'])) {
          $src[0] = $options['header_image_event']['url'];
        } elseif (is_singular('sermons') && isset($options['header_image_sermon']) && !empty($options['header_image_sermon']['url'])) {
          $src[0] = $options['header_image_sermon']['url'];
        } elseif (is_singular('gallery') && isset($options['header_image_gallery']) && !empty($options['header_image_gallery']['url'])) {
          $src[0] = $options['header_image_gallery']['url'];
        } elseif (is_singular('causes') && isset($options['header_image_cause']) && !empty($options['header_image_cause']['url'])) {
          $src[0] = $options['header_image_cause']['url'];
        }
      } ?>
    <!-- Start Nav Backed Header -->
    <?php $header_options = get_post_meta($id, 'imic_pages_Choose_slider_display', true);
        $height = get_post_meta($id, 'imic_pages_slider_height', true);
        $height = ($height == '') ? '150' : $height;
        $breadpad = intval($height) - 60;
        if ($show_parallax_header && (($header_options == 0 || $header_options == '') || (is_category() || !empty($term->term_id)))) { ?>
    <?php
            get_template_part('template-parts/header/header', 'parallax', ['url' => $src[0], 'breadpad' => $breadpad]);
            ?>
    <?php } elseif ($show_parallax_header && ($header_options == 3 || $header_options == '')) { ?>
    <?php
            $color = get_post_meta($id, 'imic_pages_banner_color', true);
            $color = ($color != '') ? $color : '';
            get_template_part('template-parts/header/header', 'parallax', ['color' => $color, 'breadpad' => $breadpad]); ?>

    <?php } elseif ($show_parallax_header) {
          include(locate_template('pages_slider.php'));
        }
        if ($show_page_title) {
          get_template_part('template-parts/header/page', 'header', ['custom' => $custom, 'blog_id' => $page_for_posts, 'flag' => $flag, 'id'=>$id]);
        } ?>
    <?php
      /**   Start Content* */
      echo '<div class="main" role="main">
                     <div id="content" class="content full">';
    } ?>
