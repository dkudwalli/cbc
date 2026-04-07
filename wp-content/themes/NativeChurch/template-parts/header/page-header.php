<div class="page-header">
  <div class="container">
    <div class="row detail-page-title-bar">
      <?php
      //Title for the search page
      if (is_search()) {
        get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'12', 'title' => sprintf(esc_html__('Search Results for :  %s', 'framework'), get_search_query())]);
      } 
      else if (is_404()) {
        //Title for the 404 error page
          get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'12', 'title' => esc_html__('Error 404 - Not Found', 'framework')]);
      } 
      elseif (is_author()) {
        //Title for the author template
        $userdata = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
        get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'8', 'title' => esc_html__('All Posts For - ', 'framework').$userdata->data->display_name]);
      } 
      else if (get_post_type($args['id']) == 'page') {
        //Title for the pages
        if (is_page_template('template-gallery-filter.php')) {
          $colmd_class = "6";
        } else {
          $colmd_class = "12";
        }
        $imic_post_custom_title = !empty($args['custom']['imic_post_page_custom_title'][0]) ? $args['custom']['imic_post_page_custom_title'][0] : get_the_title($args['id']);
        $event_cat = get_query_var('event_cat');
        $event_cat = !empty($event_cat) ? $event_cat : '';
        if (!empty($event_cat)) {
          get_template_part('template-parts/header/page','title',['hclass'=>'', 'class'=>$colmd_class, 'title' => esc_html__('All Posts For ', 'framework').strtoupper($event_cat)]);
        } else{
          get_template_part('template-parts/header/page','title',['hclass'=>'cpt-page-title','class'=>$colmd_class, 'title' => $imic_post_custom_title]);
        }
        if (is_page_template('template-gallery-filter.php')) {
            ?>
      <div class="col-md-<?php echo esc_attr($colmd_class); ?>">
        <div class=" gallery-filter">
          <ul class="nav nav-pills sort-source" data-sort-id="gallery" data-option-key="filter">
            <?php
                    $gallery_category = imic_get_term_category(get_the_ID(), 'imic_advanced_gallery_taxonomy', 'gallery-category');
                    $gallery_cats = explode(',', $gallery_category);
                    if (count($gallery_cats) == 1 && !empty($gallery_category)) {
                      ?>
            <li data-option-value="*" class="active">
              <a href="#">
                <i class="fa fa-th"></i>
                <span>
                  <?php $term = get_term_by('slug', $gallery_cats[0], 'gallery-category');
                              $name = $term->name;
                              echo esc_html($name); ?>
                </span>
              </a>
            </li>
            <?php
                        $current_term = get_term_by('slug', $gallery_cats[0], 'gallery-category');
                        $args = array(
                          'child_of' => $current_term->term_id,
                          'taxonomy' => $current_term->taxonomy,
                          'hide_empty' => 0,
                          'hierarchical' => true,
                          'depth'  => 1,
                          'title_li' => ''
                        );
                        $categories = get_categories($args);
                        foreach ($categories as $gallery_cat) { ?>
            <li data-option-value=".format-<?php echo esc_attr($gallery_cat->slug); ?>">
              <a href="#"><i class="fa <?php echo esc_attr($gallery_cat->slug); ?>"></i>
                <span><?php echo esc_attr($gallery_cat->name); ?></span>
              </a>
            </li>
            <?php  }
                    } ?>
            <?php if (!empty($gallery_cats) && count($gallery_cats) > 1) {  ?>
            <li data-option-value="*" class="active">
              <a href="#">
                <i class="fa fa-th"></i> <span><?php _e('All', 'framework') ?></span>
              </a>
            </li>
            <?php foreach ($gallery_cats as $gallery_cat) { ?>
            <li data-option-value=".format-<?php echo esc_attr($gallery_cat); ?>">
              <a href="#">
                <i class="fa <?php echo esc_attr($gallery_cat); ?>"></i>
                <span>
                  <?php $term = get_term_by('slug', $gallery_cat, 'gallery-category');
                                  $name = $term->name;
                                  echo esc_html($name); ?>
                </span>
              </a>
            </li>
            <?php
                      }
                    }
                    ?>
            <?php
                    if (empty($gallery_category)) {
                      $gallery_cats_default = get_terms("gallery-category");
                      ?>
            <li data-option-value="*" class="active">
              <a href="#">
                <i class="fa fa-th"></i>
                <span><?php _e('Show All', 'framework'); ?>
                </span>
              </a>
            </li>
            <?php foreach ($gallery_cats_default as $gallery_cat_default) { ?>
            <li data-option-value=".format-<?php echo esc_attr($gallery_cat_default->slug); ?>">
              <a href="#">
                <i class="fa <?php echo esc_attr($gallery_cat_default->description); ?>"></i>
                <span>
                  <?php echo esc_attr($gallery_cat_default->name); ?>
                </span>
              </a>
            </li>
            <?php } ?>
            <?php } ?>
          </ul>
        </div>
      </div>
      <?php
          }
        } else if (get_post_type($args['id']) == 'post') {
          //Title for the post
          if (is_category() || is_tag()) {
            get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'8', 'title' => esc_html__('All Posts For - ', 'framework').single_term_title("", false)]);
          } else {
            $custom_title = get_post_meta($args['id'],'imic_post_page_custom_title',true);
            $imic_post_custom_title = ($custom_title!='')?$custom_title:get_the_title($args['id']);
            get_template_part('template-parts/header/page','title',['hclass'=>'cpt-page-title','class'=>'8', 'title' => $imic_post_custom_title]);
            if (!empty($args['custom']['imic_post_custom_description'][0])) {
                ?>
      <div class="col-md-4 col-sm-4">
        <p class="custom-desc">
          <?php echo ''.$args['custom']['imic_post_custom_description'][0]; ?>
        </p>
      </div>
      <?php
              }
            }
          } else if (get_post_type($args['id']) == 'sermons') {
            //Title for the sermon
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            if (!empty($term->term_id)) {
              get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'12', 'title' => esc_html__('All Sermons For ', 'framework') . $term->name]);
          } else {
            $imic_post_custom_title = !empty($args['custom']['imic_post_page_custom_title'][0]) ? $args['custom']['imic_post_page_custom_title'][0] : esc_html__('Sermons', 'framework');
            $sterm = wp_get_object_terms($args['id'], 'sermons-category');
            get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'10', 'title' => $imic_post_custom_title]);
              if (!empty($sterm)) {
                $i = 1;
                foreach ($sterm as $terms) {
                  if ($i == 1) {
                    $term_link = get_term_link($terms, 'sermons-category');
                    ?>
      <div class="col-md-2 col-sm-2 col-4">
        <a href="<?php echo esc_url($term_link); ?>" class="float-end btn btn-primary">
          <?php esc_html_e('All sermons', 'framework'); ?>
        </a>
      </div>
      <?php
                  }
                  $i++;
                }
              }
            }
          } else if (get_post_type($args['id']) == 'causes') {
            //Title for the causes
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            if (!empty($term->term_id)) {
              get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'12', 'title' => esc_html__('All Causes For - ', 'framework') . $term->name]);
          } else {
            $imic_post_custom_title = !empty($args['custom']['imic_post_page_custom_title'][0]) ? $args['custom']['imic_post_page_custom_title'][0] : get_the_title();
            $sterm = wp_get_object_terms(get_the_ID(), 'causes-category');
            get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'10', 'title' => $imic_post_custom_title]);
              if (!empty($sterm)) {
                $i = 1;
                foreach ($sterm as $terms) {
                  if ($i == 1) {
                    $term_link = get_term_link($terms, 'causes-category');
                    ?>
      <div class="col-md-2 col-sm-2 col-4">
        <a href="<?php echo esc_url($term_link); ?>"
          class="float-end btn btn-primary"><?php esc_html_e('All causes', 'framework'); ?></a>
      </div>
      <?php
                  }
                  $i++;
                }
              }
            }
          } else if (get_post_type($args['id']) == 'staff') {
            //Title for the staff
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            if (!empty($term->term_id)) {
              get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'12', 'title' => esc_html__('All Staff For - ', 'framework') . $term->name]);
          } else {
            $imic_post_custom_title = !empty($args['custom']['imic_post_page_custom_title'][0]) ? $args['custom']['imic_post_page_custom_title'][0] : esc_html__('Team', 'framework');
            $sterm = wp_get_object_terms(get_the_ID(), 'staff-category');
            get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'10', 'title' => $imic_post_custom_title]);
              if (!empty($sterm)) {
                $i = 1;
                foreach ($sterm as $terms) {
                  if ($i == 1) {
                    $term_link = get_term_link($terms, 'staff-category');
                    ?>
      <div class="col-md-2 col-sm-2 col-4">
        <a href="<?php echo esc_url($term_link); ?>"
          class="float-end btn btn-primary"><?php esc_html_e('All staff', 'framework'); ?></a>
      </div>
      <?php
                  }
                  $i++;
                }
              }
            }
          } else if (get_post_type($args['id']) == 'event') {
            //Title for the events
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            if (!empty($term->term_id)) {
              get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'12', 'title' => esc_html__('All Events For - ', 'framework') . $term->name]);
          } else {
            $imic_post_custom_title = !empty($args['custom']['imic_post_page_custom_title'][0]) ? $args['custom']['imic_post_page_custom_title'][0] : esc_html__('Events', 'framework');
            $eterm = wp_get_object_terms(get_the_ID(), 'event-category');
            get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'10', 'title' => $imic_post_custom_title]);
            }
            if (!empty($eterm)) {
              $i = 1;
              foreach ($eterm as $terms) {
                if ($i == 1) {
                  $term_link = get_term_link($terms, 'event-category');
                  ?>
      <div class="col-md-2 col-sm-2 col-4">
        <a href="<?php echo esc_url($term_link); ?>"
          class="float-end btn btn-primary"><?php esc_html_e('All events', 'framework'); ?></a>
      </div>
      <?php
                }
                $i++;
              }
            }
          } else if (get_post_type($args['id']) == 'product') {
            //Title for the Woocommerce products
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            if (!empty($term->term_id)) {
              get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'12', 'title' => esc_html__('All Products For - ', 'framework') . $term->name]);
          } else {
            $variable_post_id = '';
            if (is_single()) :
              $variable_post_id = get_the_ID();
            else :
              $variable_post_id = get_option('woocommerce_shop_page_id');
            endif;
            $imic_post_page_custom_title = get_post_meta($variable_post_id, 'imic_post_page_custom_title', true);
            $imic_post_custom_title = !empty($imic_post_page_custom_title) ? $imic_post_page_custom_title : get_the_title($variable_post_id);
            get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'12', 'title' => $imic_post_custom_title]);
          }
        } else {
          if ($args['flag'] == 1) {
            $imic_post_page_custom_title = get_post_meta(get_the_ID(), 'imic_post_page_custom_title', true);
            $imic_post_custom_title = !empty($imic_post_page_custom_title) ? $imic_post_page_custom_title : get_the_title(get_the_ID());
            get_template_part('template-parts/header/page','title',['hclass'=>'','class'=>'12', 'title' => $imic_post_custom_title]);
        }
      } ?>
    </div>
  </div>
</div>