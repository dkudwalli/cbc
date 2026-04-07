<?php $enable_topbar = (isset($args['options']['enable_topbar'])) ? $args['options']['enable_topbar'] : 0;
$switch_toprow_content = (isset($args['options']['switch_toprow_content'])) ? $args['options']['switch_toprow_content'] : 0;
if($enable_topbar == 1){ ?>
<div class="toprow">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-sm-6">
        <nav class="top-menus">
          <ul>
            <?php
            $socialSites = (isset($args['options']['header_social_links'])) ? $args['options']['header_social_links'] : array();
            if ($socialSites) {
              foreach ($socialSites as $key => $value) {
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                  echo '<li><a href="mailto:' . $value . '"><i class="fa ' . $key . '"></i></a></li>';
                } elseif (filter_var($value, FILTER_VALIDATE_URL)) {
                  echo '<li><a href="' . $value . '" target="_blank"><i class="fa ' . $key . '"></i></a></li>';
                } elseif ($key == 'fa-skype' && $value != '' && $value != 'Enter Skype ID') {
                  echo '<li><a href="skype:' . $value . '?call"><i class="fa ' . $key . '"></i></a></li>';
                }
              }
            }
            ?>
          </ul>
        </nav>
      </div>
        <div class="col-md-6 col-sm-6">
          <?php
			if($switch_toprow_content == '1'){
				if (isset($args['options']['topbar_right_textarea']) && $args['options']['topbar_right_textarea'] != '') {
				  echo '<div class="topbar-custom-content">' . do_shortcode($args['options']['topbar_right_textarea']) . '</div>';
				}
			} else {
				$topbar_selected_menu = (isset($args['options']['topbar_selected_menu'])) ? $args['options']['topbar_selected_menu'] : '';
				if($topbar_selected_menu != ''){
					wp_nav_menu(array('menu' => $topbar_selected_menu, 'menu_class' => 'top-navigation sf-menu', 'container' => '', 'walker' => new imic_mega_menu_walker));
				} else{
					if (!empty($args['menu'])) {
						wp_nav_menu(array('theme_location' => 'top-menu', 'menu_class' => 'top-navigation sf-menu', 'container' => '', 'walker' => new imic_mega_menu_walker));
					}
				}
			}
            ?>
        </div>
    </div>
  </div>
</div>
<?php } ?>