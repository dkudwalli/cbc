<?php
$options = isset($args['options']) && is_array($args['options']) ? $args['options'] : array();
$header_layout = isset($args['header_layout']) ? (string) $args['header_layout'] : '1';
$mobile_label = !empty($options['mobile_menu_text']) ? $options['mobile_menu_text'] : esc_html__('Menu', 'framework');
$close_label = esc_html__('Close menu', 'framework');
$primary_exists = has_nav_menu('primary-menu');
$top_exists = has_nav_menu('top-menu');
$desktop_menu_args = array(
    'theme_location' => 'primary-menu',
    'menu_class' => 'cbc-menu cbc-menu-primary',
    'container' => false,
    'fallback_cb' => false,
);
$utility_menu_args = array(
    'theme_location' => 'top-menu',
    'menu_class' => 'cbc-menu cbc-menu-utility',
    'container' => false,
    'fallback_cb' => false,
);
$mobile_primary_args = array(
    'theme_location' => 'primary-menu',
    'menu_class' => 'cbc-mobile-menu',
    'container' => false,
    'fallback_cb' => false,
    'depth' => 4,
);
$mobile_top_args = array(
    'theme_location' => 'top-menu',
    'menu_class' => 'cbc-mobile-menu cbc-mobile-menu-utility',
    'container' => false,
    'fallback_cb' => false,
    'depth' => 3,
);

if (class_exists('imic_mega_menu_walker')) {
    $desktop_menu_args['walker'] = new imic_mega_menu_walker();
    $utility_menu_args['walker'] = new imic_mega_menu_walker();
}
?>
<header class="site-header cbc-site-header header-style<?php echo esc_attr($header_layout); ?>">
    <div class="cbc-header-shell">
        <div class="container cbc-header-container">
            <div class="cbc-header-brand">
                <?php get_template_part('template-parts/header/logo'); ?>
            </div>

            <div class="cbc-header-desktop">
                <?php if ($top_exists) : ?>
                    <nav class="cbc-utility-nav" aria-label="<?php echo esc_attr__('Utility navigation', 'framework'); ?>">
                        <?php wp_nav_menu($utility_menu_args); ?>
                    </nav>
                <?php endif; ?>

                <?php if ($primary_exists) : ?>
                    <nav class="navigation cbc-primary-nav" aria-label="<?php echo esc_attr__('Primary navigation', 'framework'); ?>">
                        <?php wp_nav_menu($desktop_menu_args); ?>
                    </nav>
                <?php endif; ?>
            </div>

            <button
                type="button"
                class="cbc-mobile-toggle"
                data-cbc-mobile-toggle
                aria-expanded="false"
                aria-controls="cbc-mobile-panel"
                aria-label="<?php echo esc_attr($mobile_label); ?>"
            >
                <span class="cbc-mobile-toggle__icon" aria-hidden="true"></span>
                <span class="cbc-mobile-toggle__label"><?php echo esc_html($mobile_label); ?></span>
            </button>
        </div>
    </div>

    <div class="cbc-mobile-panel" id="cbc-mobile-panel" hidden data-cbc-mobile-panel>
        <div class="cbc-mobile-panel__overlay" data-cbc-mobile-close></div>
        <div class="cbc-mobile-panel__dialog" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr__('Mobile navigation', 'framework'); ?>">
            <div class="cbc-mobile-panel__header">
                <span class="cbc-mobile-panel__title"><?php esc_html_e('Menu', 'framework'); ?></span>
                <button type="button" class="cbc-mobile-panel__close" data-cbc-mobile-close aria-label="<?php echo esc_attr($close_label); ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php if ($primary_exists) : ?>
                <nav class="cbc-mobile-nav" aria-label="<?php echo esc_attr__('Primary navigation', 'framework'); ?>">
                    <?php wp_nav_menu($mobile_primary_args); ?>
                </nav>
            <?php endif; ?>

            <?php if ($top_exists) : ?>
                <div class="cbc-mobile-secondary">
                    <span class="cbc-mobile-secondary__label"><?php esc_html_e('Quick links', 'framework'); ?></span>
                    <nav aria-label="<?php echo esc_attr__('Secondary navigation', 'framework'); ?>">
                        <?php wp_nav_menu($mobile_top_args); ?>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>
