<?php $options = get_option('imic_options');
	$logo = $retina_logo = $retina_logo_width = $retina_logo_height = '';
	$id = imi_page_id();
	$logo_page = get_post_meta($id, 'imic_page_logo_image', true);
	$retina_logo_page = get_post_meta($id, 'imic_page_retina_logo_image', true);
	$retina_logo_width = get_post_meta($id, 'imic_page_retina_logo_image_width', true);
	$retina_logo_height = get_post_meta($id, 'imic_page_retina_logo_image_height', true);

	// Get attchment URL
	$logo_src = wp_get_attachment_image_src($logo_page, 'full', '', array());
	$retina_logo_src = wp_get_attachment_image_src($retina_logo_page, 'full', '', array());
	if($logo_src){
		$logo = $logo_src[0];
	}
	if($retina_logo_src){
		$retina_logo = $retina_logo_src[0];
	}

	if($logo == ''){
		if (isset($options['logo_upload']) && !empty($options['logo_upload']['url'])) {
			$logo = $options['logo_upload']['url'];
		}
	}
	if($retina_logo == '' && $logo_page == ''){
		if (isset($options['retina_logo_upload']) && !empty($options['retina_logo_upload']['url'])) {
			$retina_logo = $options['retina_logo_upload']['url'];
		}
	}
	if($retina_logo == ''){
		$retina_logo = $logo;
	}
	if($retina_logo_width == ''){
		if (isset($options['retina_logo_width']) && !empty($options['retina_logo_width'])) {
			$retina_logo_width = $options['retina_logo_width'];
		}
	}
	if($retina_logo_height == ''){
		if (isset($options['retina_logo_height']) && !empty($options['retina_logo_height'])) {
			$retina_logo_height = $options['retina_logo_height'];
		}
	}
?>
<h1 class="logo">
	<?php
	if (isset($options['logo_alt_text']) && $options['logo_alt_text'] != "") {
		$logoalt = esc_html($options['logo_alt_text']);
	} else {
	  $logoalt = 'Logo';
	}
	
	if (isset($logo) && !empty($logo)) { ?>
	  <a href="<?php echo esc_url(home_url()); ?>" class="default-logo" title="<?php echo esc_attr($logoalt); ?>"><img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($logoalt); ?>"></a>
	<?php } else { ?>
	  <a href="<?php echo esc_url(home_url()); ?>" title="<?php echo esc_attr($logoalt); ?>" class="default-logo theme-blogname"><?php echo bloginfo('name'); ?></a>
	<?php }
	if (isset($retina_logo) && !empty($retina_logo)) { ?>
	  <a href="<?php echo esc_url(home_url()); ?>" title="<?php echo esc_attr($logoalt); ?>" class="retina-logo"><img src="<?php echo esc_url($retina_logo); ?>" alt="<?php echo esc_attr($logoalt); ?>" width="<?php echo esc_attr($retina_logo_width); ?>" height="<?php echo esc_attr($retina_logo_height); ?>"></a>
	<?php } elseif (isset($logo) && !empty($logo)) { ?>
	  <a href="<?php echo esc_url(home_url()); ?>" title="<?php echo esc_attr($logoalt); ?>" class="retina-logo"><img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($logoalt); ?>"></a>
	<?php } else { ?>
	  <a href="<?php echo esc_url(home_url()); ?>" title="<?php echo esc_attr($logoalt); ?>" class="retina-logo theme-blogname"><?php echo bloginfo('name'); ?></a>
	<?php }
	?>
</h1>