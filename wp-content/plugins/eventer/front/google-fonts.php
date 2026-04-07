<?php
// Enqueue the selected Google Font in the frontend
add_action('wp_enqueue_scripts', 'eventer_enqueue_google_font');
function eventer_enqueue_google_font() {
    $selected_font = eventer_get_settings('eventer_google_font_global');
	if (!$selected_font) {
		$selected_font = 'Oswald';
	}
    if ($selected_font) {
        wp_enqueue_style(
            'eventer-google-font', 
            'https://fonts.googleapis.com/css?family=' . urlencode($selected_font), 
            array(), 
            null
        );
    }
    $cursive_font = 'Great Vibes';
    if ($cursive_font) {
        wp_enqueue_style(
            'eventer-google-font-cursive', 
            'https://fonts.googleapis.com/css?family=' . urlencode($cursive_font), 
            array(), 
            null
        );
    }
	$custom_typography_css = "
	.eventer .eventer-btn,.eventer .eventer-btn:disabled,.eventer .eventer-btn:disabled:hover,.eventer label:not(.eventer-checkbox):not(.eventer-radio),.eventer-twelve-months li,.eventer-event-date > span,.eventer-actions li,.eventer-ticket-type-name,.eventer-event-share > li:first-child,.eventer-event-save > span,.eventer-countdown-timer > .eventer-timer-col,.eventer-featured-label,.eventer-status-badge,.eventer-native-list .eventer-dater,.eventer .eventer-detailed-more-btn,.eventer-detailed-list .eventer-dater .eventer-event-day,.eventer-detailed-list .eventer-dater .eventer-event-time,.eventer-detailed-list .eventer-dater .eventer-event-date,.eventer-modern-list .eventer-dater .eventer-event-date,.eventer-featured-date,.eventer-grid-modern .eventer-event-day,.eventer-grid-modern .eventer-event-date,.eventer-slider-type1 .eventer-slider-content-bottom a,.eventer-slider-type2 .eventer-event-date,.eventer-organizer-block .eventer-organizer-info > span,.eventer-schedule-stamp{
	font-family:\"". addslashes($selected_font) ."\";
	}
	.eventer-ticket-confirmation-left span{
		font-family:\"". addslashes($cursive_font) ."\";
	}";

	wp_add_inline_style( 'eventer-style', $custom_typography_css );
}
?>