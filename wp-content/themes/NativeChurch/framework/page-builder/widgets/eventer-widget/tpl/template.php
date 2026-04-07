<?php
global $framework_allowed_tags;
$shortcode_type = $instance['shortcode_type'];

// Countdown Shortcode
if($shortcode_type == 'eventer_counter'){
	$counter_ids_f = $counter_terms_cats_f = $counter_terms_tags_f = $counter_terms_venues_f = $counter_terms_organizers_f = $counter_venue_f = $counter_type_f = $counter_event_until_f = '';
	// Attributes
	$counter_ids = $instance['counter_fields']['counter_ids'];
	$counter_terms_cats = $instance['counter_fields']['counter_terms_cats'];
	$counter_terms_tags = $instance['counter_fields']['counter_terms_tags'];
	$counter_terms_venues = $instance['counter_fields']['counter_terms_venues'];
	$counter_terms_organizers = $instance['counter_fields']['counter_terms_organizers'];
	$counter_venue = $instance['counter_fields']['counter_venue'];
	$counter_type = $instance['counter_fields']['counter_type'];
	$counter_event_until = $instance['counter_fields']['counter_event_until'];
	
	if($counter_ids){$counter_ids_f = ' ids="'.$counter_ids.'"';}
	if($counter_terms_cats){$counter_terms_cats_f = ' terms_cats="'.$counter_terms_cats.'"';}
	if($counter_terms_tags){$counter_terms_tags_f = ' terms_tags="'.$counter_terms_tags.'"';}
	if($counter_terms_venues){$counter_terms_venues_f = ' terms_venue="'.$counter_terms_venues.'"';}
	if($counter_terms_organizers){$counter_terms_organizers_f = ' terms_organizer="'.$counter_terms_organizers.'"';}
	if($counter_venue){$counter_venue_f = ' venue="'.$counter_venue.'"';}
	if($counter_type){$counter_type_f = ' type="'.$counter_type.'"';}
	if($counter_event_until){$counter_event_until_f = ' event_until="'.$counter_event_until.'"';}
	
	// Shortcode
	$shortcode = ('[eventer_counter'.$counter_ids_f.$counter_terms_cats_f.$counter_terms_tags_f.$counter_terms_venues_f.$counter_terms_organizers_f.$counter_venue_f.$counter_type_f.$counter_event_until_f.']');
}

// List Shortcode
if($shortcode_type == 'eventer_list'){
	$list_view_f = $list_ids_f = $list_terms_cats_f = $list_terms_tags_f = $list_terms_venues_f = $list_terms_organizers_f = $list_type_f = $list_featured_f = $list_month_filter_f = $list_calview_f = $list_status_f = $list_filters_f = $list_venue_f = $list_count_f = $list_pagination_f = '';
	// Attributes
	$list_ids = $instance['list_fields']['list_ids'];
	$list_terms_cats = $instance['list_fields']['list_terms_cats'];
	$list_terms_tags = $instance['list_fields']['list_terms_tags'];
	$list_terms_venues = $instance['list_fields']['list_terms_venues'];
	$list_terms_organizers = $instance['list_fields']['list_terms_organizers'];
	$list_type = $instance['list_fields']['list_type'];
	$list_featured = $instance['list_fields']['list_featured'];
	$list_month_filter = $instance['list_fields']['list_month_filter'];
	$list_calview = $instance['list_fields']['list_calview'];
	$list_status = $instance['list_fields']['list_status'];
	$list_filters = $instance['list_fields']['list_filters'];
	$list_view = $instance['list_fields']['list_view'];
	$list_venue = $instance['list_fields']['list_venue'];
	$list_count = $instance['list_fields']['list_count'];
	$list_pagination = $instance['list_fields']['list_pagination'];
	
	if(is_array($list_ids)){ $list_ids = implode(',', $list_ids);}
	if(is_array($list_terms_cats)){ $list_terms_cats = implode(',', $list_terms_cats);}
	if(is_array($list_terms_tags)){ $list_terms_tags = implode(',', $list_terms_tags);}
	if(is_array($list_terms_venues)){ $list_terms_venues = implode(',', $list_terms_venues);}
	if(is_array($list_terms_organizers)){ $list_terms_organizers = implode(',', $list_terms_organizers);}
	if(is_array($list_calview)){ $list_calview = implode(',', $list_calview);}
	if(is_array($list_filters)){ $list_filters = implode(',', $list_filters);}
	
	if($list_view){$list_view_f = ' view="'.$list_view.'"';}
	if($list_ids){$list_ids_f = ' ids="'.$list_ids.'"';}
	if($list_terms_cats){$list_terms_cats_f = ' term_cat="'.$list_terms_cats.'"';}
	if($list_terms_tags){$list_terms_tags_f = ' terms_tags="'.$list_terms_tags.'"';}
	if($list_terms_venues){$list_terms_venues_f = ' terms_venue="'.$list_terms_venues.'"';}
	if($list_terms_organizers){$terms_organizer_f = ' terms_organizer="'.$list_terms_organizers.'"';}
	if($list_type){$list_type_f = ' type="'.$list_type.'"';}
	if($list_featured){$list_featured_f = ' featured="'.$list_featured.'"';}
	if($list_month_filter){$list_month_filter_f = ' month_filter="'.$list_month_filter.'"';}
	if($list_calview){$list_calview_f = ' calview="'.$list_calview.'"';}
	if($list_status){$list_status_f = ' status="'.$list_status.'"';}
	if($list_filters){$list_filters_f = ' filters="'.$list_filters.'"';}
	if($list_venue){$list_venue_f = ' venue="'.$list_venue.'"';}
	if($list_count){$list_count_f = ' count="'.$list_count.'"';}
	if($list_pagination){$list_pagination_f = ' pagination="'.$list_pagination.'"';}
	
	// Shortcode
	$shortcode = ('[eventer_list'.$list_ids_f.$list_terms_cats_f.$list_terms_tags_f.$list_terms_venues_f.$list_terms_organizers_f.$list_type_f.$list_featured_f.$list_month_filter_f.$list_calview_f.$list_status_f.' series=""'.$list_filters_f.$list_view_f.$list_venue_f.$list_count_f.$list_pagination_f.']');
}

// Grid Shortcode
if($shortcode_type == 'eventer_grid'){
	$grid_layout_f = $grid_ids_f = $grid_terms_cats_f = $grid_terms_tags_f = $grid_terms_venues_f = $grid_terms_organizers_f = $grid_type_f = $grid_featured_f = $grid_month_filter_f = $grid_calview_f = $grid_status_f = $grid_filters_f = $grid_venue_f = $grid_background_f = $grid_column_f = $grid_count_f = $grid_pagination_f = $grid_carousel_f = '';
	// Attributes
	$grid_layout = $instance['grid_fields']['grid_layout'];
	$grid_ids = $instance['grid_fields']['grid_ids'];
	$grid_terms_cats = $instance['grid_fields']['grid_terms_cats'];
	$grid_terms_tags = $instance['grid_fields']['grid_terms_tags'];
	$grid_terms_venues = $instance['grid_fields']['grid_terms_venues'];
	$grid_terms_organizers = $instance['grid_fields']['grid_terms_organizers'];
	$grid_type = $instance['grid_fields']['grid_type'];
	$grid_featured = $instance['grid_fields']['grid_featured'];
	$grid_month_filter = $instance['grid_fields']['grid_month_filter'];
	$grid_calview = $instance['grid_fields']['grid_calview'];
	$grid_status = $instance['grid_fields']['grid_status'];
	$grid_filters = $instance['grid_fields']['grid_filters'];
	$grid_venue = $instance['grid_fields']['grid_venue'];
	$grid_background = $instance['grid_fields']['grid_background'];
	$grid_column = $instance['grid_fields']['grid_column'];
	$grid_count = $instance['grid_fields']['grid_count'];
	$grid_pagination = $instance['grid_fields']['grid_pagination'];
	$carousel[] = $instance['grid_fields']['carousel_autoplay'];
	$carousel[] = $instance['grid_fields']['carousel_interval'];
	$carousel[] = $instance['grid_fields']['carousel_pagination'];
	$carousel[] = $instance['grid_fields']['carousel_arrows'];
	$carousel[] = $instance['grid_fields']['carousel_rtl'];
	
	if(is_array($grid_ids)){ $grid_ids = implode(',', $grid_ids);}
	if(is_array($grid_terms_cats)){ $grid_terms_cats = implode(',', $grid_terms_cats);}
	if(is_array($grid_terms_tags)){ $grid_terms_tags = implode(',', $grid_terms_tags);}
	if(is_array($grid_terms_venues)){ $grid_terms_venues = implode(',', $grid_terms_venues);}
	if(is_array($grid_terms_organizers)){ $grid_terms_organizers = implode(',', $grid_terms_organizers);}
	if(is_array($grid_calview)){ $grid_calview = implode(',', $grid_calview);}
	if(is_array($grid_filters)){ $grid_filters = implode(',', $grid_filters);}
	if(is_array($grid_carousel)){ $grid_carousel = implode(',', $grid_carousel);}
	
	if($grid_layout){$grid_layout_f = ' layout="'.$grid_layout.'"';}
	if($grid_ids){$grid_ids_f = ' ids="'.$grid_ids.'"';}
	if($grid_terms_cats){$grid_terms_cats_f = ' term_cat="'.$grid_terms_cats.'"';}
	if($grid_terms_tags){$grid_terms_tags_f = ' terms_tags="'.$grid_terms_tags.'"';}
	if($grid_terms_venues){$grid_terms_venues_f = ' terms_venue="'.$grid_terms_venues.'"';}
	if($grid_terms_organizers){$grid_terms_organizers_f = ' terms_organizer="'.$grid_terms_organizers.'"';}
	if($grid_type){$grid_type_f = ' type="'.$grid_type.'"';}
	if($grid_featured){$grid_featured_f = ' featured="'.$grid_featured.'"';}
	if($grid_month_filter){$grid_month_filter_f = ' month_filter="'.$grid_month_filter.'"';}
	if($grid_calview){$grid_calview_f = ' calview="'.$grid_calview.'"';}
	if($grid_status){$grid_status_f = ' status="'.$grid_status.'"';}
	if($grid_filters){$grid_filters_f = ' filters="'.$grid_filters.'"';}
	if($grid_venue){$grid_venue_f = ' venue="'.$grid_venue.'"';}
	if($grid_background){$grid_background_f = ' background="'.$grid_background.'"';}
	if($grid_column){$grid_column_f = ' column="'.$grid_column.'"';}
	if($grid_count){$grid_count_f = ' count="'.$grid_count.'"';}
	if($grid_pagination){$grid_pagination_f = ' pagination="'.$grid_pagination.'"';}
	if($grid_carousel){$grid_carousel_f = ' carousel="'.$grid_carousel.'"';}
	
	
	// Shortcode
	$shortcode = ('[eventer_grid'.$grid_layout_f.$grid_ids_f.$grid_terms_cats_f.$grid_terms_tags_f.$grid_terms_venues_f.$grid_terms_organizers_f.$grid_type_f.$grid_featured_f.$grid_month_filter_f.$grid_calview_f.$grid_status_f.$grid_filters_f.' series=""'.$grid_background_f.$grid_column_f.$grid_venue_f.$grid_count_f.$grid_pagination_f.$grid_carousel_f.']');
}

// Slider Shortcode
if($shortcode_type == 'eventer_slider'){
	$slider_layout_f = $slider_ids_f = $slider_terms_cats_f = $slider_terms_tags_f = $slider_terms_venues_f = $slider_terms_organizers_f = $slider_count_f = $slider_carousel_f = '';
	// Attributes
	$slider_layout = $instance['slider_fields']['slider_layout'];
	$slider_ids = $instance['slider_fields']['slider_ids'];
	$slider_terms_cats = $instance['slider_fields']['slider_terms_cats'];
	$slider_terms_tags = $instance['slider_fields']['slider_terms_tags'];
	$slider_terms_venues = $instance['slider_fields']['slider_terms_venues'];
	$slider_terms_organizers = $instance['slider_fields']['slider_terms_organizers'];
	$slider_count = $instance['slider_fields']['slider_count'];
	$slider_carousel[] = $instance['slider_fields']['slider_autoplay'];
	$slider_carousel[] = $instance['slider_fields']['slider_interval'];
	$slider_carousel[] = $instance['slider_fields']['slider_pagination'];
	$slider_carousel[] = $instance['slider_fields']['slider_arrows'];
	$slider_carousel[] = $instance['slider_fields']['slider_rtl'];
	
	if(is_array($slider_ids)){ $slider_ids = implode(',', $slider_ids);}
	if(is_array($slider_terms_cats)){ $slider_terms_cats = implode(',', $slider_terms_cats);}
	if(is_array($slider_terms_tags)){ $slider_terms_tags = implode(',', $slider_terms_tags);}
	if(is_array($slider_terms_venues)){ $slider_terms_venues = implode(',', $slider_terms_venues);}
	if(is_array($slider_terms_organizers)){ $slider_terms_organizers = implode(',', $slider_terms_organizers);}
	$atts_carousel = implode(',', $slider_carousel);
	
	if($slider_layout){$slider_layout_f = ' layout="'.$slider_layout.'"';}
	if($slider_ids){$slider_ids_f = ' ids="'.$slider_ids.'"';}
	if($slider_terms_cats){$slider_terms_cats_f = ' term_cat="'.$slider_terms_cats.'"';}
	if($slider_terms_tags){$slider_terms_tags_f = ' terms_tags="'.$slider_terms_tags.'"';}
	if($slider_terms_venues){$slider_terms_venues_f = ' terms_venue="'.$slider_terms_venues.'"';}
	if($slider_terms_organizers){$slider_terms_organizers_f = ' terms_organizer="'.$slider_terms_organizers.'"';}
	if($slider_count){$slider_count_f = ' count="'.$slider_count.'"';}
	if($atts_carousel){$slider_carousel_f = ' carousel="'.$atts_carousel.'"';}
	
	// Shortcode
	$shortcode = ('[eventer_slider'.$slider_layout_f.$slider_ids_f.$slider_terms_cats_f.$slider_terms_tags_f.$slider_terms_venues_f.$slider_terms_organizers_f.$slider_count_f.$slider_carousel_f.']');
}

// Calendar Shortcode
if($shortcode_type == 'eventer_calendar'){
	$calendar_terms_cats_f = $calendar_terms_tags_f = $calendar_terms_venues_f = $calendar_terms_organizers_f = $calendar_type = $calendar_preview = '';
	// Attributes
	$calendar_terms_cats = $instance['calendar_fields']['slider_terms_cats'];
	$calendar_terms_tags = $instance['calendar_fields']['slider_terms_tags'];
	$calendar_terms_venues = $instance['calendar_fields']['slider_terms_venues'];
	$calendar_terms_organizers = $instance['calendar_fields']['slider_terms_organizers'];
	$calendar_type = $instance['calendar_fields']['calendar_type'];
	$calendar_preview = $instance['calendar_fields']['calendar_preview'];
	
	if(is_array($calendar_terms_cats)){ $calendar_terms_cats = implode(',', $calendar_terms_cats);}
	if(is_array($calendar_terms_tags)){ $calendar_terms_tags = implode(',', $calendar_terms_tags);}
	if(is_array($calendar_terms_venues)){ $calendar_terms_venues = implode(',', $calendar_terms_venues);}
	if(is_array($calendar_terms_organizers)){ $calendar_terms_organizers = implode(',', $calendar_terms_organizers);}
	
	if($calendar_terms_cats){$calendar_terms_cats_f = ' term_cat="'.$calendar_terms_cats.'"';}
	if($calendar_terms_tags){$calendar_terms_tags_f = ' terms_tags="'.$calendar_terms_tags.'"';}
	if($calendar_terms_venues){$calendar_terms_venues_f = ' terms_venue="'.$calendar_terms_venues.'"';}
	if($calendar_terms_organizers){$calendar_terms_organizers_f = ' terms_organizer="'.$calendar_terms_organizers.'"';}
	if($calendar_type){$calendar_type_f = ' type="'.$calendar_type.'"';}
	if($calendar_preview){$calendar_preview_f = ' preview="'.$calendar_preview.'"';}
	
	// Shortcode
	$shortcode = ('[eventer_calendar'.$slider_terms_cats_f.$slider_terms_tags_f.$slider_terms_venues_f.$slider_terms_organizers_f.$calendar_type_f.$calendar_preview_f.']');
}

// OUTPUT
echo wp_kses($shortcode, $framework_allowed_tags);
?>
