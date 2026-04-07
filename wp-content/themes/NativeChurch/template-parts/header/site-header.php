<?php if ( !in_array($args['header_layout'], ['5','6'], true ) ) { ?>
	<header class="site-header">
	  <div class="topbar">
		<div class="container hs4-cont">
		  <div class="row">
			<div id="top-nav-clone"></div>
			<div class="col-md-4 col-sm-6 col-8">
			  <?php get_template_part('template-parts/header/logo'); ?>
			</div>
			<?php
			if (isset($args['header_layout']) && !empty($args['header_layout'])) :
			  echo '<div class="col-md-8 col-sm-6 col-4 hs4-menu">';
			  if (isset($args['options']['enable-top-menu']) && $args['options']['enable-top-menu'] == 1) {
				echo '<div class="enabled-top-mobile">';
			  }
			  if ($args['header_layout'] != 3) :
				if (!empty($args['menu']['top-menu']) && class_exists('imic_mega_menu_walker')) :
				  wp_nav_menu(array('theme_location' => 'top-menu', 'menu_class' => 'top-navigation sf-menu', 'container' => 'div', 'container_class' => 'tinymenu', 'walker' => new imic_mega_menu_walker));

				endif;
			  else :
				if (isset($args['options']['header3_textarea']) && $args['options']['header3_textarea'] != '') {
				  echo '<div class="top-search d-none d-md-block">' . do_shortcode($args['options']['header3_textarea']) . '</div>';
				} else {
				  echo '<div class="top-search d-none d-md-block">
										<form method="get" id="searchform" action="' . home_url() . '">
										<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-search"></i></span>
										<input type="text" class="form-control" name="s" id="s" placeholder="' . esc_html__('Type your keywords...', 'framework') . '">
										</div>
										</form>
										</div>';
				}
			  endif;
			  if (isset($args['options']['mobile_menu_text'])) {
				echo '<a href="#" class="d-md-none menu-toggle"><i class="fa fa-bars"></i> ' . $args['options']['mobile_menu_text'] . '</a>';
			  }

			  if (isset($args['options']['enable-top-menu']) && $args['options']['enable-top-menu'] == 1) {
				echo '</div>';
			  }
			  echo '</div>';
			endif;
			?>
		  </div>
		</div>
	  </div>
	  <?php
	  $header_layout_menu = (isset($args['header_layout'])) ? $args['header_layout'] : '';
	  if ($header_layout_menu != 4) { ?>
		<?php if (!empty($args['menu']['primary-menu']) && class_exists('imic_mega_menu_walker')) { ?>
		  <div class="main-menu-wrapper">
			<div class="container">
			  <div class="row">
				<div class="col-md-12">
				  <nav class="navigation">
					<?php wp_nav_menu(array('theme_location' => 'primary-menu', 'menu_class' => 'sf-menu', 'container' => '', 'walker' => new imic_mega_menu_walker)); ?>
				  </nav>
				</div>
			  </div>
			</div>
		  <?php } ?>
		<?php } ?>
	</header>
<?php } elseif($args['header_layout'] == 5){ ?>
	<header class="site-header new-flex-header">
		<div class="container">
			<?php get_template_part('template-parts/header/logo'); ?>
			<?php if (!empty($args['menu']['primary-menu']) && class_exists('imic_mega_menu_walker')) { ?>
				<nav class="navigation">
					<?php wp_nav_menu(array('theme_location' => 'primary-menu', 'menu_class' => 'sf-menu', 'container' => '', 'walker' => new imic_mega_menu_walker)); ?>
				</nav>
			<?php } ?>
			<div class="mmenu-opener-button">
			<?php  if (isset($args['options']['mobile_menu_text'])) {
					echo '<a href="#" class="d-md-none menu-toggle"><i class="fa fa-bars"></i> ' . $args['options']['mobile_menu_text'] . '</a>';
				  }
			?>
			</div>
		</div>
	</header>
<?php } elseif($args['header_layout'] == 6){ ?>
	<header class="site-header new-flex-header">
		<div class="container">
			<?php get_template_part('template-parts/header/logo'); ?>
			<?php if (!empty($args['menu']['primary-menu']) && class_exists('imic_mega_menu_walker')) { ?>
				<nav class="navigation">
					<?php wp_nav_menu(array('theme_location' => 'primary-menu', 'menu_class' => 'sf-menu', 'container' => '', 'walker' => new imic_mega_menu_walker)); ?>
				</nav>
			<?php } ?>
			<div class="mmenu-opener-button">
			<?php  if (isset($args['options']['mobile_menu_text'])) {
					echo '<a href="#" class="d-md-none menu-toggle"><i class="fa fa-bars"></i> ' . $args['options']['mobile_menu_text'] . '</a>';
				  }
			?>
			</div>
		</div>
	</header>
<?php } ?>