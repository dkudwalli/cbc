<?php

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

?><li <?php wc_product_class( '', $product ); ?>><?php

// If the user has created and enabled a Product Archive Page Builder layout we load and render it here.
$template_post_id = get_query_var( 'wctb_template_id' );
if ( ! empty( $template_post_id ) ) {
	echo SiteOrigin_Panels_Renderer::single()->render( $template_post_id );
}

?></li>
