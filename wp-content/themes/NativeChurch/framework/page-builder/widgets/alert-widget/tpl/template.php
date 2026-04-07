<?php
$close = esc_attr($instance['close']);
$type = (!empty($instance['type']))? $instance['type'] : 'standard' ;
$animation = (!empty($instance['animation']))? $instance['animation'] : 'fadeIn' ;
$color = esc_attr($instance['custom_color']);
$bcolor = esc_attr($instance['custom_bcolor']);
$tcolor = esc_attr($instance['custom_tcolor']) ?>

<div data-appear-animation="<?php echo esc_attr($animation); ?>"><div class="alert alert-<?php echo esc_attr($type); ?> fade in" style="background-color:<?php echo esc_attr($color); ?>; color:<?php echo esc_attr($tcolor); ?>; border-color:<?php echo esc_attr($bcolor); ?>"> <?php if($close!=""){ ?><a class="close" style="color:<?php echo esc_attr($tcolor); ?>" data-bs-dismiss="alert" href="#">&times;</a><?php } ?> <?php echo wp_kses_post($instance['content']); ?> </div></div>