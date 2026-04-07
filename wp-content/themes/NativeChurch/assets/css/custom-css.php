<?php
$options = get_option('imic_options');
// Only set Content-type header when served as a standalone CSS file (legacy AJAX path)
if (defined('DOING_AJAX') && DOING_AJAX) {
  header('Content-type: text/css');
}
// Custom CSS
$custom_css = (isset($options['custom_css'])) ? $options['custom_css'] : '';
$content_height = (isset($options['content_min_height'])) ? $options['content_min_height'] : '';
$slider_behind_header = (isset($options['slider_behind_header'])) ? $options['slider_behind_header'] : 1;
$content_height = !empty($content_height) ? $content_height : 400;
$site_width = (isset($options['site_width'])) ? $options['site_width'] : '';
$site_width = !empty($site_width) ? $site_width : 1040;
$site_width_spaced = !empty($site_width) ? intval($site_width) + 40 : 1080;
$site_width_nav = !empty($site_width) ? intval($site_width) - 30 : 1010;
$header_height = (isset($options['header_area_height'])) ? $options['header_area_height'] : '';
$header_height = !empty($header_height) ? $header_height : 80;
$logo_height = !empty($header_height) ? intval($header_height) - 15 : 65;
$slider_height = !empty($header_height) ? intval($header_height) + 1 : 81;
$slider_margin = !empty($header_height) ? intval($header_height) + 1 : 81;
$header_style3a = !empty($header_height) ? intval($header_height) + 39 : 119;
$header_style3b = !empty($header_height) ? intval($header_height) + 79 : 159;
if (isset($options['theme_color_type']) && $options['theme_color_type'][0] == 1) {
	$customColor = $options['custom_theme_color'];
		echo '.text-primary, .btn-primary .badge, .btn-link,a.list-group-item.active > .badge,.nav-pills > .active > a > .badge, p.drop-caps:first-letter, .accent-color, .events-listing .event-detail h4 a, .featured-sermon h4 a, .page-header h1, .post-more, ul.nav-list-primary > li a:hover, .widget_recent_comments a, .navigation .megamenu-container .megamenu-sub-title, .woocommerce div.product span.price, .woocommerce div.product p.price, .woocommerce #content div.product span.price, .woocommerce #content div.product p.price, .woocommerce-page div.product span.price, .woocommerce-page div.product p.price, .woocommerce-page #content div.product span.price, .woocommerce-page #content div.product p.price, .woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price, .cause-item .progress-label, .payment-to-cause a, .event-ticket h4, .event-ticket .ticket-ico{color:' . $customColor . ';}a:hover{color:' . $customColor . ';}.events-listing .event-detail h4 a:hover, .featured-sermon h4 a:hover, .featured-gallery p, .post-more:hover, .widget_recent_comments a:hover{opacity:.9}p.drop-caps.secondary:first-letter, .accent-bg, .fa.accent-color, .btn-primary,.btn-primary.disabled,.btn-primary[disabled],fieldset[disabled] .btn-primary,.btn-primary.disabled:hover,.btn-primary[disabled]:hover,fieldset[disabled] .btn-primary:hover,.btn-primary.disabled:focus,.btn-primary[disabled]:focus,fieldset[disabled] .btn-primary:focus,.btn-primary.disabled:active,.btn-primary[disabled]:active,fieldset[disabled] .btn-primary:active,.btn-primary.disabled.active,.btn-primary[disabled].active,fieldset[disabled] .btn-primary.active,.dropdown-menu > .active > a,.dropdown-menu > .active > a:hover,.dropdown-menu > .active > a:focus,.nav-pills > li.active > a,.nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus,.pagination > .active > a,.pagination > .active > span,.pagination > .active > a:hover,.pagination > .active > span:hover,.pagination > .active > a:focus,.pagination > .active > span:focus,.label-primary,.progress-bar,a.list-group-item.active,a.list-group-item.active:hover,a.list-group-item.active:focus,.panel-primary > .panel-heading, .carousel-indicators .active, .owl-theme .owl-controls .owl-page.active span, .owl-theme .owl-controls.clickable .owl-page:hover span, hr.sm, .flex-control-nav a:hover, .flex-control-nav a.flex-active, .title-note, .timer-col #days, .featured-block strong, .featured-gallery, .nav-backed-header, .next-prev-nav a, .event-description .panel-heading, .media-box .media-box-wrapper, .staff-item .social-icons a, .accordion-heading .accordion-toggle.active, .accordion-heading:hover .accordion-toggle, .accordion-heading:hover .accordion-toggle.inactive, .nav-tabs li a:hover, .nav-tabs li a:active, .nav-tabs li.active a, .site-header .social-icons a, .timeline > li > .timeline-badge,.toprow, .featured-star, .featured-event-time,.goingon-events-floater-inner, .ticket-cost, .bbp-search-form input[type="submit"]:hover{background-color: ' . $customColor . ';}.fc-event{background-color: ' . $customColor . ';}.mejs-controls .mejs-time-rail .mejs-time-loaded, p.demo_store, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce span.onsale, .woocommerce-page span.onsale, .wpcf7-form .wpcf7-submit, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle, .woocommerce .widget_layered_nav ul li.chosen a, .woocommerce-page .widget_layered_nav ul li.chosen a{background: ' . $customColor . ';}.share-buttons.share-buttons-tc > li > a{background: . $customColor . !important;}.btn-primary:hover,.btn-primary:focus,.btn-primary:active,.btn-primary.active,.open .dropdown-toggle.btn-primary, .next-prev-nav a:hover, .staff-item .social-icons a:hover, .site-header .social-icons a:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover, .woocommerce a.button.alt:active, .woocommerce button.button.alt:active, .woocommerce input.button.alt:active, .woocommerce #respond input#submit.alt:active, .woocommerce #content input.button.alt:active, .woocommerce-page a.button.alt:active, .woocommerce-page button.button.alt:active, .woocommerce-page input.button.alt:active, .woocommerce-page #respond input#submit.alt:active, .woocommerce-page #content input.button.alt:active, .wpcf7-form .wpcf7-submit{background: ' . $customColor . ';opacity:.9}.woocommerce .woocommerce-info, .woocommerce-page .woocommerce-info, .woocommerce .woocommerce-message, .woocommerce-page .woocommerce-message{border-top-color: ' . $customColor . ';}.nav .open > a,.nav .open > a:hover,.nav .open > a:focus,.pagination > .active > a,.pagination > .active > span,.pagination > .active > a:hover,.pagination > .active > span:hover,.pagination > .active > a:focus,.pagination > .active > span:focus,a.thumbnail:hover,a.thumbnail:focus,a.thumbnail.active,a.list-group-item.active,a.list-group-item.active:hover,a.list-group-item.active:focus,.panel-primary,.panel-primary > .panel-heading, .fc-events, .event-ticket-left .ticket-handle{border-color:' . $customColor . ';}.fc-event{border-color:' . $customColor . ';}.panel-primary > .panel-heading + .panel-collapse .panel-body{border-top-color:' . $customColor . ';}.panel-primary > .panel-footer + .panel-collapse .panel-body{border-bottom-color:' . $customColor . ';}blockquote{border-left-color:' . $customColor . ';}';
}

echo '@media (min-width:1200px){.container{width:' . $site_width . 'px;} .navigation{width:' . $site_width_nav . 'px}}
		body.boxed .body{max-width:' . $site_width_spaced . 'px}
		@media (min-width: 1200px) {body.boxed .body .site-header, body.boxed .body .main-menu-wrapper{width:' . $site_width_spaced . 'px;}}';
		if (isset($options['header_wide_width']) && $options['header_wide_width'] == 1) {
			echo '.topbar > .container, .toprow > .container,.new-flex-header > .container{width:100%;}';
		}
		if (isset($options['footer_wide_width']) && $options['footer_wide_width'] == 1) {
			echo '.site-footer > .container, .site-footer-bottom > .container{width:100%;}';
		}
		if (isset($options['recurring_icon']) && $options['recurring_icon'] == 1) {
			echo '.recurring-info-icon{display:inline-block;}';
		} else {
			echo '.recurring-info-icon{display:none;}';
		}
		if ($slider_behind_header == 0) {
			echo '@media only screen and (max-width: 767px) {.home .hero-slider, .home .slider-revolution-new{top:0!important; margin-bottom:0!important;}}';
		}
		if (isset($options['sidebar_position']) && $options['sidebar_position'] == 2) {
			echo ' .main-content-row{flex-direction:row-reverse}';
		} else {
			echo ' .main-content-row{flex-direction:row}';
		}
		if (isset($options['content_wide_width']) && $options['content_wide_width'] == 1) {
			echo '.content .container{width:100%;}';
		}
		if (isset($options['event_google_icon']) && $options['event_google_icon'] == 1) {
			echo '.event-detail h4 a[href^="https://www.google"]:before, .events-grid .grid-content h3 a[href^="https://www.google"]:before, h3.timeline-title a[href^="https://www.google"]:before{display:inline-block;}';
		} else {
			echo '.event-detail h4 a[href^="https://www.google"]:before, .events-grid .grid-content h3 a[href^="https://www.google"]:before, h3.timeline-title a[href^="https://www.google"]:before{display:none;}';
		}

		echo '
			.content{min-height:' . $content_height . 'px;}.site-header .topbar,.header-style5 .site-header,.header-style6 .site-header,.header-style6 .site-header>.container{height:' . $header_height . 'px;}.site-header h1.logo{height:' . $logo_height . 'px;}.home .hero-slider{top:-' . $slider_height . 'px;margin-bottom:-' . $slider_margin . 'px;}.home .slider-revolution-new{top:-' . $slider_height . 'px;margin-bottom:-' . $slider_margin . 'px;}.header-style4 .top-navigation > li ul{top:' . $header_height . 'px;}.header-style4 .topbar .top-navigation > li > a{line-height:' . $header_height . 'px;}@media only screen and (max-width: 992px) {.main-menu-wrapper{top:' . $header_height . 'px;}}@media only screen and (max-width: 992px) {.header-style3 .main-menu-wrapper{top:' . $header_style3a . 'px;}.header-style4 #top-nav-clone{top:' . $header_height . 'px;}}@media only screen and (max-width: 767px) {.header-style3 .main-menu-wrapper{top:' . $header_style3b . 'px;}}';


//Page Style Options
$id = ($_REQUEST['pgid']) ? sanitize_text_field($_REQUEST['pgid']) : '';
$taxp = ($_REQUEST['taxp']) ? sanitize_text_field($_REQUEST['taxp']) : '';
if ($taxp == "1") {
  
	$strict_no_header = get_post_meta($id, 'imic_strict_no_header', true);
	$page_topbar_show = get_post_meta($id, 'imic_page_topbar_show', true);
	$content_top_padding = get_post_meta($id, 'imic_content_padding_top', true);
	$content_bottom_padding = get_post_meta($id, 'imic_content_padding_bottom', true);
	$content_width = get_post_meta($id, 'imic_content_width', true);
	$page_header_show = get_post_meta($id, 'imic_page_header_show', true);
	$page_social_show = get_post_meta($id, 'imic_page_social_share', true);
	$page_title_show = get_post_meta($id, 'imic_pages_title_show', true);
	$page_breadcrumb_show = get_post_meta($id, 'imic_pages_breadcrumb_show', true);
	$header_image_overlay = get_post_meta($id, 'imic_header_image_overlay', true);
	$header_image_overlay_opacity = get_post_meta($id, 'imic_header_image_overlay_opacity', true);
	$pages_banner_text_color = get_post_meta($id, 'imic_pages_banner_text_color', true);
	$pages_banner_bg_color = get_post_meta($id, 'imic_pages_banner_bg_color', true);
	$pages_title_alignment = get_post_meta($id, 'imic_pages_title_alignment', true);
	$page_body_bg_color = get_post_meta($id, 'imic_pages_body_bg_color', true);
	$page_body_bg_image = get_post_meta($id, 'imic_pages_body_bg_image', true);
	$page_body_bg_image_src = wp_get_attachment_image_src($page_body_bg_image, 'full', '', array());
	$page_body_bg_size = get_post_meta($id, 'imic_pages_body_bg_wide', true);
	if ($page_body_bg_size == 0) {
	$page_body_bg_size_result = 'auto';
	$page_body_bg_size_attachment = 'scroll';
	} else {
	$page_body_bg_size_result = 'cover';
	$page_body_bg_size_attachment = 'fixed';
	}
	if ($strict_no_header == 1) {
		echo '.site-header,.toprow{display:none!important}';
	}
	if ($page_topbar_show == 0 && $page_topbar_show != '') {
		echo '.toprow{display:none!important}';
	}
	$page_body_bg_repeat = get_post_meta($id, 'imic_pages_body_bg_repeat', true);
	$page_content_bg_color = get_post_meta($id, 'imic_pages_content_bg_color', true);
	$page_content_bg_image = get_post_meta($id, 'imic_pages_content_bg_image', true);
	$page_content_bg_image_src = wp_get_attachment_image_src($page_content_bg_image, 'full', '', array());
	$page_content_bg_size = get_post_meta($id, 'imic_pages_content_bg_wide', true);
	if ($page_content_bg_size == 0) {
	$page_content_bg_size_result = 'auto';
	$page_content_bg_size_attachment = 'scroll';
	} else {
	$page_content_bg_size_result = 'cover';
	$page_content_bg_size_attachment = 'fixed';
	}
	$page_content_bg_repeat = get_post_meta($id, 'imic_pages_content_bg_repeat', true);
	if ($page_header_show == 0 && $page_header_show != '') {
	echo '.nav-backed-header{display:none;}';
	} else {
	echo '.nav-backed-header{display:block;}';
	}
	if ($page_social_show == 0 && $page_social_show != '') {
	echo '.share-bar{display:none;}';
	} else {
	echo '.share-bar{display:block;}';
	}
	if ($page_title_show == 0 && $page_title_show != '') {
	echo '.page-header{display:none;}';
	} else {
	echo '.page-header{display:block;}';
	}
	if ($page_breadcrumb_show == 0 && $page_breadcrumb_show != '') {
	echo '.breadcrumb{visibility: hidden}';
	} else {
	echo '.breadcrumb{visibility:visible}';
	}
	if ($header_image_overlay != '') {
	echo '.page-banner-image:before{background:' . $header_image_overlay . ';}';
	}
	if ($header_image_overlay_opacity != '') {
	echo '.page-banner-image:before{opacity:' . $header_image_overlay_opacity . ';}';
	} else {
	echo '.page-banner-image:before{opacity:.4;}';
	}
	if ($pages_banner_bg_color != '') {
	echo '.page-header{background-color:' . $pages_banner_bg_color . ';}';
	}
	if ($pages_banner_text_color != '') {
	echo '.page-header h1{color:' . $pages_banner_text_color . ';}';
	}
	if ($pages_title_alignment == 'left') {
	echo '.page-header h1{}';
	} elseif ($pages_title_alignment == 'right') {
	echo '.page-header h1{text-align: right}';
	} elseif ($pages_title_alignment == 'center') {
	echo '.page-header h1{text-align: center}';
	}
	echo '.content{';
	if ($content_top_padding != '') {
	echo 'padding-top:' . esc_attr($content_top_padding) . '!important;';
	}
	if ($content_bottom_padding != '') {
	echo 'padding-bottom:' . esc_attr($content_bottom_padding) . '!important;';
	}
	echo '}';
	if ($content_width != '') {
	echo '
		.content .container{
			width:' . esc_attr($content_width) . ';
		}';
	}
	echo 'body.boxed{';
	if ($page_body_bg_color != '') {
	echo 'background-color:' . esc_attr($page_body_bg_color) . ';';
	}
	if ($page_body_bg_image != '') {
	echo 'background-image:url(' . esc_attr($page_body_bg_image_src[0]) . ')!important;';
	}
	if ($page_body_bg_image != '') {
	echo 'background-size:' . esc_attr($page_body_bg_size_result) . '!important;';
	}
	if ($page_body_bg_image != '') {
	echo 'background-repeat:' . esc_attr($page_body_bg_repeat) . '!important;';
	}
	if ($page_body_bg_image != '') {
	echo 'background-attachment:' . esc_attr($page_body_bg_size_attachment) . '!important;';
	}
	echo '}
		.content{';
	if ($page_content_bg_color != '') {
	echo 'background-color:' . esc_attr($page_content_bg_color) . ';';
	}
	if ($page_content_bg_image != '') {
	echo 'background-image:url(' . esc_attr($page_content_bg_image_src[0]) . ');';
	}
	if ($page_content_bg_image != '') {
	echo 'background-size:' . esc_attr($page_content_bg_size_result) . ';';
	}
	if ($page_content_bg_image != '') {
	echo 'background-repeat:' . esc_attr($page_content_bg_repeat) . ';';
	}
	if ($page_content_bg_image != '') {
	echo 'background-attachment:' . esc_attr($page_content_bg_size_attachment) . ';';
	}
	echo '}';
}
$sidebar_position = (isset($_REQUEST['sidebar_pos'])) ? sanitize_text_field($_REQUEST['sidebar_pos']) : '';
if ($sidebar_position == 2) {
  echo ' .main-content-row{flex-direction:row-reverse}';
} elseif ($sidebar_position == 1) {
  echo ' .main-content-row{flex-direction:row}';
}

// USER STYLES
if ($custom_css) {
  echo "\n" . '/*========== User Custom CSS Styles ==========*/' . "\n";
  echo '' . $custom_css;
}

  if (isset($options['site_layout']) && $options['site_layout'] == 'boxed') {
    if (!empty($options['upload-repeatable-bg-image']['id'])) {
      echo 'body{background-image:url(' . $options['upload-repeatable-bg-image']['url'] . '); background-repeat:repeat; background-size:auto}';
    } else if (!empty($options['full-screen-bg-image']['id'])) {
      echo 'body{background-image:url(' . $options['full-screen-bg-image']['url'] . '); background-repeat: no-repeat; background-size:cover}';
    } else if (!empty($options['repeatable-bg-image'])) {
      echo 'body{background-image:url(' . get_template_directory_uri() . '/assets/images/patterns/' . $options['repeatable-bg-image'] . '); background-repeat:repeat; background-size:auto}';
    }
  }

// Theme Test Style
if (!defined('NATIVECHURCH_CORE__PLUGIN_PATH')) {
	echo '.page-header{display: none}
		  .navigation > ul > li{font-size:14px}';
}