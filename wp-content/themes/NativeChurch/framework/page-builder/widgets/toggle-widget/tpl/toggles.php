<?php
global $framework_allowed_tags;
$tabid = wp_kses_post($instance['tab_id']); ?>
<div class="accordion" id="<?php echo esc_attr($tabid); ?>">
    <?php $i =0; $class='active'; $class1='in'; $output=''; ?>
	<?php foreach( $instance['tabs'] as $i => $tab ) : ?>
    <?php if($i!=0) $class=''; $class1=''; 
      	$output.='<div class="accordion-group panel">
                        <div class="accordion-heading togglize"> <a class="accordion-toggle" data-bs-toggle="collapse" data-parent="#" href="#Area_'.$tabid.$i.'">'.wp_kses_post( $tab['tab_nav_title'] ).'<i class="fa fa-plus-circle"></i> <i class="fa fa-minus-circle"></i> </a> </div>';
      	$output.='<div id="Area_'.$tabid.$i.'" class="accordion-body collapse"><div class="accordion-inner">'.wp_kses_post( $tab['tab_nav_content'] ).'</div></div></div>';
    	 $i++; 
		 endforeach; 
         echo wp_kses($output, $framework_allowed_tags); ?>
</div>