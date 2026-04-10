<?php
$options = get_option('imic_options');
$id = imi_page_id();
$logo = '';
$retina_logo = '';
$retina_logo_width = get_post_meta($id, 'imic_page_retina_logo_image_width', true);
$retina_logo_height = get_post_meta($id, 'imic_page_retina_logo_image_height', true);
$logo_page = get_post_meta($id, 'imic_page_logo_image', true);
$retina_logo_page = get_post_meta($id, 'imic_page_retina_logo_image', true);
$logo_src = wp_get_attachment_image_src($logo_page, 'full');
$retina_logo_src = wp_get_attachment_image_src($retina_logo_page, 'full');

if ($logo_src) {
    $logo = $logo_src[0];
}

if ($retina_logo_src) {
    $retina_logo = $retina_logo_src[0];
}

if ($logo === '' && !empty($options['logo_upload']['url'])) {
    $logo = $options['logo_upload']['url'];
}

if ($retina_logo === '' && $logo_page == '' && !empty($options['retina_logo_upload']['url'])) {
    $retina_logo = $options['retina_logo_upload']['url'];
}

if ($retina_logo === '') {
    $retina_logo = $logo;
}

if ($retina_logo_width === '' && !empty($options['retina_logo_width'])) {
    $retina_logo_width = $options['retina_logo_width'];
}

if ($retina_logo_height === '' && !empty($options['retina_logo_height'])) {
    $retina_logo_height = $options['retina_logo_height'];
}

$logo_alt = !empty($options['logo_alt_text']) ? esc_html($options['logo_alt_text']) : 'Logo';
$tag = cbc_modern_is_homepage_refresh() ? 'h1' : 'div';
$size_attributes = '';

if ($retina_logo_width !== '') {
    $size_attributes .= ' width="' . esc_attr($retina_logo_width) . '"';
}

if ($retina_logo_height !== '') {
    $size_attributes .= ' height="' . esc_attr($retina_logo_height) . '"';
}

$srcset = '';
if (!empty($logo)) {
    $srcset = esc_url($logo) . ' 1x';
    if (!empty($retina_logo) && $retina_logo !== $logo) {
        $srcset .= ', ' . esc_url($retina_logo) . ' 2x';
    }
}
?>
<<?php echo $tag; ?> class="logo cbc-logo">
    <?php if (!empty($logo)) : ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="cbc-logo-link" title="<?php echo esc_attr($logo_alt); ?>">
            <img
                src="<?php echo esc_url($logo); ?>"
                alt="<?php echo esc_attr($logo_alt); ?>"
                class="cbc-logo-image"
                <?php echo $size_attributes; ?>
                <?php if ($srcset !== '') : ?>
                    srcset="<?php echo esc_attr($srcset); ?>"
                <?php endif; ?>
            >
        </a>
    <?php else : ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="cbc-logo-link cbc-logo-link--text" title="<?php echo esc_attr($logo_alt); ?>">
            <?php bloginfo('name'); ?>
        </a>
    <?php endif; ?>
</<?php echo $tag; ?>>
