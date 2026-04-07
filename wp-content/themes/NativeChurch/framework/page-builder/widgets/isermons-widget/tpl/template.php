<?php
global $framework_allowed_tags;
$shortcode_type = $instance['sermon_shortcode_type'];

// Sermon Posts Shortcode
if($shortcode_type == 'isermons-list'){
	$layout_f = $relation_f = $search_f = $filters_f = $watch_f = $columns_f = $words_f = $image_f = $per_page_f = $redirect_f = $meta_data_f = $imi_isermons_categories_f = $imi_isermons_series_f = $imi_isermons_books_f = $imi_isermons_topics_f = $imi_isermons_preachers_f = '';
	// Attributes
	$layout = $instance['posts_fields']['layout'];
	$relation = $instance['posts_fields']['relation'];
	$search = $instance['posts_fields']['search'];
	$filters = $instance['posts_fields']['filters'];
	$watch = $instance['posts_fields']['watch'];
	$columns = $instance['posts_fields']['columns'];
	$words = $instance['posts_fields']['words'];
	$image = $instance['posts_fields']['image'];
	$per_page = $instance['posts_fields']['per_page'];
	$redirect = $instance['posts_fields']['redirect'];
	$meta_data = $instance['posts_fields']['meta_data'];
	$imi_isermons_categories = $instance['posts_fields']['imi_isermons_categories'];
	$imi_isermons_series = $instance['posts_fields']['imi_isermons_series'];
	$imi_isermons_books = $instance['posts_fields']['imi_isermons_books'];
	$imi_isermons_topics = $instance['posts_fields']['imi_isermons_topics'];
	$imi_isermons_preachers = $instance['posts_fields']['imi_isermons_preachers'];
	
	if(is_array($search)){ $search = implode(',', $search);}
	if(is_array($filters)){ $filters = implode(',', $filters);}
	if(is_array($meta_data)){ $meta_data = implode(',', $meta_data);}
	if(is_array($imi_isermons_categories)){ $imi_isermons_categories = implode(',', $imi_isermons_categories);}
	if(is_array($imi_isermons_series)){ $imi_isermons_series = implode(',', $imi_isermons_series);}
	if(is_array($imi_isermons_books)){ $imi_isermons_books = implode(',', $imi_isermons_books);}
	if(is_array($imi_isermons_topics)){ $imi_isermons_topics = implode(',', $imi_isermons_topics);}
	if(is_array($imi_isermons_preachers)){ $imi_isermons_preachers = implode(',', $imi_isermons_preachers);}
	
	if($layout){$layout_f = ' layout="'.$layout.'"';}
	if($relation){$relation_f = ' relation="'.$relation.'"';}
	if($search){$search_f = ' search="'.$search.'"';}
	if($filters){$filters_f = ' filters="'.$filters.'"';}
	if($watch){$watch_f = ' watch="'.$watch.'"';}
	if($columns){$columns_f = ' columns="'.$columns.'"';}
	if($words){$words_f = ' words="'.$words.'"';}
	if($image){$image_f = ' image="'.$image.'"';}
	if($per_page){$per_page_f = ' per_page="'.$per_page.'"';}
	if($redirect){$redirect_f = ' redirect="'.$redirect.'"';}
	if($meta_data){$meta_data_f = ' meta_data="'.$meta_data.'"';}
	if($imi_isermons_categories){$imi_isermons_categories_f = ' imi_isermons-categories="'.$imi_isermons_categories.'"';}
	if($imi_isermons_series){$imi_isermons_series_f = ' imi_isermons-series="'.$imi_isermons_series.'"';}
	if($imi_isermons_books){$imi_isermons_books_f = ' imi_isermons-books="'.$imi_isermons_books.'"';}
	if($imi_isermons_topics){$imi_isermons_topics_f = ' imi_isermons-topics="'.$imi_isermons_topics.'"';}
	if($imi_isermons_preachers){$imi_isermons_preachers_f = ' imi_isermons-preachers="'.$imi_isermons_preachers.'"';}
	// Shortcode
	$shortcode = ('[isermons-list'.$layout_f.$relation_f.$search_f.$filters_f.$watch_f.$columns_f.$words_f.$image_f.$per_page_f.$per_page_f.$redirect_f.$meta_data_f.$imi_isermons_categories_f.$imi_isermons_series_f.$imi_isermons_books_f.$imi_isermons_topics_f.$imi_isermons_preachers_f.']');
}

// Sermons Taxonomy Shortcode
if($shortcode_type == 'isermons-terms'){
	$term_layout_f = $term_columns_f = $term_words_f = $term_image_f = $term_taxonomy_f = $filters_order_f = $imi_isermons_categories_f = $imi_isermons_series_f = $imi_isermons_books_f = $imi_isermons_topics_f = $imi_isermons_preachers_f = '';
	// Attributes
	$term_layout = $instance['taxonomy_fields']['term_layout'];
	$term_columns = $instance['taxonomy_fields']['term_columns'];
	$term_words = $instance['taxonomy_fields']['term_words'];
	$term_image = $instance['taxonomy_fields']['term_image'];
	$term_taxonomy = $instance['taxonomy_fields']['term_taxonomy'];
	$filters_order = $instance['taxonomy_fields']['filters_order'];
	$imi_isermons_categories = $instance['taxonomy_fields']['term_categories'];
	$imi_isermons_series = $instance['taxonomy_fields']['term_series'];
	$imi_isermons_books = $instance['taxonomy_fields']['term_books'];
	$imi_isermons_topics = $instance['taxonomy_fields']['term_topics'];
	$imi_isermons_preachers = $instance['taxonomy_fields']['term_preachers'];
	
	if(is_array($imi_isermons_categories)){ $imi_isermons_categories = implode(',', $imi_isermons_categories);}
	if(is_array($imi_isermons_series)){ $imi_isermons_series = implode(',', $imi_isermons_series);}
	if(is_array($imi_isermons_books)){ $imi_isermons_books = implode(',', $imi_isermons_books);}
	if(is_array($imi_isermons_topics)){ $imi_isermons_topics = implode(',', $imi_isermons_topics);}
	if(is_array($imi_isermons_preachers)){ $imi_isermons_preachers = implode(',', $imi_isermons_preachers);}
	
	if($term_layout){$term_layout_f = ' layout="'.$term_layout.'"';}
	if($term_columns){$term_columns_f = ' columns="'.$term_columns.'"';}
	if($term_words){$term_words_f = ' words="'.$term_words.'"';}
	if($term_image){$term_image_f = ' image="'.$term_image.'"';}
	if($term_taxonomy){$term_taxonomy_f = ' taxonomy="'.$term_taxonomy.'"';}
	if($filters_order){$filters_order_f = ' filters_order="'.$filters_order.'"';}
	if($imi_isermons_categories){$imi_isermons_categories_f = ' imi_isermons-categories="'.$imi_isermons_categories.'"';}
	if($imi_isermons_series){$imi_isermons_series_f = ' imi_isermons-series="'.$imi_isermons_series.'"';}
	if($imi_isermons_books){$imi_isermons_books_f = ' imi_isermons-books="'.$imi_isermons_books.'"';}
	if($imi_isermons_topics){$imi_isermons_topics_f = ' imi_isermons-topics="'.$imi_isermons_topics.'"';}
	if($imi_isermons_preachers){$imi_isermons_preachers_f = ' imi_isermons-preachers="'.$imi_isermons_preachers.'"';}
	// Shortcode
	$shortcode = ('[isermons-terms'.$term_layout_f.$term_columns_f.$term_words_f.$term_image_f.$term_taxonomy_f.$filters_order_f.$imi_isermons_categories_f.$imi_isermons_series_f.$imi_isermons_books_f.$imi_isermons_topics_f.$imi_isermons_preachers_f.']');
}

// OUTPUT
echo wp_kses($shortcode, $framework_allowed_tags);
?>
