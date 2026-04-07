<?php
// add the itunes namespace to the RSS opening element
function isermons_podcast_add_namespace() {
	echo 'xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"';
}

// Pre-hook for adding podcast data to the XML file.
add_action( 'pre_get_posts', 'isermons_podcast_add_hooks', 9999 );

// Create custom RSS feed for sermon podcasting
add_action( 'do_feed_podcast', 'isermons_sermon_podcast_feed', 10, 1 );

// Custom rewrite for podcast feed
function isermons_sermon_podcast_feed_rewrite( $wp_rewrite ) {
	$feed_rules = array(
		'feed/(.+)' => 'index.php?feed=' . $wp_rewrite->preg_index( 1 ),
		'(.+).xml' => 'index.php?feed=' . $wp_rewrite->preg_index( 1 )
	);
	$wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
}
//add_filter('generate_rewrite_rules', 'isermons_sermon_podcast_feed_rewrite');

// Add podcast data to the WordPress default XML feed
function isermons_podcast_add_hooks( $query ) {
	if ( !is_admin() && $query->is_main_query() && $query->is_feed() ) {
		if ( $query->get( 'post_type' ) == 'imi_isermons' || is_post_type_archive( 'imi_isermons' ) || is_tax( 'imi_isermons-categories' ) || is_tax( 'imi_isermons-series' ) || is_tax( 'imi_isermons-books' ) || is_tax( 'imi_isermons-topics' ) || is_tax( 'imi_isermons-preachers' ) ) {
			//add_filter( 'get_post_time', 'isermons_podcast_item_date', 10, 3 );
			add_filter( 'bloginfo_rss', 'isermons_bloginfo_rss_filter', 10, 2 );
			add_filter( 'wp_title_rss', 'isermons_modify_podcast_title', 99, 3 );
			add_action( 'rss_ns', 'isermons_podcast_add_namespace' );
			add_action( 'rss2_ns', 'isermons_podcast_add_namespace' );
			add_action( 'rss_head', 'isermons_podcast_add_head' );
			add_action( 'rss2_head', 'isermons_podcast_add_head' );
			add_action( 'rss_item', 'isermons_podcast_add_item' );
			add_action( 'rss2_item', 'isermons_podcast_add_item' );
			add_filter( 'the_content_feed', 'isermons_podcast_summary', 10, 3 );
			add_filter( 'the_excerpt_rss', 'isermons_podcast_summary' );
			add_filter( 'rss_enclosure', '__return_empty_string' );
		}
	}
}
// Create custom RSS feed for sermon podcasting
function isermons_sermon_podcast_feed() {
	load_template( ISERMONS__PLUGIN_PATH . 'front/podcast-feed.php' );
}
// add podcast head
function isermons_podcast_add_head() {
	$options = get_option( 'isermons_options' );
	?>
<copyright><?php echo html_entity_decode( esc_html( $options['isermons_sermons_podcast_copyright'] ?? '' ), ENT_COMPAT, 'UTF-8' ) ?></copyright>
<itunes:subtitle><?php echo esc_html( $options['isermons_sermons_podcast_subtitle'] ?? '' ) ?></itunes:subtitle>
<itunes:author><?php echo esc_html( $options['isermons_sermons_podcast_author'] ?? '' ) ?></itunes:author>
<?php if ( trim( category_description() ) !== '' ) : ?>
<itunes:summary><?php echo stripslashes( wp_filter_nohtml_kses( category_description() ) ); ?></itunes:summary>
<?php else: ?>
<itunes:summary><?php echo wp_filter_nohtml_kses( $options['isermons_sermons_podcast_summary'] ?? '' ); ?></itunes:summary>
<?php endif; ?>
<itunes:owner>
    <itunes:name><?php echo esc_html( $options['isermons_sermons_podcast_owner_name'] ?? '' ) ?></itunes:name>
    <itunes:email><?php echo esc_html( $options['isermons_sermons_podcast_owner_email'] ?? '' ) ?></itunes:email>
</itunes:owner>
<itunes:explicit>no</itunes:explicit>
<?php
$cover_image = $options['isermons_sermons_podcast_cover'] ?? '';
if ( $cover_image != '' ) {
	?>
<itunes:image href="<?php echo esc_url($cover_image) ?>" />
<?php } else { ?>
<itunes:image href="<?php echo esc_url(get_template_directory_uri()) ?>/images/cover.png" />
<?php } ?>
<itunes:category text="<?php echo esc_attr( $options['isermons_sermons_podcast_top_category'] ?? '' ); ?>">
<itunes:category text="<?php echo esc_attr( $options['isermons_sermons_podcast_sub_category'] ?? '' ) ?>"/>
</itunes:category>
<?php
}

// add itunes specific info to each item
function isermons_podcast_add_item() {
	$options = get_option( 'isermons_options' );
	global $post;
	$categories = $series = $topics = $books = $preachers = '';
	$sermon_taxonomy_categories = ( empty( isermons_get_settings( 'isermons_taxonomy_categories' ) ) ) ? array() : isermons_get_settings( 'isermons_taxonomy_categories' );
	$sermon_taxonomy_topics = ( empty( isermons_get_settings( 'isermons_taxonomy_topics' ) ) ) ? array() : isermons_get_settings( 'isermons_taxonomy_topics' );
	$sermon_taxonomy_books = ( empty( isermons_get_settings( 'isermons_taxonomy_books' ) ) ) ? array() : isermons_get_settings( 'isermons_taxonomy_books' );
	$sermon_taxonomy_series = ( empty( isermons_get_settings( 'isermons_taxonomy_series' ) ) ) ? array() : isermons_get_settings( 'isermons_taxonomy_series' );
	$sermon_taxonomy_preachers = ( empty( isermons_get_settings( 'isermons_taxonomy_preachers' ) ) ) ? array() : isermons_get_settings( 'isermons_taxonomy_preachers' );
	$sermon_taxonomies = array( 'categories', 'topics', 'books', 'series', 'preachers' );
	foreach ( $sermon_taxonomies as $taxonomy )
	{
		if ( in_array( 'hierarchical', $ {
				'sermon_taxonomy_' . $taxonomy
			} ) )
		{
			$ {
				$taxonomy
			} = strip_tags( get_the_term_list( get_the_ID(), 'imi_isermons-' . $taxonomy, '', ' &amp; ', '' ) );
		} else
		{
			$terms_not_hierarchical = wp_get_post_terms( get_the_ID(), 'imi_isermons-' . $taxonomy );
			$topics_all = false;
			if ( !is_wp_error( $terms_not_hierarchical ) && !empty( $terms_not_hierarchical ) ) {
				$c = 0;
				foreach ( $terms_not_hierarchical as $t ) {
					if ( $c == 0 ) {
						$ {
							$taxonomy
						} = esc_html( $t->name );
						++$c;
					} else {
						$ {
							$taxonomy
						} .= ', ' . esc_html( $t->name );
					}
				}
			}
		}
	}
	$attached_audio = get_post_meta( get_the_ID(), 'isermons_audio_file', true );
	$audio_raw = str_ireplace( 'https://', 'http://', $attached_audio );
	$audio_p = strrpos( $audio_raw, '/' ) + 1;
	$audio_raw = urldecode( $audio_raw );
	$audio = substr( $audio_raw, 0, $audio_p ) . rawurlencode( substr( $audio_raw, $audio_p ) );
	$post_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );
	$post_image = str_ireplace( 'https://', 'http://', !empty( $post_image[ '0' ] ) ? $post_image[ '0' ] : '' );
	$audio_duration = get_post_meta( get_the_ID(), 'isermons_audio_length', true ) ? : '0:00';
	$audio_file_size = get_post_meta( get_the_ID(), 'isermons_audio_size', 'true' ) ? : 0;

	// Fix for relative audio file URLs
	if ( substr( $audio, 0, 1 ) === '/' ) {
		$audio = home_url( $audio );
	}
	$audio_file_size = ( $audio_file_size == 0 ) ? 1 : $audio_file_size;

	?>
<itunes:author><?php echo esc_html( $preachers ); ?></itunes:author>
<itunes:subtitle><?php echo esc_html( $series ); ?></itunes:subtitle>
<?php if ( $post_image ) : ?>
<itunes:image href="<?php echo esc_url( $post_image ); ?>"/>
<?php endif; ?>
<enclosure url="<?php echo esc_url( $audio ); ?>" length="<?php echo intval( $audio_file_size ); ?>" type="audio/mpeg"/>
<itunes:duration><?php echo intval( $audio_duration ); ?></itunes:duration>
<?php if ( $topics ): ?>
<!--<itunes:keywords><?php echo esc_html( $topics ); ?></itunes:keywords>-->
<?php endif;

}
//Display the sermon description as the podcast summary
function isermons_podcast_summary( $content ) {
	global $post;
	$podcast_desc = get_post_meta( $post->ID, 'isermons_sermon_description', true );
	$content = $podcast_desc;
	return $content;
}
//Filter published date for podcast: use sermon date instead of post date
function isermons_podcast_item_date( $time, $d = 'U', $gmt = false ) {
	$time = get_the_date( 'D, d M Y H:i:s O' );
	return $time;
}
// Replace feed title with the one defined in Sermon Manager settings
function isermons_modify_podcast_title( $title ) {
	$options = get_option( 'isermons_options' );
	$podcast_title = esc_attr( $options['isermons_sermons_podcast_title'] ?? '' );
	if ( $podcast_title !== '' ) {
		return $podcast_title;
	}
	return $title;
}
// Modifies get_bloginfo output and injects Sermon Manager data
function isermons_bloginfo_rss_filter( $info, $show ) {
	$options = get_option( 'isermons_options' );
	$new_info = '';
	switch ( $show ) {
		case 'name':
			$new_info = esc_attr( $options[ 'isermons_sermons_podcast_title' ] );
			break;
		case 'description':
			$new_info = stripslashes( wp_filter_nohtml_kses( $options['isermons_sermons_podcast_description'] ?? '' ) );
			break;
	}
	if ( $new_info !== '' ) {
		return $new_info;
	}
	return $info;
}
?>