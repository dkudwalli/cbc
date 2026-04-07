<?php
$title = wp_kses_post($instance['title']);
$percentile = esc_attr($instance['percentile']);
$stripped = esc_attr($instance['stripped']);
$type = (!empty($instance['type']))? $instance['type'] : 'primary' ;
$animation = esc_attr($instance['animation']);
$color = esc_attr($instance['custom_color']) ?>

<div class="progress-label"> <span><?php echo esc_attr($title); ?></span> </div>
  <div class="progress <?php if($stripped!=""){ ?>progress-striped<?php } ?>">
    <div class="progress-bar progress-bar-<?php echo esc_attr($type); ?>" style="background-color:<?php if($color!=""){ ?><?php echo esc_attr($color); ?><?php } ?>;" data-appear-progress-animation="<?php echo esc_attr($percentile); ?>%" data-appear-animation-delay="<?php echo esc_attr($animation); ?>"> <span class="sr-only"><?php echo esc_attr($percentile); ?>% Complete (<?php echo esc_attr($type); ?>)</span><?php if($stripped!=""){ ?><span class="progress-bar-tooltip"><?php echo esc_attr($percentile); ?>%</span><?php } ?> </div>
  </div>