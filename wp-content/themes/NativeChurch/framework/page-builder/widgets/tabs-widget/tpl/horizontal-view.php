<?php
global $framework_allowed_tags;
$tabid = wp_kses_post($instance['tab_id']); ?>
<div class="tabs">
    <ul class="nav nav-tabs">
    <?php $i =0; $class='active';$output_li= $output_div=''; ?>
	<?php foreach( $instance['tabs'] as $i => $tab ) : ?>
    <?php if($i!=0) $class=''; 
      	$output_li.='<li class="'.$class.'"> <a data-bs-toggle="tab" href="#sampletab_'.$tabid.$i.'">'.wp_kses_post( $tab['tab_nav_title'] ).'</a> </li>';
      	$output_div.='<div id="sampletab_'.$tabid.$i.'" class="tab-pane '.$class.'">'.wp_kses_post( $tab['tab_nav_content'] ).'</div>';
    	 $i++; 
		 endforeach; 
         echo wp_kses($output_li, $framework_allowed_tags); ?>
        </ul>
    <div class="tab-content">
    <?php echo wp_kses($output_div, $framework_allowed_tags); ?>
    </div>
</div>