<?php

defined( 'ABSPATH' ) || exit;

/** @var WC_Product $product */
global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?><div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>><?php

// If the user has created and enabled a Single Product Page Builder layout we load and render it here.
$template_post_id = get_query_var( 'wctb_template_id' );
if ( ! empty( $template_post_id ) ) {
	// Don't call `woocommerce_output_all_notices` here, as they should already be hooked into the above
	// `woocommerce_before_single_product` action.
	SiteOrigin_Premium_Plugin_WooCommerce_Templates::single()->before_template_render();
	echo SiteOrigin_Panels_Renderer::single()->render( $template_post_id );
	SiteOrigin_Premium_Plugin_WooCommerce_Templates::single()->after_template_render();

	if ( class_exists( 'WC_Structured_Data' ) ) {
		$structured_data = new WC_Structured_Data();
		$structured_data->generate_product_data();
	}
}

?></div><?php

do_action( 'woocommerce_after_single_product' );
