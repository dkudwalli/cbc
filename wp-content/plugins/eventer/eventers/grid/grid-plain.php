<?php
$image_url = '';
if (has_post_thumbnail($params['eventer'])) {
    $image_url = get_the_post_thumbnail_url($params['eventer'], 'eventer-thumb-400x400');
}
if ($params['background'] == '2' && $image_url == '') {
    $grid_class = 'eventer-event-grid-item-plain';
} elseif ($params['background'] == '1' && ($params['color'] == '' || $image_url == '')) {
    $grid_class = 'eventer-event-grid-item-bg eventer-event-grid-item-dbg';
} elseif ($params['background'] != '3' && ($params['color'] != '' || $image_url != '')) {
    $grid_class = 'eventer-event-grid-item-bg';
} else {
    $grid_class = 'eventer-event-grid-item-plain';
}
$grid_output_bg = '';
echo '<li class="eventer-event-item eventer-event-grid-item ' . $grid_class . ' eventer-featured-' . $params['featured_class'] . ' new_eventer_status_'. esc_attr($params['new_status']).'">';
if (($params['background'] == '2' && $image_url != '') || $params['background'] == '' && $image_url != '') {
    $grid_output_bg = '<a href="' . esc_url($params['details']) . '" target="' . esc_attr($params['target']) . '" class="eventer-event-grid-item-inside eventer-event-grid-item-bg-dark eventer-event-item-link equah-item" style="border-left-color:' . $params['color'] . ';background-image:url(' . $image_url . ')">';
} elseif (($params['background'] == '1' && $params['color'] != '') || $params['background'] == '' && $params['color'] != '') {
    $grid_output_bg = '<a href="' . esc_url($params['details']) . '" class="eventer-event-grid-item-inside eventer-event-item-link equah-item" style="border-left-color:' . $params['color'] . '; background-color: ' . $params['color'] . '">';
} else {
    $grid_output_bg = '<a href="' . esc_url($params['details']) . '" class="eventer-event-grid-item-inside eventer-event-item-link equah-item" style="border-left-color:' . $params['color'] . '">';
}
echo $grid_output_bg;
?>
<span class="eventer-event-details ">
   	<?php echo eventer_display_status_badge( $params['eventer'] ); ?>
	<?php echo eventer_check_event_is_virtual( $params['eventer'] ); ?>
	<?php echo $params['featured_span']; ?>
	<?php echo $params['status']; ?>
    <span class="eventer-event-title"><?php echo $params['event']; ?></span>
    <?php if (!empty($params['address'])) { ?>
        <span class="eventer-event-venue"><i class="eventer-icon-location-pin" style="color:#8A9B0F"></i> <?php echo eventer_get_event_venue( $params['address'], $params['eventer'] ); ?></span>
    <?php } ?>
</span>
<?php
$date_show = $params['show_date'];
?>
<span class="eventer-event-date">
    <span class="eventer-event-month"><?php echo esc_attr($date_show); ?></span>
    <span class="eventer-event-time"><?php echo $params['show_time']; ?></span>
</span>
</a>
</li>